<?php
if (isset($_GET['file'])) {
    $file_path = $_GET['file'];
    
    // Wczytaj obrazek
    $image = null;
    $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        $image = imagecreatefromstring(file_get_contents($file_path));
    }
    
    if ($image) {
        // Ustaw maksymalny rozmiar miniatury
        $max_thumbnail_size = 100;
        
        // Pobierz oryginalny rozmiar obrazka
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Oblicz proporcje, aby zachować aspekt oryginalnego obrazka
        $aspect_ratio = $width / $height;
        
        if ($width > $height) {
            $new_width = $max_thumbnail_size;
            $new_height = $max_thumbnail_size / $aspect_ratio;
        } else {
            $new_width = $max_thumbnail_size * $aspect_ratio;
            $new_height = $max_thumbnail_size;
        }
        
        // Utwórz miniaturę
        $thumbnail = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        
        // Wyślij miniaturę do przeglądarki
        header('Content-Type: image/jpeg');
        imagejpeg($thumbnail);
        
        // Zwolnij pamięć
        imagedestroy($thumbnail);
        imagedestroy($image);
    }
}
?>
