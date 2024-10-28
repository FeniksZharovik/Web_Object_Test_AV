<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Artikel dengan Quill.js</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        #editor {
            height: 400px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Tambah Artikel Baru</h1>
    <form action="save_article.php" method="POST" enctype="multipart/form-data">
        <label>Judul Artikel:</label><br>
        <input type="text" name="title" required><br><br>

        <div id="editor"></div> <!-- Quill Editor -->

        <input type="file" id="image-upload" accept="image/*" multiple>
        <button type="button" id="insert-image">Sisipkan Gambar</button><br><br>

        <input type="hidden" name="content" id="content"> <!-- Hidden field to store HTML content -->

        <button type="submit">Simpan Artikel</button>
    </form>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Inisialisasi Quill
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            }
        });

        // Menyimpan konten ke hidden input sebelum submit
        document.querySelector('form').onsubmit = function() {
            const content = document.querySelector('#content');
            content.value = quill.root.innerHTML; // Ambil konten HTML
        };

        // Menyisipkan gambar ke editor
        document.getElementById('insert-image').addEventListener('click', () => {
            const imageInput = document.getElementById('image-upload');
            const files = imageInput.files;

            if (files.length) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Menyisipkan gambar ke dalam Quill
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
</body>
</html>
