<?php
require '../constants.php';
$categories_select_options = null;
$message = null;
$task_list = null;

// Creating a connection and testing if connection is established or not
$connection = new MySQLi(HOST, USER, PASSWORD, DATABASE);
if ($connection->connect_errno) {
    die('Connection failed: ' . $connection->connect_error);
}

// Query for Selection of Category
$categories_sql = "SELECT CategoryID, CategoryName FROM taskCategory";

if (!$categories_result = $connection->query($categories_sql)) {
    echo "Something went wrong with the categories query";
    exit();
}
//To generate the Category from data base
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

// To generate the Task List
$task_list_sql = "SELECT * FROM task";

if (!$result = $connection->query($task_list_sql)) {
    echo "Something went wrong with the task list query";
    exit();
}

// To check if there are any empty records/rows
if (0 === $result->num_rows) {
    $task_list = '<tr><td colspan="5">There are no Active Things To Do</td></tr>';
} else {
    while ($row = $result->fetch_assoc()) {
        $task_list .= sprintf('
        <tr>
            <td>%d</td>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td><a href="task_complete.php?task_id=%d">Complete</a></td>
            <td><a href="task_delete.php?task_id=%d">Remove</a></td>
        </tr>
        ',
            $row['TaskID'],
            $row['TaskName'],
            $row['StartDate'],
            $row['EndDate'],
            $row['TaskID'],
            $row['TaskID']
        );

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
        <input type="submit" name="reset" value="Reset" />
        <h2>Things To Do</h2>
        <table>
            <tr>
                <th>Task ID</th>
                <th>Task Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
            <?php echo $task_list; ?>

        </table>
    </form>

</body>

</html>