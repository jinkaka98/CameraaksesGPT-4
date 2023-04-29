<?php
if (isset($_POST['image'])) {
    $img = $_POST['image'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $file = 'img/' . uniqid() . '.png';
    file_put_contents($file, $data);
    echo 'Image saved as: ' . $file;
} else {
    echo 'Error: Image data not received.';
}
?>
