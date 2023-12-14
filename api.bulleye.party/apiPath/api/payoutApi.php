<?php
require_once dirname(__DIR__, 1) . '/connectionManager.php';
require_once dirname(__DIR__, 1) . '/constants.php';
require_once dirname(__DIR__, 1) . '/accessor/payoutDataAccessor.php';
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
/payout -  bulk GET
/payout/id - GET
/payout/id - POST, not supported
/payout - POST, bulk post not supported
/payout/id - PUT, {payoutID, roundID, teamID, amount}
/payout - PUT, bulk put not supported
/payout/id - DELETE, not supported
/payout - DELETE, bulk delete not supported
*/

try {
    if($setAuthorized){
        $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, Constants::$MYSQL_PASSWORD);
        $dAccessor = new PayoutDataAccessor($cm->getConnection());
        if ($method === "GET") {
            doGet($dAccessor);
        } else if ($method === "POST") {
            doPost($dAccessor);
        } else if ($method === "DELETE") {
            doDelete($dAccessor);
        } else if ($method === "PUT") {
            doPut($dAccessor);
        } else if ($method === "OPTIONS") {
            sendResponse(200,null,null);
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

        $res = $dAccessor->getPayoutByID($itemID);
        sendResponse(200, $res, null);
    }
    else {
        $res = $dAccessor->getAllPayouts();
        sendResponse(200, $res, null);
    }
}

function doDelete($dAccessor)///Workie derkie
{
    sendResponse(403, null, "DELETE not supported")
}

function doPost($dAccessor)//workie derkie
{
    sendResponse(403, null, "POST not supported")
}
function doPut($dAccessor)//Workie derkie
{
    if (isset($_GET['id'])) {
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        try {
            $obj = new Payout($contents['payoutID'],$contents["roundID"], $contents['teamName'], $contents["amount"]);
            $success = $dAccessor->putPayout($obj);
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