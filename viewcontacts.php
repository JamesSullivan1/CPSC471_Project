<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Contact List</title>
    </head>
        <body bgcolor="#FFFFCC">
<?php
session_start();
require_once 'connect.php';
require_once 'member.php';

function removeContact($fname, $bdate, $dn)
{
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }
    /* Verify the credentials of the adder */
    $cuname = $_SESSION['username'];
    if (!isEmployee($cuname)) {
        $err = "Only employees can remove contacts";
        return false;
    }

    /* Check that they're actually a contact */
    $query = "SELECT * FROM contact WHERE (fname = '$fname' AND
       d_uname = '$dn' AND birthdate = '$bdate')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        $err = "No contact found";
        return false;
    }
    /* Okay, update the table */
    $query = "DELETE FROM contact WHERE (fname = '$fname' AND
        d_uname = '$dn' AND birthdate = '$bdate')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true;
}


$un = $_POST['username'];
$auth = isset($_SESSION['username']) && (isEmployee($_SESSION['username']));
if ($auth == false) {
    header("Location: nope.php");
    die();
}

if (isset($_POST['removingcontact'])) {
    if(removeContact($_POST['fname'], $_POST['bdate'], $un)) {
        header("Location: memberpage.php");
    }
}


?>
<center>
<img src="giphy.gif" />
<h1 style="display:inline">Contact List for <?php echo $un ?></h>
<img src="giphy.gif" />
</center>
<br>
<br>
<center>
<form method="post" action="add_contact.php">
<input type="submit" value="Add Contact">
<input type="hidden" name="username" value="<?php echo $un ?>">
</form>
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
<td>
<form method="post" action="viewcontacts.php">
<input type="submit" value="REMOVE" />
<input type="hidden" name="username" value="<?php echo $un ?>"/>
<input type="hidden" name="removingcontact"/>
<input type="hidden" name="bdate" value="<?php echo $r[2]?>"/>
<input type="hidden" name="fname" value="<?php echo $r[0]?>"/>
</form>
</td>
</tr>
<?php } ?>
</table>
<br>
<?php
if (!empty($err)) {
    echo("<b>".$err."</b><br>");    
}
?>
<a href='memberpage.php'>Go Back</a>
</center>

