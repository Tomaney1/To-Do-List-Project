<?php
session_start();

require 'connection.php';

// Check if task ID and recipient user ID are set
if (isset($_POST['task_id']) && isset($_POST['recipient_user_id'])) {
    $taskId = $_POST['task_id'];
    $recipientUserId = $_POST['recipient_user_id'];

    // Insert task into recipient user's list
    $sql_send_task = "INSERT INTO tasks (user_id, task_name, priority, progress) SELECT '$recipientUserId', task_name, priority, progress FROM tasks WHERE id = $taskId";
    if ($conn->query($sql_send_task) === TRUE) {
        // Task sent successfully
        echo "Task sent successfully to user with ID: " . $recipientUserId;
    } else {
        // Error sending task
        echo "Error sending task: " . $conn->error;
    }
} else {
    // Parameters not set
    echo "Task ID and recipient user ID are required.";
}

$conn->close();
?>
