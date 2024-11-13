<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $paragraf = $_POST['paragraf'];
    $tags = explode(',', $_POST['tags']); // Memisahkan tag berdasarkan koma

    // Pastikan judul dan paragraf tidak kosong
    if (!empty($judul) && !empty($paragraf)) {
        $stmt = $pdo->prepare('INSERT INTO artikel (judul, paragraf) VALUES (?, ?)');
        $stmt->execute([$judul, $paragraf]);
        $artikelId = $pdo->lastInsertId();

        // Proses setiap tag
        foreach ($tags as $tag) {
            $tag = trim($tag);
            if (!empty($tag)) {
                // Tambahkan tag ke tabel tag dengan artikel_id
                $stmt = $pdo->prepare('INSERT INTO tag (nama, artikel_id) VALUES (?, ?)');
                $stmt->execute([$tag, $artikelId]);
            }
        }

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
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            max-width: 600px;
        }
        input[type="text"], input[type="hidden"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .tag-container {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5px;
            margin-bottom: 10px;
        }
        .tag {
            background-color: #e0e0e0;
            color: #333;
            padding: 5px 10px;
            margin: 2px;
            border-radius: 15px;
            font-size: 14px;
        }
        .tag-input {
            border: none;
            outline: none;
            flex: 1;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        #editor-container {
            height: 400px;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Buat Artikel Baru</h1>
    <form action="buat_artikel.php" method="post">
        <input type="text" name="judul" placeholder="Judul" required><br>
        <div id="editor-container"></div>
        <input type="hidden" name="paragraf" id="paragraf">
        <div class="tag-container" id="tag-container">
            <input type="text" class="tag-input" id="tag-input" placeholder="Tambahkan tag">
        </div>
        <input type="hidden" name="tags" id="tags">
        <button type="submit">Simpan</button>
    </form>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['image', 'video'],
                    ['clean']
                ]
            }
        });

        var tagInput = document.getElementById('tag-input');
        var tagContainer = document.getElementById('tag-container');
        var tags = [];

        tagInput.addEventListener('keydown', function(event) {
            if (event.key === ',') {
                event.preventDefault();
                var tag = tagInput.value.trim();
                if (tag) {
                    tags.push(tag);
                    var tagElement = document.createElement('span');
                    tagElement.className = 'tag';
                    tagElement.textContent = tag;
                    tagContainer.insertBefore(tagElement, tagInput);
                    tagInput.value = '';
                }
            }
        });

        document.querySelector('form').onsubmit = function() {
            document.querySelector('#paragraf').value = quill.root.innerHTML;
            document.querySelector('#tags').value = tags.join(',');
        };
    </script>
</body>
</html>