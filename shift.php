<?php
session_start();
require_once 'connect.php';   
require_once 'member.php';

function addShift($uname, $sdate, $edate, $reqrole, $snum) {
    global $con;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        return false;
    }
    /* Verify the credentials of the adder and addee */
    $cuname = $_SESSION['username'];
    $crole = getUserType($cuname);
    $role = getUserType($uname);
    if (!roleExceeds($role,$reqrole) || !isEmployee($uname)) {
        return false;
    }
    /* Verify the adder is a supervisor of addee, or they are a warden */
    if (!supervises($cuname, $uname) || !isWarden($cuname)) {
        return false;
    }
    $esin = get_sin($uname);
    /* Okay, update the table */
    $query = "INSERT INTO shift ($reqrole, $sdate, $edate, $esin, $snum)";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true; 
}

function removeShift($uname, $sdate, $edate, $reqrole, $snum) {
    global $con;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        return false;
    }
    /* Verify the credentials of the adder and addee */
    $cuname = $_SESSION['username'];
    /* Verify the adder is a supervisor of addee, or they are a warden */
    if (!supervises($cuname, $uname) || !isWarden($cuname)) {
        return false;
    }
    $esin = get_sin($uname);
    /* Okay, update the table */
    $query = "INSERT INTO shift ($reqrole, $sdate, $edate, $esin, $snum)";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true; 
}


?>
