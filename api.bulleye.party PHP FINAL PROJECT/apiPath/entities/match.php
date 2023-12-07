<?php
class Match implements JsonSerializable{
    /**
     * @var matchID contains the Match ID
     */
    private $matchID;
    /**
     * @var roundID contains the Round ID (see isValidRoundType
     */
    private $roundID;
    /**     
     * @var matchGroup contains the Match Group
     */
    private $matchGroup;
    /**
     * @var teamID|null contains the team ID
     */
    private $teamID;
    /**
     * @var score|null contains the score
     */
    private $score;
    /**
     * @var ranking|null contains the ranking
     */
    private $ranking;


    /**
     * Constructor for the Match class
     * @param matchID
     * @param roundID
     * @param matchGroup
     * @param teamID|null
     * @param score|null
     * @param ranking|null
     */
    public function __construct($matchID, $roundID, $matchGroup, $teamID, $score, $ranking){
        if($matchID > 0){
            $this->matchID = $matchID;
        }else{
            throw new Exception("Not a valid Match ID");
        }

        if($this->isValidRoundType($roundID)){
            $this->roundID = $roundID;
        }else{
            throw new Exception("Not a valid Round Type");
        }
        
        if($matchGroup > 0){
            $this->matchGroup = $matchGroup;
        }else{
            throw new Exception("Not a valid MatchGroup");
        }
        
        $this->teamID = $teamID;
        $this->score = $score;
        $this->ranking = $ranking;
    }
    /**
     * Get the Match ID
     * @return matchID
     */
    public function getMatchID(){
        return $this->matchID;
    }
    /**
     * Get the Round ID
     * @return roundID
     */
    public function getRoundID(){
        return $this->roundID;
    }
    /**
     * Get the Match Group
     * @return matchGroup
     */
    public function getMatchGroup(){
        return $this->matchGroup;
    }
    /**
     * Get the Team ID
     * @return teamID|null
     */
    public function getTeamID(){
        return $this->teamID;
    }
    /**
     * Get the Score
     * @return score|null
     */
    public function getScore(){
        return $this->score;
    }
    /**
     * Get the Ranking
     * @return ranking|null
     */
    public function getRanking(){
        return $this->ranking;
    }
    /**
     * Verifies if the Round Type is one of the 11 for the constructor
     * @param rt <- the round type to be checked
     * @return true|false
     */
    private function isValidRoundType($rt){
        $roundTypes = [
            1=>"FINAL",
            2=>"INIT",
            3=>"QUAL",
            4=>"RAND1",
            5=>"RAND2",
            6=>"RAND3",
            7=>"RAND4",
            8=>"SEED1",
            9=>"SEED2",
            10=>"SEED3",
            11=>"SEED4"
        ];

        foreach($roundTypes as $r){
            if($r == $rt){
                return true;
            }
        }
        return false;
    }
    /**
     * The implementation of json JsonSerializable
     * @return get_object_vars($this)
     */
    public function jsonSerialize(){
        return get_object_vars($this);
    }
    
}
?>