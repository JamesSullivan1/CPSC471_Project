<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Employee List</title>
    </head>
        <body bgcolor="#FFFFCC">
<?php
session_start();
require_once 'connect.php';
require_once 'member.php';

$auth = isset($_SESSION['username']) && isWarden($_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}

?>
<center>
<h1 style="display:inline">Employee List</h>
</center>
<br>
<br>
<center>
<a href="add_employee.php">Add more employees</a>
<br>
<br>
<table cellpadding="5" border=1>
<tr>
<th>Username</th><th>Name</th><th>Birthdate</th><th>Supervisor</th>
<th>Warden of</th>
</tr>
<?php
global $con;
$query = "SELECT * FROM people NATURAL JOIN employee";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($r = $result->fetch_row())) {?>
<tr>
<td><?php echo $r[0] ?></td>
<td><?php echo $r[3] . ", " . $r[2] ?></td>
<td><?php echo $r[4] ?></td>
<td><?php echo get_name($r[6]) ?></td>
<?php
$query2 = "SELECT name FROM section WHERE w_sin = '$r[5]'";
$result2 = mysqli_query($con,$query2) or die(mysqli_error($con));
if (mysqli_num_rows($result2) > 0) {
    $r2 = $result2->fetch_row()[0];
} else {
    $r2 = "";
}
?>
<td><?php echo $r2 ?></td>
<?php if (isWarden($_SESSION['username'])) { ?>
<td>
<form method="post" action="viewdependents.php">
<input type="submit" value="View Dependents" />
<input type="hidden" name="username" value="<?php echo $r[0] ?>" />
</form>
</td>
<td>
<form method="post" action="shift.php">
<input type="submit" value="Edit Shifts" />
<input type="hidden" name="username" value="<?php echo $r[0] ?>" />
</form>
</td>
<td>
<form method="post" action="employee_management.php">
<input type="submit" value="EDIT" />
<input type="hidden" name="username" value="<?php echo $r[0] ?>" />
</form>
</td>
<?php } ?>
</tr>
<?php } ?>
</table>
<br>
<a href='memberpage.php'>Go Back</a>
</center>

