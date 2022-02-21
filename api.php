<?php

include_once("classes/ClientRequest.php");
include_once('classes/TicketManager.php');

$man = new \classes\TicketManager("./.db");
$man->setTable("postit");
$man->open();//connexion à la base de données

$cr = new \classes\ClientRequest($man);
$cr->headerMethod();
switch($cr->getMethod()) {

    case 'OPTIONS': \classes\Client::send($cr->getOptions()); break;
    case 'DELETE': \classes\Client::send($cr->deleteTicket()); break;
    case 'POST': \classes\Client::send($cr->addTicket()); break;
    case 'PUT': \classes\Client::send($cr->updateTicket()); break;
    case 'GET': \classes\Client::send($cr->getTicket()); break;
    default: \classes\Client::send($cr->methodInvalid());
}

$man->close();//déconnexion de la base de données
?>
