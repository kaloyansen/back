<?php
//ini_set('display_errors', 0);
include_once("classes/ClientRequest.php");
include_once('classes/TicketManager.php');

$man = new TicketManager("./.db");
$man->setTable("postit");
$man->open();//connexion à la base de données

$cr = new ClientRequest($man);
$cr->headerMethod();
switch($cr->getMethod()) {

    case 'OPTIONS': Client::send($cr->getOptions()); break;
    case 'GET':     Client::send($cr->getTicket()); break;
    case 'POST':    Client::send($cr->addTicket()); break;
    case 'PUT':     Client::send($cr->updateTicket()); break;
    case 'DELETE':  Client::send($cr->deleteTicket()); break;
    default:        Client::send($cr->methodInvalid());
}

$man->close();//déconnexion de la base de données
?>
