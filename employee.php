<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <head>
        <title>Employee's Page!!!!!!1</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php  
session_start();
require_once 'connect.php';   

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

$auth = isset($_SESSION['username']) && $_SESSION['usertype'] == "employee";
if ($auth == false) {
    header("Location: nope.php");
}
?>
<center>
<img src="animated-traffic-cop-directing-traffic.gif"/>
<h1 style="display:inline">Employee Page</h>
<img src="animated-traffic-cop-directing-traffic.gif"/>
</center>
<br>
<br>
<center>
<form method="post" action="viewdependents.php" >
    <input type="submit" value="View Dependents"/>
    <input name="username" type="hidden" 
           value="<?php echo($_SESSION['username'])?>">
</form>
<form method="post" action="viewdetainees.php" >
    <input type="submit" value="View Detainees"/>
</form>
<br>
<a href='logout.php'>Logout</a>
</center>

