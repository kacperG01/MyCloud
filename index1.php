<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
    <form method="POST" action="add.php" enctype="multipart/form-data"><br>
        Post:<input type="text" name="post" id="post"><br>
      <select name="recipient">
        <?php
          session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
          if (!isset($_SESSION['loggedin']))
          {
            header('Location: index3.php');
            exit();
          }
          
          // Pobierz listę użytkowników z bazy danych
          $dbhost = 'mysql1.small.pl';
          $dbuser = 'm1936_user3';
          $dbpassword = 'User03';
          $dbname = 'm1936_zad3';
          $connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
          
          if ($connection) {
            $result = mysqli_query($connection, "SELECT username FROM users WHERE username!='".$_SESSION['username']."'") or die("DB error: $dbname");
            
            while ($row = mysqli_fetch_array($result)) {
              $username = $row['username'];
              echo "<option value='$username'>$username</option>";
            }
            
            mysqli_close($connection);
          }
          ?>
        </select><br>

        Wybierz plik: <input type="file" name="file"><br>
        <input type="submit" value="Send"/>
    </form>
  <?php 
          
          session_start(); // zapewnia dostęp do zmiennych sesyjnych w danym pliku
          if (!isset($_SESSION['loggedin']))
          {
            header('Location: index3.php');
            exit();
          }
          
          echo "Czatujesz jako: ".$_SESSION['username'].".<br/><a href='logout.php'>Wylogować się?</a>";
          
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
          
          // Zapytanie sprawdzające, czy użytkownik jest administratorem
          $username = $_SESSION['username'];
          $admin_query = mysqli_query($connection, "SELECT admin FROM users WHERE username='$username'") or die("DB error: $dbname");
          $admin_row = mysqli_fetch_assoc($admin_query);
          $is_admin = $admin_row['admin'];
          
          // Zmienione zapytanie w zależności od tego, czy użytkownik jest administratorem
          if ($is_admin) {
            $result = mysqli_query($connection, "SELECT * FROM messages ORDER BY idk DESC;") or die("DB error: $dbname");
          } else {
            $result = mysqli_query($connection, "SELECT * FROM messages WHERE user='$username' OR recipient='$username' ORDER BY idk DESC;") or die("DB error: $dbname");
          }
          
          print "<TABLE CELLPADDING=5 BORDER=1>";
          print "<TR><TD>Date/Time</TD><TD>User</TD><TD>Recipient</TD><TD>Message</TD></TR>\n";
          while ($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $date = $row[1];
            $message = $row[2];
            $user = $row[3];
            $file_path = "./".$row['file_path'];
            $rec = $row['recipient'];
            
            print "<TR><TD>$date</TD><TD>$user</TD><TD>$rec</TD>";
            
            // Wyświetl link do pliku, jeśli istnieje
            if (!empty($file_path)) {
              $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
              switch ($file_extension) {
                case 'png':
                case 'gif':
                case 'jpg':
                  echo "<TD>$message<img src='$file_path' alt='image' style='max-width: 100px; max-height: 100px;'></TD>";
                  break;
                case 'mp3':
                  echo "<TD>$message<audio controls><source src='$file_path' type='audio/mp3'></audio></TD>";
                  break;
                case 'mp4':
                  echo "<TD>$message<video width='100' height='100' controls><source src='$file_path' type='video/mp4'></video></TD>";
                  break;
                default:
                  echo "<TD>$message</TD>";
                                      }
            } else {
              echo "<TD>$message</TD>";
            }
            
            print "</TR>\n";
          }
          print "</TABLE>";
          
          mysqli_close($connection);
         ?>
</body>
</html>
