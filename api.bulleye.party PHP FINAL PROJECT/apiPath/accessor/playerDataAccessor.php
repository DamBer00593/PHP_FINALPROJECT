<?php
/*
PHP SQL Median, all the queries for:
    Teams 
    Players
    Games
    and Matchups are stored in here for reference in API.php
*/
require_once dirname(__DIR__, 1) . '/entities/player.php';

class PlayerDataAccessor
{
    private $getAllPlayersString = "select * from player";
    private $getPlayerByIDString = "select * from player where playerID = :ID";
    private $postPlayerString = "insert into player values (:ID, :TeamID, :FirstName, :LastName, :Hometown, :ProvinceCode)";
    private $putPlayerString = "update player set playerID = :ID, teamID = :TeamID, firstName = :FirstName, lastName = :LastName, hometown = :HomeTown, provinceCode = :ProvinceCode where PlayerID = :ID";
    private $deletePlayerString = "delete from player where playerID = :ID";
    private $getAllPlayersStatement = null;
    private $getPlayerByIDStatement = null;
    private $postPlayerStatement = null;
    private $putPlayerStatement = null;
    private $deletePlayerStatement = null;


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

        $this->getAllPlayersStatement = $conn->prepare($this->getAllPlayersString);
        if(is_null($this->getAllPlayersStatement)){
            throw new Exception("bad statement: '" . $this->getAllPlayersString . "'");
        }  

        $this->getPlayerByIDStatement = $conn->prepare($this->getPlayerByIDString);
        if(is_null($this->getPlayerByIDStatement)){
            throw new Exception("bad statement: '" . $this->getPlayerByIDString . "'");
        }   

        $this->postPlayerStatement = $conn->prepare($this->postPlayerString);
        if(is_null($this->postPlayerStatement)){
            throw new Exception("bad statement: '" . $this->postPlayerString . "'");
        }  

        $this->putPlayerStatement = $conn->prepare($this->putPlayerString);
        if(is_null($this->putPlayerStatement)){
            throw new Exception("bad statement: '" . $this->putPlayerString . "'");
        }  

        $this->deletePlayerStatement = $conn->prepare($this->deletePlayerString);
        if(is_null($this->deletePlayerStatement)){
            throw new Exception("bad statement: '" . $this->deletePlayerString . "'");
        }  
    }
    
    /*
        GET SERIES
    */
    public function getAllPlayers(){
        $results = [];
        try{
            $this->getAllPlayersStatement->execute();
            $dbresults = $this->getAllPlayersStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach($dbresults as $r){
                $playerID = $r["playerID"];
                $teamID = $r["teamID"];
                $firstName = $r["firstName"];
                $lastName = $r["lastName"];
                $hometown = $r["hometown"];
                $provinceCode = $r["provinceCode"];
                $object = new Player($playerID, $teamID, $firstName, $lastName, $hometown, $provinceCode);
                array_push($results, $object);
            }
        }
        catch(Exception $e) {
            $results = [];
        } finally {
            if(!is_null($this->getAllPlayersStatement)){
                $this->getAllPlayersStatement->closeCursor();
            }
        }
        return $results;
    }

    /*
        END OF GET SERIES
        START OF GET BY ID SERIES
    */
    public function getPlayerByID($id){
        $result = null;
        try{
            $this->getPlayerByIDStatement->bindParam(":ID", $id);
            $this->getPlayerByIDStatement->execute();
            $dbresults = $this->getPlayerByIDStatement->fetch(PDO::FETCH_ASSOC);

            if($dbresults){
                $playerID = $dbresults["playerID"];
                $teamID = $dbresults["teamID"];
                $firstName = $dbresults["firstName"];
                $lastName = $dbresults["lastName"];
                $hometown = $dbresults["hometown"];
                $provinceCode = $dbresults["provinceCode"];
                $object = new Player($playerID, $teamID, $firstName, $lastName, $hometown, $provinceCode);
                $result = $object;
                //$strtemp = $object->jsonSerialize();
                //throw new Exception(implode(" ",$strtemp));
            }
        }
        catch(Exception $e) {
            $result = null;
            throw new Exception($e);
        } finally {
            if(!is_null($this->getPlayerByIDStatement)){
                $this->getPlayerByIDStatement->closeCursor();
            }
        }
        return $result;
    }
    /*
        END OF GET BY ID SERIES
        START OF EXISTS SERIES
    */
    public function playerExists($item){
        return $this->getPlayerByID($item->getPlayerID()) !== null;
    }
    /*
        END OF EXISTS SERIES
        START OF POST SERIES
    */
    public function postPlayer($item){
        if($this->playerExists($item)){
            return false;
        }
        $success = false;
        $playerID = $item->getPlayerID();
        $teamID = $item->getTeamID();
        $firstName = $item->getFirstName();
        $lastName = $item->getLastName();
        $hometown = $item->getHometown();
        $provincecode = $item->getProvinceCode();

        try{
            $this->postPlayerStatement->bindParam(":ID",$playerID);
            $this->postPlayerStatement->bindParam(":TeamID",$teamID);
            $this->postPlayerStatement->bindParam(":FirstName",$firstName);
            $this->postPlayerStatement->bindParam(":LastName",$lastName);
            $this->postPlayerStatement->bindParam(":Hometown",$hometown);
            $this->postPlayerStatement->bindParam(":ProvinceCode",$provincecode);

            $success = $this->postPlayerStatement->execute();
            $success = $this->postPlayerStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->postPlayerStatement)) {
                $this->postPlayerStatement->closeCursor();
            }
        }
        return $success;

    }
    /*
        END OF POST SERIES
        START OF PUT SERIES
    */
    
    public function putPlayer($item){
        if(!$this->playerExists($item)){
            return false;
        }
        $success = false;
        $playerID = $item->getPlayerID();
        $teamID = $item->getTeamID();
        $firstName = $item->getFirstName();
        $lastName = $item->getLastName();
        $hometown = $item->getHometown();
        $provincecode = $item->getProvinceCode();

        try{
            $this->putPlayerStatement->bindParam(":ID",$playerID);
            $this->putPlayerStatement->bindParam(":TeamID",$teamID);
            $this->putPlayerStatement->bindParam(":FirstName",$firstName);
            $this->putPlayerStatement->bindParam(":LastName",$lastName);
            $this->putPlayerStatement->bindParam(":HomeTown",$hometown);
            $this->putPlayerStatement->bindParam(":ProvinceCode",$provincecode);

            $success = $this->putPlayerStatement->execute();
            $success = $this->putPlayerStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->putPlayerStatement)) {
                $this->putPlayerStatement->closeCursor();
            }
        }
        return $success;

    }
    /*
    END OF PUT SERIES
    START OF DELETE SERIES
    */
    public function deletePlayer($item){
        if(!$this->playerExists($item)){
            return false;
        }
        $success = false;
        $playerID = $item->getPlayerID();


        try{
            $this->deletePlayerStatement->bindParam(":ID",$playerID);

            $success = $this->deletePlayerStatement->execute();
            $success = $success && $this->deletePlayerStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->deletePlayerStatement)) {
                $this->deletePlayerStatement->closeCursor();
            }
        }
        return $success;

    }
}
?>