<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<BODY>
<?php
$user = htmlentities($_POST['user'], ENT_QUOTES, "UTF-8"); // login z formularza
$pass = htmlentities($_POST['pass'], ENT_QUOTES, "UTF-8");  // hasło z formularza
$rpass = htmlentities($_POST['rpass'], ENT_QUOTES, "UTF-8");  // hasło z formularza
$link = mysqli_connect('mysql1.small.pl', 'm1936_user4', 'User04', 'm1936_zad4'); // połączenie z BD – wpisać swoje dane

if (!$link) {
    echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
} else {
    if ($pass === $rpass) {
        mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
        $query = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
        if (mysqli_query($link, $query)) {
            // Utwórz folder o nazwie użytkownika
            $userFolder = __DIR__ . '/' . $user;
            if (!is_dir($userFolder)) {
                mkdir($userFolder);
                echo "Utworzono folder dla użytkownika: $user";
            } else {
                echo "Folder dla użytkownika $user już istnieje.";
            }

            header('Location: index3.php');
            exit();  // Dodałem exit, aby zakończyć skrypt po przekierowaniu.
        } else {
            echo "Rejestracja nie powiodła się (czy wszystkie pola są uzupełnione?).";
        }
    } else {
        echo "Podane hasła są różne.";
    }
    mysqli_close($link);
}
?>
</BODY>
</HTML>
