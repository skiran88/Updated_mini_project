<?php
session_start();

$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$student_email = $_SESSION['user'];

// Ensure subscriptions table
$conn->query("
  CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_email VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY ux_student_category (student_email, category)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Handle subscribe/unsubscribe
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['category'], $_POST['category_action'])) {
  $category = trim($_POST['category']);
  $action = $_POST['category_action'];

  if ($action === "subscribe") {
    $stmt = $conn->prepare("INSERT INTO subscriptions (student_email, category) VALUES (?, ?) ON DUPLICATE KEY UPDATE student_email = student_email");
    if ($stmt) {
      $stmt->bind_param("ss", $student_email, $category);
      $stmt->execute();
      $stmt->close();
    }

    // ✅ Add notification when subscribed
    $message = "You have successfully subscribed to the '$category' category.";
    $notice_id = NULL;
    $is_read = 0;

    $notify = $conn->prepare("INSERT INTO notifications (student_email, notice_id, message, is_read, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($notify) {
      $notify->bind_param("sssi", $student_email, $notice_id, $message, $is_read);
      $notify->execute();
      $notify->close();
    }

  } elseif ($action === "unsubscribe") {
    $stmt = $conn->prepare("DELETE FROM subscriptions WHERE student_email = ? AND category = ?");
    if ($stmt) {
      $stmt->bind_param("ss", $student_email, $category);
      $stmt->execute();
      $stmt->close();
    }

    // ✅ Add notification when unsubscribed
    $message = "You have unsubscribed from the '$category' category.";
    $notice_id = NULL;
    $is_read = 0;

    $notify = $conn->prepare("INSERT INTO notifications (student_email, notice_id, message, is_read, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($notify) {
      $notify->bind_param("sssi", $student_email, $notice_id, $message, $is_read);
      $notify->execute();
      $notify->close();
    }
  }

  // Only redirect if not AJAX
  if (empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header("Location: dashboard.php");
    exit();
  }
}

// Fetch categories
$categories = [];
$resCats = $conn->query("SELECT DISTINCT category FROM notices");
if ($resCats && $resCats->num_rows > 0) {
  while ($r = $resCats->fetch_assoc()) {
    $categories[] = $r['category'];
  }
}

// Add default categories
$defaultCategories = ["Academics", "Sports", "Arts", "Photography Club", "Placements", "Exams", "Events"];
foreach ($defaultCategories as $default) {
  if (!in_array($default, $categories, true)) {
    $categories[] = $default;
  }
}

// Fetch user subscriptions
$userSubs = [];
$stmt = $conn->prepare("SELECT category FROM subscriptions WHERE student_email = ?");
if ($stmt) {
  $stmt->bind_param("s", $student_email);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $userSubs[] = $row['category'];
  }
  $stmt->close();
}

// Fetch notices
$notices = $conn->query("SELECT * FROM notices ORDER BY id DESC");

// Notices expiring tomorrow
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$important = $conn->query("SELECT * FROM notices WHERE expiry_date = '$tomorrow' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard</title>
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background-color: #f9f9f9;
      height: 100vh;
      display: flex;
      overflow: hidden;
    }

    /* Sidebar */
    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, #e63946, #b81d29);
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 0 20px 20px 0;
      overflow-y: auto;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
    }

    .sidebar h2 {
      text-align: center;
      padding: 25px;
      margin: 0;
      font-size: 22px;
      letter-spacing: 1px;
      background: rgba(255, 255, 255, 0.1);
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .sidebar section {
      padding: 20px;
    }

    .sidebar h3 {
      font-size: 16px;
      text-transform: uppercase;
      color: #ffe5e5;
      margin-bottom: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
      padding-bottom: 5px;
    }

    .category-list, .subscription-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .category {
      background: rgba(255, 255, 255, 0.15);
      padding: 10px 12px;
      border-radius: 8px;
      color: #fff;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
      text-align: center;
    }

    .category:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateY(-2px);
    }

    .logout {
      text-align: center;
      padding: 15px;
      background: rgba(255, 255, 255, 0.1);
      cursor: pointer;
      border-top: 1px solid rgba(255, 255, 255, 0.3);
      transition: 0.3s;
    }

    .logout:hover { background: rgba(255, 255, 255, 0.25); }

    /* Main */
    .main {
      margin-left: 260px;
      flex: 1;
      padding: 20px 40px;
      overflow-y: auto;
      height: 100vh;
    }

    h1 { color: #333; }

    .search-bar { margin-bottom: 20px; }
    input[type="text"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    .card {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      margin-bottom: 20px;
      transition: 0.2s;
    }

    .card:hover { transform: translateY(-4px); }

    button {
      background: #e63946;
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      color: white;
      cursor: pointer;
    }

    button:hover { background: #c71f2b; }

    .cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill,minmax(280px,1fr));
      gap: 16px;
    }

    .small-note { color: #666; font-size: 13px; }

    /* Checkbox styling */
    .subscription-checkbox {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(255, 255, 255, 0.15);
      padding: 10px 12px;
      border-radius: 8px;
      color: #fff;
      font-weight: 500;
      transition: all 0.2s;
      cursor: pointer;
    }

    .subscription-checkbox input[type="checkbox"] {
      accent-color: #fff;
      width: 18px;
      height: 18px;
      cursor: pointer;
    }

    .subscription-checkbox:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateY(-2px);
    }

    .subscription-checkbox.active {
      background: #fff;
      color: #e63946;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <div>
      <h2>Dashboard</h2>

      <section>
        <h3>Categories</h3>
        <div class="category-list">
          <?php foreach ($categories as $cat): ?>
            <div class="category" onclick="filterCategory('<?php echo htmlspecialchars($cat, ENT_QUOTES); ?>')">
              <?php echo htmlspecialchars($cat); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <section>
        <h3>Subscriptions</h3>
        <form method="POST" id="subscriptionForm">
          <div class="subscription-list">
            <?php foreach ($categories as $cat): 
              $isSubscribed = in_array($cat, $userSubs, true);
            ?>
              <label class="subscription-checkbox <?php echo $isSubscribed ? 'active' : ''; ?>">
                <input 
                  type="checkbox" 
                  name="subscriptions[]" 
                  value="<?php echo htmlspecialchars($cat, ENT_QUOTES); ?>" 
                  onchange="toggleSubscription(this)" 
                  <?php echo $isSubscribed ? 'checked' : ''; ?>>
                <span><?php echo htmlspecialchars($cat); ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </form>
      </section>
    </div>

    <div class="logout" onclick="window.location='logout.php'">Logout</div>
  </div>

  <div class="main">
    <h1>Welcome, Student</h1>

    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Search notices..." onkeyup="searchNotices(this.value)">
      <p class="small-note">Suggestions will appear as you type.</p>
    </div>

    <div class="section">
      <h2>📢 Important (Expiring Tomorrow)</h2>
      <div class="cards-grid">
        <?php
        if ($important && $important->num_rows > 0) {
          while ($r = $important->fetch_assoc()) {
            echo "<div class='card'>
                    <h3>" . htmlspecialchars($r['category']) . "</h3>
                    <p><strong>Description:</strong> " . htmlspecialchars($r['description']) . "</p>
                    <p>Expires on: " . htmlspecialchars($r['expiry_date']) . "</p>
                    <button onclick=\"window.open('" . htmlspecialchars($r['file_path'], ENT_QUOTES) . "', '_blank')\">View Notice</button>
                  </div>";
          }
        } else {
          echo "<p class='small-note'>No important notices for tomorrow.</p>";
        }
        ?>
      </div>
    </div>

    <div class="section">
      <h2>All Notices</h2>
      <div id="notice-container" class="cards-grid">
        <?php
        if ($notices && $notices->num_rows > 0) {
          while ($row = $notices->fetch_assoc()) {
            echo "<div class='card'>
                    <h3>" . htmlspecialchars($row['category']) . "</h3>
                    <p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>
                    <p>Expires on: " . htmlspecialchars($row['expiry_date']) . "</p>
                    <button onclick=\"window.open('" . htmlspecialchars($row['file_path'], ENT_QUOTES) . "', '_blank')\">View Notice</button>
                  </div>";
          }
        } else {
          echo "<p class='small-note'>No notices available.</p>";
        }
        ?>
      </div>
    </div>
  </div>

  <script>
    function filterCategory(cat) {
      fetch("search_suggestions.php?filter=" + encodeURIComponent(cat))
        .then(res => res.text())
        .then(html => {
          document.getElementById("notice-container").innerHTML = html;
        });
    }

    function searchNotices(q) {
      if (!q || q.trim() === "") {
        location.href = "dashboard.php";
        return;
      }
      fetch("search_suggestions.php?filter=" + encodeURIComponent(q))
        .then(res => res.text())
        .then(html => {
          document.getElementById("notice-container").innerHTML = html;
        });
    }

    function toggleSubscription(checkbox) {
      const formData = new FormData();
      formData.append('category', checkbox.value);
      formData.append('category_action', checkbox.checked ? 'subscribe' : 'unsubscribe');

      fetch('dashboard.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(() => {
        checkbox.parentElement.classList.toggle('active', checkbox.checked);
      })
      .catch(err => console.error('Subscription update failed:', err));
    }
  </script>

</body>
</html>
