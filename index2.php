<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camera Popup</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
    <style>
        /* Add your CSS code here */
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
                // Load face detection models
                async function loadModels() {
                    await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
                }

                loadModels();

                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');
                const snapButton = document.getElementById('snap');

                const constraints = {
                    video: {
                        facingMode: 'user',
                        width: {ideal: 640},
                        height: {ideal: 480}
                    }
                };

                function handleSuccess(stream) {
                    video.srcObject = stream;
                    video.addEventListener('loadedmetadata', () => {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                    });
                }

                function handleError(error) {
                    console.error('An error occurred: ', error);
                }

                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia(constraints)
                        .then(handleSuccess)
                        .catch(handleError);
                } else {
                    alert('getUserMedia not supported in this browser.');
                }

                video.addEventListener('play', async function detectFaces() {
                    if (!faceapi.nets.tinyFaceDetector.params) {
                        setTimeout(detectFaces, 100);
                        return;
                    }

                    const options = new faceapi.TinyFaceDetectorOptions({inputSize: 160});
                    const detections = await faceapi.detectSingleFace(video, options);

                    if (detections) {
                        context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
                        snapButton.disabled = false;
                    } else {
                        snapButton.disabled = true;
                    }

                    setTimeout(detectFaces, 100);
                });

                snapButton.addEventListener('click', () => {
    let imgData = canvas.toDataURL('image/jpeg');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_image.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText.startsWith('Image saved as:')) {
                alert('Foto berhasil disimpan: ' + xhr.responseText);
            } else {
                alert('Gagal menyimpan foto: ' + xhr.responseText);
            }
        }
    };
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
