<?php 

use Mywork\App\Classes\NewGame;

ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../vendor/autoload.php';

$tournaments = NewGame::fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ігри</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }

        a:hover {
            color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: separate;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid black;
            padding: 12px;
            text-align: left;
            /* border-bottom: 1px solid #ddd; */
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Турніри</h1>
    <a href="/index.php">Додати турнір</a>
    <br/><br/>
    <a href="/show_games.php">Переглянути турніри</a>
    <br/><br/>
    
    <!-- Кнопки фильтрации -->
    <div>
        <button onclick="filterTable('all')">All</button>
        <button onclick="filterTable('CS:GO')">CS:GO</button>
        <button onclick="filterTable('DOTA 2')">DOTA 2</button>
        <button onclick="filterTable('Valorant')">Valorant</button>
    </div>
    <br/>
    
    <table id="gamesTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Гра</th>
                <th>Опис</th>
                <th>Дата початку турніру</th>
                <th>Дата закінчення турніру</th>
                <th>Призовий фонд</th>
                <th>Формат</th>
                <th>Команди</th>
                <th>Логотип</th>
                <th>Дата створення</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($tournaments as $new_game) {
                echo "<tr>";
                echo "<td>{$new_game['id']}</td>";
                echo "<td>{$new_game['game_name']}</td>";
                echo "<td>{$new_game['description']}</td>";
                echo "<td>{$new_game['startDate']}</td>";
                echo "<td>{$new_game['endDate']}</td>";
                echo "<td>{$new_game['prizePool']} $ </td>";
                echo "<td>{$new_game['format']} </td>";
                echo "<td>{$new_game['teams']}</td>";
                echo "<td><img src='{$new_game['logo_path']}' alt='Логотип'></td>";
                echo "<td>{$new_game['created_at']}</td>";
                echo "<td><a href='/game_display.php?id={$new_game['id']}'>Переглянути</a></td>";
                echo "</tr>";
            }  
            ?>
        </tbody>
    </table>

    <script>
        function filterTable(gameName) {
            var table, tr, td, i;
            table = document.getElementById("gamesTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none"; // Скрываем все строки
                td = tr[i].getElementsByTagName("td")[1]; // Получаем столбец с названием игры
                if (td) {
                    if (gameName === "all" || td.innerHTML.toUpperCase().indexOf(gameName.toUpperCase()) > -1) {
                        tr[i].style.display = ""; // Показываем строки, соответствующие фильтру
                    }
                }       
            }
        }
    </script>
</body>
</html>
