<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Tasks</title>
    </head>
        <body bgcolor="#FFFFCC">
<?php
session_start();
require_once 'connect.php';
require_once 'member.php';

$auth = isset($_SESSION['username']);
if ($auth == false) {
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
<?php if (!isEmployee($un)) { ?>
    <th>Task ID</th><th>Desc</th><th>Start</th><th>End</th><th>Supervisor</th><th>Section</th><th>Equipment</th>
<?php } else { ?>
    <th>Task ID</th><th>Desc</th><th>Start</th><th>End</th><th>Detainee</th><th>Section</th><th>Equipment</th>
<?php } ?>
</tr>
<?php
global $con;
$query;
$result;
$query2;
$result2;
if (!isEmployee($un)) {
    $query = "SELECT * FROM works W INNER JOIN task T ON W.t_id=id 
                WHERE d_uname = '$un'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
} else {
    $sin = get_sin($un);
    $query = "SELECT * FROM works W INNER JOIN task T ON W.t_id=id 
                WHERE s_sin = '$sin'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
}
while (($r = $result->fetch_row())) {
if (!isEmployee($un)) {
    $query2 = "SELECT name FROM equipment E INNER JOIN works W ON E.t_id=W.t_id
            WHERE d_uname = '$un' AND E.t_id='$r[1]'";
    $result2 = mysqli_query($con,$query2) or die(mysqli_error($con));
} else {
    $sin = get_sin($un);
    $query2 = "SELECT * FROM equipment E INNER JOIN task T ON E.t_id=id 
                WHERE s_sin = '$sin' AND E.t_id='$r[1]'";
    $result2 = mysqli_query($con,$query2) or die(mysqli_error($con));
}
?>
<tr>
<td><?php echo $r[1]  ?></td>
<td><?php echo $r[3] ?></td>
<td><?php echo $r[4] ?></td>
<td><?php echo $r[5] ?></td>
<td><?php if (!isEmployee($un)) { echo $r[6]; } else { echo $r[0]; } ?></td>
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

