<html>
    <head>
        <link rel="stylesheet" href="bootstrap.css" media="screen">
        <title>Login</title>
    </head>
    <body bgcolor="#FFFFCC">

<?php
session_start();
require_once 'connect.php';   

function authenticate() {
    global $con;
    if (isset($_POST['username']) and isset($_POST['password'])){
        $username = $con->real_escape_string($_POST['username']);
        $password = $con->real_escape_string($_POST['password']);
        $query = "SELECT * FROM people WHERE uname='$username' and pass=SHA1('$password')";

        $result = mysqli_query($con,$query) or die(mysqli_error($con));
        $count = mysqli_num_rows($result);
        if ($count == 1){
            $_SESSION['username'] = $username;
            header("Location: member.php");
            return true;
        }else{
            return false;
        }
    }
    
}

$loginFailed = false;
if (isset($_POST['username']) and isset($_POST['password'])){
    if(!authenticate()) {
        $loginFailed = true;
    }
}

?>

<center>
<h3>User Login</h3>
<hr />
<?php
if($loginFailed) {
    echo "<b>Fail</b>";
}
?>
<form method="post" action="login.php" >
    <table border="0" >
    <tr>
    <td>
    <b>Username</b>
    </td>
    <td><input type="text" name="username">
    </tr>
    <tr>
    <td><b>Password</b></td>
    <td><input name="password" type="password"></input></td>
    </tr> <br/>
    <tr>
    <td><input type="submit" value="Submit"/>
    </tr>
    </table>
</form>
</center>
</body>
</html>
