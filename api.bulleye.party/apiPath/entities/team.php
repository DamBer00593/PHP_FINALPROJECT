<?php
class Team implements JsonSerializable{
    /**
     * @var teamID contains the team ID
     */
    private $teamID;
    /**
     * @var teamName contains the Team Name
     */
    private $teamName;
    /**
     * Constructor for the Team class
     * @param teamID
     * @param teamName
     */
    public function __construct($teamID, $teamName){
        if($teamID > 0){
            $this->teamID = $teamID;
        }else{
            throw new Exception("Not a valid Team ID");
        }
        if(isset($teamName)){
            $this->teamName = $teamName;
        }else{
            throw new Exception("Not a valid Team Name");
        }
        
    }
    /**
     * Get the Team ID
     * @return teamID
     */
    public function getTeamID(){
        return $this->teamID;
    }
    /**
     * Get the Team Name
     * @return teamName
     */
    public function getTeamName(){
        return $this->teamName;
    }
    /**
     * The implementation of json JsonSerializable
     * @return get_object_vars($this)
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
?>