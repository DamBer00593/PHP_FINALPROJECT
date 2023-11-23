<?php
class Team implements JsonSerializable{
    private $teamID;
    private $teamName;

    public function __construct($teamID, $teamName){
        if($teamID > 0){
            $this->teamID = $teamID;
        }else{
            throw new Exception("Not a valid Team   ID");
        }
        if(isset($teamName)){
            $this->teamName = $teamName;
        }else{
            throw new Exception("Not a valid Team Name");
        }
        
    }

    public function getTeamID(){
        return $this->teamID;
    }
    public function getTeamName(){
        return $this->teamName;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
?>