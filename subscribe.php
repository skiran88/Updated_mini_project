<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_email = $_POST['student_email'];
    $category = $_POST['category'];

    // Check if already subscribed
    $check = $conn->prepare("SELECT * FROM subscription WHERE student_email = ? AND category = ?");
    $check->bind_param("ss", $student_email, $category);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Already subscribed!";
    } else {
        // Add subscription
        $stmt = $conn->prepare("INSERT INTO subscription (student_email, category) VALUES (?, ?)");
        $stmt->bind_param("ss", $student_email, $category);
        $stmt->execute();

        // Create notification message
        $message = "You have successfully subscribed to the '$category' category.";

        // Insert notification (notice_id can be NULL since this is a system message)
        $notice_id = NULL;
        $is_read = 0; // unread

        $notify = $conn->prepare("INSERT INTO notifications (student_email, notice_id, message, is_read, created_at)
                                  VALUES (?, ?, ?, ?, NOW())");
        $notify->bind_param("sssi", $student_email, $notice_id, $message, $is_read);
        $notify->execute();

        echo "Subscription successful and notification sent!";
    }

    $check->close();
    $stmt->close();
    $notify->close();
    $conn->close();
}
?>
