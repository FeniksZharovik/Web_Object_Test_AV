<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Slider</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .slider {
            position: relative;
            width: 320px;
            height: 640px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            height: 100%;
            position: absolute;
            transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
        }
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .slide:not(.active) {
            transform: scale(0.8);
            opacity: 0.5;
        }
        .navigation {
            position: absolute;
            bottom: -30px;
            display: flex;
        }
        .navigation button {
            border: none;
            background-color: #fff;
            border-radius: 50%;
            width: 10px;
            height: 10px;
            margin: 0 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .navigation button.active {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="slider">
        <div class="slides">
            <div class="slide active"><img src="intro1.png" alt="Intro 1"></div>
            <div class="slide"><img src="intro2.png" alt="Intro 2"></div>
            <div class="slide"><img src="intro3.png" alt="Intro 3"></div>
        </div>
        <div class="navigation">
            <button class="active"></button>
            <button></button>
            <button></button>
        </div>
    </div>

    <script>
        const slides = document.querySelectorAll('.slide');
        const buttons = document.querySelectorAll('.navigation button');
        let currentIndex = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            buttons.forEach((button, i) => {
                button.classList.toggle('active', i === index);
            });
        }

        buttons.forEach((button, index) => {
            button.addEventListener('click', () => {
                currentIndex = index;
                showSlide(currentIndex);
            });
        });

        // Auto slide
        setInterval(() => {
            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
        }, 3000);
    </script>
</body>
</html>