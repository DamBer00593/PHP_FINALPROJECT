<?php
/*
PHP SQL Median, all the queries for:
    Teams
    Players
    Games
    and Matchups are stored in here for reference in API.php
*/
require_once dirname(__DIR__, 1) . '/api.bulleye.party/entities/team.php';
require_once dirname(__DIR__, 1) . '/api.bulleye.party/entities/match.php';
require_once dirname(__DIR__, 1) . '/api.bulleye.party/entities/player.php';
require_once dirname(__DIR__, 1) . '/api.bulleye.party/entities/game.php';

class DataAccessor
{
    private $getAllTeamsString = "select * from team";
    private $getAllPlayersString = "select * from player";
    private $getAllGameString = "select * from game";
    private $getAllMatchUpString = "select * from matchup";

    private $getTeamByIDString = "select * from team where teamID = :ID";
    private $getPlayerByIDString = "select * from player where playerID = :ID";
    private $getGameByIDString = "select * from game where gameID = :ID";
    private $getMatchByIDString = "select * from matchup where matchID = :ID";

    private $postTeamString = "insert into team values (:ID, :TeamName)";
    private $postPlayerString = "insert into player values (:ID, :TeamID, :FirstName, :LastName, :Hometown, :ProvinceCode)";
    private $postGameString = "insert into game values (:ID, :MatchID, :GameNumber, :GameStateID, :PlayerID, :Score, :Balls)";
    private $postMatchUpString = "insert into matchup values (:ID, :RoundID, :MatchGroup, :TeamID, :Score, :Ranking)";

    private $putTeamString = "update team set teamID = :ID, teamName = :TeamName where teamID = :ID";
    private $putPlayerString = "update player set playerID = :ID, teamID = :TeamID, firstName = :FirstName, lastName = :LastName, hometown = :HomeTown, provinceCode = :ProvinceCode where PlayerID = :ID";
    private $putGameString = "update game set gameID = :ID, matchID = :MatchID, gameNumber = :GameNumber, gameStateID = :GameStateID, score = :Score, balls = :Balls, playerID = :PlayerID where gameID = :ID";
    private $putMatchString = "update matchup set matchID = :ID, roundID = :RoundID, matchGroup = :MatchGroup, teamID = :TeamID, score = :Score, ranking = :Ranking where matchID = :ID";

    private $deleteTeamString = "delete from team where teamID = :ID";
    private $deletePlayerString = "delete from player where playerID = :ID";
    private $deleteGameString = "delete from game where gameID = :ID";
    private $deleteMatchString = "delete from matchup where matchID = :ID";
    
    private $getAllTeamsStatement = null;
    private $getAllPlayersStatement = null;
    private $getAllGamesStatement = null;
    private $getAllMatchupStatement = null;

    private $getTeamByIDStatement = null;
    private $getPlayerByIDStatement = null;
    private $getGameByIDStatement = null;
    private $getMatchByIDStatement = null;

    private $postTeamStatement = null;
    private $postPlayerStatement = null;
    private $postGameStatement = null;
    private $postMatchUpStatement = null;

    private $putTeamStatement = null;
    private $putPlayerStatement = null;
    private $putGameStatement = null;
    private $putMatchStatement = null;

    private $deleteTeamStatement = null;
    private $deletePlayerStatement = null;
    private $deleteGameStatement = null;
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

        $this->getAllTeamsStatement = $conn->prepare($this->getAllTeamsString);
        if(is_null($this->getAllTeamsStatement)){
            throw new Exception("bad statement: '" . $this->getAllTeamsString . "'");
        }   

        $this->getAllPlayersStatement = $conn->prepare($this->getAllPlayersString);
        if(is_null($this->getAllPlayersStatement)){
            throw new Exception("bad statement: '" . $this->getAllPlayersString . "'");
        }  

        $this->getAllGamesStatement = $conn->prepare($this->getAllGameString);
        if(is_null($this->getAllGamesStatement)){
            throw new Exception("bad statement: '" . $this->getAllGameString . "'");
        }  

        $this->getAllMatchupStatement = $conn->prepare($this->getAllMatchUpString);
        if(is_null($this->getAllMatchupStatement)){
            throw new Exception("bad statement: '" . $this->getAllMatchUpString . "'");
        }  

        $this->getTeamByIDStatement = $conn->prepare($this->getTeamByIDString);
        if(is_null($this->getTeamByIDStatement)){
            throw new Exception("bad statement: '" . $this->getTeamByIDString . "'");
        }   

        $this->getPlayerByIDStatement = $conn->prepare($this->getPlayerByIDString);
        if(is_null($this->getPlayerByIDStatement)){
            throw new Exception("bad statement: '" . $this->getPlayerByIDString . "'");
        }  

        $this->getGameByIDStatement = $conn->prepare($this->getGameByIDString);
        if(is_null($this->getGameByIDStatement)){
            throw new Exception("bad statement: '" . $this->getGameByIDString . "'");
        }  

        $this->getMatchByIDStatement = $conn->prepare($this->getMatchByIDString);
        if(is_null($this->getMatchByIDStatement)){
            throw new Exception("bad statement: '" . $this->getMatchByIDString . "'");
        }  

        $this->postTeamStatement = $conn->prepare($this->postTeamString);
        if(is_null($this->postTeamStatement)){
            throw new Exception("bad statement: '" . $this->postTeamString . "'");
        }   

        $this->postPlayerStatement = $conn->prepare($this->postPlayerString);
        if(is_null($this->postPlayerStatement)){
            throw new Exception("bad statement: '" . $this->postPlayerString . "'");
        }  

        $this->postGameStatement = $conn->prepare($this->postGameString);
        if(is_null($this->postGameStatement)){
            throw new Exception("bad statement: '" . $this->postGameString . "'");
        }  

        $this->postMatchUpStatement = $conn->prepare($this->postMatchUpString);
        if(is_null($this->postMatchUpStatement)){
            throw new Exception("bad statement: '" . $this->postMatchUpString . "'");
        }  

        $this->putTeamStatement = $conn->prepare($this->putTeamString);
        if(is_null($this->putTeamStatement)){
            throw new Exception("bad statement: '" . $this->putTeamString . "'");
        }   

        $this->putPlayerStatement = $conn->prepare($this->putPlayerString);
        if(is_null($this->putPlayerStatement)){
            throw new Exception("bad statement: '" . $this->putPlayerString . "'");
        }  

        $this->putGameStatement = $conn->prepare($this->putGameString);
        if(is_null($this->putGameStatement)){
            throw new Exception("bad statement: '" . $this->putGameString . "'");
        }  

        $this->putMatchStatement = $conn->prepare($this->putMatchString);
        if(is_null($this->putMatchStatement)){
            throw new Exception("bad statement: '" . $this->putMatchString . "'");
        }  

        $this->deleteTeamStatement = $conn->prepare($this->deleteTeamString);
        if(is_null($this->deleteTeamStatement)){
            throw new Exception("bad statement: '" . $this->deleteTeamString . "'");
        }   

        $this->deletePlayerStatement = $conn->prepare($this->deletePlayerString);
        if(is_null($this->deletePlayerStatement)){
            throw new Exception("bad statement: '" . $this->deletePlayerString . "'");
        }  

        $this->deleteGameStatement = $conn->prepare($this->deleteGameString);
        if(is_null($this->deleteGameStatement)){
            throw new Exception("bad statement: '" . $this->deleteGameString . "'");
        }  

        $this->deleteMatchStatement = $conn->prepare($this->deleteMatchString);
        if(is_null($this->deleteMatchStatement)){
            throw new Exception("bad statement: '" . $this->deleteMatchString . "'");
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
    public function teamExists($item){
        return $this->getTeamByID($item->getTeamID()) !== null;
    }

    public function playerExists($item){
        return $this->getPlayerByID($item->getPlayerID()) !== null;
    }

    public function gameExists($item){
        return $this->getGameByID($item->getGameID()) !== null;
    }
    
    public function matchUpExists($item){
        return $this->getMatchByID($item->getMatchID()) !== null;
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