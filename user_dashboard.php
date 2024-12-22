<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?= $_SESSION['username'] ?>!</h1>
        <button onclick="location.href='scan_face.php'">Start Face Recognition</button>
        <button class="logout-button" onclick="location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
