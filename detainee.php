<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <head>
        <title>Detainee's Page.</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php  
session_start();
require_once 'connect.php';   

$auth = isset($_SESSION['username']) && $_SESSION['usertype'] == "detainee";
if ($auth == false) {
    header("Location: nope.php");
}
?>
<center>
<img src="Handcuffed.gif"/>
<h1 style="display:inline">Detainee Page</h>
<img src="Handcuffed.gif"/>
<br>
<br>
<a href='logout.php'>Logout</a>
</center>

