# application programming interface representational state transfer 100% php
- ## ticket: <ins>id</ins>, title, body, position, status, color
- ## classes: 
- ## Ticket - data container,
- ## DBManager - database manager,
- ## Client - client response catalogue,
- ## TicketManager extends DBManager - database interface,
- ## ClientRequest extends Client - client request analyse


<!-- MARKDOWN-AUTO-DOCS:START (CODE:src=./api.php) -->
<!-- The below code snippet is automatically added from ./api.php -->
```php
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
    case 'DELETE': $request::send($request->deleteTicket()); break;
    case 'OPTIONS': $request::send($request->getOptions()); break;
    case 'PUT': $request::send($request->updateTicket()); break;
    case 'POST': $request::send($request->addTicket()); break;
    case 'GET': $request::send($request->getTicket()); break;
    default: $request::send($request->methodInvalid());
}

$manager->close();/* database deconnexion
*/
?>
```
<!-- MARKDOWN-AUTO-DOCS:END -->

### &#9993; [Kaloyan KRASTEV](mailto:kaloyansen@gmail.com)
### &#128241; +33 6 812 44 812
### &#9793; 32 quai Xavier JOUVIN, 38000 Grenoble, FRANCE

#### [&#128281;&#127383;&#9940;&#8678;](../.)


