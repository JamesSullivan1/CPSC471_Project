<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <head>
        <title>Member's Page!!!!!!1</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php  

session_start();
require_once 'connect.php';   

function isWarden($username) {
    global $con;
    if (!isEmployee($username)) {
        return false;
    }
    $query = "SELECT * FROM people P, employee E, section S WHERE ( 
                    P.uname = '$username' AND
                    P.uname = E.uname AND
                    E.sin = S.w_sin)";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        return true;
    }
    return false;
}

function isEmployee($username) {
    global $con;
    $query = "SELECT * FROM employee E,people P WHERE (
                    P.uname = '$username' AND E.uname = P.uname)";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        return true;
    }
    return false;
}

function getUserType($username) {
    if (isEmployee($username)) {
        if (isWarden($username)) {
            return "warden";
        }
        return "employee";
    }
    return "detainee";
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $usertype = getUserType($username);
    $_SESSION['usertype'] = $usertype;
    if ($usertype == "warden") {
        header("Location: warden.php");
    }
    else if ($usertype == "employee") {
        header("Location: employee.php");
    } else {
        header("Location: detainee.php");
    }
    echo "Hai " . $username . "<br>";
    echo "Ur a great " . $usertype . "<br>";
    echo "This is the Members Area <br>";
    echo "<a href='logout.php'>Logout</a>"; 
}else{
    header("Location: login.php");
} 

?>
 </body>
</html>

