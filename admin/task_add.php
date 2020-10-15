<?php
require '../constants.php';
$categories_select_options = null;
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
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">

        <label for="task">Task</label>
        <input type="text" id="task" name="task">
        <label for="due_date">Due Date</label>
        <input type="date" name="due_date" min="2020-08-01" max="2021-01-01">
        <label for="task_category">Task Category</label>
        <select name="task_category" id="task_category">
            <option value="">Pick a Category</option>
            <?php echo $categories_select_options; ?>
        </select>
        <input type="submit" value="Add new task">

    </form>

</body>

</html>