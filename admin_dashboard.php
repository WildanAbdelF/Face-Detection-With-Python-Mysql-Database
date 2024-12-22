<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="style.css">
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .dashboard-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 80%;
            max-width: 800px;
        }
        .dashboard-container h1 {
            margin-bottom: 20px;
        }
        .dashboard-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .dashboard-container table, .dashboard-container th, .dashboard-container td {
            border: 1px solid #ccc;
        }
        .dashboard-container th, .dashboard-container td {
            padding: 10px;
            text-align: left;
        }
        .dashboard-container th {
            background-color: #f2f2f2;
        }
        
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        <h2>History</h2>
        <table>
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
        <button class="logout-button" onclick="location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
