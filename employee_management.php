<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Employee Management</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php

session_start();
require_once 'connect.php';
require_once 'member.php';

$err = "";

function addEmployee($uname, $pass, $fname, $lname, $birthdate, $sin, $s_uname)
{
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }
    if (empty($uname) || empty($pass) || empty($fname) || empty($lname) ||
        empty($sin)) {
        $err = "Not enough arguments given.";
        return false;
    }

    /* Verify the credentials of the adder */
    $cuname = $_SESSION['username'];
    if (!isWarden($cuname)) {
        $err = "Only wardens can add employees";
        return false;
    }

    /* Verify the supervisor is a real person */
    $query = "SELECT sin FROM employee WHERE uname = '$s_uname'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        $err = "No supervisor with username " .$s_uname ."found";
        return false;
    }
    $s_sin = $result->fetch_row()[0];


    /* Verify the supervisor is a real person */
    $query = "SELECT sin FROM employee WHERE uname = '$s_uname'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        $err = "No supervisor with username " .$s_uname ."found";
        return false;
    }
    $s_sin = $result->fetch_row()[0];

    $cuname = $_SESSION['username'];
    if (!isWarden($cuname)) {
        $err = "Only wardens can add employees";
        return false;
    }

    /* Okay, update the table */
    $query = "INSERT INTO people VALUES ('$uname', SHA1('$pass'), '$fname', 
        '$lname', '$birthdate')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $query = "INSERT INTO employee VALUES ('$sin', '$s_sin', '$uname')"; 
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true;
}

function updateEmployee($uname, $pass, $fname, $lname, $birthdate, $sin, $s_uname)
{
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }

    if (empty($uname) || empty($pass) || empty($fname) || empty($lname) ||
        empty($sin)) {
        $err = "Not enough arguments given.";
        return false;
    }

    /* Verify the credentials of the adder */
    $cuname = $_SESSION['username'];
    if (!isWarden($cuname)) {
        $err = "Only wardens can add employees";
        return false;
    }

    /* Verify the supervisor is a real person */
    $query = "SELECT sin FROM employee WHERE uname = '$s_uname'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        $err = "No supervisor with username " .$s_uname ."found";
        return false;
    }
    $s_sin = $result->fetch_row()[0];

    /* Make sure there's actually an employee there */
    $query = "SELECT * FROM employee E WHERE E.uname = '$uname'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        $err = "No employee with username " .$uname." found";
        return false;
    }

    /* Okay, update the table */
    $query = "UPDATE people SET uname='$uname', pass=SHA1('$pass'), fname='$fname', 
        lname='$lname', birthdate='$birthdate' WHERE uname = '$uname'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $query = "UPDATE employee SET uname='$uname', sin='$sin', s_sin='$s_sin' 
        WHERE uname = '$uname'"; 
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true;
}

function removeEmployee($uname)
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
        $err = "Only wardens can remove employees";
        return false;
    }

    /* Check that they're actually an employee */
    $query = "SELECT * FROM employee E WHERE E.uname = '$uname'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        $err = "No employee with username ".$uname." found";
        return false;
    }
    /* Okay, update the table */
    $query = "DELETE FROM people WHERE uname = '$uname'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true;
}

$auth = isset($_SESSION['username']) && isWarden($_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}
if (!isset($_POST['username'])) {
    header("Location: viewemployees.php");
    die();
}
if (!empty($err)) {
    echo($err);
}

$dn = $_POST['username'];

if (isset($_POST['addingemployee'])) {
    addEmployee($_POST['username'],$_POST['pass'],$_POST['fname'],
        $_POST['lname'], $_POST['bdate'], $_POST['sin'], $_POST['s_uname']);
} else if (isset($_POST['removingemployee'])) {
    if(removeEmployee($_POST['username'])) {
        header("Location: viewemployees.php");
    }
} else if (isset($_POST['updatingemployee'])) {
    updateEmployee($_POST['username'],$_POST['pass'],$_POST['fname'],
        $_POST['lname'], $_POST['bdate'], $_POST['sin'], $_POST['s_uname']);
}

$bestquery = "SELECT * FROM people NATURAL JOIN employee WHERE uname = '$dn'";
$bestresult = mysqli_query($con, $bestquery) or die (mysqli_error($con));
$r = $bestresult->fetch_row();

?>
<center>
<h1 style="display:inline">Employee Management</h>
</center>
<br>
<br>
<br>
<center>
<b>Update Employee <?php echo($dn) ?></b>
<form method="post" action="employee_management.php" >
    <table border="0" >
    <tr>
    <td><b>Password</b></td>
    <td><input name="pass" type="password"></input></td>
    </tr> <br/>

    <tr>
    <td><b>First Name</b></td>
    <td><input name="fname" type="text" value="<?php echo $r[2] ?>"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Last Name</b></td>
    <td><input name="lname" type="text" value="<?php echo $r[3] ?>"></input></td>
    </tr> <br/>

    <tr>
    <td><b>SIN</b></td>
    <td><input name="sin" type="text" value="<?php echo $r[5] ?>"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Supervisor Username</b></td>
    <td><input name="s_uname" type="text" value="<?php echo get_name($r[6]) ?>"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Birth Date</b></td>
    <td><input name="bdate" type="text" class="datetimepicker" value="<?php echo $r[4] ?>"></input></td>
    </tr> <br/>

    <tr>
    <td><input type="hidden" name="updatingemployee" /></td>
    <td><input type="hidden" name="username" value="<?php echo($dn) ?>"/>
    <td><input type="submit" value="Submit"/>
    </table>
    </form>
<form method="post" action="employee_management.php" >
<input type="hidden" name="removingemployee"/>
<input type="hidden" name="username" value="<?php echo($dn) ?>"/>
<input type="submit" value="Remove Employee"/>
</form>
<br>
<?php
if (!empty($err)) {
    echo("<b>".$err."</b><br>");    
}
?>
<a href='viewemployees.php'>Go Back</a>
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

