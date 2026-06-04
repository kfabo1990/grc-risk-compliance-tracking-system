
<?php include 'project_header.txt'; ?>

<h1>Display Risks with Owners and Departments</h1>

<?php
  // Pull in the database connection ($conn) from grc_db.php
  include_once 'grc_db.php';

  try {
    // Run a SELECT query using Risk, Employee, and Department
    $stmt = $conn->query("SELECT r.risk_id, r.risk_title, r.risk_level, r.risk_status, e.employee_name, d.dept_name FROM Risk r, Employee e, Department d WHERE r.employee_id = e.employee_id AND e.dept_id = d.dept_id ");

    // Fetch rows as associative arrays
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    // Start HTML table
    echo "<table>\n";
    echo "<tr><th>Risk ID</th><th>Risk Title</th><th>Risk Level</th><th>Risk Status</th><th>Employee Owner</th><th>Department</th></tr>\n";

    // Output each row
    while ($row = $stmt->fetch()) {
      printf(
        "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
        $row['risk_id'],
        $row['risk_title'],
        $row['risk_level'],
        $row['risk_status'],
        $row['employee_name'],
        $row['dept_name']
      );
    }

    echo "</table>\n";

  } catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
  }

  // Close connection
  $conn = null;
?>

<?php include 'project_footer.txt'; ?>