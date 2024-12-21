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
</head>
<body>
    <h1>Welcome, <?= $_SESSION['username'] ?>!</h1>
    <a href="scan_face.php">Start Face Recognition</a>
</body>
</html>
