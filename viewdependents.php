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

function removeDependent($fname, $bdate, $e_sin)
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
    if (!isWarden($cuname) && get_sin($cuname) != $e_sin) {
        $err = "Only wardens can remove other people's dependents";
        return false;
    }

    /* Check that they're actually a dependent */
    $query = "SELECT * FROM dependent WHERE (fname = '$fname' AND
        e_sin = '$e_sin' AND birthdate = '$bdate')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        $err = "No dependent found";
        return false;
    }
    /* Okay, update the table */
    $query = "DELETE FROM dependent WHERE (fname = '$fname' AND
        e_sin = '$e_sin' AND birthdate = '$bdate')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true;
}


$un = $_POST['username'];
$auth = isset($_SESSION['username']) && (isWarden($_SESSION['username']) ||
                                         isEmployee($_SESSION['username']) &&
                                         $un == $_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}

$sin = get_sin($un);
if (isset($_POST['removingdependent'])) {
    if(removeDependent($_POST['fname'], $_POST['bdate'], $sin)) {
        header("Location: memberpage.php");
    }
}


?>
<center>
<img src="giphy.gif" />
<h1 style="display:inline">Dependent List for <?php echo $un ?></h>
<img src="giphy.gif" />
</center>
<br>
<br>
<center>
<form method="post" action="add_dependent.php">
<input type="submit" value="Add Dependent">
<input type="hidden" name="e_sin" value="<?php echo $sin ?>">
</form>
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
<td><?php echo $r[0] . " " . $r[1]?></td>
<td><?php echo $r[2] ?></td>
<td><?php echo $r[3] ?></td>
<td>
<form method="post" action="viewdependents.php">
<input type="submit" value="REMOVE" />
<input type="hidden" name="username" value="<?php echo $un ?>"/>
<input type="hidden" name="removingdependent"/>
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

