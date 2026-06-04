

<?php include 'project_header.txt'; ?>

<?php
  include_once 'grc_db.php'; // gives us $conn

  // Check whether the form has been submitted
  // $_SERVER['REQUEST_METHOD'] is 'POST' when the user submitted the form,
  // and 'GET' when they first visit the page in their browser.
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Extract and clean form data
    $control_id = $_POST['control_id'];
    $control_name = trim($_POST['control_name']);
    $control_status = trim($_POST['control_status']);
    $employee_id = $_POST['employee_id'];

    // 2. Server-side validation
    $errors = []; // Find the next available index and use it.

    if (empty($control_id) || !is_numeric($control_id)) {
      $errors[] = "Control ID is required and must be a number.";
    }

    if (empty($control_name)) {
      $errors[] = "Control name is required.";
    }

    if (empty($control_status)) {
      $errors[] = "Control status is required.";
    }

    if (empty($employee_id) || !is_numeric($employee_id)) {
      $errors[] = "Employee ID is required and must be a number.";
    }

    // 3. Insert or re-show form with errors
    if (empty($errors)) {
      try {
        $sql = "INSERT INTO Control (control_id, control_name, control_status, employee_id)
                VALUES (:control_id, :control_name, :control_status, :employee_id)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':control_id', $control_id, PDO::PARAM_INT);
        $stmt->bindParam(':control_name', $control_name);
        $stmt->bindParam(':control_status', $control_status);
        $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "<p><strong>New control inserted successfully.</strong></p>";

      } catch (PDOException $e) {
        die("Insert failed: " . $e->getMessage());
      }
    }

    // If there were errors, fall through to show the form again.
    // $errors and the original $_POST values are still in scope.
  }
?>

<h2>Add New Control</h2>

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

<!-- action="insert_control.php" submits back to this same file -->
<form action="insert_control.php" method="post">
  <label>Control ID:</label>
  <input
    type="text"
    name="control_id"
    value="<?php echo htmlspecialchars($_POST['control_id'] ?? ''); ?>"
    required
  /><br>

  <label>Control Name:</label>
  <input
    type="text"
    name="control_name"
    value="<?php echo htmlspecialchars($_POST['control_name'] ?? ''); ?>"
    required
  /><br>

  <label>Control Status:</label>
  <input
    type="text"
    name="control_status"
    value="<?php echo htmlspecialchars($_POST['control_status'] ?? ''); ?>"
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