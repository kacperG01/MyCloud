<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index3.php');
    exit();
}

$time = date('H:i:s', time());
$post = $_POST['post'];
$recp = $_POST['recipient'];

if (isset($_POST['post'])) {
    $dbhost = 'mysql1.small.pl';
    $dbuser = 'm1936_user3';
    $dbpassword = 'User03';
    $dbname = 'm1936_zad3';

    $connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

    if (!$connection) {
        echo "MySQL Connection error." . PHP_EOL;
        echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    $usr = $_SESSION['username'];
    $target_dir = './' . $usr . '/';
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo htmlspecialchars(basename($_FILES["file"]["name"])) . " uploaded.";
        // Pamiętaj, żeby użyć $target_file, a nie $file_path, który nie jest zdefiniowany w tym miejscu
        $result = mysqli_query($connection, "INSERT INTO messages (message, user, file_path, recipient) VALUES ('$post', '$usr', '$target_file', '$recp');") or die("DB error: $dbname");
    } else {
        echo "Error uploading file.";
        $result = mysqli_query($connection, "INSERT INTO messages (message, user, recipient) VALUES ('$post', '$usr', '$recp');") or die("DB error: $dbname");
    }

    mysqli_close($connection);
}

header('Location: index1.php');
?>
