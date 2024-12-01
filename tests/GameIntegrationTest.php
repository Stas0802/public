<?php

namespace Mywork\App\Classes;

use PHPUnit\Framework\TestCase;

class GameIntegrationTest extends TestCase
{
    private $db;

    public function setUp(): void {
        $this->db = Database::getInstance()->getConnection();
        $this->db->query("TRUNCATE TABLE tournaments");
        $this->db->query("INSERT INTO tournaments (id, game, description, startDate, endDate, prizePool, format, teams, logo_path) VALUES (1, 1, 'description', '2024-11-12', '2024-11-13', 20.99, 'Single Elimination', 'teams', 'test_image.png')");
    }
    
    public function testSaveDb() {
    
        $new_game = new NewGame(1, 'Description', '2024-11-12', '2024-11-13', 20.99, 'Single Elimination', 'Teams',  ['name'=> 'image.png', 'logo_path' => 'logo_path']);
        $new_game->saveDb();
    
        $new_game = $this->db->query("SELECT * FROM tournaments WHERE id = " . $new_game->getId())->fetch_assoc();
    
        $this->assertNotEmpty($new_game, 'Гра не знайдена в базе данных.');
        $this->assertEquals(2, $new_game['id']);
        $this->assertEquals(1, $new_game['game']);
        $this->assertEquals('Description', $new_game['description']);
        $this->assertEquals('2024-11-12', $new_game['startDate']);
        $this->assertEquals('2024-11-13', $new_game['endDate']);
        $this->assertEquals(20.99, $new_game['prizePool']); 
        $this->assertEquals('Single Elimination', $new_game['format']);
        $this->assertEquals('Teams', $new_game['teams']);
         $this->assertEquals('image.png', $new_game['logo_path']);
    }


    public function testUpdateDb() {

        $this->db = Database::getInstance()->getConnection();
        $this->db->query("TRUNCATE TABLE tournaments");
        $this->db->query("INSERT INTO tournaments (id, game, description, startDate, endDate, prizePool, format, teams, logo_path) VALUES (1, 1, 'description', '2024-11-12', '2024-11-13', 20.99, 'Single Elimination', 'teams', 'test_image.png')");
        $new_game = new NewGame(1, 'Description', '2024-11-12', '2024-11-13', 20.99, 'Single Elimination', 'Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path']);
        $new_game->saveDb();

        $new_game = $this->db->query("SELECT * FROM tournaments WHERE id = " . $new_game->getId())->fetch_assoc();

        $this->assertEquals(1,$new_game['game']);
        $this->assertEquals('Description',$new_game['description']);
        $this->assertEquals('2024-11-12',$new_game['startDate']);
        $this->assertEquals('2024-11-13',$new_game['endDate']);
        $this->assertEquals(20.99,$new_game['prizePool']);
        $this->assertEquals('Single Elimination',$new_game['format']);
        $this->assertEquals('Teams',$new_game['teams']);
        $this->assertEquals('image.png',$new_game['logo_path']);
    }

    public function testFetchById() {
        
        $new_game = NewGame::fetchById(1);

        $this->assertNotNull($new_game);
        $this->assertEquals(1, $new_game['id']);
        $this->assertEquals(1, $new_game['game']);
        $this->assertEquals('description', $new_game['description']);
        $this->assertEquals('2024-11-12', $new_game['startDate']);
        $this->assertEquals('2024-11-13', $new_game['endDate']);
        $this->assertEquals(20.99, $new_game['prizePool']); 
        $this->assertEquals('Single Elimination', $new_game['format']);
        $this->assertEquals('teams', $new_game['teams']);
         $this->assertEquals('test_image.png', $new_game['logo_path']);

    }

    public function testFetchAll() {

        $tournaments = NewGame::fetchAll();
        $this->assertCount(1,$tournaments);

    }

    public function testDeleteById() {

        $new_game = NewGame::fetchById(1);

        $this->assertNotNull($new_game);

        NewGame::deleteById(1);

        try {
            $new_game = NewGame::fetchById(1);
        } catch (\Exception $e) {
            $this->assertEquals('Гра за id = 1 не знайдена', $e->getMessage());
        }
    }
    

   
}