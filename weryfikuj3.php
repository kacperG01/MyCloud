<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
  <HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </HEAD>
  <BODY>
    <?php
      function getBrowser() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = "N/A";
        $browsers = [
          '/msie/i' => 'Internet explorer',
          '/firefox/i' => 'Firefox',
          '/safari/i' => 'Safari',
          '/chrome/i' => 'Chrome',
          '/edge/i' => 'Edge',
          '/opera/i' => 'Opera',
          '/mobile/i' => 'Mobile browser',
          ];
        foreach ($browsers as $regex => $value) {
          if (preg_match($regex, $user_agent)) {
            $browser = $value;
          }
        }
        return $browser;
      }
      
      $user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8"); // login z formularza
      $pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8");  // hasło z formularza
      
      $screenWidth = $_POST['screenWidth'];
      $screenHeight = $_POST['screenHeight'];
      $colorDepth = $_POST['colorDepth'];
      $cookiesEnabled = $_POST['cookiesEnabled'];
      $javaEnabled = $_POST['javaEnabled'];
      $language = $_POST['language'];
      $windowWidth = $_POST['windowWidth'];
      $windowHeight = $_POST['windowHeight'];
      
      $link = mysqli_connect('mysql1.small.pl', 'm1936_user4', 'User04', 'm1936_zad4'); // połączenie z BD – wpisać swoje dane
      if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
      mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
      $result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'"); // wiersza, w którym login=login z formularza
      $rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
      if(!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
      {
        mysqli_close($link); // zamknięcie połączenia z BD
        echo "Brak logowania.";
      }
      else
      { // jeśli $rekord istnieje
        if($rekord['password']==$pass) // czy hasło zgadza się z BD
        {
          $ipaddress = $_SERVER["REMOTE_ADDR"];
          function ip_details($ip) {
            $json = file_get_contents ("http://ipinfo.io/{$ip}/geo");
            $details = json_decode ($json);
            return $details;
          }
          
          $mybrowser=getBrowser();
          
          $queryx = "INSERT INTO goscieportalu (ipaddress, datetime, browser, colors, cookies, java, language, screen_res, window_size) VALUES ('$ipaddress', NOW(), '$mybrowser', '$colorDepth', '$cookiesEnabled', '$javaEnabled', '$language', '$screenWidth x $screenHeight', '$windowWidth x $windowHeight')";
          if(mysqli_query($link, $queryx)){   
            
          }else{  
            echo "Błąd zapytania.";       
          }
          
          echo "Logowanie Ok. User: {$rekord['username']}. Hasło: {$rekord['password']}";
          session_start();
          $_SESSION ['loggedin'] = true;
          $_SESSION["username"] = $user;
          header('Location: select.php');
        }
        else
        {
          mysqli_close($link);
          echo "Błąd logowania.";
        }
      }

      ?>
  </BODY>
</HTML>
