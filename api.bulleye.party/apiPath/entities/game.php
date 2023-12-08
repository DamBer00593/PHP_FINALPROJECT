<?php
class Game implements JsonSerializable{
    /**
     * @var gameID contains the game ID
     */
    private $gameID;
    /**
     * @var matchID contains the match ID
     */
    private $matchID;
    /**
     * @var gameNumber contains the game number
     */
    private $gameNumber;
    /**
     * @var gameStateID contains the game state ID (see isValidGameState)
     */
    private $gameStateID;
    /**
     * @var score|null contains the score
     */
    private $score;
    /**
     * @var balls|null contains the balls
     */
    private $balls;
    /**
     * @var playerID|null contains the player ID
     */
    private $playerID;
    /**
     * this is the constructor for the game class
     * @param gameID
     * @param matchID
     * @param gameNumber
     * @param gameStateID
     * @param score|null
     * @param balls|null
     * @param playerID|null
     */
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
        $this->playerID = $playerID;
        $this->score = $score;
        $this->balls = $balls;    
    }
    /**
     * Get the Game ID
     * @return gameID
     */
    public function getGameID(){
        return $this->gameID;
    }
    /**
     * Get the Match ID
     * @return matchID
     */
    public function getMatchID(){
        return $this->matchID;
    }
    /**
     * Get the Game Number
     * @return gameNumber
     */
    public function getGameNumber(){
        return $this->gameNumber;
    }
    /**
     * Get the Game State ID
     * @return gameStateID
     */
    public function getGameStateID(){
        return $this->gameStateID;
    }
    /**
     * Get the score
     * @return score|null
     */
    public function getScore(){
        return $this->score;
    }
    /**
     * Get the balls
     * @return balls|null
     */
    public function getBalls(){
        return $this->balls;
    }
    /**
     * Get the Player ID
     * @return playerID|null
     */
    public function getPlayerID(){
        return $this->playerID;
    }
    /**
     * The implementation of json JsonSerializable
     * @return get_object_vars($this)
     */
    public function jsonSerialize(){
        return get_object_vars($this);
    }
    /**
     * Verifies if the Game State is one of the 4 for the constructor
     * @param gs <- game state to verify 
     * @return true|false
     */
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