

<?php include 'project_header.txt'; ?>

<?php
  include_once 'grc_db.php'; // gives us $conn

  // Check whether the form has been submitted
  // $_SERVER['REQUEST_METHOD'] is 'POST' when the user submitted the form,
  // and 'GET' when they first visit the page in their browser.
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Extract and clean form data
    $risk_id = $_POST['risk_id'];
    $risk_title = trim($_POST['risk_title']);
    $risk_level = trim($_POST['risk_level']);
    $risk_status = trim($_POST['risk_status']);
    $employee_id = $_POST['employee_id'];

    // 2. Server-side validation
    $errors = []; // Find the next available index and use it.

    if (empty($risk_id) || !is_numeric($risk_id)) {
      $errors[] = "Risk ID is required and must be a number.";
    }

    if (empty($risk_title)) {
      $errors[] = "Risk title is required.";
    }

    if (empty($risk_level)) {
      $errors[] = "Risk level is required.";
    }

    if (empty($risk_status)) {
      $errors[] = "Risk status is required.";
    }

    if (empty($employee_id) || !is_numeric($employee_id)) {
      $errors[] = "Employee ID is required and must be a number.";
    }

    // 3. Insert or re-show form with errors
    if (empty($errors)) {
      try {
        $sql = "INSERT INTO Risk (risk_id, risk_title, risk_level, risk_status, employee_id)
                VALUES (:risk_id, :risk_title, :risk_level, :risk_status, :employee_id)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':risk_id', $risk_id, PDO::PARAM_INT);
        $stmt->bindParam(':risk_title', $risk_title);
        $stmt->bindParam(':risk_level', $risk_level);
        $stmt->bindParam(':risk_status', $risk_status);
        $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "<p><strong>New risk inserted successfully.</strong></p>";

      } catch (PDOException $e) {
        die("Insert failed: " . $e->getMessage());
      }
    }

    // If there were errors, fall through to show the form again.
    // $errors and the original $_POST values are still in scope.
  }
?>

<h2>Add New Risk</h2>

<?php
  // Show validation errors if any
  if (!empty($errors)) {
    echo "<div class='error'><ul>";

    foreach ($errors as $err) {
      echo "<li>" . htmlspecialchars($err) . "</li>";
    }

    echo "</ul></div>";
  }
?>

<!-- action="insert_risk.php" submits back to this same file -->
<form action="insert_risk.php" method="post">
  <label>Risk ID:</label>
  <!-- htmlspecialchars() re-fills fields safely after a failed submission -->
  <input
    type="text"
    name="risk_id"
    value="<?php echo htmlspecialchars($_POST['risk_id'] ?? ''); ?>"
    required
  /><br>

  <label>Risk Title:</label>
  <input
    type="text"
    name="risk_title"
    value="<?php echo htmlspecialchars($_POST['risk_title'] ?? ''); ?>"
    required
  /><br>

  <label>Risk Level:</label>
  <input
    type="text"
    name="risk_level"
    value="<?php echo htmlspecialchars($_POST['risk_level'] ?? ''); ?>"
    required
  /><br>

  <label>Risk Status:</label>
  <input
    type="text"
    name="risk_status"
    value="<?php echo htmlspecialchars($_POST['risk_status'] ?? ''); ?>"
    required
  /><br>

  <label>Employee ID:</label>
  <input
    type="text"
    name="employee_id"
    value="<?php echo htmlspecialchars($_POST['employee_id'] ?? ''); ?>"
    required
  /><br>

  <input type="submit" value="Insert" />
  <input type="reset" />
</form>

<?php
  $conn = null;
?>

<?php include 'project_footer.txt'; ?>