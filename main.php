<?php

    session_start();

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require 'connection.php';


    // Add task
    if (isset($_POST['add_task']) && !empty($_POST['task_name'])) {
        $taskName = $_POST['task_name'];
        $priority = $_POST['priority'];
        $progress = $_POST['progress'];
        $userId = $_SESSION['user_id'];


    // Insert task into database
    $sql_add_task = "INSERT INTO tasks (user_id, task_name, priority, progress) VALUES ('$userId', '$taskName', '$priority', '$progress')";
        if ($conn->query($sql_add_task) === TRUE) {
            // Redirect to avoid form resubmission
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error adding task: " . $conn->error;
        }
    }

    // Delete task
    if (isset($_GET['delete'])) {
        $taskId = $_GET['delete'];
        
        // Delete task from database
        $sql_delete_task = "DELETE FROM tasks WHERE id=$taskId";
        if ($conn->query($sql_delete_task) === TRUE) {
            // Redirect to avoid form resubmission
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error deleting task: " . $conn->error;
        }
    }

    // Edit task
    if (isset($_POST['edit_task']) && isset($_POST['task_id']) && isset($_POST['task_name'])) {
        $taskId = $_POST['task_id'];
        $taskName = $_POST['task_name'];
        $priority = $_POST['priority']; 
        $progress = $_POST['progress']; 
        
        // Update task in database
        $sql_edit_task = "UPDATE tasks SET task_name='$taskName', priority='$priority', progress='$progress' WHERE id=$taskId";
        if ($conn->query($sql_edit_task) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Task updated successfully.']);
            exit();
        } else {
            echo "Error updating task: " . $conn->error;
        }
    }

    // Fetch tasks from database
    $userId = $_SESSION['user_id']; // Retrieve user ID from session
    $sql_fetch_tasks = "SELECT * FROM tasks WHERE user_id = $userId";
    // Rest of the code remains the same
    $result = $conn->query($sql_fetch_tasks);
    $tasks = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    }



    $conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function editTask(taskId) {
            var newTaskName = prompt("Enter the new task name:", "");
            if (newTaskName !== null && newTaskName.trim() !== "") {
                var newPriority = prompt("Enter the priority:", "");
                if (newPriority !== null && newPriority.trim() !== "") {
                    var newProgress = prompt("Enter the progress:", "");
                    if (newProgress !== null && newProgress.trim() !== "") {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    alert(response.message);
                                    window.location.reload();
                                } else {
                                    alert(response.message);
                                }
                            }
                        };
                        xhr.open("POST", window.location.href, true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.send("edit_task=true&task_id=" + taskId + "&task_name=" + encodeURIComponent(newTaskName) + "&priority=" + encodeURIComponent(newPriority) + "&progress=" + encodeURIComponent(newProgress));
                    }
                }
            }
        }
    </script>
</head>
<body>

    <div class="header">
        <ul>
            <li style="padding: 0;"><h1>To do list</h1></li>
            <div class="container">             
                <li class="calendar"><a href='index.php'><img src="calendar_2.jpeg"></a></li>
                <li class="logout"> 
                    <a href='logout.php'><img src="logout_2.png"></a>
                </li>   
            </div>         
        </ul>        
    </div>


    <div id="todo-list">
        <h2>Task List</h2>
        <form method="post">
            <input type="text" name="task_name" placeholder="Enter task">
            <select name="priority" id="prioritySelect">               
                <option value="low">Priority...</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
            <select name="progress" id="progressionSelect">
                <option value="low">Progress...</option>
                <option value="notBegun">Not Started</option>
                <option value="inProgress">In Progress</option>
                <option value="Done">Complete</option>
            </select>
            <button id="task-button" type="submit" name="add_task">Add Task</button>

        </form>
        <div class="elements">
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    
                    <button class="edit-btn" onclick="editTask(<?php echo $task['id']; ?>)"></button>
                    <a class="delete-btn" href="?delete=<?php echo $task['id']; ?>"></a>            
                    <span><?php echo $task['task_name']; ?></span>
                    
                        <!-- Display priority -->
                        <?php 
                            if($task['priority'] == 'low') {
                                echo "<h6 id='low'>Low</h6>";
                            } elseif($task['priority'] == 'medium') {
                                echo "<h6 id='medium'>Medium</h6>";
                            } elseif($task['priority'] == 'high') {
                                echo "<h6 id='high'>High</h6>";
                            }
                        ?>
                        <!-- Display progress -->
                        <?php 
                            if($task['progress'] == 'notBegun' || $task['progress'] == 'not started' || $task['progress'] == 'not begun') {
                                echo "<h6 id='not_started'>Not Started</h6>";
                            } elseif($task['progress'] == 'In progress' || $task['progress'] == 'in progress' || $task['progress'] == 'inProgress') {
                                echo "<h6 id='in_progress'>In Progress</h6>";
                            } elseif($task['progress'] == 'Done' || $task['progress'] == 'done' || $task['progress'] == 'finished' || $task['progress'] == 'Finished') {
                                echo "<h6 id='done'>Complete</h6>";
                            }
                        ?>
                
                </li>
            <?php endforeach; ?>

     
        </ul>
    </div>
</body>
</html>
