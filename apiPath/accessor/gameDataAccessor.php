<?php
/*
PHP SQL Median, all the queries for:
    Teams 
    Players
    Games
    and Matchups are stored in here for reference in API.php
*/
require_once dirname(__DIR__, 1) . '/entities/game.php';

class  GameDataAccessor
{

    private $getAllGameString = "select * from game";
    private $getGameByIDString = "select * from game where gameID = :ID";
    private $postGameString = "insert into game values (:ID, :MatchID, :GameNumber, :GameStateID, :PlayerID, :Score, :Balls)";
    private $putGameString = "update game set gameID = :ID, matchID = :MatchID, gameNumber = :GameNumber, gameStateID = :GameStateID, score = :Score, balls = :Balls, playerID = :PlayerID where gameID = :ID";
    private $deleteGameString = "delete from game where gameID = :ID";
    private $getAllGamesStatement = null;
    private $getGameByIDStatement = null;
    private $postGameStatement = null;
    private $putGameStatement = null;
    private $deleteGameStatement = null;



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

        $this->getAllGamesStatement = $conn->prepare($this->getAllGameString);
        if(is_null($this->getAllGamesStatement)){
            throw new Exception("bad statement: '" . $this->getAllGameString . "'");
        }  

        $this->getGameByIDStatement = $conn->prepare($this->getGameByIDString);
        if(is_null($this->getGameByIDStatement)){
            throw new Exception("bad statement: '" . $this->getGameByIDString . "'");
        }  

        $this->postGameStatement = $conn->prepare($this->postGameString);
        if(is_null($this->postGameStatement)){
            throw new Exception("bad statement: '" . $this->postGameString . "'");
        }  

        $this->putGameStatement = $conn->prepare($this->putGameString);
        if(is_null($this->putGameStatement)){
            throw new Exception("bad statement: '" . $this->putGameString . "'");
        }  

        $this->deleteGameStatement = $conn->prepare($this->deleteGameString);
        if(is_null($this->deleteGameStatement)){
            throw new Exception("bad statement: '" . $this->deleteGameString . "'");
        }  
    }
    /*
        GET SERIES
    */
    public function getAllGames(){
        $results = [];
        try{
            $this->getAllGamesStatement->execute();
            $dbresults = $this->getAllGamesStatement->fetchAll(PDO::FETCH_ASSOC);

            foreach($dbresults as $r){
                $gameID = $r["gameID"];
                $matchID = $r["matchID"];
                $gameNumber = $r["gameNumber"];
                $gameStateID = $r["gameStateID"];
                $score = $r["score"];
                $balls = $r["balls"];
                $playerID = $r["playerID"];
                $object = new Game($gameID, $matchID, $gameNumber, $gameStateID, $score, $balls, $playerID);
                array_push($results, $object);
            }
        }
        catch(Exception $e) {
            $results = [];
            
        } finally {
            if(!is_null($this->getAllGamesStatement)){
                $this->getAllGamesStatement->closeCursor();
            }
        }
        return $results;
    }
    /*
        END OF GET SERIES
        START OF GET BY ID SERIES
    */
    public function getGameByID($id){
        $result = null;
        try{
            $this->getGameByIDStatement->bindParam(":ID", $id);
            $this->getGameByIDStatement->execute();
            $dbresults = $this->getGameByIDStatement->fetch(PDO::FETCH_ASSOC);

            if($dbresults){
                $gameID = $dbresults["gameID"];
                $matchID = $dbresults["matchID"];
                $gameNumber = $dbresults["gameNumber"];
                $gameStateID = $dbresults["gameStateID"];
                $score = $dbresults["score"];
                $balls = $dbresults["balls"];
                $playerID = $dbresults["playerID"];
                $object = new Game($gameID, $matchID, $gameNumber, $gameStateID, $score, $balls, $playerID);
                $result = $object;
            }
        }
        catch(Exception $e) {
            $result = null;
        } finally {
            if(!is_null($this->getGameByIDStatement)){
                $this->getGameByIDStatement->closeCursor();
            }
        }
        return $result;
    }
    /*
        END OF GET BY ID SERIES
        START OF EXISTS SERIES
    */
    public function gameExists($item){
        return $this->getGameByID($item->getGameID()) !== null;
    }
    /*
        END OF EXISTS SERIES
        START OF POST SERIES
    */
    public function postGame($item){
        if($this->gameExists($item)){
            return false;
        }
        $success = false;
        $gameID = $item->getGameID();
        $matchID = $item->getMatchID();
        $gameNumber = $item->getGameNumber();
        $gameStateID = $item->getGameStateID();
        $score = $item->getScore();
        $balls = $item->getBalls();
        $playerID = $item->getPlayerID();

        try{
            $this->postGameStatement->bindParam(":ID",$gameID);
            $this->postGameStatement->bindParam(":MatchID",$matchID);
            $this->postGameStatement->bindParam(":GameNumber",$gameNumber);
            $this->postGameStatement->bindParam(":GameStateID",$gameStateID);
            $this->postGameStatement->bindParam(":Score",$score);
            $this->postGameStatement->bindParam(":Balls",$balls);
            $this->postGameStatement->bindParam(":PlayerID",$playerID);

            $success = $this->postGameStatement->execute();
            $success = $this->postGameStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->postGameStatement)) {
                $this->postGameStatement->closeCursor();
            }
        }
        return $success;

    }
    /*
        END OF POST SERIES
        START OF PUT SERIES
    */
    public function putGame($item){
        if(!$this->gameExists($item)){
            return false;
        }
        $success = false;
        $gameID = $item->getGameID();
        $matchID = $item->getMatchID();
        $gameNumber = $item->getGameNumber();
        $gameStateID = $item->getGameStateID();
        $score = $item->getScore();
        $balls = $item->getBalls();
        $playerID = $item->getPlayerID();

        try{
            $this->putGameStatement->bindParam(":ID",$gameID);
            $this->putGameStatement->bindParam(":MatchID",$matchID);
            $this->putGameStatement->bindParam(":GameNumber",$gameNumber);
            $this->putGameStatement->bindParam(":GameStateID",$gameStateID);
            $this->putGameStatement->bindParam(":Score",$score);
            $this->putGameStatement->bindParam(":Balls",$balls);
            $this->putGameStatement->bindParam(":PlayerID",$playerID);

            $success = $this->putGameStatement->execute();
            $success = $this->putGameStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->putGameStatement)) {
                $this->putGameStatement->closeCursor();
            }
        }
        return $success;

    }
    /*
    END OF PUT SERIES
    START OF DELETE SERIES
    */
    public function deleteGame($item){
        if(!$this->gameExists($item)){
            return false;
        }
        $success = false;
        $gameID = $item->getGameID();
        

        try{
            $this->deleteGameStatement->bindParam(":ID",$gameID);

            $success = $this->deleteGameStatement->execute();
            $success = $success && $this->deleteGameStatement->rowCount() === 1;
        } catch (PDOException $e) {
            $success = false;
            throw new Exception($e);
        } finally {
            if (!is_null($this->deleteGameStatement)) {
                $this->deleteGameStatement->closeCursor();
            }
        }
        return $success;

    }
}
?>