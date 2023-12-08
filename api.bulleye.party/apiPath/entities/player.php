<?php
class Player implements JsonSerializable{
    /**
     * @var playerID stores the Player ID
     */
    private $playerID;
    /**
     * @var teamID stores the team ID
     */
    private $teamID;
    /**
     * @var firstName stores the first name
     */
    private $firstName;
    /**
     * @var lastName stores the Last Name
     */
    private $lastName;
    /**
     * @var hometown stores the hometown
     */
    private $hometown;
    /**
     * @var provinceCode stores the provice code (see isValidProvinceCode)eee
     */
    private $provinceCode;

    public function __construct($playerID, $teamID, $firstName, $lastName, $hometown, $provinceCode){
        if($playerID > 0){
            $this->playerID = $playerID;
        }else{
            throw new Exception("Not a valid Player ID");
        }
        
        if($teamID > 0){
            $this->teamID = $teamID;
        }else{
            throw new Exception("Not a valid Team ID");
        }
        
        if(isset($firstName)){
            $this->firstName = $firstName;
        }else{
            throw new Exception("Not a valid First Name");
        }
        
        if(isset($lastName)){
            $this->lastName = $lastName;
        }else{
            throw new Exception("Not a valid Last Name");
        }

        if(isset($hometown)){
            $this->hometown = $hometown;
        }else{
            throw new Exception("Not a valid hometown");
        }
        
        
        if($this->isValidProvinceCode($provinceCode)){
            $this->provinceCode = $provinceCode;
        }else{
            throw new Exception("Not a valid Province Code");
        }
    }

    /**
     * Get the Player ID
     * @return playerID
     */
    public function getPlayerID(){
        return $this->playerID;
    }
    /**
     * Get the Team ID
     * @return teamID
     */
    public function getTeamID(){
        return $this->teamID;
    }
    /**
     * Get the First Name
     * @return firstName
     */
    public function getFirstName(){
        return $this->firstName;
    }
    /**
     * Get the Last Name
     * @return lastName
     */
    public function getLastName(){
        return $this->lastName;
    }
    /**
     * Get the Full name
     * @return lastName+fullName
     */
    public function getFullName(){
        return $this->firstname . " " . $this->lastName;
    }
    /**
     * Get the Hometown
     * @return hometown
     */
    public function getHometown(){
        return $this->hometown;
    }
    /**
     * Get the Province Code
     * @return provinceCode
     */
    public function getProvinceCode(){
        return $this->provinceCode;
    }
    /**
     * The implementation of json JsonSerializable
     * @return get_object_vars($this)
     */
    public function jsonSerialize(){
        return get_object_vars($this);
    }
    /**
     * Verifies if the Province Code is one of the 13 for the constructor
     * @param pc <- the province code to be checked
     * @return true|false
     */
    private function isValidProvinceCode($pc){
        $provinceArray = [
            1=>"ON",
            2=>"QC",
            3=>"NS",
            4=>"NB",
            5=>"MB",
            6=>"BC",
            7=>"PE",
            8=>"SK",
            9=>"AB",
            10=>"NL",
            11=>"NT",
            12=>"YT",
            13=>"NU",
        ];

        foreach($provinceArray as $p){
            if($p == $pc){
                return true;
            }
        }
        return false;
    }
}
?>