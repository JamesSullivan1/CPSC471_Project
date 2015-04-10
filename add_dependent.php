<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Add a Dependent</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php

session_start();
require_once 'connect.php';
require_once 'member.php';

function addDependent($fname, $lname, $bdate, $relship, $e_sin)
{
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }
    if (empty($fname) || empty($lname) || empty($bdate) || empty($e_sin)
            || empty($relship)) {
        $err = "Not enough arguments given.";
        return false;
    }

    /* Verify the credentials of the adder */
    $cuname = $_SESSION['username'];
    if (!isWarden($cuname) || get_sin($cuname) != $e_sin) {
        $err = "Only wardens can add dependents for others";
        return false;
    }

    /* Okay, update the table */
    $query = "INSERT INTO dependent VALUES ('$fname', '$lname', '$bdate',
        '$relship', '$e_sin')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true;
}

$sin = $_POST['e_sin'];
if (empty($sin)) die("No SIN");
$un = get_name($sin);
$auth = isset($_SESSION['username']) && (isWarden($_SESSION['username']) ||
                                         isEmployee($_SESSION['username']) &&
                                         $un == $_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}

if (isset($_POST['addingdependent'])) {
    addDependent($_POST['fname'], $_POST['lname'], $_POST['bdate'], 
        $_POST['relship'], $sin);
}

$err = "";
?>
<center>
<h1 style="display:inline">Add a Dependent</h>
</center>
<br>
<br>
<br>
<center>
<b>Add Dependent for <?php echo $un?></b>
<form method="post" action="add_dependent.php" >
    <table border="0" >

    <tr>
    <td><b>First Name</b></td>
    <td><input name="fname" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Last Name</b></td>
    <td><input name="lname" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Birth Date</b></td>
    <td><input name="bdate" type="text" class="datetimepicker"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Relationship</b></td>
    <td><input name="relship" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><input type="hidden" name="addingdependent" /></td>
    <td><input type="hidden" name="e_sin" value="<?php echo $sin?>"/></td>
    <td><input type="submit" value="Submit"/>
    </table>
    </form>
<br>
<?php
if (!empty($err)) {
    echo("<b>".$err."</b><br>");    
}
?>
<form method="post" action="viewdependents.php" >
<input type="hidden" name="username" value="<?php echo $un?>"/>
<input type="submit" value="Go Back"/>
</form>
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

