<?php

use Mywork\App\Classes\Game;
use Mywork\App\Classes\NewGame;


require __DIR__ . '/../vendor/autoload.php';

// Получение всех игор для отображения в выпадающем списке
$games = Game::fetchAll();
$tournaments = NewGame::fetchAll();

// Создание массива для хранения URL-адресов изображений
$imageUrls = [
    1 => 'https://maincast.com/wp-content/uploads/2021/09/csgo-1.svg',
    2 => 'https://maincast.com/wp-content/uploads/2021/09/dota-1.png',
    3 => 'https://maincast.com/wp-content/uploads/2021/03/valorant_logo.png',

];

$bannerImages = [
    1 => 'https://i.postimg.cc/rsT2z8LK/banner-1036483-960-720.webp',
    2 => 'https://i.postimg.cc/rwXfp5wF/banner-5222645-1280.jpg',
    3 => 'https://i.postimg.cc/d1cYJhZb/banner-1014112-1280.webp',
    
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Пример формы</title>
    <style>
         body {
            font-family: Arial, sans-serif; 
           /* background-image: url('https://i.postimg.cc/tTMDmkBy/banner-1082644-1280.webp');  /*фон cтраницы  */
           /* background-size: cover; /*Обеспечиваем, чтобы изображение покрывало весь фон */
           background-color: darkslategray;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            color: white;
        } 
        .container {
            width: 80%;
            max-width: 1200px;
            text-align: center;
            margin-top: 150px;
        }
        .buttons {
            margin: 20px 0;
            text-align: left; /* Сдвигаем кнопки влево */
        }
        .buttons img {
            padding: 10px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 50px; /* Ширина изображения кнопки */
            height: 50px; /* Высота изображения кнопки */
            transition: transform 0.3s, background-color 0.3s;
        }
        .buttons img:hover {
            transform: scale(1.1); /* Увеличение кнопки при наведении */
            background-color: rgba(255, 255, 255, 0.2); /* Легкий фон при наведении */
        }
        .banners {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 50px 0 20px 0;
        }
        .banner-container {
            width: 100%;
        }
        .banner {
            width: 100%; /* Увеличиваем ширину до 100% */
            max-width: 1200px; /* Ограничиваем максимальную ширину */
            height: 150px; /* Увеличенная высота баннера */
            padding: 10px; /* Пропорционально увеличенные отступы */
            margin: 10px 0;
            background-size: cover; /* Обеспечиваем, чтобы изображение покрывало весь баннер */
            background-position: center;
            position: relative;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 18px; /* Увеличенный шрифт */
            display: none; /* Скрываем баннеры по умолчанию */
            transition: transform 0.3s;
            color: white;
        }
        .banner.active {
            display: block; /* Отображаем только активный баннер */
        }
        .banner:hover {
            transform: scale(1.05);
        }
        .details-content {
            display: none;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px;
            border-radius: 5px;
            width: calc(100% - 20px); /* Учитываем отступы */
            box-sizing: border-box; /* Убедитесь, что элементы внутри контейнера корректно отображаются */
            margin-top: 10px; /* Располагаем ниже баннера */
        }
        .details-content.active {
            display: block;
        }
        .details-button, .close-button {
            position: absolute;
            bottom: 10px;
            right: 10px;
            padding: 5px 10px;
            background-color: rgba(0, 0, 0, 0.6);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            color: white;
        }
        .close-button {
            display: none;
        }
        .banner img {
            max-width: 100%; /* Убедитесь, что изображение не выходит за пределы баннера */
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1></h1>
        <div class="buttons">
            <img src="https://i.postimg.cc/Hsm0KLsq/All.jpg" alt="All" onclick="showAllBanners()">
            <?php foreach ($games as $game): ?>
                <img src="<?php echo $imageUrls[$game['id']] ?? 'path/to/default-icon.png'; ?>" alt="<?php echo $game['name']; ?>" onclick="showBanner(<?php echo $game['id']; ?>)">
            <?php endforeach; ?>
        </div>
        <div class="banners">
            <?php foreach ($games as $game): ?>
                <div class="banner-container">
                    <div class="banner" id="banner<?php echo $game['id']; ?>" style="background-image: url('<?php echo $bannerImages[$game['id']] ?? 'path/to/default-banner.jpg'; ?>');">
                        <!-- <img src="<?php echo $imageNames[$game['id']] ?? 'path/to/default-image.jpg'; ?>" alt=""> -->
                        <button class="details-button" onclick="toggleDetails(<?php echo $game['id']; ?>)">Детальнее</button>
                        <button class="close-button" onclick="toggleDetails(<?php echo $game['id']; ?>)">X</button>
                    </div>
                    <div class="details-content" id="details<?php echo $game['id']; ?>">
                        <?php
                        $tournamentFound = false;
                        foreach ($tournaments as $tournament) {
                            if ($tournament['game'] == $game['id']) {
                                $tournamentFound = true;
                                echo "<p>Дата початку турніру: " . $tournament['startDate'] . "</p>";
                                echo "<p>Дата закінчення турніру: " . $tournament['endDate'] . "</p>";
                                echo "<p>Призовой фонд: " . $tournament['prizePool'] . " $ </p>";
                                echo "<p>Формат: " . $tournament['format'] . "</p>";
                                echo "<p>Команди: " . $tournament['teams'] . "</p>";
                                break;
                            }
                        }
                        if (!$tournamentFound) {
                            echo "<p>На сьгодні турнірів немає</p>";
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function showBanner(bannerNumber) {
            // Скрыть все баннеры
            var banners = document.querySelectorAll('.banner');
            banners.forEach(function(banner) {
                banner.classList.remove('active');
            });

            // Показать выбранный баннер
            var banner = document.getElementById('banner' + bannerNumber);
            if (banner) {
                banner.classList.add('active');
            }

            // Скрыть все детали
            var details = document.querySelectorAll('.details-content');
            details.forEach(function(detail) {
                detail.classList.remove('active');
            });

            // Показать кнопку "Детальнее" и скрыть кнопку "X" для всех баннеров
            var detailsButtons = document.querySelectorAll('.details-button');
            var closeButtons = document.querySelectorAll('.close-button');
            detailsButtons.forEach(function(button) {
                button.style.display = 'block';
            });
            closeButtons.forEach(function(button) {
                button.style.display = 'none';
            });
        }

        function showAllBanners() {
            // Показать все баннеры
            var banners = document.querySelectorAll('.banner');
            banners.forEach(function(banner) {
                banner.classList.add('active');
            });
        }

        function toggleDetails(bannerNumber) {
            // Показать/скрыть дополнительную информацию
            var details = document.getElementById('details' + bannerNumber);
            var detailsButton = document.querySelector('#banner' + bannerNumber + ' .details-button');
            var closeButton = document.querySelector('#banner' + bannerNumber + ' .close-button');
            if (details) {
                var isActive = details.classList.toggle('active');
                detailsButton.style.display = isActive ? 'none' : 'block';
                closeButton.style.display = isActive ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>
