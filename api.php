<?php
/***************************************************************************/
/*   php code by Kaloyan KRASTEV, kaloyansen@gmail.com                    */
/*  published at https://kaloyansen.github.io/back by MARKDOWN-AUTO-DOCS */
/************************************************************************/
include_once("classes/ClientRequest.php");
include_once('classes/TicketManager.php');

$manager = new \classes\TicketManager("./.db");
/* secrets are loaded from a local dot file
*/
$manager->setTable("postit");
$manager->open();/* database connexion
*/
$request = new \classes\ClientRequest($manager);
/* client request to be managed by a TicketManager instance
*/
$request->headerMethod();
switch($request->getMethod()) {/* request method dependant response
*/
    case 'OPTIONS': $request::send($request->getOptions()); break;
    case 'DELETE': $request::send($request->deleteTicket()); break;
    case 'POST': $request::send($request->addTicket()); break;
    case 'PUT': $request::send($request->updateTicket()); break;
    case 'GET': $request::send($request->getTicket()); break;
    default: $request::send($request->methodInvalid());
}

$manager->close();/* database deconnexion
*/
?>
