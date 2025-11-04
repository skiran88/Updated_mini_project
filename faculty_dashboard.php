<?php
session_start();
if (!isset($_SESSION['faculty_id'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Faculty Dashboard - College UPdated</title>
  <style>
    body {
      margin: 0;
      font-family: "Poppins", Arial, sans-serif;
      background-color: #F3F3F3;
      color: #222;
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 250px;
      background-color: #C6F53A;
      color: #222;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 40px;
      font-size: 22px;
      letter-spacing: 1px;
    }

    .sidebar a {
      text-decoration: none;
      color: #222;
      font-size: 15px;
      margin: 12px 0;
      display: block;
      padding: 10px 15px;
      border-radius: 8px;
      transition: background 0.3s, color 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #222;
      color: #F3F3F3;
    }

    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      padding: 30px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .topbar h1 {
      margin: 0;
      font-size: 24px;
      color: #222;
    }

    .search-bar input {
      padding: 8px 15px;
      border-radius: 20px;
      border: 1px solid #ccc;
      width: 200px;
      outline: none;
    }

    .section {
      margin-bottom: 40px;
    }

    .section h2 {
      font-size: 20px;
      color: #222;
      margin-bottom: 20px;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: #FFFFFF;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s, box-shadow 0.2s;
      position: relative;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
    }

    .card h3 {
      margin-top: 0;
      color: #222;
      font-size: 17px;
    }

    .card p {
      color: #555;
      font-size: 14px;
      margin-bottom: 15px;
    }

    .card button {
      background-color: #C6F53A;
      color: #222;
      border: none;
      border-radius: 5px;
      padding: 8px 15px;
      cursor: pointer;
      font-size: 13px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .card button:hover {
      background-color: #A2D82E;
    }

    .delete-btn {
      background-color: #ff5252;
      color: white;
      padding: 7px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }

    .delete-btn:hover {
      background-color: #e03e3e;
    }

    .upload-form {
      margin-top: 15px;
      padding-top: 10px;
      border-top: 1px solid #eee;
      display: none;
    }

    .upload-form label {
      font-size: 13px;
      display: block;
      margin: 8px 0 5px;
      color: #222;
    }

    .upload-form input[type="file"],
    .upload-form input[type="date"],
    .upload-form textarea,
    .upload-form input[type="text"] {
      width: 100%;
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 13px;
    }

    .upload-form textarea {
      resize: none;
      height: 60px;
    }

    .upload-form input[type="submit"] {
      background-color: #C6F53A;
      color: #222;
      border: none;
      border-radius: 5px;
      padding: 8px 15px;
      margin-top: 10px;
      cursor: pointer;
      width: 100%;
      font-weight: bold;
    }

    .upload-form input[type="submit"]:hover {
      background-color: #A2D82E;
    }

    ::-webkit-scrollbar {
      width: 6px;
    }
    ::-webkit-scrollbar-thumb {
      background: #C6F53A;
      border-radius: 5px;
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <div>
      <h2>UPdated</h2>
      <a href="#" class="active">Dashboard</a>
      <a href="#">Sports</a>
      <a href="#">Arts</a>
      <a href="#">Photography Club</a>
      <a href="#">Placements</a>
      <a href="#">Exams</a>
      <a href="#">Events</a>
    </div>
    <a href="logout.php" style="background:#222; color:#F3F3F3; text-align:center; border-radius:8px;">Logout</a>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Faculty NoticeBoard</h1>
      <div class="search-bar">
        <input type="text" placeholder="Search notices...">
      </div>
    </div>

    <div class="section">
      <h2>Upload Notices</h2>
      <div class="cards">
        <?php
        $categories = ["Sports", "Arts", "Photography Club", "Placements", "Exams", "Events"];
        foreach ($categories as $cat) {
            echo '
            <div class="card">
              <h3>' . $cat . '</h3>
              <p>Upload ' . strtolower($cat) . ' related notices.</p>
              <button onclick="toggleForm(this)">Upload Notice</button>

              <form class="upload-form" action="upload_notice.php" method="POST" enctype="multipart/form-data">
                <label for="title">Notice Title:</label>
                <input type="text" name="title" placeholder="Enter notice title" required>

                <label for="description">Notice Description:</label>
                <textarea name="description" placeholder="Enter short description..." required></textarea>

                <label for="pdf">Select PDF Notice:</label>
                <input type="file" name="pdf" accept="application/pdf" required>

                <label for="expiry">Expiry Date:</label>
                <input type="date" name="expiry" required>

                <input type="hidden" name="category" value="' . $cat . '">
                <input type="submit" value="Submit">
              </form>
            </div>';
        }
        ?>
      </div>
    </div>

    <div class="section">
      <h2>My Uploaded Notices</h2>
      <div class="cards">
        <?php
        $query = "SELECT * FROM notices ORDER BY uploaded_at DESC";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '
              <div class="card">
                <h3>' . htmlspecialchars($row['title']) . '</h3>
                <p><b>Category:</b> ' . htmlspecialchars($row['category']) . '</p>
                <p>' . htmlspecialchars($row['description']) . '</p>
                <p><b>Expiry:</b> ' . htmlspecialchars($row['expiry_date']) . '</p>
                <a href="' . htmlspecialchars($row['file_path']) . '" target="_blank">
                  <button>View PDF</button>
                </a>
                <form action="delete_notice.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this notice?\');" style="margin-top:10px;">
                  <input type="hidden" name="id" value="' . $row['id'] . '">
                  <button type="submit" class="delete-btn">Delete</button>
                </form>
              </div>';
          }
        } else {
          echo '<p>No notices uploaded yet.</p>';
        }
        ?>
      </div>
    </div>
  </div>

  <script>
    function toggleForm(button) {
      const form = button.nextElementSibling;
      form.style.display = form.style.display === "block" ? "none" : "block";
    }
  </script>
</body>
</html>
