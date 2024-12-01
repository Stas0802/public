<?php

use Mywork\App\Classes\Game;
use Mywork\App\Classes\NewGame;

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors',1);
error_reporting(E_ALL);

if (!isset($_GET['id'])) {
    header("Location: /index.php");
}

$new_game = NewGame::fetchById($_GET['id']);
$games = Game::fetchAll(); 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Редагувати гру</title>
</head>
<body>
    <form action="handle_form.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $new_game['id']; ?>">
        <label for="game">Назва гри:</label>
        <select  name="game" >
            <?php foreach ($games as $game):  ?>
                <option value="<?php echo $game['id']; ?>"<?php echo $game['id'] == $new_game['game'] ? 'selected' : ''; ?>><?php echo $game['name']; ?></option>
                <?php endforeach; ?> 
        </select><br/>
        <br/>
         <label for="description">Опис турніру:</label><br/>
        <textarea id="description" name="description" required><?php echo $new_game['description']; ?></textarea><br>
        <br/>
        <label for="startDate">Дата початку турніру:</label><br/>
        <input type="date" id="startDate" name="startDate" required><?php echo $new_game['startDate']; ?><br>
        <br/>
        <label for="endDate">Дата кінця турніру:</label><br/>
        <input type="date" id="endDate" name="endDate" required><?php echo $new_game['endDate']; ?><br>
        <br/>
        <label for="prizePool">Призовий фонд:</label><br/>
        <input type="number" id="prizePool" name="prizePool" ><?php echo $new_game['prizePool']; ?><br>
        <br/>
        <label for="format">Формат:</label><br/>
     <?php
         $saved_format = $new_game['format']; // Предположим, что это сохраненный формат
         
         $options = [
            'Single Elimination' => 'Single Elimination',
            'Double Elimination' => 'Double Elimination',
            'Round Robin' => 'Round Robin',
        ];
        echo '<select id="format" name="format">';
        
        foreach ($options as $value => $label) {
            $selected = ($saved_format == $value) ? 'selected' : '';
            echo "<option value=\"$value\" $selected>$label</option>";
        }
        echo '</select><br>';
        ?>
        <br/>
        <label for="teams">Команди (через кому):</label><br/>
        <textarea id="teams" name="teams" required><?php echo $new_game['teams']; ?></textarea><br>
        <br/>
         <label for="logo">Логотип:</label><br/>
        <input type="file" id="logo" name="logo"><br>
        <br/>
         <input type="submit" value="Зберегти">
    </form>
</body>
</html>
