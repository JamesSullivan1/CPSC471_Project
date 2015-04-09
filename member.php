<?php  

require_once 'connect.php';   

function isWarden($username) {
    global $con;
    if (!isEmployee($username)) {
        return false;
    }
    $query = "SELECT * FROM people P, employee E, section S WHERE ( 
                    P.uname = '$username' AND
                    P.sin = E.sin AND
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
                    P.uname = '$username' AND E.sin = P.sin)";
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

function roleExceeds($role, $required)
{
    if (isEmployee($role)) {
        if (isWarden($role)) {
            return true;
        }
        return (!isWarden($required));
    }
    return false;
}

function supervises($emp1, $emp2)
{
    $query = "SELECT * FROM 
                (SELECT E.ssin FROM employee E, people P WHERE (
                 P.uname = '$emp2' AND E.sin = P.sin)) NATURAL JOIN 
                (SELECT E.sin FROM employee E, people P WHERE (
                 P.uname = '$emp1' AND E.sin = P.sin))";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        return true;
    }
    return false;
}

function get_sin($uname)
{
    $query = "SELECT sin FROM employee E, people P WHERE
              P.uname = '$uname' AND E.sin = P.sin";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        return $result->fetch_row()[0];
    }
    return null;

}

?>

