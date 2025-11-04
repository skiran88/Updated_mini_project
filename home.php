<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>College NoticeBoard - Home</title>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f9fff1; /* light background */
      color: #000; /* black text */
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #C6F53A; /* bright green sidebar */
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 30px 20px;
      color: #000;
    }

    .logo {
      font-size: 22px;
      font-weight: bold;
      color: #000; /* black logo text */
      text-align: center;
      margin-bottom: 40px;
    }

    .menu a {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #333; /* darker text for readability */
      padding: 12px 15px;
      border-radius: 10px;
      margin-bottom: 10px;
      transition: 0.3s;
      font-size: 14px;
      font-weight: 500;
    }

    .menu a:hover, .menu a.active {
      background-color: #eaff94; /* soft hover effect */
      color: #000;
    }

    .menu a i {
      margin-right: 12px;
    }

    .logout {
      text-align: center;
    }

    .logout button {
      background-color: #000; /* black button */
      border: none;
      color: #C6F53A;
      font-weight: bold;
      padding: 10px 20px;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    .logout button:hover {
      background-color: #333;
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .header h1 {
      font-size: 24px;
      margin: 0;
      color: #000;
    }

    .search input {
      background-color: #fff;
      border: 2px solid #C6F53A;
      border-radius: 8px;
      padding: 8px 12px;
      color: #000;
      width: 200px;
      outline: none;
      transition: 0.3s;
    }

    .search input:focus {
      border-color: #a6d82f;
    }

    /* Cards Section */
    .section {
      margin-bottom: 40px;
    }

    .section h2 {
      margin-bottom: 20px;
      font-size: 18px;
      color: #000;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: #fff;
      padding: 20px;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 0 10px rgba(198, 245, 58, 0.3);
      transition: transform 0.2s ease, background-color 0.2s ease;
      color: #000;
    }

    .card:hover {
      transform: translateY(-5px);
      background-color: #f3ffd3;
    }

    .card img {
      width: 60px;
      height: 60px;
      margin-bottom: 10px;
    }

    .card h3 {
      margin: 10px 0 5px;
      color: #000;
    }

    .card p {
      font-size: 13px;
      color: #333;
    }

  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div>
      <div class="logo">UpdateED</div>
      <div class="menu">
        <a href="#" class="active">🏠 Dashboard</a>
        <a href="#">📘 Academics</a>
        <a href="#">📢 Notices</a>
        <a href="#">⚽ Activities</a>
        <a href="#">🧠 Learning</a>
      </div>
    </div>

    <div class="logout">
      <form action="logout.php" method="POST">
        <button type="submit">Logout</button>
      </form>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="header">
      <h1>College NoticeBoard</h1>
      <div class="search">
        <input type="text" placeholder="Search...">
      </div>
    </div>

    <div class="section">
      <h2>Recent</h2>
      <div class="cards">
        <div class="card">
          <img src="https://img.icons8.com/fluency/96/book.png" alt="Academics">
          <h3>Academics</h3>
          <p>Find all recent academic notices and updates here.</p>
        </div>

        <div class="card">
          <img src="https://img.icons8.com/fluency/96/confetti.png" alt="Events">
          <h3>Team Notices</h3>
          <p>All team and department-level announcements.</p>
        </div>

        <div class="card">
          <img src="https://img.icons8.com/fluency/96/party-popper.png" alt="Celebration">
          <h3>Event Notices</h3>
          <p>Special events and celebration information.</p>
        </div>
      </div>
    </div>

    <div class="section">
      <h2>Important</h2>
      <div class="cards">
        <div class="card">
          <img src="https://img.icons8.com/fluency/96/document.png" alt="General">
          <h3>General Updates</h3>
          <p>Read the most essential updates for all students.</p>
        </div>

        <div class="card">
          <img src="https://img.icons8.com/fluency/96/soccer-ball.png" alt="Sports">
          <h3>Events</h3>
          <p>Get the latest event and sports schedules.</p>
        </div>

        <div class="card">
          <img src="https://img.icons8.com/fluency/96/basketball.png" alt="Sports">
          <h3>Sports</h3>
          <p>Sports updates and tournament notices.</p>
        </div>

        <div class="card">
          <img src="https://img.icons8.com/fluency/96/megaphone.png" alt="Announcement">
          <h3>General Notices</h3>
          <p>Announcements and general circulars from college.</p>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
