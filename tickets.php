<?php

header("HTTP/1.1 200 OK");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');

// Connect to database
include("db_connect.php");
$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {

    case 'OPTIONS':
        http_response_code(200);
        break;


    case 'GET':// Retrive Products
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            getTicket($id);
        } else {
            getTicket();
        }
        break;

    case 'POST':// Ajouter un produit
        addTicket();
        break;

    case 'PUT':// Modifier un produit
        $id = intval($_GET["id"]);
        updateTicket($id);
        break;

    case 'DELETE':// Supprimer un produit
        $id = intval($_GET["id"]);
        deleteTicket($id);
        break;

    default:// Invalid Request Method
        //header("HTTP/1.0 405 Method Not Allowed");
        header("HTTP/1.1 200 OK");
        break;
}


//status_header(200);



function getTicket($id = null) {
    global $conn;
    $query = "SELECT * FROM postit";
    $selected = 0;
    if ($id != null) {
        $query = $query." WHERE id=".$id;
        $selected = 1;
    }

    //$response = array();
    $result = mysqli_query($conn, $query);
    if ($result) {
        $response = array('ok' => 200, 'status' => 200, 'status_message' => 'postit got successfully' );
        while ($row = mysqli_fetch_array($result)) $response[] = ['message_body' => $row];
    } else {
        $response = array('ok' => 500, 'status' => 500, 'status_message' => 'error: '.mysqli_error($conn));
    }

    echo json_encode($response);
}


function para($quoi = null) {
    echo "<p>".$quoi."</p>";
    return null;

}


function addTicket() {
    global $conn;
    $title = $_POST["title"];
    $body = $_POST["body"];
    $actualPosition = $_POST["actualPosition"];
    $status = $_POST["status"];
    $color = $_POST["color"];

    echo $query = "INSERT INTO postit(title, body, ActualPosition, status, color) VALUES('".$title."', '".$body."', '".$actualPosition."', '".$status."', '".$color."')";

    if (mysqli_query($conn, $query)) $response = array('status' => 201, 'status_message' =>'product added');
    else $response = array('status' => 500, 'status_message' =>'error: '. mysqli_error($conn));

    //header('Content-Type: application/json');
    echo json_encode($response);
}

function updateTicket($id) {
    global $conn;
    $_PUT = array();
    parse_str(file_get_contents('php://input'), $_PUT);
    $title = $_PUT["title"];
    $body = $_PUT["body"];
    $actualPosition = $_PUT["actualPosition"];
    $status = $_PUT["status"];
    $color = $_POST["color"];
    $query = "UPDATE postit SET title='".$title."', body='".$body."', actualPosition='".$actualPosition."', status='".$status."', color='".$color."' WHERE id=".$color;

    if (mysqli_query($conn, $query)) $response = array('status' => 200, 'status_message' => 'update done');
    else $response = array('status' => 500, 'status_message' =>'update canceled: '.mysqli_error($conn));

    header('Content-Type: application/json');
    echo json_encode($response);
}

function deleteTicket($id) {
    global $conn;
    $query = "DELETE FROM postit WHERE id=".$id;

    if (mysqli_query($conn, $query)) $response = array('status' => 200, 'status_message' =>'product deleted');
    else $response = array('status' => 500, 'status_message' =>'delete canceled: '.mysqli_error($conn));

    header('Content-Type: application/json');
    echo json_encode($response);
}

