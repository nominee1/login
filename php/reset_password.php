<?php
// Database credentials
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_db = 'testDB';

// MySQLi connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Get email from form submission

    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email exists in users table
        $sql_check = "SELECT UserID FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email found, proceed with code generation

            // Generate unique random digits
            $digits = generateUniqueRandomDigits();

            // Set expiration time (60 seconds from now)
            $expirationDuration = 60;
            $expires_at = date('Y-m-d H:i:s', time() + $expirationDuration);
            $created_at = date('Y-m-d H:i:s');

            // Delete any existing code for this email
            $sql_delete = "DELETE FROM generated_digits WHERE email = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("s", $email);
            $stmt_delete->execute();

            // Insert the new generated digits into the database
            $sql_insert = "INSERT INTO generated_digits (email, digits, created_at, expires_at) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssss", $email, $digits, $created_at, $expires_at);

            if ($stmt_insert->execute()) {
                // Send verification email
                sendVerificationEmail($email, $digits);
                echo "Verification email sent to $email!";
            } else {
                echo "Error storing digits: " . $conn->error;
            }
        } else {
            echo "Email does not exist in our records.";
        }
    } else {
        echo "Invalid email format.";
    }
}

$conn->close();

// Function to generate unique random digits
function generateUniqueRandomDigits() {
    $min = 1;
    $max = 9;
    $quantity = 6;
    $uniqueNumbers = [];

    while (count($uniqueNumbers) < $quantity) {
        $randomNumber = random_int($min, $max);
        if (!in_array($randomNumber, $uniqueNumbers)) {
            $uniqueNumbers[] = $randomNumber;
        }
    }

    return implode("", $uniqueNumbers);  // Return as a concatenated string
}

// Function to send the verification email
function sendVerificationEmail($to, $digits) {
    $subject = "Your Verification Code";

    // HTML message with company logo and marketing materials
    $message = "
    <html>
    <head>
        <title>Your Verification Code</title>
    </head>
    <body>
        <div style='text-align: center;'>
            <img src='https://yourdomain.com/logo.png' alt='Company Logo' style='width:150px;'>
            <h2>Hello,</h2>
            <p>Thank you for using our service. Your verification code is:</p>
            <h1 style='color: #4CAF50;'>$digits</h1>
            <p>This code is valid for the next 60 seconds. Please use it to verify your identity.</p>
            <p>For more updates, follow us on our social channels:</p>
            <a href='https://facebook.com/yourpage'>Facebook</a> |
            <a href='https://twitter.com/yourpage'>Twitter</a> |
            <a href='https://instagram.com/yourpage'>Instagram</a>
        </div>
    </body>
    </html>
    ";

    // Set content-type for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Additional headers
    $headers .= "From: noreply@yourdomain.com\r\n";
    $headers .= "Reply-To: noreply@yourdomain.com\r\n";

    // Send email
    mail($to, $subject, $message, $headers);
}