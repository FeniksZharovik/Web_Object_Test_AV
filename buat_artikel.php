<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $paragraf = $_POST['paragraf'];

    // Pastikan judul dan paragraf tidak kosong
    if (!empty($judul) && !empty($paragraf)) {
        $stmt = $pdo->prepare('INSERT INTO artikel (judul, paragraf) VALUES (?, ?)');
        $stmt->execute([$judul, $paragraf]);

        header('Location: index.php');
        exit;
    } else {
        echo "Judul dan paragraf tidak boleh kosong!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Artikel</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        #editor-container {
            height: 400px; /* Ukuran tetap untuk area teks */
            max-height: 400px;
            overflow-y: auto;
        }
        .ql-editor img, .ql-editor video {
            max-width: 200px; /* Membatasi lebar gambar dan video */
            max-height: 150px; /* Membatasi tinggi gambar dan video */
            width: auto;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Buat Artikel Baru</h1>
    <form action="buat_artikel.php" method="post" enctype="multipart/form-data">
        <input type="text" name="judul" placeholder="Judul" required><br>
        <div id="editor-container"></div>
        <input type="hidden" name="paragraf" id="paragraf">
        <input type="file" id="videoInput" accept="video/*"><br>
        <button type="submit">Simpan</button>
    </form>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'],

            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction

            [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'font': [] }],
            [{ 'align': [] }],

            ['image', 'video'],                               // image and video buttons
            ['clean']                                         // remove formatting button
        ];

        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            }
        });

        document.getElementById('videoInput').addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var range = quill.getSelection();
                    quill.insertEmbed(range.index, 'video', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        document.querySelector('form').onsubmit = function() {
            document.querySelector('#paragraf').value = quill.root.innerHTML;
        };
    </script>
</body>
</html>