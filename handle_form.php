<?php

use Mywork\App\Classes\NewGame;

require __DIR__ . '/../vendor/autoload.php'; 

ini_set('display_errors',1);
error_reporting(E_ALL);

$id = $_POST ['id'] ?? null;
$game = $_POST['game'] ?? null;
$description = $_POST['description'] ?? null;
$startDate = $_POST['startDate'] ?? null;
$endDate = $_POST['endDate'] ?? null;
$prizePool = $_POST['prizePool'] ?? null;
$format = $_POST['format'] ?? null;
$teams = $_POST['teams'] ?? null;
$logo = $_FILES['logo'] ?? null;

   
    try {
        $new_game = new NewGame($game, $description,  $startDate, $endDate, $prizePool, $format, $teams, $logo,  $id);
        $new_game->validateInput();
        $new_game->saveAll();
        
        header("Location: /game_display.php?id=" . $new_game->getId());
        exit;
    } catch (\Exception $e) {
        echo "Ошибка: " . $e->getMessage();
    }

?>
