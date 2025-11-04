<?php
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// --- LIVE SUGGESTIONS ---
if (isset($_GET['q'])) {
  $q = trim($_GET['q']);
  $escaped = $conn->real_escape_string($q);

  // Split search into words
  $words = explode(" ", $escaped);
  $conditions = [];
  foreach ($words as $w) {
    $w = trim($w);
    if ($w !== "") {
      $conditions[] = "description LIKE '%$w%'";
      $conditions[] = "SOUNDEX(description) = SOUNDEX('$w')";
    }
  }

  $where = !empty($conditions) ? implode(" OR ", $conditions) : "1=0";
  $sql = "SELECT DISTINCT description 
          FROM notices 
          WHERE $where 
          ORDER BY description 
          LIMIT 8";

  $res = $conn->query($sql);
  $out = [];
  if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      $out[] = $row['description'];
    }
  }

  header('Content-Type: application/json');
  echo json_encode($out);
  exit;
}

// --- FILTER RESULTS ---
if (isset($_GET['filter'])) {
  $f = trim($conn->real_escape_string($_GET['filter']));
  $sql = "SELECT * FROM notices 
          WHERE description LIKE '%$f%' 
             OR category LIKE '%$f%'
             OR SOUNDEX(description) = SOUNDEX('$f') 
          ORDER BY id DESC";

  $res = $conn->query($sql);

  if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
      echo '<div class="card">';
      echo '<h3>' . htmlspecialchars($r['category']) . '</h3>';
      echo '<p><strong>Description:</strong> ' . htmlspecialchars($r['description']) . '</p>';
      echo '<p>Expires on: ' . htmlspecialchars($r['expiry_date']) . '</p>';
      echo '<button onclick="window.open(\'' . htmlspecialchars($r['file_path']) . '\',\'_blank\')">View Notice</button>';
      echo '</div>';
    }
  } else {
    echo '<p>No notices found.</p>';
  }
}
?>
