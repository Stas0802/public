<?php

namespace Mywork\App\Classes;



abstract class AbstractGame
{
    
    // Переменные для хранения данных турнира

    protected $id;
    protected $game;
    protected $description;
    protected $startDate;
    protected $endDate;
    protected $prizePool;
    protected $format;
    protected $teams;
    protected $logo;
    protected $uploadedLogoPath;
    protected $saveLogo = false;
    
    
    // Конструктор класса для инициализации переменных
    public function __construct( $game, $description,  $startDate, $endDate, $prizePool, $format, $teams, $logo, $id = null) {
        
        $this->id = $id;
        $this->game = $game;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->prizePool = $prizePool;
        $this->format = $format;
        $this->teams = $teams;
        $this->logo = $logo;
       $this->cleanInputFormXSS();
    }

    private function cleanInputFormXSS(){
        
        $this->description = htmlspecialchars($this->description, ENT_QUOTES,'UTF-8');
        $this->prizePool =  htmlspecialchars($this->prizePool, ENT_QUOTES,'UTF-8');
        $this->teams = htmlspecialchars($this->teams, ENT_QUOTES,'UTF-8');
    }

    // Методы доступа (геттеры)
    public function getId() {
        return $this->id;
    }
    public function getGame() {
        return $this->game;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getPrizePool() {
        return $this->prizePool;
    }

    public function getFormat() {
        return $this->format;
    }

    public function getTeams() {
        return $this->teams;
    }
   
    public function getLogoName() {
        return $this->logo['name'];
    }
    
    
   
}

