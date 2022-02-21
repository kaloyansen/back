<?php

/* published at https://kaloyansen.github.io/back by MARKDOWN-AUTO-DOCS
php code by Kaloyan KRASTEV */

include_once("classes/ClientRequest.php");
include_once('classes/TicketManager.php');

$manager = new \classes\TicketManager("./.db");
/* secrets are loaded from a local file */

$manager->setTable("postit");
$manager->open();/* database connexion */

$request = new \classes\ClientRequest($manager);
/* client request to be managed by a TicketManager instance */

$request->headerMethod();
switch($request->getMethod()) {
    /* request method dependant response */
    case 'OPTIONS': \classes\Client::send($request->getOptions()); break;
    case 'DELETE': \classes\Client::send($request->deleteTicket()); break;
    case 'POST': \classes\Client::send($request->addTicket()); break;
    case 'PUT': \classes\Client::send($request->updateTicket()); break;
    case 'GET': \classes\Client::send($request->getTicket()); break;
    default: \classes\Client::send($request->methodInvalid());
}

$manager->close();/* database deconnexion */
?>
