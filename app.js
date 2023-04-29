// ambil elemen-elemen HTML
const video = document.querySelector("#camera-stream");
const canvas = document.querySelector("#camera-canvas");
const photo = document.querySelector("#camera-icon");
const takePictureButton = document.querySelector("#take-picture");

// atur dimensi canvas sesuai ukuran video
const width = 320;
const height = 0;

// akses kamera
navigator.mediaDevices
  .getUserMedia({ video: true, audio: false })
  .then((stream) => {
    // tampilkan video di elemen video
    video.srcObject = stream;
    video.play();
  })
  .catch((err) => console.error(err));

// ketika tombol "Take a Picture" di klik
takePictureButton.addEventListener("click", () => {
  // atur dimensi canvas
  canvas.width = width;
  canvas.height = video.videoHeight / (video.videoWidth / width);
  // gambar frame saat ini dari video ke canvas
  canvas.getContext("2d").drawImage(video, 0, 0, canvas.width, canvas.height);
  // tampilkan foto yang diambil di elemen img
  photo.src = canvas.toDataURL("image/png");
  photo.classList.add("show");
  // simpan foto ke server (dalam folder "img")
  savePhoto(canvas.toDataURL("image/png"));
});

// simpan foto ke server
function savePhoto(photoData) {
  // buat objek XMLHttpRequest
  const xhr = new XMLHttpRequest();
  // atur request ke file PHP untuk menyimpan foto
  xhr.open("POST", "save_photo.php", true);
  // kirim data foto ke server sebagai form data
  const formData = new FormData();
  formData.append("photo_data", photoData);
  xhr.send(formData);
}
