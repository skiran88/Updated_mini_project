<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Get file path before deleting
    $result = $conn->query("SELECT file_path FROM notices WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        $filePath = $row['file_path'];

        // Delete from database
        $conn->query("DELETE FROM notices WHERE id = $id");

        // Delete file if exists
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
header("Location: faculty_dashboard.php");
exit();
?>
