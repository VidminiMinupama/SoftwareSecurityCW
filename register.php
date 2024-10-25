<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "TheRealSound";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Google reCAPTCHA secret key
$secretKey = "YOUR_SECRET_KEY"; // Replace this with your actual secret key

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get CAPTCHA response from the form
    $captchaResponse = $_POST['g-recaptcha-response'];

    // Verify CAPTCHA response
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse";
    $response = file_get_contents($verifyUrl);
    $responseData = json_decode($response);

    if ($responseData->success) {
        // CAPTCHA was successful, proceed with registration

        // Get form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing password for security

        // Insert data into the database using prepared statements
        $stmt = $conn->prepare("INSERT INTO register (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "CAPTCHA verification failed. Please try again.";
    }
}

$conn->close();
?>
