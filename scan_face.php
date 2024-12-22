<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Face Recognition</title>
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
        .recognition-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 700px;
            position: relative;
        }
        .recognition-container h1 {
            margin-bottom: 20px;
        }
        .recognition-container video, .recognition-container canvas {
            display: block;
            margin: 0 auto;
        }
        .recognition-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        .recognition-container button:hover {
            background-color: #45a049;
        }
        .recognition-container .back-button {
            background-color: #f44336;
        }
        .recognition-container .back-button:hover {
            background-color: #e53935;
        }
        .hidden {
            display: none;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div class="recognition-container">
        <h1>Face Recognition</h1>
        <video id="video" width="640" height="480" autoplay></video>
        <canvas id="overlay" width="640" height="480" style="position: absolute; top: 0; left: 0;"></canvas>
        <div id="result">
            <h2 class="hidden" >Recognized User:</h2 >
            <p class="hidden" id="user_info">No user recognized yet.</p>
            <h2>Face Detection Result :</h2>
            <p id="latest_data">No data available yet.</p>
        </div>
        <button class="back-button" onclick="location.href='user_dashboard.php'">Back to Dashboard</button>
    </div>
    <script>
        // Akses webcam
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                document.getElementById('video').srcObject = stream;
            })
            .catch(err => {
                console.error("Error accessing webcam: " + err);
            });

        // Ambil frame dari video
        function captureFrame() {
            const video = document.getElementById('video');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            return canvas.toDataURL('image/jpeg');
        }

        // Kirim frame ke server untuk face recognition
        function recognizeFace() {
            const frame = captureFrame();
            $.ajax({
                url: 'recognize.php',
                type: 'POST',
                data: { image: frame },
                dataType: 'json',
                success: function(result) {
                    console.log(result); // Tambahkan log untuk memeriksa data yang diterima
                    if (result.user_info && result.user_info.name !== "Unknown") {
                        document.getElementById('user_info').innerText = `
                            Name: ${result.user_info.name}
                            NIM: ${result.user_info.nim}
                            Alamat: ${result.user_info.alamat}
                            Email: ${result.user_info.email}
                            Phone: ${result.user_info.phone}
                        `;
                        drawBox(result.user_info);
                        document.getElementById('result');
                    } else {
                        document.getElementById('user_info').innerText = "Face not recognized";
                        clearBox();
                        document.getElementById('result');
                    }

                    // Perbarui data terbaru dari tabel detection_history
                    if (result.latest_data) {
                        document.getElementById('latest_data').innerText = `
                            Name: ${result.latest_data.name}
                            NIM: ${result.latest_data.nim}
                            Alamat: ${result.latest_data.alamat}
                            Email: ${result.latest_data.email}
                            Phone: ${result.latest_data.phone}
                            Timestamp: ${result.latest_data.timestamp}
                        `;
                    }
                },
               
            });
        }

        // Gambar kotak di sekitar wajah dan tambahkan teks informasi user
        function drawBox(user_info) {
            const canvas = document.getElementById('overlay');
            const context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.strokeStyle = 'green';
            context.lineWidth = 2;
            context.strokeRect(user_info.left, user_info.top, user_info.right - user_info.left, user_info.bottom - user_info.top);
            context.font = '16px Arial';
            context.fillStyle = 'green';
            context.fillText(`${user_info.name}, ${user_info.nim}`, user_info.left, user_info.top - 10);
        }

        // Hapus kotak dari canvas
        function clearBox() {
            const canvas = document.getElementById('overlay');
            const context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);
        }

        // Panggil fungsi recognizeFace setiap 3 detik
        setInterval(recognizeFace, 3000);
    </script>
</body>
</html>
