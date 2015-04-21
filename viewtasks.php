<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Viewtasks</title>
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
 <img src="task.gif" />
<h1 style="display:inline">My Tasks</h>
<img src="task.gif" />
</center>
<br>
<br>
<center>
<table cellpadding="5" border=1>
<tr>
    <th>Task ID</th><th>Desc</th><th>Start</th><th>End</th><th>Supervisor</th><th>Section</th><th>Equipment</th>
</tr>
<?php
global $con;
$query = "SELECT * FROM works W INNER JOIN task T ON W.t_id=id 
            WHERE d_uname = '$un'";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($r = $result->fetch_row())) {
    $query2 = "SELECT name FROM equipment E INNER JOIN works W ON E.t_id=W.t_id
            WHERE d_uname = '$un'";
    $result2 = mysqli_query($con,$query2) or die(mysqli_error($con));
?>
<tr>
<td><?php echo $r[1]  ?></td>
<td><?php echo $r[3] ?></td>
<td><?php echo $r[4] ?></td>
<td><?php echo $r[5] ?></td>
<td><?php echo $r[6] ?></td>
<td><?php echo $r[7] ?></td>
<td>
<ul>
<?php while (($r2 = $result2->fetch_row())) {
    echo "<li> " . $r2[0] . "</li>";
} ?>
</ul>
</td>
</tr>
<?php } ?>
</table>
<br>
<a href='memberpage.php'>Go Back</a>
</center>

