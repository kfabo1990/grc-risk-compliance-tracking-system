
<?php include 'project_header.txt'; ?>

<h1>Display Audit Results for Controls</h1>

<?php
  include_once 'grc_db.php';

  try {
    $stmt = $conn->query("SELECT a.audit_id, a.audit_name, a.audit_date, c.control_id, c.control_name, c.control_status, e.finding_status
      FROM Audit a, Evaluates e, Control c
      WHERE a.audit_id = e.audit_id
      AND e.control_id = c.control_id");

    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    echo "<table>\n";
    echo "<tr><th>Audit ID</th><th>Audit Name</th><th>Audit Date</th><th>Control ID</th><th>Control Name</th><th>Control Status</th><th>Finding Status</th></tr>\n";

    while ($row = $stmt->fetch()) {
      printf(
        "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
        $row['audit_id'],
        $row['audit_name'],
        $row['audit_date'],
        $row['control_id'],
        $row['control_name'],
        $row['control_status'],
        $row['finding_status']
      );
    }

    echo "</table>\n";

  } catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
  }

  $conn = null;
?>

<?php include 'project_footer.txt'; ?>