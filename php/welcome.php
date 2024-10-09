<?php
// Database credentials
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'testDB';

// Create a new MySQLi connection
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_db);

// Check for connection errors
if ($mysqli->connect_error) {
    die('Connection failed: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Output connection success
echo 'Success: A proper connection to MySQL was made.<br>';
echo 'Host information: ' . $mysqli->host_info . '<br>';
echo 'Protocol version: ' . $mysqli->protocol_version . '<br>';

// Define variables and set to empty values
$fnameErr = $emailErr = $pwdErr = "";
$fname = $email = $pwd = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // First name validation
    if (empty($_POST["fname"])) {
        $fnameErr = "First Name is required";
    } else {
        $fname = test_input($_POST["fname"]);
        if (!preg_match("/^[a-zA-Z' ]*$/", $fname)) {
            $fnameErr = "Only letters and white space allowed";
        }
    }

    // Email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Password validation
    if (empty($_POST["pwd"])) {
        $pwdErr = "Password is required";
    } else {
        $pwd = test_input($_POST["pwd"]);
        // Password hashing for security
        $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);
    }

    // If no errors, proceed with inserting into the database
    if (empty($fnameErr) && empty($emailErr) && empty($pwdErr)) {
        // Use a prepared statement to avoid SQL injection
        $stmt = $mysqli->prepare("INSERT INTO users (Fullname, email, password) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: (' . $mysqli->errno . ') ' . $mysqli->error);
        }

        // Bind parameters (s for string type)
        $stmt->bind_param('sss', $fname, $email, $hashed_pwd);

        // Execute the statement
        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the MySQLi connection
$mysqli->close();

// Function to sanitize input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}