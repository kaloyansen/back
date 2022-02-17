<?php

/********************************/
/* code php by Kaloyan KRASTEV */
/* kaloyansen@gmail.com       */
/*****************************/

$JSONFILE = "./connexion.json";

class DBManager {
    /* usage: secret connexion to the database in two lines:
    $dbm = new DBManager(<initfile>);
    $connexion = $dbm->call();//connexion à la base de données,

    // where <initfile> is the name of the file
    // with information for the identification
    // it is a text file in line format property: value

    // <=========================== <initfile> example 
    server: localhost
    username: toto
    password: i7klkj5hK4sZER45
    database: totobase

    // ===============================> end of example

    // warning! this information is not public
    // keep <initfile> secret and put the name into .gitignore
    // end of usage -> begin code php */

    private $server;
    private $username;
    private $password;
    private $database;
    private $conn;
    private $ok;

    public function __construct($infile) { $this->initFrom($infile); }
    public function call() {

        $this->ok = $this->connexion() == $this->error() ? false : true;
        if (!$this->ok) $this->reconnexion();//hypotetic
        return $this->export();
    }

    private function export() {
        global $JSONFILE;      
        file_put_contents($JSONFILE, print_r(json_encode(DBManager::getPropArray($this->conn)), true));
        return $this->conn;
    }

    private function reconnexion() { $doNothing = true; }
    private function error() { return $this->conn->connect_error; }
    private function initFrom($infile) {

        if ($handle = fopen($infile, 'r'))
            while ($data = fgetcsv($handle, 0, ":")) {
                $property = trim($data[0]);
                $this->$property = trim($data[1]);
            }
        else echo "error while read ".$infile;
    }

    private function connexion() {

        $this->conn = mysqli_connect($this->server, $this->username, $this->password, $this->database);
        return $this->error() ? $this->error() : $this->conn;
    }

    public static function getPropArray($objet) {//array of properties
        $reflectionClass = new ReflectionClass(get_class($objet));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($objet);
            $property->setAccessible(false);
        }
        return $array;
    }

    private function importValue($prop) {
        $lines = file($this->initfile);
        foreach($lines as $line) {
            $value = explode(':', $line);
            if(trim($value[0]) == $prop) return trim($value[1]);
        }
        return false;
    }
}

?>
