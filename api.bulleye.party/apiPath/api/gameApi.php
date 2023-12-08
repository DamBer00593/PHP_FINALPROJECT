<?php
require_once dirname(__DIR__, 1) . '/connectionManager.php';
require_once dirname(__DIR__, 1) . '/constants.php';
require_once dirname(__DIR__, 1) . '/accessor/gameDataAccessor.php';
$setAuthorized = true;

$method = $_SERVER['REQUEST_METHOD'];
if(isset($_SERVER["HTTP_AUTHORIZATION"])){
    $auth  = $_SERVER["HTTP_AUTHORIZATION"];
    if($auth !== "Bearer 8RbYFydpaHA3G*sja4AL7Hkum!5f4tp5"){
        $setAuthorized = false;
    }
}
else{
    $setAuthorized = false;
}
/*
End points defined
/game - bulk GET
/game/id - GET
/game/id - POST, {gameID, matchID, gameNumber, gameStateID, playerID, score, balls}
/game - POST, bulk post not supported
/game/id - PUT, {gameID, matchID, gameNumber, gameStateID, playerID, score, balls}
/game - PUT, bulk put not supported
/game/id - DELETE
/game - DELETE, bulk delete not supported
*/

try {
    if($setAuthorized){
        $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, Constants::$MYSQL_PASSWORD);
        $dAccessor = new GameDataAccessor($cm->getConnection());
        if ($method === "GET") {
            doGet($dAccessor);
        } else if ($method === "POST") {
            doPost($dAccessor);
        } else if ($method === "DELETE") {
            doDelete($dAccessor);
        } else if ($method === "PUT") {
            doPut($dAccessor);
        } else {
            sendResponse(405, null, "method not allowed");
        }
    } else{
        echo '<div style="width:100%;height:0;padding-bottom:89%;position:relative;"><iframe src="https://giphy.com/embed/IcifS1qG3YFlS" width="50%" height="50%" style="position:absolute" frameBorder="0" class="giphy-embed" allowFullScreen></iframe></div>';
        sendResponse(403,null,"ACCESS IS DENIED (teehee ;p)");
    }
} catch (Exception $e) {
    sendResponse(500, null, "ERROR " . $e->getMessage());
} finally {
    if (!is_null($cm)) {
        $cm->closeConnection();
    }
}

function doGet($dAccessor)//Workie Derkie
{
    if (isset($_GET["id"])) {
        $itemID = $_GET['id'];
        $res = $dAccessor->getGameByID($itemID);
        sendResponse(200, $res, null);
    }
    else {
        $res = $dAccessor->getAllGames();
        sendResponse(200, $res, null);
    }
}

function doDelete($dAccessor)///Workie derkie
{
    if (isset($_GET['id'])) {
        $itemID = $_GET['id'];
        
            $menuItemObj = new Game($itemID, 1, 1, "AVAILABLE", 0, 0, 0);
            $success = $dAccessor->deleteGame($menuItemObj);
            if ($success) {
                sendResponse(200, $success, null);
            } else {
                sendResponse(404, null, "could not delete item - it does not exist");
            }
        
    } else {
        sendResponse(405, null, "bulk DELETEs not allowed");
    }
}

function doPost($dAccessor)//workie derkie
{
    if (isset($_GET['id'])) {
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);
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
    } else {
        sendResponse(405, null, "bulk INSERTs not allowed");
    }
}
function doPut($dAccessor)//Workie derkie
{
    if (isset($_GET['id'])) {
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

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
        
    } else {
        sendResponse(405, null, "bulk UPDATEs not allowed");
    }
}

function sendResponse($statusCode, $data, $error)
{
    header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, X-PINGOTHER, Authorization");
    header('Access-Control-Allow-Credentials: true');
    header("Access-Control-Max-Age: 86400");
    header("Content-Type: application/json");

    
    http_response_code($statusCode);
    $resp = ['data' => $data, 'error' => $error];
    echo json_encode($resp, JSON_NUMERIC_CHECK);
}