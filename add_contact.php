<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Add a Non-Contact</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php

session_start();
require_once 'connect.php';
require_once 'member.php';

function addContact($fname, $lname, $bdate, $relship, $dn)
{
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }
    if (empty($fname) || empty($lname) || empty($bdate) || empty($dn)
            || empty($relship)) {
        $err = "Not enough arguments given.";
        return false;
    }

    /* Verify the credentials of the adder */
    $cuname = $_SESSION['username'];
    if (!isEmployee($cuname)) {
        die("asdf");
        $err = "Only employees can add contacts";
        return false;
    }

    /* Okay, update the table */
    $query = "INSERT INTO contact VALUES ('$fname', '$lname', '$bdate',
        '$relship', '$dn')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true;
}

$dn = $_POST['username'];
$auth = isset($_SESSION['username']) && (isEmployee($_SESSION['username']));
if ($auth == false) {
    header("Location: nope.php");
    die();
}

if (isset($_POST['addingcontact'])) {
    if( addContact($_POST['fname'], $_POST['lname'], $_POST['bdate'], 
        $_POST['relship'], $dn)) {
        header("Location: memberpage.php");
    }
}

$err = "";
?>
<center>
<h1 style="display:inline">Add a Non-Contact</h>
</center>
<br>
<br>
<br>
<center>
<b>Add Non-Contact for <?php echo $dn?></b>
<form method="post" action="add_contact.php" >
    <table border="0" >

    <tr>
    <td><b>First Name</b></td>
    <td><input name="fname" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Last Name</b></td>
    <td><input name="lname" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Birth Date</b></td>
    <td><input name="bdate" type="text" class="datetimepicker"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Relationship</b></td>
    <td><input name="relship" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><input type="submit" value="Submit"/>
    </tr>
    <input type="hidden" name="addingcontact" />
    <input type="hidden" name="username" value="<?php echo $dn?>"/>
    </tr>
    </table>
    </form>
<br>
<?php
if (!empty($err)) {
    echo("<b>".$err."</b><br>");    
}
?>
<form method="post" action="viewcontacts.php" >
<input type="hidden" name="username" value="<?php echo $dn?>"/>
<input type="submit" value="Go Back"/>
</form>
</center>
<script src="jquery.js"></script>
<script src="jquery.datetimepicker.js"></script>
<script>
$('.datetimepicker').datetimepicker({
dayOfWeekStart : 1,
timepicker : false,
format:'Y-m-d',
lang:'en'
});
$('.datetimepicker').datetimepicker();
</script>

