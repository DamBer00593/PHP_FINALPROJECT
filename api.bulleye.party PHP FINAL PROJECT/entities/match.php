<?php
class Match implements JsonSerializable{
    private $matchID;
    private $roundID;
    private $matchGroup;
    private $teamID;
    private $score;
    private $ranking;

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

    public function getMatchID(){
        return $this->matchID;
    }
    public function getRoundID(){
        return $this->roundID;
    }
    public function getMatchGroup(){
        return $this->matchGroup;
    }
    public function getTeamID(){
        return $this->teamID;
    }
    public function getScore(){
        return $this->score;
    }
    public function getRanking(){
        return $this->ranking;
    }
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

    public function jsonSerialize(){
        return get_object_vars($this);
    }
    
}
?>