<?php
require '../constants.php';
$categories_select_options = null;
$message = null;
$task_list = null;

// Query for Selection of Category
$categories_sql = "SELECT CategoryID, CategoryName FROM taskCategory";

// Creating a connection and testing if connection is established or not
$connection = new MySQLi(HOST, USER, PASSWORD, DATABASE);
if ($connection->connect_errno) {
    die('Connection failed: ' . $connection->connect_error);
}
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

$task_list_sql = "SELECT * FROM task";

if (!$result = $connection->query($task_list_sql)) {
    echo "Something went wrong with the task list query";
    exit();
}

if (0 === $result->num_rows) {
    $task_list = '<tr><td colspan="4">There are no Active Things To Do</td></tr>';
} else {
    while ($row = $result->fetch_assoc()) {

    }
}

if ($_POST) {
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    //Sanitize Input fields using PREPARE statement
    // Add task to the list Here ? indicate Placeholders for the text
    if ($insert = $connection->prepare("INSERT INTO task(TaskID, CategoryID, TaskName, TaskTimeMinutes, StartDate,EndDate)

    VALUES(NULL, ?, ?, ?, ?, ?)")) {
        //Since Task id is NULL and auto incremented it is not accounted here; i is integer for CategoryID;s is string for task Name;
        //d is for double for TaskTime Minutes;s is string for start date; and s is string for end date

        if ($insert->bind_param("isdss", $_POST['task_category'], $_POST['task_name'], $_POST['task_time'], $_POST['start_date'], $_POST['end_date'])) {
            if ($insert->execute()) {
                $message = "You have added " . $_POST['task_name'] . " to the database";
            } else {
                exit("There was a problem with the execute");
            }
        } else {
            exit("There was a problem with the bind_param");
        }
    } else {
        exit("There was a problem with the prepare statement");
    }
    $insert->close();
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
    <?php if ($message) {
    echo $message;
}
?>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">

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


        <h2>Things To Do</h2>
        <table>
            <tr>
                <th>Category ID</th>
                <th>Task Name</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Shopping</td>
                <td>2020-10-15</td>
                <td>Edit</td>
            </tr>
            <?php echo $task_list; ?>

        </table>







    </form>

</body>

</html>