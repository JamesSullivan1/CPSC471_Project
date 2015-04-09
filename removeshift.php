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

function removeShift($uname, $sdate) {
    global $con;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }
    /* Verify the credentials of the adder and addee */
    $cuname = $_SESSION['username'];
    /* Verify the adder is a supervisor of addee, or they are a warden */
    if (!supervises($cuname, $uname) || !isWarden($cuname)) {
        $err = "Can't remove a shift for somebody you don't supervise.";
        return false;
    }
    $esin = get_sin($uname);
    /* Check that there's actually a shift there */
    $query = "SELECT * FROM shift S WHERE (
        S.e_sin = '$esin' AND S.start = '$sdate')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        return false;
    }
    /* Okay, update the table */
    $query = "DELETE FROM shift WHERE (
        e_sin = '$esin' AND start= '$sdate')";
    mysqli_query($con,$query) or die(mysqli_error($con));
    return true; 
}

$auth = isset($_SESSION['username']) && isEmployee($_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}

if (empty($_POST['username'])) {
        header("Location: shift.php");
        die();
}

if (isset($_POST['removingshift'])) {
        removeShift($_POST['username'], $_POST['sdate']);
}

?>
<center>
<h1 style="display:inline">Shift Removal</h>
</center>
<br>
<br>
<center>
<form method="post" action="removeshift.php" >
<select name="sdate" style="width:390px">
<?php
global $con;
if (!isset($_POST['username'])) die("lol");
$un = $_POST['username'];
$sin = get_sin($un);
$query = "SELECT * FROM shift S WHERE (
          S.e_sin = '$sin')";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($shift = $result->fetch_row())) {
    echo("<option width=400px value=\"".$shift[1]."\"> ".$shift[1]." - ".$shift[2]."</option>");
}
?>
</select>
<br>
<input type="submit" value="Submit"/>
<input type="hidden" name="removingshift"/>
<input type="hidden" name="username" value="<?php echo($un) ?>"/>
</form>
<br>
<a href='shift.php'>Go Back</a>
</center>

