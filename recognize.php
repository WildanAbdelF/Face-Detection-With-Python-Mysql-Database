<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $_POST['image'];
    $image = str_replace('data:image/jpeg;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    $image = base64_decode($image);

    // Simpan gambar sementara
    $image_path = 'temp.jpg';
    file_put_contents($image_path, $image);

    // Panggil skrip Python untuk pengenalan wajah
    $command = escapeshellcmd("python recognize_face.py $image_path");
    $output = shell_exec($command);
    $user_info = json_decode($output, true);

    // Ambil data terbaru dari tabel detection_history
    $sql = "SELECT * FROM detection_history ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    $latest_data = $result->fetch_assoc();

    echo json_encode([
        'user_info' => $user_info,
        'latest_data' => $latest_data
    ]);
}
?>
