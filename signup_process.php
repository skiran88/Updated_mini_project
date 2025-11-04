<?php
// --- 1. Database connection ---
$servername = "localhost";
$username = "root";      // default for XAMPP
$password = "";          // default for XAMPP
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- 2. Collect data from form ---
$student_id = $_POST['student_id'];
$email = $_POST['email'];
$password = $_POST['password'];
$department = $_POST['department'];

// --- 3. Hash the password ---
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// --- 4. Insert into database ---
$sql = "INSERT INTO users (student_id, email, password, department) 
        VALUES ('$student_id', '$email', '$hashed_password', '$department')";

if ($conn->query($sql) === TRUE) {
    // --- 5. Redirect to login page ---
    header("Location: login.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
