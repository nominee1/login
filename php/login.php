<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'testDB');

// Create a new MySQLi connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    error_log('Connection failed: ' . $mysqli->connect_error);
    echo "<h2>Could not connect to the database. Please try again later.</h2>";
    exit();
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Assuming password is not sanitized for security reasons

    // Prepare the SQL statement to select user data
    $stmt = $mysqli->prepare("SELECT * FROM Users WHERE email = ?");
    if ($stmt === false) {
        error_log('Prepare failed: ' . $mysqli->error);
        echo "<h2>Could not prepare the statement. Please try again later.</h2>";
        exit();
    }

    // Bind the email parameter to the statement
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Login successful
            echo "<h2>Login Successful</h2>";
        } else {
            // Invalid password
            echo "<h2>Invalid Email or Password</h2>";
        }
    } else {
        // User not found
        echo "<h2>Invalid Email or Password</h2>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$mysqli->close();
