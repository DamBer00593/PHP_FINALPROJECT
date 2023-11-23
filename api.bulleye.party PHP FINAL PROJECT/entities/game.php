<?php
class Game implements JsonSerializable{
    private $gameID;
    private $matchID;
    private $gameNumber;
    private $gameStateID;
    private $score;
    private $balls;
    private $playerID;

    public function __construct($gameID, $matchID, $gameNumber, $gameStateID, $score, $balls, $playerID){
        if($gameID > 0){
            $this->gameID = $gameID;
        }else{
            throw new Exception("Not a valid Game ID");
        }
        if($matchID > 0){
            $this->matchID = $matchID;
        }else{
            throw new Exception("Not a valid Match ID");
        }
        if($gameNumber > 0){
            $this->gameNumber = $gameNumber;
        }else{
            throw new Exception("Not a valid Game Number");
        }
        if($this->isValidGameState($gameStateID)){
            $this->gameStateID = $gameStateID;
        }else{
            throw new Exception("Not a valid Game State");
        }
        if($playerID > 0){
            $this->playerID = $playerID;
        }else{
            throw new Exception("Not a Player ID");
        }
        $this->score = $score;
        $this->balls = $balls;    
    }
    
    public function getGameID(){
        return $this->gameID;
    }
    public function getMatchID(){
        return $this->matchID;
    }
    public function getGameNumber(){
        return $this->gameNumber;
    }
    public function getGameStateID(){
        return $this->gameStateID;
    }
    public function getScore(){
        return $this->score;
    }
    public function getBalls(){
        return $this->balls;
    }
    public function getPlayerID(){
        return $this->playerID;
    }

    public function jsonSerialize(){
        return get_object_vars($this);
    }

    private function isValidGameState($gs){
        $gameStates = [
            1=>"AVAILABLE",
            2=>"COMPLETE",
            3=>"INPROGRESS",
            4=>"UNASSIGNED"
        ];

        foreach($gameStates as $g){
            if($g == $gs){
                return true;
            }
        }
        return false;
    }
}
?>