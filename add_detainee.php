<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Detainee Management</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php

session_start();
require_once 'connect.php';
require_once 'member.php';

$err = "";

