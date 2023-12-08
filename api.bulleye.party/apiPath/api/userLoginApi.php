<?php
require_once dirname(__DIR__, 1) . '/connectionManager.php';
require_once dirname(__DIR__, 1) . '/constants.php';
require_once dirname(__DIR__, 1) . '/accessor/userDataAccessor.php';
require_once dirname(__DIR__, 1) . '/entities/user.php';
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
/auth/user/login/email/ - POST {username: "username", password: "password"} 
/auth/user/login - BULK POST NOT SUPPORTED
*/

try {
    if($setAuthorized){
        $cm = new ConnectionManager(Constants::$MYSQL_CONNECTION_STRING, Constants::$MYSQL_USERNAME, Constants::$MYSQL_PASSWORD);
        $dAccessor = new UserDataAccessor($cm->getConnection());
        if ($method === "GET") {
            sendResponse(405, null, "method GET is not allowed");
        } else if ($method === "POST") {
            doPost($dAccessor);
        } else if ($method === "DELETE") {
            sendResponse(405, null, "Method Delete is not allowed");
        } else if ($method === "PUT") {
            sendResponse(405, null, "Method Delete is not allowed");
        } else if ($method === "OPTION"){
            sendResponse(200,null, null);
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

function doPost($dAccessor)//workie derkie
{
    if (isset($_GET['id'])) {
        $body = file_get_contents('php://input');
        $contents = json_decode($body, true);

        try {
            $email = $contents["userEmail"];
            $unencryptedPass = $contents["userPassword"];
            $obj = $dAccessor->getUserByID($email);
            $resp = "";
            if($obj->comparePassToHash($unencryptedPass)){
                $resp = $obj->getUserPermission();
            } else {
                $resp = false;
            }
            sendResponse(200,$resp,null);
            //$obj->comparePassToHash($unencryptedPass)
        } catch (Exception $e) {
            sendResponse(400, null, $e->getMessage());
        }
    } else {
        sendResponse(405, null, "bulk post is not allowed");
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