<?php
class Player implements JsonSerializable{
    private $playerID;
    private $teamID;
    private $firstName;
    private $lastName;
    private $hometown;
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

    public function getPlayerID(){
        return $this->playerID;
    }
    public function getTeamID(){
        return $this->teamID;
    }
    public function getFirstName(){
        return $this->firstName;
    }
    public function getLastName(){
        return $this->lastName;
    }
    public function getFullName(){
        return $this->firstname . " " . $this->lastName;
    }
    public function getHometown(){
        return $this->hometown;
    }
    public function getProvinceCode(){
        return $this->provinceCode;
    }

    public function jsonSerialize(){
        return get_object_vars($this);
    }

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