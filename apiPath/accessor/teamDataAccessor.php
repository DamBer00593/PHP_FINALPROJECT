<?php
/*
PHP SQL Median, all the queries for:
    Teams
    Players
    Games
    and Matchups are stored in here for reference in API.php
*/
require_once dirname(__DIR__, 1) . '/entities/team.php';

class TeamDataAccessor
{
    private $getAllTeamsString = "select * from team";
    private $getTeamByIDString = "select * from team where teamID = :ID";
    private $postTeamString = "insert into team values (:ID, :TeamName)";
    private $putTeamString = "update team set teamID = :ID, teamName = :TeamName where teamID = :ID";
    private $deleteTeamString = "delete from team where teamID = :ID";
    private $getAllTeamsStatement = null;
    private $getTeamByIDStatement = null;
    private $postTeamStatement = null;
    private $putTeamStatement = null;
    private $deleteTeamStatement = null;

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

        $this->getAllTeamsStatement = $conn->prepare($this->getAllTeamsString);
        if(is_null($this->getAllTeamsStatement)){
            throw new Exception("bad statement: '" . $this->getAllTeamsString . "'");
        }   
        $this->getTeamByIDStatement = $conn->prepare($this->getTeamByIDString);
        if(is_null($this->getTeamByIDStatement)){
            throw new Exception("bad statement: '" . $this->getTeamByIDString . "'");
        }
        $this->postTeamStatement = $conn->prepare($this->postTeamString);
        if(is_null($this->postTeamStatement)){
            throw new Exception("bad statement: '" . $this->postTeamString . "'");
        }   
        $this->putTeamStatement = $conn->prepare($this->putTeamString);
        if(is_null($this->putTeamStatement)){
            throw new Exception("bad statement: '" . $this->putTeamString . "'");
        }   
        $this->deleteTeamStatement = $conn->prepare($this->deleteTeamString);
        if(is_null($this->deleteTeamStatement)){
            throw new Exception("bad statement: '" . $this->deleteTeamString . "'");
        }   
    }
    /*
        GET SERIES
    */
    public function getAllTeams(){
        $results = [];
        try{
            $this->getAllTeamsStatement->execute();
            $dbresults = $this->getAllTeamsStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach($dbresults as $r){
                $teamID = $r["teamID"];
                $teamName = $r["teamName"];
                $object = new Team($teamID, $teamName);
                array_push($results, $object);
            }
        }
        catch(Exception $e) {
            $results = [];
        } finally {
            if(!is_null($this->getAllTeamsStatement)){
                $this->getAllTeamsStatement->closeCursor();
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
            $this->getTeamByIDStatement->bindParam(":ID", $id);
            $this->getTeamByIDStatement->execute();
            $dbresults = $this->getTeamByIDStatement->fetch(PDO::FETCH_ASSOC);

           if($dbresults){
                $teamID = $dbresults["teamID"];
                $teamName = $dbresults["teamName"];
                
                $object = new Team($teamID, $teamName);
                $result = $object;
            }
        }
        catch(Exception $e) {
            $result = null;
            
        } finally {
            if(!is_null($this->getTeamByIDStatement)){
                $this->getTeamByIDStatement->closeCursor();
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
            return false;
        }

        $success = false;
        $teamID = $item->getTeamID();
        $teamName = $item->getTeamName();

        try{
            $this->postTeamStatement->bindParam(":ID", $teamID);
            $this->postTeamStatement->bindParam(":TeamName", $teamName);

            $success = $this->postTeamStatement->execute();
            $success = $this->postTeamStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->postTeamStatement)) {
                $this->postTeamStatement->closeCursor();
            }
        }
        return $success;
    }
    /*
        END OF POST SERIES
        START OF PUT SERIES
    */
    public function putTeam($item){
        if(!$this->teamExists($item)){
            return false;
        }

        $success = false;
        $teamID = $item->getTeamID();
        $teamName = $item->getTeamName();

        try{
            $this->putTeamStatement->bindParam(":ID", $teamID);
            $this->putTeamStatement->bindParam(":TeamName", $teamName);

            $success = $this->putTeamStatement->execute();
            $success = $this->putTeamStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->putTeamStatement)) {
                $this->putTeamStatement->closeCursor();
            }
        }
        return $success;
    }
    /*
    END OF PUT SERIES
    START OF DELETE SERIES
    */
    public function deleteTeam($item){
        if(!$this->teamExists($item)){
            return false;
        }

        $success = false;
        $teamID = $item->getTeamID();

        try{
            $this->deleteTeamStatement->bindParam(":ID", $teamID);

            $success = $this->deleteTeamStatement->execute();
            $success = $success && $this->deleteTeamStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->deleteTeamStatement)) {
                $this->deleteTeamStatement->closeCursor();
            }
        }
        return $success;
    }
}
?>