<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <head>
        <title>Detainee's Page.</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php  
session_start();
require_once 'connect.php';   

function timeLeft($uname) {
    global $con;
    $query = "SELECT rel_date FROM detainee D, people P WHERE (
                P.uname = '$uname' AND
                P.uname = D.uname)";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $res = $result->fetch_row()[0];
    $cdate = new DateTime();
    $rdate = new DateTime($res);
    $diff = $rdate->diff($cdate);
    return $diff->format("%y years, %m months, and %d days");
}

$auth = isset($_SESSION['username']) && $_SESSION['usertype'] == "detainee";
if ($auth == false) {
    header("Location: nope.php");
}
$timeLeft = timeLeft($_SESSION['username']);
?>
<center>
<img src="Handcuffed.gif"/>
<h1 style="display:inline">Detainee Page</h>
<img src="Handcuffed.gif"/>
<br>
<br>
</center>
<center>
<?php
echo($timeLeft . " until you're free.");
?>
<br>
<br>
<br>
<center>
    <form method="post" action="viewtasks.php" >
    <input type="submit" value="View My Tasks"/>
</form>
</center>
<center>
    <form method="post" action="non_contacts.php" >
    <input type="submit" value="Non Contact(s)"/>
</form>
</center>
<a href='logout.php'>Logout</a>
</center>
