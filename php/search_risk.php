

<?php include 'project_header.txt'; ?>

<h2>Search Risks by Level</h2>

<form method="post">
  Risk Level: <input type="text" name="risk_level" />
  <input type="submit" value="Search" />
  <input type="reset" />
</form>

<?php
  // Only run search if form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include_once 'grc_db.php'; // database connection

    // Get and clean input
    $risk_level = trim($_POST['risk_level'] ?? '');

    try {
      // Prepare SELECT with named placeholder
      $sql = "SELECT risk_id, risk_title, risk_level, risk_status FROM Risk WHERE risk_level LIKE :risk_level";

      $stmt = $conn->prepare($sql);

      // Bind value with wildcard for partial match
      $stmt->bindValue(':risk_level', '%' . $risk_level . '%');

      $stmt->execute();

      $count = $stmt->rowCount();

      if ($count > 0) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Search Results (" . $count . " found):</h2>";

        echo "<table>\n";
        echo "<tr><th>Risk ID</th><th>Risk Title</th><th>Risk Level</th><th>Risk Status</th></tr>\n";

        foreach ($results as $row) {
          printf(
            "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
            htmlspecialchars($row['risk_id']),
            htmlspecialchars($row['risk_title']),
            htmlspecialchars($row['risk_level']),
            htmlspecialchars($row['risk_status'])
          );
        }

        echo "</table>\n";

      } else {
        echo "<p>No risks found matching '<b>" 
          . htmlspecialchars($risk_level) 
          . "</b>'.</p>";
      }

      // Close statement and connection
      $stmt = null;
      $conn = null;

    } catch (PDOException $e) {
      die("Search failed: " . $e->getMessage());
    }
  }
?>

<?php include 'project_footer.txt'; ?>