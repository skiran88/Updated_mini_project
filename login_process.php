<?php
session_start(); // Start session to store user info

// --- 1. Database connection ---
$servername = "localhost";
$username = "root"; // default XAMPP
$password = "";     // default XAMPP
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- 2. Collect form data ---
$email = $_POST['email'];
$password = $_POST['password'];

// --- 3. Check if email exists ---
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User exists
    $row = $result->fetch_assoc();
    
    // --- 4. Verify password ---
    if (password_verify($password, $row['password'])) {
        // Password is correct
        $_SESSION['user'] = $email; // store email in session
        header("Location: dashboard.php"); // redirect to home/dashboard page
        exit();
    } else {
        // Incorrect password
        echo "Invalid password. <a href='login.html'>Try again</a>";
    }
} else {
    // Email not found
    echo "No account found with this email. <a href='signup.html'>Sign Up</a>";
}

$conn->close();
?>
