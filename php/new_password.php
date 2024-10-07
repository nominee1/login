<?php
// Database connection
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'testDB';

$conn = new mysqli($db_host, $db_user, $db_password, $db_db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if passwords match
    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the users table
        $sql_update = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            echo "<div class='success'>Password reset successfully! Redirecting to login...</div>";
            header("refresh:2;url=login.php"); // Redirect to login page in 2 seconds
        } else {
            echo "<div class='error'>Error resetting password: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='error'>Passwords do not match. Please try again.</div>";
    }
}

$conn->close();