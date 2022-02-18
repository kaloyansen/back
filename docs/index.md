# application programming interface representational state transfer backend 

#### envirenmet => object oriented php 
### subject => ticket => properties:
- ticket.id
- ticket.body.title
- ticket.body.body
- ticket.body.position
- ticket.body.status
- ticket.body.color

### request method => action => operation(argument)
### get => download ticket => select($id)
### post => upload ticket => insert($body)
### put => update ticket => update($id, $body)
### delete => delete ticket => delete($id)

### class list:
## &#9743;️ DBManager(database secure connexion)
## &#10050; TicketManager(query abstraction)
## &#9856; Ticket (data container)

[back](../.)
