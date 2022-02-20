<?php

include_once 'classes/Client.php';
class ClientRequest extends Client {

    protected function clientIsTerminal() {
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

    public function getOptions() {
        return Client::success(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']);
    }

    public function methodInvalid() {
        return Client::badMethod($this->getMethod());
    }

    private static function validateRequestBody($body) {

        if (!isset($body->title) || !isset($body->body) || !isset($body->position) || !isset($body->status) || !isset($body->color)) return false;
        else return $body;
    }

}

?>