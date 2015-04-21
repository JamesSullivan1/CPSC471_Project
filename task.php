<html>
    <link rel="stylesheet" href="bootstrap.css" media="screen">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/>
    <head>
        <title>Task Management</title>		
    </head>
        <body bgcolor="#FFFFCC">
<?php
session_start();
require_once 'connect.php';
require_once 'member.php';

$err = "";

function addTask($uname, $descr, $sdate, $edate, $s_sin, $s_num) {
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }
    /* Verify the credentials of the adder and addee */
    $cuname = $_SESSION['username'];
    $crole = getUserType($cuname);
    $role = getUserType($uname);
    if (isEmployee($uname)) {
        $err = $uname . " isn't a detaine...";
        return false;
    }
    /* Verify the adder is a supervisor of addee, or they are a warden */
    if (!supervises($cuname, $s_sin) && !isWarden($cuname)) {
        $err = "Can't add a task for somebody you don't supervise.";
        return false;
    }

    if (has_task_during($uname, $sdate, $edate)) {
        $err = "Detainee " . $uname . " is already working a task then.";
        return false;
    } 
    else if (supervises_task_during($s_sin, $sdate, $edate)) {
        $err = "Employee" . $s_uname . " is already supervising a task then.";
        return false;
    } 

    /* Okay, update the table */
    $id = rand(); # Impenetrable next-level RNG
    $query = "INSERT INTO task VALUES ('$id', '$descr', '$sdate', 
            '$edate', '$s_sin', '$s_num')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $query = "INSERT INTO works VALUES ('$uname', '$id')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    return true; 
}

function removeTask($id, $uname) {
    global $con;
    global $err;
    /* Get the current user creds */
    if (!isset($_SESSION['username'])) {
        die();
        return false;
    }
    /* Verify the credentials of the adder and addee */
    $cuname = $_SESSION['username'];
    $query = "SELECT * FROM task T WHERE (T.id = '$id')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        return false;
    }
    /* Retrieve the SIN of the employee on the shift */
    $query = "SELECT s_sin FROM task INNER JOIN works ON t_id=id WHERE 
        (id = '$id')";
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    if ($count != 1) {
        return false;
    }
    $s_uname = get_name($result->fetch_row()[0]);
    /* Verify the adder is a supervisor of addee, or they are a warden */
    if (!supervises($cuname, $s_uname) || !isWarden($cuname)) {
        $err = "Can't remove a task for somebody you don't supervise.";
        return false;
    }
    $esin = get_sin($uname);
    /* Check that there's actually a task there */
    /* Okay, update the table */
    $query = "DELETE FROM task WHERE (id = '$id')";
    mysqli_query($con,$query) or die(mysqli_error($con));
    return true; 
}


function has_task_during($uname, $sdate, $edate) {
    global $con;
    $esin = get_sin($uname);
    $query = "SELECT * FROM works INNER JOIN task on id=t_id WHERE (
            works.d_uname = '$esin' AND 
            (('$sdate' < start AND '$edate' > start) OR
            ('$sdate' >= start AND '$sdate' < end)))";      
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    return ($count > 0);
}

function supervises_task_during($s_sin, $sdate, $edate) {
    global $con;
    $query = "SELECT * FROM works INNER JOIN task ON id=t_id WHERE (
            s_sin = '$s_sin' AND 
            (('$sdate' < start AND '$edate' > start) OR
            ('$sdate' >= start AND '$sdate' < end)))";      
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    $count = mysqli_num_rows($result);
    return ($count > 0);
}



$auth = isset($_SESSION['username']) && isEmployee($_SESSION['username']);
if ($auth == false) {
    header("Location: nope.php");
    die();
}

if (!isset($_POST['username'])) {
    header("Location: memberpage.php");
}
$en = $_POST['username'];
$sn = get_sin($_SESSION['username']);

if (isset($_POST['addingtask'])) {
    if ($_POST['sdate'] >= $_POST['edate']) {
        $err = "Can't add a time paradox.";
    } else {
        addTask($_POST['username'], $_POST['descr'], $_POST['sdate'], 
            $_POST['edate'], $sn, $_POST['snum']);
    }
} else if (isset($_POST['removingtask'])) {
    removeTask($_POST['id'], $en);
}

?>
<center>
<h1 style="display:inline">Task Management</h>
</center>
<br>
<br>
<center>
<table border="0" >
<tr>
<td>
<b>Add Task for <?php echo($en) ?></b>
</td>
<td>
<b>Remove Task for <?php echo($en) ?></b>
</td>
</tr>
<tr>
<td>
<form method="post" action="task.php" >
    <table border="0" >
    <tr>
    <td><b>Description</b></td>
    <td><input name="descr" type="text"></input></td>
    </tr> <br/>
    <tr>
    <td><b>Start Time</b></td>
    <td><input name="sdate" type="text" class="datetimepicker"></input></td>
    </tr> <br/>
    <tr>
    <td><b>End Time</b></td>
    <td><input name="edate" type="text" class="datetimepicker"></input></td>
    </tr> <br/>
    <tr>
    <td><b>Section Number</b></td>
    <td><input name="snum" type="text"></input></td>
    </tr> <br/>
    <tr>
      <td><input type="hidden" name="addingtask" />
	<input type="hidden" name="username" value="<?php echo $en ?>"></td>
    <td><input type="submit" value="Add Task"/></td>
    </tr>
    </table>
</form>
</td>
<td>
<form method="post" action="task.php" >
<select name="id" style="width:390px">
<?php
global $con;
$query = "SELECT * FROM task INNER JOIN works ON id=t_id WHERE (d_uname = '$en')";
$result = mysqli_query($con,$query) or die(mysqli_error($con));
while (($task = $result->fetch_row())) {
    echo("<option width=400px value=\"".$task[0]."\"> ".$task[2]." - ".$task[3]."</option>");
}
?>
</select>
<br>
<input type="submit" value="Remove Task"/>
<input type="hidden" name="removingtask"/>
<input type="hidden" name="username" value="<?php echo($en) ?>"/>
</form>
</td>
</tr>
</table>
</center>
<center>
<?php
if (!empty($err)) {
    echo("<b>".$err."</b><br>");    
}
?>
<br>
<a href='memberpage.php'>Go Back</a>
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

