<?php
require_once dirname(__DIR__, 1) . '/api.bulleye.party/connectionManager.php';
require_once dirname(__DIR__, 1) . '/api.bulleye.party/constants.php';
require_once dirname(__DIR__, 1) . '/api.bulleye.party/DataAccessor.php';


$method = $_SERVER['REQUEST_METHOD'];


/*
End points defined
/team -  bulk GET
/team/id - GET
/team/id - POST, {teamID, teamName}
/team - POST, bulk post not supported
/team/id - PUT, {teamID, teamName}
/team - PUT, bulk put not supported
/team/id - DELETE-+
/team - DELETE, bulk delete not supported

/player - bulk GET
/player/id - GET
/player/id - POST, {playerID, teamID, firstName, lastName, hometown, province}
/player - POST, bulk post not supported
/player/id - PUT, {playerID, teamID, firstName, lastName, hometown, province}
/player - PUT, bulk put not supported
/player/id - DELETE
/player - DELETE, bulk delete not supported

/game - bulk GET
/game/id - GET
/game/id - POST, {gameID, matchID, gameNumber, gameStateID, score, balls, playerID}
/game - POST, bulk post not supported
/game/id - PUT, {gameID, matchID, gameNumber, gameStateID, score, balls, playerID}
/game - PUT, bulk put not supported
/game/id - DELETE
/game - DELETE, bulk delete not supported

/match - bulk GET
/match/id - GET
/match/id - POST, {matchID, roundID, matchGroup, teamID, score, ranking}
/match - POST, bulk post not supported
/match/id - PUT, {matchID, roundID, matchGroup, teamID, score, ranking}
/match - PUT, bulk put not supported
/match/id - DELETE
/match - DELETE, bulk delete not supported

*/

try {
    $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, Constants::$MYSQL_PASSWORD);
    $dAccessor = new DataAccessor($cm->getConnection());
    $action = $_GET["action"];
    if ($method === "GET") {
        doGet($dAccessor,$action);
    } else if ($method === "POST") {
        doPost($dAccessor,$action);
    } else if ($method === "DELETE") {
        doDelete($dAccessor,$action);
    } else if ($method === "PUT") {
        doPut($dAccessor,$action);
    } else {
        sendResponse(405, null, "method not allowed");
    }
} catch (Exception $e) {
    sendResponse(500, null, "ERROR " . $e->getMessage());
} finally {
    if (!is_null($cm)) {
        $cm->closeConnection();
    }
}

function doGet($dAccessor,$action)//Workie Derkie
{
    if (isset($_GET["id"])) {
        $itemID = $_GET['id'];
        if($action == "team"){
            $res = $dAccessor->getTeamByID($itemID);
            sendResponse(200, $res, null);
        } else if ($action == "player"){
            $dAccessor->getPlayerByID($itemID);
            sendResponse(200, $res, null);
        } else if($action == "game"){
            $res = $dAccessor->getGameByID($itemID);
            sendResponse(200, $res, null);
        } else if($action == "match"){
            $res = $dAccessor->getMatchByID($itemID);
            sendResponse(200, $res, null);
        }else{
            sendResponse(405, null, "Something went horribily wrong"); 
        }
    }
    else {
        if($action == "team"){
            $res = $dAccessor->getAllTeams();
            sendResponse(200, $res, null);
        } else if ($action == "player"){
            $res = $dAccessor->getAllPlayers();
            sendResponse(200, $res, null);
        } else if($action == "game"){
            $res = $dAccessor->getAllGames();
            sendResponse(200, $res, null);
        } else if($action == "match"){
            $res = $dAccessor->getAllMatches();
            sendResponse(200, $res, null);
        } else{
            sendResponse(405, null, "Something went horribily wrong"); 
        }
    }
}

function doDelete($dAccessor,$action)//Broken af
{
    if (isset($_GET['id'])) {
        $itemID = $_GET['id'];
        if($action == "team"){
            $menuItemObj = new Team($itemID, "exampleTeamName");
            $success = $dAccessor->deleteTeam($menuItemObj);
            if ($success) {
                sendResponse(200, $success, null);
            } else {
                sendResponse(404, null, "could not delete item - it does not exist");
            }
        } else if ($action == "player"){
            $menuItemObj = new Player($itemID, "dummyCat", "dummyDesc", 8, 0,6);
            $success = $dAccessor->deletePlayer($menuItemObj);
            if ($success) {
                sendResponse(200, $success, null);
            } else {
                sendResponse(404, null, "could not delete item - it does not exist");
            }
        } else if($action == "game"){
            $menuItemObj = new Game($itemID, "dummyCat", "dummyDesc", 8, 0, 0, 0);
            $success = $dAccessor->deleteGame($menuItemObj);
            if ($success) {
                sendResponse(200, $success, null);
            } else {
                sendResponse(404, null, "could not delete item - it does not exist");
            }
        } else if($action == "match"){
            $menuItemObj = new Match($itemID, "dummyCat", "dummyDesc", 8, 0,6);
            $success = $dAccessor->deleteMatch($menuItemObj);
            if ($success) {
                sendResponse(200, $success, null);
            } else {
                sendResponse(404, null, "could not delete item - it does not exist");
            }
        }else{
            sendResponse(405, null, "Something went horribily wrong"); 
        }
    } else {
        sendResponse(405, null, "bulk DELETEs not allowed");
    }
}

function doPost($dAccessor,$action)//Abolutely fucked
{
    if (isset($_GET['id'])) {
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);
        
        if($action == "team"){
            try {
                $obj = new Team($contents['teamID'], $contents['teamName']);
                $success = $dAccessor->postTeam($obj);
                if ($success) {
                    sendResponse(201, $success, null);
                } else {
                    sendResponse(409, null, "could not insert item - it already exists");
                }
            } catch (Exception $e) {
                sendResponse(400, null, $e->getMessage());
            }
        } else if ($action == "player"){
            try {
                $obj = new Player($contents['playerID'], $contents['teamID'], $contents['firstName'], $contents['lastName'], $contents['hometown'], $contents['provinceCode']);
                $success = $dAccessor->postPlayer($obj);
                if ($success) {
                    sendResponse(201, $success, null);
                } else {
                    sendResponse(409, null, "could not insert item - it already exists");
                }
            } catch (Exception $e) {
                sendResponse(400, null, $e->getMessage());
            }
        } else if($action == "game"){
            try {
                $obj = new Game($contents['gameID'], $contents['matchID'], $contents['gameNumber'], $contents['gameStateID'], $contents['score'], $contents['balls'], $contents['playerID']);
                $success = $dAccessor->postGame($obj);
                if ($success) {
                    sendResponse(201, $success, null);
                } else {
                    sendResponse(409, null, "could not insert item - it already exists");
                }
            } catch (Exception $e) {
                sendResponse(400, null, $e->getMessage());
            }
        } else if($action == "match"){
            try {
                $obj = new Match($contents['matchID'], $contents['roundID'], $contents['matchGroup'], $contents['teamID'], $contents['score'], $contents['ranking']);
                $success = $dAccessor->postMatch($obj);
                if ($success) {
                    sendResponse(201, $success, null);
                } else {
                    sendResponse(409, null, "could not insert item - it already exists");
                }
            } catch (Exception $e) {
                sendResponse(400, null, $e->getMessage());
            }
        }else{
            sendResponse(405, null, "Something went horribily wrong"); 
        }
    } else {
        sendResponse(405, null, "bulk INSERTs not allowed");
    }
}
function doPut($dAccessor, $action)//Super duper fucked
{
    if (isset($_GET['id'])) {
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);


        if($action == "team"){
            try {
                $obj = new Team($contents['teamID'], $contents['teamName']);
                $success = $dAccessor->putTeam($obj);
                if ($success) {
                    sendResponse(201, $success, null);
                } else {
                    sendResponse(404, null, "could not update item - it does not exist");
                }
            } catch (Exception $e) {
                sendResponse(400, null, $e->getMessage());
            }
        } else if ($action == "player"){
            try {
                $obj = new Player($contents['playerID'], $contents['teamID'], $contents['firstName'], $contents['lastName'], $contents['hometown'], $contents['provinceCode']);
                $success = $dAccessor->putPlayer($obj);
                if ($success) {
                    sendResponse(201, $success, null);
                } else {
                    sendResponse(404, null, "could not update item - it does not exist");
                }
            } catch (Exception $e) {
                sendResponse(400, null, $e->getMessage());
            }
        } else if($action == "game"){
            try {
                $obj = new Game($contents['gameID'], $contents['matchID'], $contents['gameNumber'], $contents['gameStateID'], $contents['score'], $contents['balls'], $contents['playerID']);
                $success = $dAccessor->putGame($obj);
                if ($success) {
                    sendResponse(201, $success, null);
                } else {
                    sendResponse(404, null, "could not update item - it does not exist");
                }
            } catch (Exception $e) {
                sendResponse(400, null, $e->getMessage());
            }
        } else if($action == "match"){
            try {
                $obj = new Match($contents['matchID'], $contents['roundID'], $contents['matchGroup'], $contents['teamID'], $contents['score'], $contents['ranking']);
                $success = $dAccessor->putMatch($obj);
                if ($success) {
                    sendResponse(201, $success, null);
                } else {
                    sendResponse(404, null, "could not update item - it does not exist");
                }
            } catch (Exception $e) {
                sendResponse(400, null, $e->getMessage());
            }
        }else{
            sendResponse(405, null, "Something went horribily wrong"); 
        }
    } else {
        sendResponse(405, null, "bulk UPDATEs not allowed");
    }
}

function sendResponse($statusCode, $data, $error)
{
    header("Content-Type: application/json");
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    echo json_encode($resp, JSON_NUMERIC_CHECK);
}