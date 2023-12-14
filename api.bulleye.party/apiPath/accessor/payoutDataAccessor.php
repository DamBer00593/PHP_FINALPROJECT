<?php
/*
PHP SQL Median, all the queries for:
    Teams
    Players
    Games
    and Matchups are stored in here for reference in API.php
*/
require_once dirname(__DIR__, 1) . '/entities/payout.php';

class PayoutDataAccessor
{
    private $getAllPayoutsString = "select * from payout";
    private $getPayoutByIDString = "select * from payout where payoutID = :ID";
    private $putPayoutString = "update payout set payoutID = :ID, teamID = :teamID, roundID= :roundID, amount= :amount where payoutID = :ID";
    private $getAllPayoutsStatement = null;
    private $getPayoutByIDStatement = null;
    private $putPayoutStatement = null;


    /**
     * Creates a new instance of the accessor with the supplied database connection.
     * 
     * @param PDO $conn - a database connection
     */
    public function __construct($conn)
    {
        if (is_null($conn)) {
            throw new Exception("no connection");
        }

        $this->getAllPayoutsStatement = $conn->prepare($this->getAllPayoutsString);
        if(is_null($this->getAllPayoutsStatement)){
            throw new Exception("bad statement: '" . $this->getAllPayoutsString . "'");
        }   
        $this->getPayoutByIDStatement = $conn->prepare($this->getPayoutByIDString);
        if(is_null($this->getPayoutByIDStatement)){
            throw new Exception("bad statement: '" . $this->getPayoutByIDString . "'");
        }
        
        $this->putPayoutStatement = $conn->prepare($this->putPayoutString);
        if(is_null($this->putPayoutStatement)){
            throw new Exception("bad statement: '" . $this->putPayoutString . "'");
        }   
          
    }
    /*
        GET SERIES
    */
    public function getAllPayouts(){
        $results = [];
        try{
            $this->getAllPayoutsStatement->execute();
            $dbresults = $this->getAllPayoutsStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach($dbresults as $r){
                $payoutID = $r["payoutID"]
                $teamID = $r["teamID"];
                $roundID = $r["roundID"];
                $amount = $r["amount"];
                $object = new Team($payoutID, $roundID, $teamID,$amount);
                array_push($results, $object);
            }
        }
        catch(Exception $e) {
            $results = [];
        } finally {
            if(!is_null($this->getAllPayoutsStatement)){
                $this->getAllPayoutsStatement->closeCursor();
            }
        }
        return $results;
    }
    /*
        END OF GET SERIES
        START OF GET BY ID SERIES
    */
    public function getTeamByID($id){
        $result = null;
        try{
            $this->getPayoutByIDStatement->bindParam(":ID", $id);
            $this->getPayoutByIDStatement->execute();
            $dbresults = $this->getPayoutByIDStatement->fetch(PDO::FETCH_ASSOC);

           if($dbresults){
                $payoutID = $r["payoutID"]
                $teamID = $r["teamID"];
                $roundID = $r["roundID"];
                $amount = $r["amount"];
                $object = new Team($payoutID, $roundID, $teamID,$amount);
                $result = $object;
            }
        }
        catch(Exception $e) {
            $result = null;
            
        } finally {
            if(!is_null($this->getPayoutByIDStatement)){
                $this->getPayoutByIDStatement->closeCursor();
            }
        }
        return $result;
    }
    /*
        END OF GET BY ID SERIES
        START OF EXISTS SERIES
    */
    public function teamExists($item){
        return $this->getTeamByID($item->getTeamID()) !== null;
    }
    /*
        END OF EXISTS SERIES
        START OF POST SERIES
    */
    public function postTeam($item){
        if($this->teamExists($item)){
     
    /*
        END OF POST SERIES
        START OF PUT SERIES
    */
    public function putTeam($item){
        if(!$this->teamExists($item)){
            return false;
        }

        $success = false;
        $payoutID = $item->getPayoutID();
        $roundID = $item->getRoundID();
        $teamID = $item->getTeamID();
        $mount = $item->getAmount();

        try{

            $this->putPayoutStatement->bindParam(":ID", $payoutID);
            $this->putPayoutStatement->bindParam(":teamID", $teamID);
            $this->putPayoutStatement->bindParam(":roundID", $roundID);
            $this->putPayoutStatement->bindParam(":amount", $amount);

            $success = $this->putPayoutStatement->execute();
            $success = $this->putPayoutStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->putPayoutStatement)) {
                $this->putPayoutStatement->closeCursor();
            }
        }
        return $success;
    }
    /*
    END OF PUT SERIES
    START OF DELETE SERIES
    */
    
}
?>