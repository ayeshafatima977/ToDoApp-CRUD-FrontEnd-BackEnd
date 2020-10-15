<?php
require '../constants.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDoList</title>
</head>

<body>
    <h1>Add Todo</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
        <p>
            <label for="task">Task</label>
            <input type="text" id="task" name="task">
        </p>
        <p>
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" min="2020-08-01" max="2021-01-01">
        </p>
        <p>
            <label for="task_category">Task Category</label>
            <select name="task_category" id="task_category">
                <option value="">Pick a Category</option>
            </select>
        </p>
        <p>
            <input type="submit" value="Add new task">
        </p>
    </form>

</body>

</html>