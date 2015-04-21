<?php  

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

function roleExceeds($role, $required)
{
    if ($required == "employee") {
        return ($role == "employee" || $role == "warden");
    } else if ($required == "warden") {
        return $role == "warden";
    }
    return false;
}

function supervises($emp1, $emp2)
{
    global $con;
    $query = "SELECT * FROM 
                ((SELECT E.s_sin FROM employee E, people P WHERE (
                 P.uname = '$emp2' AND E.uname = P.uname)) AS t1 
                 INNER JOIN 
                 (SELECT E.sin FROM employee E, people P WHERE (
                 P.uname = '$emp1' AND E.uname = P.uname)) AS t2
                 ON t1.s_sin = t2.sin
                )";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        return true;
    }
    return false;
}

function get_sin($uname)
{
    global $con;
    $query = "SELECT sin FROM employee E, people P WHERE
              P.uname = '$uname' AND E.uname = P.uname";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        return $result->fetch_row()[0];
    }
    return null;

}

function get_name($sin)
{
    global $con;
    $query = "SELECT uname FROM employee E WHERE sin = '$sin'";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        return $result->fetch_row()[0];
    }
    return null;

}

?>

