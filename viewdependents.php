<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Dependent List</title>
    </head>
        <body bgcolor="#FFFFCC">
<?php
session_start();
require_once 'connect.php';
require_once 'member.php';

if (!isset($_POST['username'])) die("lol");
$un = $_POST['username'];

$auth = isset($_SESSION['username']) && (isWarden($_SESSION['username']) ||
                                         isEmployee($_SESSION['username']) &&
                                         $un == $_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}

$sin = get_sin($un);

?>
<center>
<img src="giphy.gif" />
<h1 style="display:inline">Dependent List for <?php echo $un ?></h>
<img src="giphy.gif" />
</center>
<br>
<br>
<center>
<table cellpadding="5" border=1>
<tr>
<th>Name</th><th>Birthdate</th><th>Relationship</th>
</tr>
<?php
global $con;
$query = "SELECT * FROM dependent WHERE e_sin = '$sin'";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($r = $result->fetch_row())) {?>
<tr>
<td><?php echo $r[0] ?></td>
<td><?php echo $r[1] ?></td>
<td><?php echo $r[2] ?></td>
</tr>
<?php } ?>
</table>
<br>
<a href='memberpage.php'>Go Back</a>
</center>

