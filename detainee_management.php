<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Detainee Management</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php

session_start();
require_once 'connect.php';
require_once 'member.php';

$err = "";

function addDetainee($uname, $pass, $fname, $lname, $birthdate, $rel_date,
    $c_num, $cs_num)
{
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }

    if (empty($uname) || empty($pass) || empty($fname) || empty($lname) ||
        empty($rel_date) || empty($c_num) || empty($cs_num)) {
        $err = "Not enough arguments given.";
        return false;
    }

    /* Verify the credentials of the adder */
    $cuname = $_SESSION['username'];
    if (!isWarden($cuname)) {
        $err = "Only wardens can add detainees";
        return false;
    }
    /* Okay, update the table */
    $query = "INSERT INTO people VALUES ('$uname', SHA1('$pass'), '$fname',
              '$lname', '$birthdate')";
    mysqli_query($con,$query) or die(mysqli_error($con));
    $query = "INSERT INTO detainee VALUES ('$uname', '$rel_date')";
    mysqli_query($con,$query) or die(mysqli_error($con));
    $query = "INSERT INTO livesin VALUES ('$uname', '$c_num', '$cs_num')";
    mysqli_query($con, $query) or die(mysqli_error($con));
    return true;
}

function updateDetainee($uname, $pass, $fname, $lname, $birthdate, $rel_date,
    $c_num, $cs_num)
{
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }

    if (empty($uname) || empty($pass) || empty($fname) || empty($lname) ||
        empty($rel_date)) {
        $err = "Not enough arguments given.";
        return false;
    }

    /* Verify the credentials of the adder */
    $cuname = $_SESSION['username'];
    if (!isWarden($cuname)) {
        $err = "Only wardens can add detainees";
        return false;
    }
    /* Make sure there's actually a detainee there */
    $query = "SELECT * FROM detainee D WHERE D.uname = '$uname'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        $err = $uname."No detainee with that username found";
        return false;
    }

    if (empty($c_num) || empty($cs_num)) {
        $r = $result->fetch_row();
        $c_num = $r[2];
        $cs_num = $r[3];
    }

    /* Okay, update the table */
    $query = "UPDATE people SET uname='$uname', pass=SHA1('$pass'),
              fname='$fname', lname='$lname', birthdate='$birthdate'
              WHERE uname = '$uname'";
    mysqli_query($con,$query) or die(mysqli_error($con));
    $query = "UPDATE detainee SET uname='$uname', rel_date='$rel_date'
              WHERE uname = '$uname'";
    mysqli_query($con,$query) or die(mysqli_error($con));
    $query = "UPDATE livesin SET c_num='$c_num', cs_num='$cs_num'
              WHERE d_uname = '$uname'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    return true;
}

function removeDetainee($uname)
{
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }
    /* Verify the credentials of the adder */
    $cuname = $_SESSION['username'];
    if (!isWarden($cuname)) {
        $err = "Only wardens can remove detainees";
        return false;
    }

    /* Check that they're actually a detainee */
    $query = "SELECT * FROM detainee WHERE uname = '$uname'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        $err = $uname."No detainee with that username found";
        return false;
    }
    /* Okay, update the table */
    $query = "DELETE FROM people WHERE uname = '$uname'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    $query = "DELETE FROM detainee WHERE uname = '$uname'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    $query = "DELETE FROM livesin WHERE d_uname = '$uname'";
    mysqli_query($con, $query) or die(mysqli_error($con));
    return true;
}

$auth = isset($_SESSION['username']) && isWarden($_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}
if (!isset($_POST['username'])) {
    header("Location: viewdetainees.php");
    die();
}

$dn = $_POST['username'];

$c_num = "";
$cs_num = "";
if (isset($_POST['celldesc'])) {
    $split = explode(",", $_POST['celldesc']);
    $cs_num = $split[0];
    $c_num = $split[1];
}

if (isset($_POST['addingdetainee'])) {
    addDetainee($_POST['username'],$_POST['pass'],$_POST['fname'],
        $_POST['lname'], $_POST['bdate'], $_POST['rdate'], $c_num,
        $cs_num);
} else if (isset($_POST['removingdetainee'])) {
    if(removeDetainee($_POST['username'])) {
        header("Location: viewdetainees.php");
    }
} else if (isset($_POST['updatingdetainee'])) {
    updateDetainee($_POST['username'],$_POST['pass'],$_POST['fname'],
        $_POST['lname'], $_POST['bdate'], $_POST['rdate'], $c_num,
        $cs_num);
}

$query = "SELECT * FROM people NATURAL JOIN detainee WHERE uname = '$dn'";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
$r = $result->fetch_row();

?>
<center>
<h1 style="display:inline">Detainee Management</h>
</center>
<br>
<br>
<br>
<center>
<b>Update Detainee <?php echo($dn) ?></b>
<form method="post" action="detainee_management.php" >
    <table border="0" >
    <tr>
    <td><b>First Name</b></td>
    <td><input name="fname" value="<?php echo $r[2]?>" type="text"</input></td>
    </tr> <br/>

    <tr>
    <td><b>Last Name</b></td>
    <td><input name="lname" value="<?php echo $r[3]?>" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Password</b></td>
    <td><input name="pass" type="password"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Release Date</b></td>
    <td><input name="rdate" type="text" value="<?php echo $r[5]?>" 
            class="datetimepicker"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Birth Date</b></td>
    <td><input name="bdate" type="text" value="<?php echo $r[4]?>"
            class="datetimepicker"></input></td>
    </tr> <br/>

<?php if(isWarden($_SESSION['username'])) { ?>
    <tr>
    <td><b>Cell</b></td>
    <td><select name="celldesc">
<?php 
global $con;
$query = "SELECT * FROM cell INNER JOIN section ON cell.s_num=section.num ORDER BY s_num,cell.num;";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($descr = $result->fetch_row())) {
    echo("<option width=200px value=\"".$descr[1].",".$descr[0]."\"> 
        Section ".$descr[1].", Cell ".$descr[0]." </input>");
}
?>
    </select></td>
    </tr> <br/>
<?php } ?>

    <tr>
    <td><input type="hidden" name="updatingdetainee" /></td>
    <td><input type="hidden" name="username" value="<?php echo($dn) ?>"/>
    <td><input type="submit" value="Submit"/>
    </table>
    </form>
<form method="post" action="detainee_management.php" >
<input type="hidden" name="removingdetainee"/>
<input type="hidden" name="username" value="<?php echo($dn) ?>"/>
<input type="submit" value="Remove Detainee"/>
</form>
<br>
<?php
if (!empty($err)) {
    echo("<b>".$err."</b><br>");    
}
?>
<a href='viewdetainees.php'>Go Back</a>
</center>
<script src="jquery.js"></script>
<script src="jquery.datetimepicker.js"></script>
<script>
$('.datetimepicker').datetimepicker({
dayOfWeekStart : 1,
timepicker : false,
format:'Y-m-d',
lang:'en'
});
$('.datetimepicker').datetimepicker();
</script>

