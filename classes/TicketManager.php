<?php
include('classes/Ticket.php');
class TicketManager {

    private $conn;
    public function __construct($conn) { $this->setConnexion($conn); }
    private function setConnexion($conn) { $this->conn = $conn; }

    public function count() {
        $result = mysqli_query($this->conn, "SELECT * FROM postit");
        if ($result) return mysqli_num_rows($result);
        return 0;
    }

    public function select($id) {
        $result = mysqli_query($this->conn, "SELECT * FROM postit WHERE id=".$id);
        if ($result) {
            $fobj = mysqli_fetch_object($result);//$array = mysqli_fetch_array($result);
            if ($fobj) return new Ticket($fobj);
        }
        return false;
    }

    public function insert(Ticket $ticket) {
        return mysqli_query($this->conn, "INSERT INTO postit(title, body, ActualPosition, status, color) VALUES('".$ticket->getTitle()."', '".$ticket->getBody()."', '".$ticket->getAP()."', '".$ticket->getStatus()."', '".$ticket->getColor()."')");
    }

    public function update($id, Ticket $ticket) {
        if (mysqli_query($this->conn, "UPDATE postit SET title='".$ticket->getTitle()."', body='".$ticket->getBody()."', ActualPosition='".$ticket->getAP()."', status='".$ticket->getStatus()."', color='".$ticket->getColor()."' WHERE id=".$id))
            return true;
        return false;
    }

    public function delete($id) {
        if (mysqli_query($this->conn, "DELETE FROM postit WHERE id=".$id))
            return true;
        return false;
    }

}
?>