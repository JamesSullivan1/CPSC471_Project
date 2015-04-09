<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Shift Management</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php
session_start();
require_once 'connect.php';   
require_once 'member.php';

function addShift($uname, $sdate, $edate, $reqrole, $snum) {
    global $con;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        return false;
    }
    /* Verify the credentials of the adder and addee */
    $cuname = $_SESSION['username'];
    $crole = getUserType($cuname);
    $role = getUserType($uname);
    if (!roleExceeds($role,$reqrole) || !isEmployee($uname)) {
        return false;
    }
    /* Verify the adder is a supervisor of addee, or they are a warden */
    if (!supervises($cuname, $uname) || !isWarden($cuname)) {
        return false;
    }
    $esin = get_sin($uname);
    /* Verify that we don't already have the employee scheduled */
    if (has_shift_during($uname, $sdate, $edate)) {
        return false;
    }
    /* Okay, update the table */
    $query = "INSERT INTO shift ($reqrole, $sdate, $edate, $esin, $snum)";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true; 
}

function removeShift($uname, $sdate) {
    global $con;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        return false;
    }
    /* Verify the credentials of the adder and addee */
    $cuname = $_SESSION['username'];
    /* Verify the adder is a supervisor of addee, or they are a warden */
    if (!supervises($cuname, $uname) || !isWarden($cuname)) {
        return false;
    }
    $esin = get_sin($uname);
    /* Check that there's actually a shift there */
    $query = "SELECT * FROM shift S WHERE (
        S.e_sin = '$esin' AND S.sdate = '$sdate')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        return false;
    }
    /* Okay, update the table */
    $query = "DELETE FROM shift WHERE (
        S.e_sin = '$esin' AND S.sdate = '$sdate')";
    mysqli_query($con,$query) or die(mysqli_error($con));
    return true; 
}

function has_shift_during($uname, $sdate, $edate) {
    global $con;
    $esin = get_sin($uname);
    $query = "SELECT * FROM shift S WHERE (
        S.e_sin = '$esin')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    $sdt = new DateTime($sdate);
    $edt = new DateTime($edate);
    for ($i = 0; $i < $count; $i++) {
        $cur = $result->fetch_row()[$i]; 
        $csdt = new DateTime($cur[1]);
        $cedt = new DateTime($cur[2]);
        if ($sdt >= $csdt && $sdt < $cedt) {
            return true;
        } else if ($edt > $cedt && $edt <= $cedt) {
            return true;
        }
    }
    return false;
}

$auth = isset($_SESSION['username']) && isEmployee($_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
}

if (isset($_POST['addingshift'])) {
    add_shift($_POST['username'], $_POST['sdate'], $_POST['edate'],
        $_POST['reqrole'], $_POST['snum']);
}

?>
<center>
<h1 style="display:inline">Shift Management</h>
</center>
<br>
<br>
<b>Add Shift</b>
<form method="post" action="shift.php" >
    <table border="0" >
    <tr>
    <td>
    <b>Username</b>
    </td>
    <td><input type="text" name="username">
    </tr>
    <tr>
    <td><b>Start Time</b></td>
    <td><input name="sdate" type="text" class="datetimepicker"></input></td>
    </tr> <br/>
    <tr>

    <td><b>End Time</b></td>
    <td><input name="edate" type="text" class="datetimepicker"></input></td>
    </tr> <br/>
    <tr>
    <td><b>Section Number</b></td>
    <td><input name="snum" type="text"></input></td>
    </tr> <br/>
    <tr>
    <td><b>Required Role</b></td>
    <td>
    <select>
        <option value="warden">Warden</option>
        <option value="employee">Employee</option>
    </select> 
    </td>
    </tr> <br/>
    <tr>
    <td><input type="submit" value="Submit"/>
    <td><input type="hidden" value="addingshift"/>
    </tr>
    </table>
</form>
<br>
<a href='member.php'>Go Back</a>
</center>
<script src="jquery.js"></script>
<script src="jquery.datetimepicker.js"></script>
<script>
$('.datetimepicker').datetimepicker({
dayOfWeekStart : 1,
lang:'en'
});
$('.datetimepicker').datetimepicker();
</script>

