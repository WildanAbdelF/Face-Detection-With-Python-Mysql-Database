<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.html");
    exit;
}

include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Face Recognition</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <h1>Face Recognition</h1>
    <video id="video" width="640" height="480" autoplay></video>
    <div id="result">
        <h2>Recognized User:</h2>
        <p id="user_info">No user recognized yet.</p>
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
            $.post('recognize.php', { image: frame }, function(data) {
                const result = JSON.parse(data);
                if (result.name !== "Unknown") {
                    document.getElementById('user_info').innerText = `
                        Name: ${result.name}
                        NIM: ${result.nim}
                        Alamat: ${result.alamat}
                        Email: ${result.email}
                        Phone: ${result.phone}
                    `;
                } else {
                    document.getElementById('user_info').innerText = "Face not recognized";
                }
            });
        }

        // Panggil fungsi recognizeFace setiap 3 detik
        setInterval(recognizeFace, 3000);
    </script>
</body>
</html>
