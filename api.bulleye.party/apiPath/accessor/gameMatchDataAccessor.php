<?php
/*
PHP SQL Median, all the queries for:
    Teams 
    Players
    Games
    and Matchups are stored in here for reference in API.php
*/
require_once dirname(__DIR__, 1) . '/entities/gameMatch.php';

class  GameMatchDataAccessor
{

    private $getAllGameMatchesString = "select g.gameid, g.matchid, g.gamestateid, g.playerid, g.score, g.balls, m.roundid, m.matchgroup, m.teamid, m.ranking from game g inner join matchup m on m.matchid = g.matchid";
    private $getAllGameMatchesStatement = null;
   



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
        if(is_null($this->getAllGameMatchesStatement)){
            throw new Exception("bad statement: '" . $this->getAllGameMatchString . "'");
        }  
         
    }
    /*
        GET SERIES
    */
    public function getAllGameMatches(){
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
                $roundID = $r["roundID"];
                $matchGroup = $r["matchGroup"];
                $teamID = $r["teamID"];
                $ranking = $r["ranking"];
                $object = new GameMatch($gameID, $matchID, $gameNumber, $gameStateID, $score, $balls, $playerID, $roundID, $matchGroup, $teamID, $ranking);
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
   
}
?>