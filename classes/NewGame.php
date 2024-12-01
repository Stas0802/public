<?php


namespace Mywork\App\Classes;

use Mywork\App\Classes\AbstractGame;

class NewGame extends AbstractGame
{

    public function validateInput(){
        
        if (empty($_POST['description']) || empty($_POST['prizePool']) || empty($_POST['teams'])) {
            throw new \Exception("Будь ласка, заповніть всі обов'язкові поля.");
         }
        
         $allowed_types = ['image/jpeg', 'image/png'];
        if (!in_array(mime_content_type($_FILES['logo']['tmp_name']), $allowed_types)) {
            throw new \Exception("Файл повинен бути у форматі JPG або PNG.");
         }
          if ($_FILES['logo']['size'] > 5000000) {
            throw new \Exception("Розмір файлу перевищує допустимий ліміт у 5 МБ.");
          }
        
    }
    
    public function saveLogo() {
        
        if (empty($this->logo)) {
            return;
        }
        $uploads_dir = 'uploads';
        if (!file_exists($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }
        
        if (isset($this->logo['error']) && isset($this->logo['tmp_name'])) {
            if ($this->logo['error'] == 0) {
                $tmp_name = $this->logo['tmp_name'];
                $name = $this->logo['name'];
                $logo_path = $uploads_dir . "/" . $name;
                if (move_uploaded_file($tmp_name, $logo_path)) {
                    $this->uploadedLogoPath = $logo_path;
                    $this->saveLogo = true;
                }
            } else {
                throw new \Exception("Помилка завантаження файлу");
            }
        } else {
            throw new \Exception("Файл не передан або відсутні необхідні ключі.");
        }
    }
    // Сохраняем данные в БД
    public function saveDb()
    {
        if($this->id === null) {
            $this->insertDb();
        } else {
            $this->updateDb();
        }
    }

    //Метод для вставки нового турнира в базу данных
     private function insertDb() { 
        $dbConnection = Database::getInstance()->getConnection(); 
        $stmt = $dbConnection->prepare("INSERT INTO tournaments (game, description,  startDate, endDate, prizePool, format, teams, logo_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"); 
        if ($stmt === false) { 
            throw new \Exception('Ошибка при подготовке запроса'); 
        } 
           // Раскомментировать для прохождения теста  testSaveDb
         //$this->uploadedLogoPath = $this->logo['name'];

        $stmt->bind_param('isssssss', $this->game, $this->description,  $this->startDate, $this->endDate, $this->prizePool, $this->format, $this->teams,$this->uploadedLogoPath); 
        if (!$stmt->execute()) { 
            throw new \Exception('Ошибка выполнения запроса: ' . $stmt->error); 
        } 
        $this->id = $dbConnection->insert_id; 
        $stmt->close();
    }

    //Метод для обновления существующего турнира в базе данных
    private function updateDb() {
        $dbConnection = Database::getInstance()->getConnection();
        $stmt = $dbConnection->prepare("UPDATE tournaments SET game = ?, description = ?,  startDate = ?, endDate = ?, prizePool = ?, format = ?, teams = ?, logo_path = ? WHERE id = ?");
        if ($stmt === false) {
            throw new \Exception("Помилка підготовки запиту");
        }
        
        $stmt->bind_param("isssssssi", $this->game, $this->description,  $this->startDate, $this->endDate, $this->prizePool, $this->format, $this->teams, $this->uploadedLogoPath,$this->id);
        if ($stmt->execute() === false) {
            throw new \Exception("Помилка оновлення гри в базі даних");
        }
        $stmt->close();
    }

    
    public function saveAll(){
       
        $this->saveLogo();
        $this->saveDb();
    }

    //Метод для поиска турнира по id
    public static function fetchById($id) {
        
        $dbConnection = Database::getInstance()->getConnection();
    
        // Подготавливаем запрос
        $stmt = $dbConnection->prepare("SELECT * FROM tournaments WHERE id = ?");
        if ($stmt === false) {
            throw new \Exception("Помилка підготовкі запиту");
        }
    
        // Привязываем параметр
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new \Exception("Помилка виконання запиту");
        }
    
        // Получаем результат запроса
        $result = $stmt->get_result();
        if ($result === false) {
            throw new \Exception("Помилка отримання гри з бази даних");
        }
    
        $new_game = $result->fetch_assoc();
        if ($new_game === null) {
            throw new \Exception("Гра за id = {$id} не знайдена");
        }

        //Получаем имя игры
        $game = Game::fetchById($new_game['game']);
        if (!empty($game) && array_key_exists('name', $game)) {
            $new_game['game_name'] = $game['name'];
        } else {
            $new_game['game_name'] = null;
        }
        
        // Закрываем выражение
        $stmt->close();
    
        return $new_game;
    
    
    }

    //Метод для получения всех игр
    // Сортировка по дате добавления
     public static function fetchAll() {
        
        $dbConnection = Database::getInstance()->getConnection();
        $stmt = $dbConnection->prepare("SELECT * FROM tournaments ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result === false) {
            throw new \Exception("Помилка отримання игры з бази даних");
        }
        $tournaments= $result->fetch_all(MYSQLI_ASSOC);
        foreach ($tournaments as &$new_game) {
            $game = Game::fetchById($new_game["game"]);
            $new_game['game_name'] = !empty($game) ? $game['name'] : null;
        }
        return $tournaments;
       
    }

    // Метод удаления по id
    public static function deleteById($id) {
        
        $new_game = self::fetchById($id);
        $dbConnection = Database::getInstance()->getConnection();
        // Проверка, существует ли запись
        $checkStmt = $dbConnection->prepare("SELECT id FROM tournaments WHERE id = ?");
        if ($checkStmt === false) {
            throw new \Exception("Ошибка при подготовке запроса проверки существования записи");
        }
        
        $checkStmt->bind_param('i', $id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows === 0) {
            throw new \Exception("Запись с id $id не найдена");
        }
        
        // Закрываем выражение проверки
        $checkStmt->close();
        
        // Подготавливаем выражение для удаления записи
        $stmt = $dbConnection->prepare("DELETE FROM tournaments WHERE id = ?");
        if ($stmt === false) {
            throw new \Exception("Ошибка при подготовке запроса удаления");
        }
        
        // Привязываем параметр id
        $stmt->bind_param('i', $id);
        if ($stmt->execute() === false) {
            throw new \Exception("Ошибка выполнения запроса удаления: " . $stmt->error);
        }
        
        // Закрываем выражение
        $stmt->close();
        
        if (file_exists($new_game['logo_path'])) {
            unlink($new_game['logo_path']); 
        }
    }




}