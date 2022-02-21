<?php

/********************************/
/* code php by Kaloyan KRASTEV */
/* kaloyansen@gmail.com       */
/*****************************/

namespace classes;
include_once 'classes/Client.php';

class ClientRequest extends \classes\Client {/* client
                                                request
                                                analyse */
    protected function clientIsTerminal() {

        $this->req_met = "GET";
        $this->req_id = false;
        $this->req_body = self::randomBody();

        global $argc, $argv;
        if ($argc > 1) $this->req_met = strtoupper($argv[1]);
        if ($argc > 2) $this->req_id = $argv[2];
    }

    public function getTicket() {//GET => $TM->select($id);

        $man = $this->getManager();
        $id = $this->getId();
        $table = $man->getTable();

        if (!$id) return self::success($man->count().' tickets in '.$table);

        if ($ticket = $man->select($id)) return self::success('got ticket '.$id, 200, \classes\TicketManager::getPropArray($ticket));
        return self::badId($id);
    }

    public function addTicket() {//POST => $TM->insert($ticket);

        $man = $this->getManager();
        $body = $this->getBody();

        //if (!$body) return self::badRequest("payload");

        $ticket = new \classes\Ticket($body);
        if (!$ticket->validation()) return self::badRequest("payload");
        if ($man->insert($ticket)) return self::success('product added id '.$man->last(), 201);
        return self::queryError($man->error());
    }

    public function updateTicket() {//PUT => $TM->update($id, $ticket);

        $man = $this->getManager();
        $id = $this->getId();
        $body = $this->getBody();

        if (!$id) $reponse = self::badRequest("id");
        //elseif (!$body) $reponse = self::badRequest("payload");
        elseif (!$man->select($id)) $reponse = self::badId($id);
        else {
            $ticket = new \classes\Ticket($body);
            if (!$ticket->validation()) $reponse = self::badRequest("payload");
            if ($man->update($id, $ticket)) $reponse = self::success('ticket '.$id.' updated', 205);
            else $reponse = self::queryError($man->error());
        }

        return $reponse;
    }
    
    public function deleteTicket() {//DELETE => $TM->delete($id):

        $man = $this->getManager();
        $id = $this->getId();

        if (!$id) $reponse = self::badRequest("id");
        elseif (!$man->select($id)) $reponse = self::badId($id);
        elseif ($man->delete($id)) $reponse = self::success('ticket '.$id.' deleted', 205);
        else $reponse = self::queryError($man->error());

        return $reponse;
    }

    public function getOptions() {
        return self::success(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], 204);
    }

    public function methodInvalid() {
        return self::badMethod($this->getMethod());
    }

    private static function randomBody($wordset = "abcdef", $wordlen = 6) {
        $wordlet = str_repeat($wordset, 100);
        return (object) array(
            'title' => substr(str_shuffle($wordlet), 0, $wordlen),
            'body' => substr(str_shuffle($wordlet), 0, $wordlen),
            'position' => substr(str_shuffle($wordlet), 0, $wordlen),
            'status' => substr(str_shuffle($wordlet), 0, $wordlen),
            'color' => substr(str_shuffle($wordlet), 0, $wordlen)
        );
    }

}

?>