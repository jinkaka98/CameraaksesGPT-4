<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camera Popup</title>
    <style>
        #popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 100;
        }
        #popup-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <button id="open-popup">Buka Kamera</button>
    <div id="popup">
        <div id="popup-content">
            <video id="video" width="640" height="480" autoplay></video>
            <button id="snap">Take a Picture</button>
            <canvas id="canvas" width="640" height="480"></canvas>
            <script>
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');
                const snapButton = document.getElementById('snap');

                navigator.mediaDevices.getUserMedia({video: true})
                    .then(stream => {
                        video.srcObject = stream;
                    })
                    .catch(err => {
                        console.error('An error occurred: ', err);
                    });

                snapButton.addEventListener('click', () => {
                    context.drawImage(video, 0, 0, 640, 480);
                    let imgData = canvas.toDataURL('image/png');
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', 'save_image.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('image=' + imgData);
                });

                document.getElementById('open-popup').addEventListener('click', () => {
                    document.getElementById('popup').style.display = 'block';
                });

                video.addEventListener('click', () => {
                    document.getElementById('popup').style.display = 'none';
                });
            </script>
        </div>
    </div>
</body>
</html>
