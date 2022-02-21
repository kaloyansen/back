# application programming interface representational state transfer 
- ## classes: 
- ## Ticket - data container,
- ## DBManager - database manager,
- ## Client - client response catalogue,
- ## TicketManager extends DBManager - database interface,
- ## ClientRequest extends Client - client request analyse

### data
- <ins>id</ins>,
- title,
- body,
- position,
- status,
- color


<!-- MARKDOWN-AUTO-DOCS:START (CODE:src=./api.php) -->
<!-- The below code snippet is automatically added from ./api.php -->
```php
<?php

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
```
<!-- MARKDOWN-AUTO-DOCS:END -->

### &#9787; [Kaloyan KRASTEV](mailto:kaloyansen@gmail.com)
### &#128241; +33 6 812 44 812
### &#8982; 32 quai Xavier JOUVIN, 38000 Grenoble, FRANCE

#### [&#128281;](../.)


