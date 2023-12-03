<?php
  session_start();
  
  if (!isset($_SESSION['loggedin'])) {
    header('Location: index3.php');
    exit();
  }
  
  $target_dir = isset($_GET['dir']) ? $_GET['dir'] : $_SESSION['username'];
  $target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
  
  $dbhost = 'mysql1.small.pl';
  $dbuser = 'm1936_user4';
  $dbpassword = 'User04';
  $dbname = 'm1936_zad4';
  
  $connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
  
  if (!$connection) {
    echo "MySQL Connection error." . PHP_EOL;
    echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Error: " . mysqli_connect_error() . PHP_EOL;
    exit;
  }
  
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " uploaded.";
    $result = mysqli_query($connection, "INSERT INTO files (datetime, user, file_path) VALUES (CURRENT_TIMESTAMP, '$target_dir', '$target_file');") or die("DB error: $dbname");
    header("Location: select.php?dir=" . urlencode($target_dir));
  } else {
    echo "Error uploading file.";
  }
  
  mysqli_close($connection);
  ?>
