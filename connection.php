<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Database connection parameters
$servername = "localhost";
$username = "zsirajo1";
$password = "zsirajo1";
$database = "zsirajo1";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Create tasks table if not exists
$sql_create_table = "CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task_name VARCHAR(255) NOT NULL,
    priority VARCHAR(50) NOT NULL,
    progress VARCHAR(50) NOT NULL,
	due_date DATE
)";

// Create users table if not exists
$sql_create_users = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";


// Execute queries to create tables
if ($conn->query($sql_create_table) === TRUE && $conn->query($sql_create_users) === TRUE) {
    echo "";
} else {
    echo "Error creating table: " . $conn->error;
}



// Fetch users from the database
$sql_fetch_users = "SELECT DISTINCT user_id, username FROM users";
$result_users = $conn->query($sql_fetch_users);

// Check if the query was successful
if ($result_users) {
    // Check if there are any rows returned
    if ($result_users->num_rows > 0) {
        // Fetch user data and store it in the $users array
        while ($row = $result_users->fetch_assoc()) {
            $users[] = $row;
        }
    } else {
        // No users found in the database
        echo "No users found.";
    }
} else {
    // Error executing the query
    echo "Error fetching users: " . $conn->error;
}



?>
