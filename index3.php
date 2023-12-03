<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  </head>
  <BODY>
    Formularz logowania
    <form method="post" action="weryfikuj3.php">
      Login:<input type="text" name="user" maxlength="20" size="20"><br>
      Hasło:<input type="password" name="pass" maxlength="20" size="20"><br>
      <!-- Ukryte pola -->
          <input type="hidden" id="screenWidth" name="screenWidth" value="">
          <input type="hidden" id="screenHeight" name="screenHeight" value="">
          <input type="hidden" id="colorDepth" name="colorDepth" value="">
          <input type="hidden" id="cookiesEnabled" name="cookiesEnabled" value="">
          <input type="hidden" id="javaEnabled" name="javaEnabled" value="">
          <input type="hidden" id="language" name="language" value="">
          <input type="hidden" id="windowWidth" name="windowWidth" value="">
          <input type="hidden" id="windowHeight" name="windowHeight" value="">
          <input type="submit" value="Send"/>
    </form>
    
    Formularz rejestracji.
    <form method="post" action="reg.php">
      Login:<input type="text" name="user" maxlength="20" size="20"><br>
      Hasło:<input type="password" name="pass" maxlength="20" size="20"><br>
      Powtórz hasło:<input type="password" name="rpass" maxlength="20" size="20"><br>
      <input type="submit" value="Send"/>
    </form>
    <script>
    document.getElementById("screenWidth").value = screen.availWidth;
    document.getElementById("screenHeight").value = screen.availHeight;
    document.getElementById("colorDepth").value = screen.colorDepth;
    document.getElementById("cookiesEnabled").value = navigator.cookieEnabled;
    document.getElementById("javaEnabled").value = navigator.javaEnabled();
    document.getElementById("language").value = navigator.language;
    var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    var windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    
    // Przypisz te wartości do ukrytych pól formularza
    document.getElementById("windowWidth").value = windowWidth;
    document.getElementById("windowHeight").value = windowHeight;
  </script>
  </BODY>
</HTML>