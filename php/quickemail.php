<?php 
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = htmlspecialchars($_POST["fullname"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $location = htmlspecialchars($_POST["location"]);
    $message = htmlspecialchars($_POST["message"]);

    // Here you can process the data, e.g., send an email or save it in a database
    // For demonstration, we'll just echo the values back
    echo "Thank you, $fullName. Your message has been received!";

} else {
    echo "Invalid request.";
}
