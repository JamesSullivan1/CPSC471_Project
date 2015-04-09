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
    /* Verify that we don't already have the employee scheduled */
    if (has_shift_during($uname, $sdate, $edate)) {
        return false;
    }
    /* Okay, update the table */
    $query = "INSERT INTO shift ($reqrole, $sdate, $edate, $esin, $snum)";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true; 
}

function removeShift($uname, $sdate) {
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
    /* Check that there's actually a shift there */
    $query = "SELECT * FROM shift S WHERE (
              S.e_sin = '$esin' AND S.sdate = '$sdate')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        return false;
    }

    /* Okay, update the table */
    $query = "DELETE FROM shift WHERE (
              S.e_sin = '$esin' AND S.date = '$sdate')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true; 
}

function has_shift_during($uname, $sdate, $edate) {
    global $con;
    $esin = get_sin($uname);
    $query = "SELECT * FROM shift S WHERE (
              S.e_sin = '$esin')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    $sdt = new DateTime($sdate);
    $edt = new DateTime($edate);
    for ($i = 0; $i < $count, $i++) {
        $cur = $result->fetch_row()[$i]; 
        $csdt = new DateTime($cur[1]);
        $cedt = new DateTime($cur[2]);
        if ($sdt >= $csdt && $sdt < $cedt) {
            return true;
        } else if ($edt > $cedt && $edt <= $cedt) {
            return true;
        }
    }
    return false;
}

?>
