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
        // CAPTCHA was successful, proceed with login

        // Get form data
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the user exists using prepared statements
        $stmt = $conn->prepare("SELECT * FROM register WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Set session variables
                $_SESSION['username'] = $username;

                // Redirect to home page
                header("Location: home.html");
                exit(); // Make sure to exit after the redirect
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }

        $stmt->close();
    } else {
        echo "CAPTCHA verification failed. Please try again.";
    }
}

$conn->close();
?>
