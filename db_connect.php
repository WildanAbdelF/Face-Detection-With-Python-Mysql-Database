<?php
$conn = new mysqli('localhost', 'root', '', 'face_detection');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
