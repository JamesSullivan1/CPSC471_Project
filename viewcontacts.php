<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Non-Contact List</title>
    </head>
        <body bgcolor="#FFFFCC">
<?php
session_start();
require_once 'connect.php';
require_once 'member.php';

if (!isset($_POST['username'])) die("lol");
$un = $_POST['username'];

$auth = isset($_SESSION['username']) && (isEmployee($_SESSION['username']) ||
                                         $un == $_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}

$sin = get_sin($un);

?>
<center>
<h1 style="display:inline">Non-Contact List for <?php echo $un ?></h>
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
$query = "SELECT * FROM contact WHERE d_uname = '$un'";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($r = $result->fetch_row())) {?>
<tr>
<td><?php echo $r[1] . ", " . $r[0] ?></td>
<td><?php echo $r[2] ?></td>
<td><?php echo $r[3] ?></td>
<?php if (isEmployee($_SESSION['username'])) { ?>
<td>
  <form method="post" action="contact_management.php">
    <input type="submit" value="Edit" />
    <input type="hidden" name="fname" value="<?php echo $r[0] ?>" />
    <input type="hidden" name="birthdate" value="<?php echo $r[2] ?>" />
    <input type="hidden" name="d_uname" value="<?php echo $un ?>" />
  </form>
</td>
<?php } ?>
</tr>
<?php } ?>
</table>
<br>
<a href='memberpage.php'>Go Back</a>
</center>

