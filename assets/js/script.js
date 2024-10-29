document.getElementById('uploadFile').onchange = function(event) {
    const [file] = event.target.files;
    if (file) {
        document.getElementById('profileImage').src = URL.createObjectURL(file);
        document.getElementById('profileIcon').style.display = 'none';
    }
};

document.getElementById('changePhotoButton').onclick = function() {
    const fileInput = document.getElementById('uploadFile');
    if (fileInput.files.length === 0) {
        alert("Pilih foto terlebih dahulu!");
        return;
    }

    const formData = new FormData();
    formData.append('profilePicture', fileInput.files[0]);

    fetch('upload.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        window.location.reload();
    }).catch(error => console.error('Error:', error));
};