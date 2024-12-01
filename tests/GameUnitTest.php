<?php 

namespace Mywork\App\Classes;

use PHPUnit\Framework\TestCase;

class GameUnitTest extends TestCase 
{

    private $fakeImagePath; 

    protected function setUp(): void
    {
        // Путь к фиктивному файлу в директории тестов
        $this->fakeImagePath = __DIR__ . '/../tests/test_image.png';

        // Создаем пустой PNG файл
        file_put_contents($this->fakeImagePath, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/wcAAgAB/1JlcYkAAAAASUVORK5CYII='));
    }

    protected function tearDown(): void
    {
        // Удаляем фиктивный файл после теста
        if (file_exists($this->fakeImagePath)) {
            unlink($this->fakeImagePath);
        }
    }
    public function testGetGame() {
         
        $new_game = new NewGame(1,'Description', 2024-11-12, 2024-11-13, 20.99, 'Single Elimination', 'Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path']);

        $this->assertEquals(1, $new_game->getGame());
    }

    public function testGetDiscription(){

        $new_game = new NewGame(1,'Description', 2024-11-12, 2024-11-13, 20.99, 'Single Elimination', 'Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path']);
        $this->assertEquals('Description', $new_game->getDescription());
    }

    public function testGetStartDate() {
         
        $new_game = new NewGame(1,'Description', 2024-11-12, 2024-11-13, 20.99, 'Single Elimination', 'Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path']);
        $this->assertEquals(2024-11-12, $new_game->getStartDate());
    }

    public function testGetEndDate() {

        $new_game = new NewGame(1,'Description', 2024-11-12, 2024-11-13, 20.99, 'Single Elimination', 'Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path']);
        $this->assertEquals(2024-11-13, $new_game->getEndDate());
    }

    public function testGetPrizePool() {

        $new_game = new NewGame(1,'Description', 2024-11-12, 2024-11-13, 20.99, 'Single Elimination' , 'Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path']);
        $this->assertEquals(20.99, $new_game->getPrizePool());
    }

    public function testGetFormat() {
         $new_game = new NewGame(1,'Description', 2024-11-12, 2024- 11-13, 20.99, 'Single Elimination', 'Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path']);
         $this->assertEquals('Single Elimination', $new_game->getFormat());
    }

    public function testGetTeams() {

        $new_game = new NewGame(1,'Description', 2024-11-12, 2024- 11-13, 20.99, 'Single Elimination', 'Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path']);
        $this->assertEquals('Teams', $new_game->getTeams());
    }

    public function testGetLogoName() {
        $logo = [
            'name' => 'image.png',
            'type' => 'image/png',
            'tmp_name' => '/path/to/tmp/file',  // Убедитесь, что путь верный
            'error' => 0,
            'size' => 123456  // Пример размера файла
        ];
    
        $new_game = new NewGame(1, 'Description', 2024-11-12, 2024-11-13, 20.99, 'Single Elimination', 'Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path'],'id');
        
        
        $this->assertEquals('image.png', $new_game->getLogoName());
    }

    public function testGetIdNotNull() {
        
        $new_game = new NewGame(1,'Description', 2024-11-12 , 2024-11-13, 20.99, 'Single Elimination', ' Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path'],'id');
        $this->assertNotNull($new_game->getId());

    }

    public function testInstanceOff() {
        $new_game = new NewGame(1,'Description', 2024-11-12 , 2024-11-13, 20.99, 'Single Elimination', ' Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path'], 'id');
        $this->assertInstanceOf(NewGame::class, $new_game);
    }

    public function testValidateInput() {
        $new_game = new NewGame(1,'Description', 2024-11-12 , 2024-11-13, 20.99, 'Single Elimination', ' Teams', ['name'=> 'image.png', 'logo_path' => 'logo_path']);
        $_POST ['description'] = 'Description';
        $_POST ['prizePool'] = 20.99;
        $_POST ['teams'] = 'Single Elimination';
        $_FILES ['logo'] = [
            'name' => 'test_image.png',
            'type' => 'image/png',
            'tmp_name' => $this->fakeImagePath,
            'error' => 0,
            'size' => 123456
        ];

        try {
            $new_game->validateInput();
            $this->expectNotToPerformAssertions();
        } catch (\Exception $e) {
            $this->fail('Валидация не прошла: ' . $e->getMessage());
        
        }
    }
    
    
}