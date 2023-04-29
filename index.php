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
        #open-popup {
            margin: 30% auto;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            font-size: 1.5rem;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        #popup-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            max-width: 90%;

        }
        #video, #canvas {
            max-width: 100%;
            height: auto;
        }
        #snap {
            display: block;
            margin: 10px auto;
            font-size: 1.2rem;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            #popup-content {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <button id="open-popup">Buka Kamera</button>
    <div id="popup">
        <div id="popup-content">
            <video id="video" autoplay></video>
            <button id="snap">Take a Picture</button>
            <canvas id="canvas"></canvas>
            <script>
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');
                const snapButton = document.getElementById('snap');

                navigator.mediaDevices.getUserMedia({video: true})
                    .then(stream => {
                        video.srcObject = stream;
                        video.addEventListener('loadedmetadata', () => {
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                        });
                    })
                    .catch(err => {
                        console.error('An error occurred: ', err);
                    });

                snapButton.addEventListener('click', () => {
                    context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
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
