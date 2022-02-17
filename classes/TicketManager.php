<?php

/********************************/
/* code php by Kaloyan KRASTEV */
/* kaloyansen@gmail.com       */
/*****************************/

include('classes/Ticket.php');
class TicketManager {/* backend
                        database
                        interface */
    private $conn;//la connexion à la base de donnée
    private $tab;//le nom du tableau dans la base de donnée
    private function setTable($table) { $this->tab = $table; }
    private function setConnexion($conn) { $this->conn = $conn; }
    private function error() { return mysqli_error($this->conn); }

    public function __construct($conn, $table) {

        $this->setConnexion($conn);
        $this->setTable($table);
    }

    public function count() {

        $query = "SELECT * FROM ".$this->tab;
        if ($result = mysqli_query($this->conn, $query))
            return mysqli_num_rows($result);
        else return $this->error();
    }

    public function select($id) {

        $query = "SELECT * FROM ".$this->tab." WHERE id=".$id;
        if ($result = mysqli_query($this->conn, $query))
            if ($mfobj = mysqli_fetch_object($result))
                return new Ticket($mfobj, $id);

        return false;
    }

    public function insert(Ticket $ticket) {

        $query = "INSERT INTO ".$this->tab."(title, body, ActualPosition, status, color) VALUES('".$ticket->getTitle()."', '".$ticket->getBody()."', '".$ticket->getAP()."', '".$ticket->getStatus()."', '".$ticket->getColor()."')";
        return mysqli_query($this->conn, $query);
    }

    public function update($id, Ticket $ticket) {

        $query = "UPDATE ".$this->tab." SET title='".$ticket->getTitle()."', body='".$ticket->getBody()."', ActualPosition='".$ticket->getAP()."', status='".$ticket->getStatus()."', color='".$ticket->getColor()."' WHERE id=".$id;
        return mysqli_query($this->conn, $query);
    }

    public function delete($id) {

        $query = "DELETE FROM ".$this->tab." WHERE id=".$id;
        return mysqli_query($this->conn, $query);
    }

}
?>
