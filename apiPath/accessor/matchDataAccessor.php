<?php
/*
PHP SQL Median, all the queries for:
    Teams 
    Players
    Games
    and Matchups are stored in here for reference in API.php
*/
require_once dirname(__DIR__, 1) . '/entities/match.php';

class MatchDataAccessor
{
    private $getAllMatchUpString = "select * from matchup";
    private $getMatchByIDString = "select * from matchup where matchID = :ID";
    private $postMatchUpString = "insert into matchup values (:ID, :RoundID, :MatchGroup, :TeamID, :Score, :Ranking)";
    private $putMatchString = "update matchup set matchID = :ID, roundID = :RoundID, matchGroup = :MatchGroup, teamID = :TeamID, score = :Score, ranking = :Ranking where matchID = :ID";
    private $deleteMatchString = "delete from matchup where matchID = :ID";
    private $getAllMatchupStatement = null;
    private $getMatchByIDStatement = null;
    private $postMatchUpStatement = null;
    private $putMatchStatement = null;
    private $deleteMatchStatement = null;



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

        $this->getAllMatchupStatement = $conn->prepare($this->getAllMatchUpString);
        if(is_null($this->getAllMatchupStatement)){
            throw new Exception("bad statement: '" . $this->getAllMatchUpString . "'");
        }  

        $this->getMatchByIDStatement = $conn->prepare($this->getMatchByIDString);
        if(is_null($this->getMatchByIDStatement)){
            throw new Exception("bad statement: '" . $this->getMatchByIDString . "'");
        }  

        $this->postMatchUpStatement = $conn->prepare($this->postMatchUpString);
        if(is_null($this->postMatchUpStatement)){
            throw new Exception("bad statement: '" . $this->postMatchUpString . "'");
        }  
       
        $this->putMatchStatement = $conn->prepare($this->putMatchString);
        if(is_null($this->putMatchStatement)){
            throw new Exception("bad statement: '" . $this->putMatchString . "'");
        }  

        $this->deleteMatchStatement = $conn->prepare($this->deleteMatchString);
        if(is_null($this->deleteMatchStatement)){
            throw new Exception("bad statement: '" . $this->deleteMatchString . "'");
        }  

    }
    /*
        GET SERIES
    */
    public function getAllMatches(){
        $results = [];
        try{
            $this->getAllMatchupStatement->execute();
            $dbresults = $this->getAllMatchupStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach($dbresults as $r){
                $matchID = $r["matchID"];
                $roundID = $r["roundID"];
                $matchGroup = $r["matchgroup"];
                $teamID = $r["teamID"];
                $score = $r["score"];
                $ranking = $r["ranking"];
                $object = new Match($matchID, $roundID, $matchGroup, $teamID, $score, $ranking);
                array_push($results, $object);
            }
        }
        catch(Exception $e) {
            $results = [];
            throw new Exception($e);
        } finally {
            if(!is_null($this->getAllMatchupStatement)){
                $this->getAllMatchupStatement->closeCursor();
            }
        }
        return $results;
    }
    /*
        END OF GET SERIES
        START OF GET BY ID SERIES
    */
    public function getMatchByID($id){
        $result = null;
        try{
            $this->getMatchByIDStatement->bindParam(":ID", $id);
            $this->getMatchByIDStatement->execute();
            $dbresults = $this->getMatchByIDStatement->fetch(PDO::FETCH_ASSOC);

            if($dbresults){
                $matchID = $dbresults["matchID"];
                $roundID = $dbresults["roundID"];
                $matchGroup = $dbresults["matchgroup"];
                $teamID = $dbresults["teamID"];
                $score = $dbresults["score"];
                $ranking = $dbresults["ranking"];
                $object = new Match($matchID, $roundID, $matchGroup, $teamID, $score, $ranking);
                $result = $object;
            }
        }
        catch(Exception $e) {
            $result = null;
        } finally {
            if(!is_null($this->getMatchByIDStatement)){
                $this->getMatchByIDStatement->closeCursor();
            }
        }
        return $result;
    }
    /*
        END OF GET BY ID SERIES
        START OF EXISTS SERIES
    */
    public function matchUpExists($item){
        return $this->getMatchByID($item->getMatchID()) !== null;
    }
    /*
        END OF EXISTS SERIES
        START OF POST SERIES
    */
    public function postMatch($item){
        if($this->matchUpExists($item)){
            return false;
        }
        $success = false;
        $matchID = $item->getMatchID();
        $roundID = $item->getRoundID();
        $matchGroup = $item->getMatchGroup();
        $teamID = $item->getTeamID();
        $score = $item->getScore();
        $ranking = $item->getRanking();

        try{
            $this->postMatchUpStatement->bindParam(":ID",$matchID);
            $this->postMatchUpStatement->bindParam(":RoundID",$roundID);
            $this->postMatchUpStatement->bindParam(":MatchGroup",$matchGroup);
            $this->postMatchUpStatement->bindParam(":TeamID",$teamID);
            $this->postMatchUpStatement->bindParam(":Score",$score);
            $this->postMatchUpStatement->bindParam(":Ranking",$ranking);
      
            $success = $this->postMatchUpStatement->execute();
            $success = $this->postMatchUpStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->postMatchUpStatement)) {
                $this->postMatchUpStatement->closeCursor();
            }
        }
        return $success;
    }
    /*
        END OF POST SERIES
        START OF PUT SERIES
    */
    public function putMatch($item){
        if(!$this->matchUpExists($item)){
            return false;
        }

        $success = false;
        $matchID = $item->getMatchID();
        $roundID = $item->getRoundID();
        $matchGroup = $item->getMatchGroup();
        $teamID = $item->getTeamID();
        $score = $item->getScore();
        $ranking = $item->getRanking();

        try{
            $this->putMatchStatement->bindParam(":ID",$matchID);
            $this->putMatchStatement->bindParam(":RoundID",$roundID);
            $this->putMatchStatement->bindParam(":MatchGroup",$matchGroup);
            $this->putMatchStatement->bindParam(":TeamID",$teamID);
            $this->putMatchStatement->bindParam(":Score",$score);
            $this->putMatchStatement->bindParam(":Ranking",$ranking);
            
            $success = $this->putMatchStatement->execute();
            $success = $this->putMatchStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->putMatchStatement)) {
                $this->putMatchStatement->closeCursor();
            }
        }
        return $success;
    }
    /*
    END OF PUT SERIES
    START OF DELETE SERIES
    */
    public function deleteMatch($item){
        if(!$this->matchUpExists($item)){
            return false;
        }
        $success = false;
        $matchID = $item->getMatchID();

        try{
            $this->deleteMatchStatement->bindParam(":ID",$matchID);
      
            $success = $this->deleteMatchStatement->execute();
            $success = $success && $this->deleteMatchStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
        } finally {
            if (!is_null($this->deleteMatchStatement)) {
                $this->deleteMatchStatement->closeCursor();
            }
        }
        return $success;
    }
}
?>