<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h2>History</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>NIM</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Timestamp</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM detection_history");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['nim']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['timestamp']}</td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>
