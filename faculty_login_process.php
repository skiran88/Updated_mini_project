<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize inputs
$faculty_id = trim($_POST['faculty_id']);
$password = trim($_POST['password']);

// Validate inputs
if (empty($faculty_id) || empty($password)) {
    echo "<script>alert('Please fill in all fields'); window.location.href='faculty_login.html';</script>";
    exit();
}

// Prepared statement for safety
$stmt = $conn->prepare("SELECT * FROM faculty WHERE faculty_id = ?");
$stmt->bind_param("s", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Plain-text password check (for now)
    // 👉 If you used password_hash() while signup, replace this with password_verify($password, $row['password'])
    if ($password === $row['password']) {
        // ✅ FIXED: use consistent session name
        $_SESSION['faculty_id'] = $faculty_id;

        header("Location: faculty_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid password'); window.location.href='faculty_login.html';</script>";
    }
} else {
    echo "<script>alert('Faculty ID not found'); window.location.href='faculty_login.html';</script>";
}

$stmt->close();
$conn->close();
?>
