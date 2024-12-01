<?php

use Mywork\App\Classes\NewGame;

require __DIR__ . '/../vendor/autoload.php';
// Перевіряємо, чи встановлено параметр 'id' в GET-запиті
// Якщо 'id' не встановлено, перенаправляємо користувача на сторінку форми
if (!isset($_GET['id'])) {
    header("Location: /index.php");
    exit;
   
}

// Получаемо гру по id - якщо ігри немае(Помилека-гра по id не знайдена)
try {
    $new_game = NewGame::fetchById($_GET['id']);
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
    echo "<a href='/all_games.php'><button type='button'>Повернутися до ігор</button></a><br/>";
    exit;
}

// Переверяемо метод запиту
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        try {
            // Видаляемо гру по id
            NewGame::deleteById($_GET['id']);
            echo "<p>Гра видалена</p>";
            echo "<a href='/all_games.php'><button type='button'>Повернутися до ігор</button></a><br/>";
        } catch (Exception $e) {
            
            echo "Помилка при видаленні гри: " . $e->getMessage();
        }
     }
    
    } else {
    // Отображаем форму для  удаления и редактирование игры
    echo "</form>";
    echo "<form method='POST'>";
    echo "<button type='submit' name='delete' formaction='/game_display.php?id=" . $new_game['id'] ."'>Видалити  гру</button>";

    echo "</form>";
    echo "<a href='/all_games.php'><button type='button'>Повернутися до ігор</button></a><br/><br/>";
    echo "</form>";
    echo "<a href='/edit_form.php?id=" . $new_game['id'] . "'><button type='button'>Редагувати гру</button></a><br/>";

    echo "</form>";
    echo "<h1>Гра готова!</h1>";
    echo "Гра: " . $new_game['game_name'] . "<br>";
    echo "опис: " . $new_game['description'] . "<br>";  
    echo "Дата початку турніру: " . $new_game['startDate'] . "<br>";
    echo "Дата закінчення турніру: " . $new_game['endDate'] . "<br>";
    echo "Призовой фонд:" . $new_game['prizePool'] . " $ <br>";
    echo "формат:" . $new_game['format'] . "<br>";
    echo "Команди:" . $new_game['teams'] . "<br>";
    echo "Зображення: <img src='" . $new_game['logo_path'] . "'/><br>";
} 