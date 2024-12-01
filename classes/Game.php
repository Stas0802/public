<?php

namespace Mywork\App\Classes;

//require __DIR__ . '/../vendor/autoload.php';

class Game
{


  public static function fetchAll()
  {

    $db = Database::getInstance();
    $stmt = $db->getConnection()->prepare("SELECT * FROM games");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public static function fetchById($id)
  {
    $db = Database::getInstance();
    $stmt = $db->getConnection()->prepare("SELECT * FROM games WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  } 


}