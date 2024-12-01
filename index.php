<?php

use Mywork\App\Classes\Game;

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors',1);
error_reporting(E_ALL);


$games = Game::fetchAll(); 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Турніри</title>
</head>
<body>
    <form action="handle_form.php" method="post" enctype="multipart/form-data">
        <label for="game">Назва гри:</label>
        <select  name="game" >
            <?php foreach ($games as $game):  ?>
                <option value="<?php echo $game['id']; ?>"><?php echo $game['name']; ?></option>
                <?php endforeach; ?> 
             </select><br>
             <br/>
              <label for="description">Опис турніру:</label><br/>
              <textarea id="description" name="description" required></textarea><br/>
              <br/>
              <label for="startDate">Дата початку турніру:</label><br/>
              <input type="date" id="startDate" name="startDate" required><br/>
              <br/>
              <label for="endDate">Дата кінця турніру:</label><br/>
              <input type="date" id="endDate" name="endDate" required><br/>
              <br/>
              <label for="prizePool">Призовий фонд:</label><br/>
              <input type="number" id="prizePool" name="prizePool" required><br/>
              <br/>
              <label for="format">Формат:</label><br/>
              <select  id="format"name="format" >
                <option value="Single Elimination">Single Elimination</option>
                <option value="Double Elimination">Double Elimination</option>
                <option value="Round Robin">Round Robin</option>
            </select><br>
            <br/>
            <label for="teams">Команди (через кому):</label><br/>
            <textarea id="teams" name="teams" required></textarea><br>
            <br/>
            <label for="logo">Логотип:</label><br/>
            <input type="file" id="logo" name="logo"><br/>
            <br/>
            <input type="submit" value="Зберегти">
    </form>
</body>
</html>
