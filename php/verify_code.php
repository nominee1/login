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
    $user_code = trim($_POST['code']);

    // Fetch the digits for the given email and check expiry
    $sql = "SELECT digits, expires_at FROM generated_digits WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_digits = $row['digits'];
        $expires_at = $row['expires_at'];

        // Check if the code matches and is still valid
        if ($user_code === $stored_digits && strtotime($expires_at) > time()) {
            // Code is valid, proceed to reset password
            echo "<div class='success'>Code verified! Redirecting to reset your password...</div>";
            header("refresh:2;url=reset_password.php?email=$email"); // Redirect in 2 seconds
        } else {
            echo "<div class='error'>Invalid or expired code. Please try again.</div>";
        }
    } else {
        echo "<div class='error'>No verification code found for this email.</div>";
    }
}

$conn->close();
?>

<!-- HTML form to input verification code with basic CSS -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
    <style>
        .success { color: green; }
        .error { color: red; }
        .container { width: 50%; margin: 0 auto; text-align: center; }
        input[type="submit"] { background-color: #4CAF50; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enter Verification Code</h1>
        <form action="verify_code.php" method="post">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="code">Verification Code:</label><br>
            <input type="text" id="code" name="code" required><br><br>
            
            <input type="submit" value="Verify Code">
        </form>
    </div>
</body>
</html>