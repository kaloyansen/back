<?php


include_once 'classes/Client.php';
class ClientRequest extends Client {
    private $req_met;
    private $req_id;
    private $req_body;
    private $manager;
    public function getMethod() { return $this->req_met; }
    public function getId() { return $this->req_id; }
    public function getBody() { return $this->req_body; }
    public function setManager($man) { $this->manager = $man; }
    public function __construct() {
        $this->req_met = empty($_SERVER["REQUEST_METHOD"]) ?
                         false : $_SERVER["REQUEST_METHOD"];
        if ($this->req_met) {
            $this->req_id = empty($_GET["id"]) ?
                            false : intval($_GET["id"]);
            $this->req_body = json_decode(file_get_contents('php://input'));
            //if (!$this->req_body) $this->req_body = false;
            $this->req_body = $this->validateRequestBody() ? $this->req_body : false;
        } else {
            $this->clientIsTerminal();
        }
    }

    private function getManager() { return $this->manager; }
    private function validateRequestBody() {
        $ok = true;
        if (!$this->req_body) $ok = false;
        elseif (!isset($this->req_body->title)) $ok = false;
        elseif (!isset($this->req_body->body)) $ok = false;
        elseif (!isset($this->req_body->position)) $ok = false;
        elseif (!isset($this->req_body->status)) $ok = false;
        elseif (!isset($this->req_body->color)) $ok = false;
        return $ok;
    }

    private function clientIsTerminal() {
        global $argc, $argv;
        if ($argc < 2) die("usage: ".$argv[0]." method id\n");
        $this->req_met = $argv[1] ? strtoupper($argv[1]) : false;
        $this->req_id = $this->req_met == "POST" ? false : $argv[2];
        $wordlen = 6;
        $wordlet = str_repeat("abcdef", 100);
        $this->req_body = (object) array(
            'title' => substr(str_shuffle($wordlet), 0, $wordlen),
            'body' => substr(str_shuffle($wordlet), 0, $wordlen),
            'position' => substr(str_shuffle($wordlet), 0, $wordlen),
            'status' => substr(str_shuffle($wordlet), 0, $wordlen),
            'color' => substr(str_shuffle($wordlet), 0, $wordlen)
        );
    }

    public function headerMethod($origin = "*", $contentype = "application/json; charset=UTF-8") {
        header('Access-Contol-Allow-Origin: '.$origin);
        header('Content-Type: '.$contentype);
        header('Access-Contol-Allow-Methods: '.$this->getMethod());
        header('Access-Contol-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, x-Requestet-With');
    }

    public function getTicket() {//GET => $TM->select($id);

        $man = $this->getManager();
        $id = $this->getId();
        $table = $man->getTable();
        if (!$id) return Client::success($man->count().' tickets in '.$table);

        if ($ticket = $man->select($id)) return Client::success('got ticket '.$id, 200, TicketManager::getPropArray($ticket));
        return Client::badId($id);
    }

    public function addTicket() {//POST => $TM->insert($ticket);

        $man = $this->getManager();
        $body = $this->getBody();
        if (!$body) return Client::badRequest("payload");

        $ticket = new Ticket($body);
        if ($man->insert($ticket)) return Client::success('product added id '.$man->last(), 201);
        return Client::queryError($man->error());
    }

    public function updateTicket() {//PUT => $TM->update($id, $ticket);

        $man = $this->getManager();
        $id = $this->getId();
        $body = $this->getBody();
        if (!$id) $reponse = Client::badRequest("id");
        elseif (!$body) $reponse = Client::badRequest("payload");
        elseif (!$man->select($id)) $reponse = Client::badId($id);
        else {
            $ticket = new Ticket($body);
            if ($man->update($id, $ticket)) $reponse = Client::success('ticket '.$id.' updated', 202);
            else $reponse = Client::queryError($man->error());
        }
        return $reponse;
    }
    
    public function deleteTicket() {//DELETE => $TM->delete($id):

        $man = $this->getManager();
        $id = $this->getId();
        if (!$id) $reponse = Client::badRequest("id");
        elseif (!$man->select($id)) $reponse = Client::badId($id);
        elseif ($man->delete($id)) $reponse = Client::success('ticket '.$id.' deleted', 202);
        else $reponse = Client::queryError($man->error());
        return $reponse;
    }

}

?>