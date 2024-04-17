<?php
// Include the connection file
require 'connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Initialize variables
$username = "";
$password = "";

if(isset($_POST['signin'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if the database connection is established
    if ($conn) {
        // Query to fetch user details including user_id
        $check_database_query = mysqli_query($conn, "SELECT user_id FROM users WHERE username = '$username' AND password = '$password'");
        $check_login_query = mysqli_num_rows($check_database_query);
        
        if($check_login_query == 1){
            $row = mysqli_fetch_array($check_database_query);
            $user_id = $row['user_id'];
            $fullname = $row['fullname'];

            // Store user_id in session
            $_SESSION['user_id'] = $user_id;

            // Store username in session if needed
            $_SESSION['username'] = $username;

            // Redirect to main page
            header("Location: main.php");
            exit();
        } else {
            echo "Login failed. Please check your username and password.";
        }
    } else {
        echo "Database connection failed.";
    }
}

// Handle sign-up form submission
if(isset($_POST['signup'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Insert user into database
    $insert_user_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if(mysqli_query($conn, $insert_user_query)){

        // Add JavaScript code to show popup after successful signup
        echo '<script>alert("User registered successfully!");</script>';
    } else {
        echo "Error: " . $insert_user_query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <link rel="stylesheet" href="styles.css">
    <title>To-do List</title>
      <script>
        function showPopup(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <div class="loginform">
        <img src="todolist.jpg" alt="ToDoListPhoto" class="forphoto">
        <form action="login.php" method="POST">
            <br>
            <input type="text" name="username" placeholder="Username" style="display:block; margin : 0 auto;" value ="
                <?php
                    if(isset($_SESSION['username'])){
                        echo $_SESSION['username'];
                    }
                ?>
            ">
            <br>
            <input type="password" name="password" placeholder="Password" style="display:block; margin : 0 auto;">
            <br>
            <div class="buttonsCenter">
                <input type="submit" name="signin" value="Log In">
                <input type="submit" name="signup" value="Sign Up">
            </div>
        </form>
    </div>
</body>
</html>
