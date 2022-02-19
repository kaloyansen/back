<?php

ini_set('display_errors', 1);
/*header("HTTP/1.1 200 OK");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Methods, Access-Control-Request-Headers, Authorization");
*/

// connect to database
include("db_connect.php");

// invastigate request
$request_method = $_SERVER["REQUEST_METHOD"];
$request_id = false;
$request_body = json_decode(file_get_contents('php://input'));

if (!empty($_GET["id"])) $request_id = intval($_GET["id"]);
$request_body = validateRequestBody($request_body);


switch($request_method) {

    case 'OPTIONS':
        http_response_code(200);
        break;

    case 'GET':// Retrive Products
        header('Access-Contol-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Contol-Allow-Methods: GET');
        header('Access-Contol-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, x-Requestet-With');
        $response = getTicket($request_id);
        http_response_code($response['status']);
        echo json_encode($response);
        break;

    case 'POST':// Ajouter un produit
        header('Access-Contol-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Contol-Allow-Methods: POST');
        header('Access-Contol-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, x-Requestet-With');
        $response = addTicket($request_body);
        http_response_code($response['status']);
        echo json_encode($response);
        break;

    case 'PUT':// Modifier un produit
        header('Access-Contol-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Contol-Allow-Methods: PUT');
        header('Access-Contol-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, x-Requestet-With');
        $response = updateTicket($request_id, $request_body);
        http_response_code($response['status']);
        echo json_encode($response);
        break;

    case 'DELETE':// Supprimer un produit
        header('Access-Contol-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Contol-Allow-Methods: DELETE');
        header('Access-Contol-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, x-Requestet-With');
        $response = deleteTicket($request_id);
        http_response_code($response['status']);
        echo json_encode($response);
        break;

    default:// Invalid Request Method
        //header("HTTP/1.0 405 Method Not Allowed");
        //header("HTTP/1.1 200 OK");
        http_response_code(405);
        echo json_encode(array(
            'status' => 405,
            'message' => 'méthode non autorisée'
        ));
        break;
}

//status_header(200);
global $conn;
mysqli_close($conn);


function getTicket($id) {//GET

    global $conn;
    $query = "SELECT * FROM postit";
    if ($id) $query = $query." WHERE id=".$id;
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (!$id) return array(
            'status' => 200,
            'message' => mysqli_num_rows($result).' tickets found'
        );
        $response = array(
            'status' => 200,
            'message' => 'postit got success',
            'body' => mysqli_fetch_array($result)
        );
        //while ($row = mysqli_fetch_array($result)) $response[] = ['body' => $row];

        if (!isset($response['body'])) {
            $response['status'] = 404;
            $response['message'] = 'no ticket id: '.$id;
        }
    } else {
        $response = array(
            'status' => 500,
            'message' => 'connexion à la base refusée',
            'error' => mysqli_error($conn)
        );
    }
    return $response;
}

function addTicket($body) {//POST
    if (!$body) return array(
        'status' => 400,
        'message' => 'bad add request body'
    );
    global $conn;
    $query = "INSERT INTO postit(title, body, position, status, color) VALUES('".$body->title."', '".$body->body."', '".$body->position."', '".$body->status."', '".$body->color."')";
    if (mysqli_query($conn, $query)) $response = array(
        'status' => 201,
        'message' =>'product added'
    );
    else $response = array(
        'status' => 500,
        'message' => 'connexion à la base refusée',
        'error' => mysqli_error($conn)
    );
    return $response;
}

function updateTicket($id, $body) {//PUT
    if (!$body || !$id) return array(
        'status' => 400,
        'message' => 'bad update request'
    );
    $repget = getTicket($id);
    if ($repget['status'] > 399) return array(
        'status' => 400,
        'message' => 'bad update id: '.$id.' not found'
    );

    global $conn;
    $query = "UPDATE postit SET title='".$body->title."', body='".$body->body."', position='".$body->position."', status='".$body->status."', color='".$body->color."' WHERE id=".$id;
    if (mysqli_query($conn, $query)) $response = array(
        'status' => 202,
        'message' => 'update done id = '.$id
    );
    else $response = array(
        'status' => 500,
        'message' => 'update canceled',
        'error' => mysqli_error($conn)
    );
    return $response;
}

function deleteTicket($id) {//DELETE
    if (!$id) return array(
        'status' => 400,
        'message' => 'bad delete no id in request');
    $repget = getTicket($id);
    if ($repget['status'] > 399) return array(
        'status' => 400,
        'message' => 'bad delete id: '.$id.' not found'
    );
    global $conn;
    $query = "DELETE FROM postit WHERE id=".$id;
    if (mysqli_query($conn, $query)) $response = array(
        'status' => 202,
        'message' =>'product deleted id = '.$id
    );
    else $response = array(
        'status' => 500,
        'message' =>'delete canceled',
        'error' => mysqli_error($conn)
    );
    return $response;
}

function validateRequestBody($body) {
    if (!isset($body->title) || !isset($body->body) || !isset($body->position) || !isset($body->status) || !isset($body->color)) return false;
    return $body;
}

