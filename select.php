<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styl.css">
</head>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
  <!-- Pole z plikiem do przesłania -->
  Select file to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">

  <!-- Przycisk z zawartością SVG -->
  <button type="submit" name="submit">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-upload" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
      <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708z"/>
    </svg>
    Upload
  </button>
</form>

  <?php
    session_start();

    if (!isset($_SESSION['loggedin'])) {
      header('Location: index3.php');
      exit();
    }

    $current_directory = isset($_GET['dir']) ? $_GET['dir'] : $_SESSION['username'];
    $is_subdirectory = $current_directory != $_SESSION['username'];

    echo "Zalogowano jako: ".$_SESSION['username'].".<br/><a href='logout.php'>Wylogować się?</a>";

    echo "<h2>Lista plików:</h2>";

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Sprawdź, czy został przesłany formularz usuwania
      if (isset($_POST['delete-file'])) {
        $fileToDelete = $_POST['delete-file'];

        // Sprawdź, czy to plik czy folder
        if (is_file($fileToDelete)) {
          // Usuń plik z systemu plików
          unlink($fileToDelete);
        } elseif (is_dir($fileToDelete)) {
          // Usuń folder z systemu plików, ale tylko jeśli jest pusty
          if (is_dir_empty($fileToDelete)) {
            rmdir($fileToDelete);
          } else {
            echo "Nie można usunąć folderu, ponieważ nie jest pusty.";
          }
        }

        // Usuń wpis z bazy danych
        $deleteQuery = "DELETE FROM files WHERE file_path = '$fileToDelete'";
        $deleteResult = mysqli_query($connection, $deleteQuery);

        if (!$deleteResult) {
          echo "Błąd podczas usuwania pliku/folderu z bazy danych: " . mysqli_error($connection);
        }
      }
    }

    function is_dir_empty($dir) {
      $handle = opendir($dir);
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          closedir($handle);
          return false;
        }
      }
      closedir($handle);
      return true;
    }

    $query = "SELECT file_path FROM files WHERE user = '$current_directory'";
    $result = mysqli_query($connection, $query);

    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $file_path = $row['file_path'];
        $file_name = basename($file_path);

        // Sprawdź, czy to plik czy folder
        if (is_file($file_path)) {
          echo "<div class='file-box'>";
          $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);

          // Wyświetl miniaturę dla plików graficznych
          if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Dodaj hiperlink otwierający obrazek w nowej karcie
            echo "<a href='$file_path' target='_blank'>";
            echo "<img src='thumbnail.php?file=$file_path' alt='$file_name'>";
            echo "</a>";
          } elseif (in_array($file_extension, ['mp3'])) {
            // Dodaj odtwarzacz audio
            echo "<audio controls><source src='$file_path' type='audio/mp3'></audio>";
          } elseif (in_array($file_extension, ['mp4'])) {
            // Dodaj odtwarzacz wideo
            echo "<video controls width='100' height='100'><source src='$file_path' type='video/mp4'></video>";
          } else {
            // Pozostałe pliki wyświetlaj jako linki do pobrania
            echo "<a class='download-link' href='download.php?file=" . urlencode($file_path) . "'>$file_name</a>";
          }

          // Dodaj link do usuwania pliku
          echo "<form method='post'>";
          echo "<input type='hidden' name='delete-file' value='$file_path'>";
            echo "<button type='submit'>
              
              <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>
  <path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>
</svg>
              
              </button>";
          echo "</form>";

          echo "<div class='file-info'>";
          echo "<div class='file-name' data-file-path='$file_path'>$file_name</div>";
          echo "</div>";
          echo "</div>";
        } elseif (is_dir($file_path)) {
          if ($is_subdirectory) {
            // W podkatalogach tylko nazwy folderów, bez linków
            echo "<div class='file-box'>";
            echo "<div class='folder-icon'></div>";
            echo "<div class='file-info'>";
            echo "<div class='file-name'>$file_name</div>";
            
            // Dodaj link do usuwania folderu (jeśli jest pusty)
          if (is_dir_empty($file_path)) {
            echo "<form method='post'>";
            echo "<input type='hidden' name='delete-file' value='$file_path'>";
            echo "<button type='submit'>
              
              <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>
  <path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>
</svg>
              
              </button>";
            echo "</form>";
          }
            
            echo "</div>";
            echo "</div>";
          } else {
            // W folderze macierzystym linki do podkatalogów
            echo "<div class='file-box'>";
            echo "<div class='folder-icon'></div>";
            echo "<div class='file-info'>";
            echo "<div class='file-name'><a href='?dir=$current_directory/$file_name'>$file_name</a></div>";
            
            // Dodaj link do usuwania folderu (jeśli jest pusty)
          if (is_dir_empty($file_path)) {
            echo "<form method='post'>";
            echo "<input type='hidden' name='delete-file' value='$file_path'>";
            echo "<button type='submit'>
              
              <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>
  <path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>
</svg>
              
              </button>";
            echo "</form>";
          }
            
            echo "</div>";
            echo "</div>";
          }
        }
      }

      // Dodaj link do dodawania podfolderów tylko w folderze macierzystym
      if (!$is_subdirectory) {
        echo "<div class='file-box'>";
        echo "<a class='create-subdirectory' href='create_subdirectory.php?dir=$current_directory'>

          <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
<path d='m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672Z'/>
<path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
</svg>

          </a>";
        echo "</div>";
      }

      // Dodaj opcję powrotu do katalogu głównego, jeśli jesteśmy w podkatalogu
      if ($is_subdirectory) {
        echo "<div class='file-box'>";
        echo "<a class='go-to-main-directory' href='?dir={$_SESSION['username']}'>

            
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-folder-symlink' viewBox='0 0 16 16'>
  <path d='m11.798 8.271-3.182 1.97c-.27.166-.616-.036-.616-.372V9.1s-2.571-.3-4 2.4c.571-4.8 3.143-4.8 4-4.8v-.769c0-.336.346-.538.616-.371l3.182 1.969c.27.166.27.576 0 .742'/>
  <path d='m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m.694 2.09A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09l-.636 7a1 1 0 0 1-.996.91H2.826a1 1 0 0 1-.995-.91l-.637-7zM6.172 2a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z'/>
</svg>
            
            </a>";
          echo "</div>";
        }
        
        mysqli_free_result($result);
      } else {
        echo "Błąd w zapytaniu do bazy danych: " . mysqli_error($connection);
      }
      
      mysqli_close($connection);
    ?>
    
  </body>
</html>
