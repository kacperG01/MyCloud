<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index3.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_subdirectory = $_POST['new_subdirectory'];

    // Sprawdź, czy katalog o danej nazwie już nie istnieje
    $target_dir = $_SESSION['username'];
    $new_subdirectory_path = $target_dir . '/' . $new_subdirectory;

    if (!file_exists($new_subdirectory_path)) {
        mkdir($new_subdirectory_path);

        // Zapisz informacje o nowym katalogu do bazy danych
        $dbhost = 'mysql1.small.pl';
        $dbuser = 'm1936_user4';
        $dbpassword = 'User04';
        $dbname = 'm1936_zad4';

        $connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

        if ($connection) {
            $query = "INSERT INTO files (datetime, user, file_path) VALUES (CURRENT_TIMESTAMP, '$target_dir', '$new_subdirectory_path')";
            mysqli_query($connection, $query);
            mysqli_close($connection);
        }

        echo "Utworzono podkatalog: $new_subdirectory";
    } else {
        echo "Katalog o nazwie $new_subdirectory już istnieje.";
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Utwórz nowy podkatalog</h2>

<form action="" method="post">
  Nazwa podkatalogu: <input type="text" name="new_subdirectory" required>
  <input type="submit" value="Utwórz">
</form>

</body>
</html>
