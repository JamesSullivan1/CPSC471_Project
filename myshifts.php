<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>My Shifts</title>
    </head>
        <body bgcolor="#FFFFCC">
<?php
session_start();
require_once 'connect.php';
require_once 'member.php';

$auth = isset($_SESSION['username']) && isEmployee($_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}

?>
<center>
<img src="cuffs04.gif" />
<h1 style="display:inline">My Duties</h>
<img src="cuffs04.gif" />
</center>
<br>
<br>
<center>
<table cellpadding="5" border=1>
<tr>
<th>Time</th><th>Section</th>
</tr>
<?php
global $con;
$sin = get_sin($_SESSION['username']);
$query = "SELECT * FROM shift S, section E WHERE S.e_sin = '$sin' AND S.s_num = E.num ORDER BY S.start";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($r = $result->fetch_row())) {?>
<tr>
<td><?php echo $r[1] . " - " . $r[2] ?></td>
<td><?php echo $r[5] ?></td>
</tr>
<?php } ?>
</table>
<br>
<a href='memberpage.php'>Go Back</a>
</center>

