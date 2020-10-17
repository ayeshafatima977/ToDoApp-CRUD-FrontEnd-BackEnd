<?php
require '../constants.php';
$categories_select_options = null;
$message = null;
$task_list = null;
$task_name = null;
$temp_taskName = null;
$staff_id = null;
$operation = null;
$task_status_message = null;
$completed_form = null;

// Creating a connection and testing if connection is established or not

$connection = new MySQLi(HOST, USER, PASSWORD, DATABASE);
if ($connection->connect_errno) {
    die('Connection failed: ' . $connection->connect_error);
}

if (isset($_POST['task_name'])) {

    //Assigning temporary variable so that task doesn't repeat when you refresh
    $temp_taskName = $_POST['task_name'];

    // Query to get tasks from the database
    $task_list_sql = "SELECT * FROM task";
    $repeated_tasks_flag = false;

    $result = $connection->query($task_list_sql);
    if (!$result = $connection->query($task_list_sql)) {
        echo "Something went wrong with the task list query";
        exit();
    }
//If the tasks are repeated don't populate in list and database
    while ($row = $result->fetch_assoc()) {
        // Add other categories such as Date ,category to check for any repeated
        if ($temp_taskName === $row['TaskName']) {
            $repeated_tasks_flag = true;
        }
        //var_dump($row);
    }

    if (!$repeated_tasks_flag) {
        //Sanitize Input fields using PREPARE statement
        // Add task to the list Here ? indicate Placeholders for the text
        $insert = $connection->prepare("INSERT INTO task(TaskID, CategoryID, TaskName, TaskTimeMinutes, StartDate,EndDate)
    VALUES(NULL, ?, ?, ?, ?, ?)");
        //Since Task id is NULL and auto incremented it is not accounted here; i is integer for CategoryID;s is string for task Name;
        //d is for double for TaskTime Minutes;s is string for start date; and s is string for end date
        $insert->bind_param("isdss", $_POST['task_category'], $_POST['task_name'], $_POST['task_time'], $_POST['start_date'], $_POST['end_date']);
        if ($insert->execute()) {
            $message = "You have added " . $_POST['task_name'] . " to the database";
        } else {
            exit("There was a problem with adding a task");
        }
        $insert->close();
    }
}

// For COMPLETED TASKS-Get the Task Id and UPDATE the Completed Status on click
//GET the URL key values
if (isset($_GET)) {
    if ($_GET['operation'] = "complete") {
        // Get the task status from database and update it if user clicks completed
        if ($task_id_statement = $connection->prepare("UPDATE task SET Completed=1 WHERE TaskID=?")) {
            if ($task_id_statement->bind_param("i", $_GET['task_id'])) {
                if ($task_id_statement->execute()) {
                    $task_id_message = "You have Completed task successfully";
                } else {
                    exit("There was a problem with the execute");
                }
            } else {
                exit("There was a problem with the bind_param");}
        } else {
            exit("There was a problem with the prepare statement");
        }
        $task_id_statement->close();
    }}

//If we don't have a task id, do not continue
if (!isset($_GET['task_id']) || $_GET['task_id'] === "") {
    exit("You have reached this page by mistake");
}

//SANITIZATION: If the task id is not an INT, do not continue
if (filter_var($_GET['task_id'], FILTER_VALIDATE_INT)) {
    $task_id = $_GET['task_id'];
} else {
    exit("An incorrect value was passed");
}
//To check status
$task_status_sql = "SELECT * FROM task WHERE Completed IS TRUE";
$task_status_result = $connection->query($task_status_sql);
//If result is False
if (!$task_status_result) {
    exit('There was a problem fetching results');
}
if (0 === $task_status_result->num_rows) {
    exit("There was no task with that ID");
}

while ($row = $task_status_result->fetch_assoc()) {
    $task_name .= $row['TaskName'];

}

if (isset($_GET)) {
    // Query to generate selection of category from database
    $categories_sql = "SELECT CategoryID, CategoryName FROM taskCategory";
    if (!$categories_result = $connection->query($categories_sql)) {
        echo "Something went wrong with the categories query";
        exit();
    }

    if ($categories_result->num_rows > 0) {
        while ($categories = $categories_result->fetch_assoc()) {
            $categories_select_options .= sprintf('
                        <option value="%s">%s</option>
                    ',
                $categories['CategoryID'],
                $categories['CategoryName']
            );
        }
    }

    //To generate the Task List only for tasks that are not completed
    $task_list_sql = "SELECT * FROM task  WHERE Completed IS NOT TRUE";

    if (!$result = $connection->query($task_list_sql)) {
        echo "Something went wrong with the task list query";
        exit();
    }

    // To check if there are any empty records/rows
    if (0 === $result->num_rows) {
        $task_list = '<tr><td colspan="4">There are no Active Things To Do</td></tr>';
    } else {
        while ($row = $result->fetch_assoc()) {

            $task_list .= sprintf('
            <tr>
                <td>%d</td>
                <td>%s</td>
                <td>%s</td>
                <td><a href="task_add.php?task_id=%d&operation=%s">Complete</a></td>
                <td><a href="task_add.php?task_id=%d&operation=%s">Delete</a></td>
            </tr>
            ',
                $row['TaskID'],
                $row['TaskName'],
                $row['EndDate'],
                $row['TaskID'],
                "complete",
                $row['TaskID'], "delete"
            );

        }
    }
}

$connection->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDoList</title>
</head>

<body>
    <h1>My ToDo List </h1>
    <h2>Add Todo</h2>
    <form action="#" method="POST" id="todo_form">
        <label for="task_name">Task</label>
        <input type="text" id="task_name" name="task_name">
        <label for="task_time">Task Time(Minutes)</label>
        <input type="number" step="any" name="task_time" id="task_time">
        <label for="start_date">Start Date</label>
        <input type="date" name="start_date" min="2020-08-01" max="2021-03-01">
        <label for="end_date">End Date</label>
        <input type="date" name="end_date" min="2020-08-01" max="2021-03-01">
        <label for="task_category">Task Category</label>
        <select name="task_category" id="task_category">
            <option value="">Pick a Category</option>
            <?php echo $categories_select_options; ?>
        </select>
        <input type="submit" value="Add new task">
    </form>
    <h2>Things To Do</h2>
    <table>
        <tr>
            <th>Task ID</th>
            <th>Task Name</th>
            <th>End Date</th>
            <th>Completed</th>
            <th>Deleted</th>
        </tr>

        <?php echo $task_list; ?>

    </table>
    <h2>Overdue Tasks</h2>


    <h2>Completed Tasks</h2>
    <?php if ($task_status_message) {
    echo $task_status_message;
}
?>
    <ul>
        <li> <?php echo $task_name; ?></li>
    </ul>

</body>

</html>