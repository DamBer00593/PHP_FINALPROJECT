<?php
class Payout implements JsonSerializable{
    /**
     * @var payoutID contains the payout ID
     */
    private $payoutID;
    /**
     * @var teamID contains the team ID
     */
    private $teamID;
    /**
     * @var teamName contains the Team Name
     */
    /**
     * @var roundID contans the round ID
     */
    private $roundID;
    /**
     * @var amount contains the amount
     */
    private $amount;

    public function __construct($payoutID, $roundID, $teamID, $amount){
        if(isset($teamID)){
            $this->teamID = $teamID;
        }
        if($payoutID > 0){
            $this->payoutID = $payoutID;
        }
        else{
            throw("Not a valid payout ID");
        } 
        if (isValidRoundType($roundID)){
            $this->roundId = $roundID;
        }
        else{
            throw("Not a valid round ID");
        }
        if ($amount > 0){
            $this->amount = $amount;
        }
        else{
            throw("Not a valid amount");
        }

        
    }
    /**
     * Get the Team ID
     * @return payoutID
     */
    public function getPayoutID(){
        return $this->payoutID;
    }
    /**
     * Get the Team ID
     * @return roundID
     */
    public function getRoundID(){
        return $this->roundID;
    }
    /**
     * Get the Team ID
     * @return teamID
     */
    public function getTeamID(){
        return $this->teamID;
    }
    /**
     * Get the Team ID
     * @return amount
     */
    public function getAmount(){
        return $this->amount;
    }
    
    
    /**
     * The implementation of json JsonSerializable
     * @return get_object_vars($this)
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
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
}
?>