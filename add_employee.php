<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Add a Employee</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php

session_start();
require_once 'connect.php';
require_once 'member.php';

$err = "";
?>
<center>
<h1 style="display:inline">Add a Employee</h>
</center>
<br>
<br>
<br>
<center>
<b>Add Employee</b>
<form method="post" action="employee_management.php" >
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
    <td><b>SIN</b></td>
    <td><input name="sin" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Supervisor Username</b></td>
    <td><input name="s_uname" type="text"></input></td>
    </tr> <br/>

    <tr>
    <td><b>Birth Date</b></td>
    <td><input name="bdate" type="text" class="datetimepicker"></input></td>
    </tr> <br/>

    <tr>
    <td><input type="hidden" name="addingemployee" /></td>
    <td><input type="submit" value="Submit"/>
    </table>
    </form>
<br>
<?php
if (!empty($err)) {
    echo("<b>".$err."</b><br>");    
}
?>
<a href='viewemployees.php'>Go Back</a>
</center>
<script src="jquery.js"></script>
<script src="jquery.datetimepicker.js"></script>
<script>
$('.datetimepicker').datetimepicker({
dayOfWeekStart : 1,
lang:'en'
});
$('.datetimepicker').datetimepicker();
</script>

