<?php

//ini_set('display_errors', 0);


/* request investigation */

$request_method = empty($_SERVER["REQUEST_METHOD"]) ?
                "GET" : $_SERVER["REQUEST_METHOD"];
$request_id = empty($_GET["id"]) ?
        11 : intval($_GET["id"]);

$request_body = json_decode(file_get_contents('php://input'));
$request_body = validateRequestBody($request_body);

include("classes/DBManager.php");
include('classes/TicketManager.php');

$dbm = new DBManager("./.db");
$conn = $dbm->call();//connexion à la base de données
$table = "postit";
$man   = new TicketManager($conn, $table);

switch($request_method) {

    case 'OPTIONS':
        headerMethod('OPTIONS');
        http_response_code(200);
        echo json_encode(array('status' => 200, 'message' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']));
        break;

    case 'GET':// Retrive Products
        headerMethod('GET');
        $response = getTicket($request_id);
        http_response_code($response['status']);
        echo json_encode($response);
        break;

    case 'POST':// Ajouter un produit
        headerMethod('POST');
        $response = addTicket($request_body);
        http_response_code($response['status']);
        echo json_encode($response);
        break;

    case 'PUT':// Modifier un produit
        headerMethod('PUT');
        $response = updateTicket($request_id, $request_body);
        http_response_code($response['status']);
        echo json_encode($response);
        break;

    case 'DELETE':// Supprimer un produit
        headerMethod('DELETE');
        $response = deleteTicket($request_id);
        http_response_code($response['status']);
        echo json_encode($response);
        break;

    default:// Invalid Request Method
        headerMethod($request_method);
        http_response_code(405);
        echo json_encode(array('status' => 405, 'message' => 'request method ('.$request_method.') not authorized'));
        echo "\n";
}

mysqli_close($conn);


function getTicket($id) {//GET => $TM->select($id);

    global $man, $table;

    if (!$id) return success($man->count().' tickets in '.$table);
    if ($ticket = $man->select($id)) return success('got ticket '.$id, 200, $ticket->getProperties());
    return badId($id);
}

function addTicket($body) {//POST => $TM->insert($ticket);

    if (!$body) return badRequest("payload");
    global $man, $conn;
    $ticket = new Ticket($body);

    if ($man->insert($ticket)) return success('product added', 201);
    return queryError($conn);
}

function updateTicket($id, $body) {//PUT => $TM->update($id, $ticket);

    if (!$id) return badRequest("id");
    if (!$body) return badRequest("payload");
    global $man, $conn;
    $ticket = new Ticket($body);

    if (!$man->select($id)) return badId($id);
    if ($man->update($id, $ticket)) return success('ticket '.$id.' updated', 202);
    return queryError($conn);
}

function deleteTicket($id) {//DELETE => $TM->delete($id):

    if (!$id) return badRequest("id");
    global $man, $conn;

    if (!$man->select($id)) return badId($id);
    if ($man->delete($id)) return success('ticket '.$id.' deleted', 202);
    return queryError($conn);
}

function queryError($connexion) {
    return array('status' => 500,
                 'message' => 'query error',
                 'error' => mysqli_error($connexion));
}

function badId($id) {
    return array('status' => 404,
                 'message' => 'no ticket id '.$id);
}

function badRequest($message) {
    return array('status' => 400,
                 'message' => 'bad request '.$message);
}

function success($message, $status = 200, $body = false) {
    $arr = array('status' => $status,
                 'message' => 'success '.$message);
    if ($body) $arr["body"] = $body;
    return $arr;
}

function validateRequestBody($body) {
    if (!isset($body->title)) return false;
    elseif (!isset($body->body)) return false;
    elseif (!isset($body->actualPosition)) return false;
    elseif (!isset($body->status)) return false;
    elseif (!isset($body->color)) return false;
    return $body;
}


function headerMethod($method, $origin = "*", $contentype = "application/json; charset=UTF-8") {
    header('Access-Contol-Allow-Origin: '.$origin);
    header('Content-Type: '.$contentype);
    header('Access-Contol-Allow-Methods: '.$method);
    header('Access-Contol-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, x-Requestet-With');
}

?>
