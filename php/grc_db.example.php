
<?php
  // grc_db.example.php
  // Example database connection file.
  // Copy this file to grc_db.php and replace the placeholder values
  // with your own database credentials before running the project locally.

  $host = "localhost";
  $dbname = "your_database_name";
  $user = "your_username";
  $pass = "your_password"; 

  try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  } catch (PDOException $e) {
    die("Could not connect to the database $dbname: " . $e->getMessage());
  }
?>