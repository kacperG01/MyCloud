<?php
// Pobierz nazwę pliku z parametru GET
$file_name = urldecode($_GET['file']);

// Sprawdź, czy plik istnieje
if (file_exists($file_name)) {
    // Ustaw nagłówek dla przeglądarki
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_name));
    
    // Otwórz plik do odczytu binarnego i wyślij jego zawartość do przeglądarki
    readfile($file_name);
    
    exit;
} else {
    // Jeśli plik nie istnieje, zwróć błąd
    echo "Plik nie jest dostępny na serwerze.";
}
?>
