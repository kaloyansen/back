<?php

/********************************/
/* code php by Kaloyan KRASTEV */
/* kaloyansen@gmail.com       */
/*****************************/
//namespace classes;
include('classes/Ticket.php');
include('classes/DBManager.php');
class TicketManager extends DBManager {/* backend
                                          database
                                          interface */
    private $tab;//le nom du tableau dans la base de donnée
    public function setTable($table) { $this->tab = $table; }
    public function getTable() { return $this->tab; }
    public function count() {
        
        $query = "SELECT * FROM ".$this->tab;
        if ($result = mysqli_query($this->get(), $query))
            return mysqli_num_rows($result);
            else return $this->error();
    }

    public function last() {
        
        $query = "SELECT MAX(id) FROM ".$this->tab;
        if ($result = mysqli_query($this->get(), $query))
            return mysqli_fetch_array($result)[0];
        else return $this->error();
    }

    public function select($id) {

        $query = "SELECT * FROM ".$this->tab." WHERE id=".$id;
        if ($result = mysqli_query($this->get(), $query))
            if ($mfobj = mysqli_fetch_object($result))
                return new Ticket($mfobj, $id);

        return false;
    }

    public function insert(Ticket $ticket) {

        $query = "INSERT INTO ".$this->tab."(title, body, position, status, color) VALUES('".$ticket->getTitle()."', '".$ticket->getBody()."', '".$ticket->getPosition()."', '".$ticket->getStatus()."', '".$ticket->getColor()."')";
        return mysqli_query($this->get(), $query);// ? $this->last() : $this->error();
    }

    public function update($id, Ticket $ticket) {

        $query = "UPDATE ".$this->tab." SET title='".$ticket->getTitle()."', body='".$ticket->getBody()."', position='".$ticket->getPosition()."', status='".$ticket->getStatus()."', color='".$ticket->getColor()."' WHERE id=".$id;
        return mysqli_query($this->get(), $query);
    }

    public function delete($id) {

        $query = "DELETE FROM ".$this->tab." WHERE id=".$id;
        return mysqli_query($this->get(), $query);
    }

}
?>
