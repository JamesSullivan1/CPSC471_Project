<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Add a Detainee</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php

session_start();
require_once 'connect.php';
require_once 'member.php';

$err = "";
?>
<center>
<h1 style="display:inline">Add a Detainee</h>
</center>
<br>
<br>
<br>
<center>
<b>Add Detainee</b>
<form method="post" action="detainee_management.php" >
    <table border="0" >
    <tr>
    <td><b>Username</b></td>
    <td><input name="username" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Password</b></td>
    <td><input name="pass" type="password"></input></td>
    </tr> <br/>

    <tr>
    <td><b>First Name</b></td>
    <td><input name="fname" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Last Name</b></td>
    <td><input name="lname" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Release Date</b></td>
    <td><input name="rdate" type="text" class="datetimepicker"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Birth Date</b></td>
    <td><input name="bdate" type="text" class="datetimepicker"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Cell</b></td>
    <td><select name="celldesc">
<?php 
global $con;
$query = "SELECT * FROM cell INNER JOIN section ON cell.s_num=section.num ORDER BY s_num,cell.num;";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($descr = $result->fetch_row())) {
    echo("<option width=200px value=\"".$descr[1].",".$descr[0]."\"> Section ".$descr[1].", Cell ".
                $descr[0]." </input>");
}
?>
    </select></td>
    </tr> <br/>

    <tr>
    <td><input type="hidden" name="addingdetainee" /></td>
    <td><input type="submit" value="Submit"/>
    </table>
    </form>
<br>
<?php
if (!empty($err)) {
    echo("<b>".$err."</b><br>");    
}
?>
<a href='viewdetainees.php'>Go Back</a>
</center>
<script src="jquery.js"></script>
<script src="jquery.datetimepicker.js"></script>
<script>
$('.datetimepicker').datetimepicker({
dayOfWeekStart : 1,
timepicker : false,
format:'Y/m/d',
format:'Y/m/d',
lang:'en'
});
$('.datetimepicker').datetimepicker();
</script>

