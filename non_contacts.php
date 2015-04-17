<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>NO FRIEND ZONE</title>
    </head>
        <body bgcolor="#FFFFCC">
<?php
session_start();
require_once 'connect.php';
require_once 'member.php';

$auth = isset($_SESSION['username']) && isEmployee($_SESSION['username']) || isWarden($_SESSION['username']);
if ($auth == true) {
    header("Location: nope.php");
    die();
}
$un = $_SESSION['username'];
?>
<center>
<h1 style="display:inline">No Friend Zone (Non Contacts(s))</h>
</center>
<br>
<center>
    <img src="beastmode.gif" />
</center>
<br>
<center>
<table cellpadding="5" border=1>
<tr>
<th>Name</th><th>Birthdate</th><th>Relationship</th>
</tr>
<?php
global $con;
$query = "SELECT * FROM contact WHERE d_uname = '$un'";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($r = $result->fetch_row())) {?>
<tr>
<td><?php echo $r[0] . " " . $r[1] ?></td>
<td><?php echo $r[2] ?></td>
<td><?php echo $r[3] ?></td>
</tr>
<?php } ?>
</table>
<br>
<a href='memberpage.php'>Go Back</a>
</center>

