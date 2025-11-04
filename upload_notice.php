<?php
session_start();
include 'db_connect.php'; // database connection

// Ensure only logged-in faculty can upload
if (!isset($_SESSION['faculty_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $expiry = $_POST['expiry'];
    $uploadDir = "uploads/";

    // Create uploads folder if not present
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES["pdf"]["name"]);
    $targetFile = $uploadDir . time() . "_" . $fileName;

    // Upload the PDF file
    if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $targetFile)) {

        // Insert into notices table
        $sql = "INSERT INTO notices (category, file_path, description, expiry_date, title, uploaded_at)
                VALUES (?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("SQL error: " . $conn->error);
        }

        $stmt->bind_param("sssss", $category, $targetFile, $description, $expiry, $title);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Notice uploaded successfully!');
                    window.location='faculty_dashboard.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Database error while saving notice.');
                    window.location='faculty_dashboard.php';
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Error uploading file.');
                window.location='faculty_dashboard.php';
              </script>";
    }
}
$conn->close();
?>
