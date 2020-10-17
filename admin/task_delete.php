<!-- <?php

require '../constants.php';
$task_id = null;
$task_name = null;
$show_form = true;
$message = null;

$connection = new MySQLi(HOST, USER, PASSWORD, DATABASE);
if ($connection->connect_errno) {
    die("Connection failed:" . $connection->connect_error);
}

if (!$_POST) {
    if (!isset($_GET['task_id']) || $_GET['task_id'] === "") {
        exit("You have reached this page by mistake");
    }
    if (!isset($_GET['operation']) || $_GET['operation'] === "") {
        exit("You have reached this page by mistake");
    }

    if (filter_var($_GET['task_id'], FILTER_VALIDATE_INT)) {
        $task_id = $_GET['task_id'];
    } else {
        exit("An incorrect value for Task ID was used");
    }
// If the operation is not a string, do not continue
    if (filter_var($_GET['operation'], FILTER_SANITIZE_STRING)) {
        $operation = $_GET['operation'];
    } else {
        exit("***An incorrect value was passed***");
    }

    $sql = "SELECT * FROM task WHERE TaskID=$task_id";
    $result = $connection->query($sql);
    if (!$result) {
        exit("There was a problem fetching results");
    }
    if (0 === $result->num_rows) {
        exit("The task id provided did not match anyone in the database");
    }

    while ($row = $result->fetch_assoc()) {
        $task_name = $row['TaskName'];
    }
}

if ($_POST) {
    $show_form = false;
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    if (filter_var($_POST['task_id'], FILTER_VALIDATE_INT)) {
        $task_id = $_POST['task_id'];
    } else {
        exit("An incorrect value for Task ID was used");
    }

    $task_sql = "SELECT TaskName FROM task WHERE taskID = $task_id AND operation=$operation";
    $result = $connection->query($task_sql);
    if (!$result) {
        exit("There was a problem fetching results");
    }
    if (0 === $result->num_rows) {
        $delete_sql = "DELETE FROM task WHERE taskID = $task_id";
        if ($connection->query($delete_sql)) {
            $message = "task  deleted successfully";
        } else {
            exit("There was a problem deleting this task");
        }
    }
    // else {
    //     $message = "This task is dependent ";
    //     while ($row = $result->fetch_assoc()) {
    //         $message .= sprintf("%s, ", $row['Name']);
    //     }
    //     $message .= " and they cannot be removed. Change who looks after these animals and try again.";
    // }

}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Remove Task</title>
</head>

<body>
    <h1>Remove Task</h1>
    <?php if ($show_form): ?>
    <form action="#" method="POST">
        <p>Are you certain you want to remove <?php echo $task_name ?></p>
        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
        <input type="submit" value="Yes, remove task">
    </form>
    <?php else: ?>
    <p><?php echo $message; ?></p>
    <?php endif;?>
</body>

</html> -->