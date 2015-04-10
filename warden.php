<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <head>
        <title>Warden's Page!!!!!!1</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php  
session_start();
require_once 'connect.php';   

function addUser($uname, $pass, $fname, $lname, $sin) {
    global $con;
    $query = "INSERT INTO user ($uname, $pass, $fname, $lname, $sin, NULL)";
    mysqli_query($con,$query) or die(mysqli_error($con));
}

function removeUser($con, $uname) {
    global $con;
    $query = "DELETE FROM user WHERE uname = '$uname'";
}

function addEmployee($uname, $pass, $fname, $lname, $sin) {
    global $con;
    addUser($con,$uname, SHA1($pass), $fname, $lname, $sin);
    $query = "INSERT INTO employee ('$sin', NULL)";
        $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        echo "Employee $uname created.";
    } else {
        echo "Invalid employee!!!11!!1";
    }
}

function addDetainee($uname, $pass, $fname, $lname, $sin, $rdate) {
    global $con;
    addUser($con,$uname, $pass, $fname, $lname, $sin);
    $query = "INSERT INTO employee ('$sin', '$rdate')";
        $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        echo "Employee $uname created.";
    } else {
        echo "Invalid employee!!!11!!1";
    }
}

$auth = isset($_SESSION['username']) && $_SESSION['usertype'] == "warden";
if ($auth == false) {
    header("Location: nope.php");
}
?>
<center>
<img src="a4.gif"/>
<h1 style="display:inline">Warden Page</h>
<img src="a4.gif"/>
</center>
<br>
<br>
<center>
<form method="post" action="viewdependents.php" >
    <input type="submit" value="View Dependents"/>
    <input name="username" type="hidden" 
           value="<?php echo($_SESSION['username'])?>">
</form>
<form method="post" action="viewemployees.php" >
    <input type="submit" value="View Employees"/>
</form>
<form method="post" action="viewdetainees.php" >
    <input type="submit" value="View Detainees"/>
</form>
<a href='logout.php'>Logout</a>
</center>

