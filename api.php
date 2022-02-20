<?php

//ini_set('display_errors', 0);

include_once("classes/ClientRequest.php");
include_once('classes/TicketManager.php');

$man = new TicketManager("./.db");
$man->setTable("postit");
$conn = $man->call();

$cr = new ClientRequest();
$cr->setManager($man);
$cr->headerMethod();

switch($cr->getMethod()) {

    case 'OPTIONS':

        http_response_code(200);
        echo json_encode(array('status' => 200, 'message' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']));
        break;

    case 'GET':// Retrive Products

        Client::send($cr->getTicket());
        break;

    case 'POST':// Ajouter un produit

        Client::send($cr->addTicket());
        break;

    case 'PUT':// Modifier un produit

        Client::send($cr->updateTicket());
        break;

    case 'DELETE':// Supprimer un produit

        Client::send($cr->deleteTicket());
        break;

    default:// Invalid Request Method

        http_response_code(405);
        echo json_encode(array(
            'status' => 405,
            'message' => 'request method ('.$cr->getMethod().') not authorized')
        );

}

echo "\n";
mysqli_close($conn);


?>
