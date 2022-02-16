<?php

ini_set('display_errors', 1);
// invastigate request
$request_method = $_SERVER["REQUEST_METHOD"];
$request_id = false;
$request_body = json_decode(file_get_contents('php://input'));

if (!empty($_GET["id"])) $request_id = intval($_GET["id"]);
$request_body = validateRequestBody($request_body);

include("db_connect.php");
include('classes/TicketManager.php');

switch($request_method) {

    case 'OPTIONS':
        headerMethod('OPTIONS');
        http_response_code(200);
        echo json_encode(array('status' => 200, 'message' => ['GET', 'POST', 'PUT', 'DELETE']));
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
        headerMethod('GET, POST, PUT, DELETE, OPTIONS');
        http_response_code(405);
        echo json_encode(array('status' => 405, 'message' => 'méthode non autorisée'));
}

//status_header(200);
global $conn;
mysqli_close($conn);


function getTicket($id) {//GET

    global $conn;
    $man = new TicketManager($conn);

    if (!$id) return array('status' => 200, 'message' => $man->count().' tickets in database');
    $ticket = $man->select($id);
    if (!$ticket) return array('status' => 404, 'message' => 'no ticket id '.$id);
    return array('status' => 200, 'message' => 'success', 'body' => array(
            'id' => $id,
            'title' => $ticket->getTitle(),
            'body' => $ticket->getBody(),
            'ActualPosition' => $ticket->getAP(),
            'status' => $ticket->getStatus(),
            'color' => $ticket->getColor()
    ));
}

function addTicket($body) {//POST

    if (!$body) return array('status' => 400, 'message' => 'bad request body');

    global $conn;
    $man = new TicketManager($conn);
    $ticket = new Ticket($body);

    if ($man->insert($ticket)) return array('status' => 201, 'message' =>'product added');
    else return array('status' => 500, 'message' => 'dberr', 'error' => mysqli_error($conn));
}

function updateTicket($id, $body) {//PUT

    if (!$body || !$id) return array('status' => 400, 'message' => 'bad update request');

    global $conn;
    $man = new TicketManager($conn);
    $ticket = new Ticket($body);

    if (!$man->select($id)) return array('status' => 400, 'message' => 'no ticket id '.$id);
    if ($man->update($id, $ticket)) return array('status' => 202, 'message' => 'okup id '.$id);
    else return array('status' => 500, 'message' => 'dberr', 'error' => mysqli_error($conn));
}

function deleteTicket($id) {//DELETE

    if (!$id) return array('status' => 400, 'message' => 'bad request no id found');

    global $conn;
    $man = new TicketManager($conn);

    if (!$man->select($id)) return array('status' => 400, 'message' => 'id '.$id.' not found');
    if ($man->delete($id)) return array('status' => 202, 'message' => $id.' deleted');
    else return array('status' => 500, 'message' => 'dberr', 'error' => mysqli_error($conn));
}

function validateRequestBody($body) {
    if (!isset($body->title) || !isset($body->body) || !isset($body->actualPosition) || !isset($body->status) || !isset($body->color)) return false;
    return $body;
}


function headerMethod($method) {
    header('Access-Contol-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Contol-Allow-Methods: '.$method);
    header('Access-Contol-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, x-Requestet-With');
}

?>