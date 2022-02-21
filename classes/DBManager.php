<?php

/********************************/
/* code php by Kaloyan KRASTEV */
/* kaloyansen@gmail.com       */
/*****************************/

namespace classes;

class DBManager {

    /* usage: secret connexion to the database in two lines:
    $dbm = new DBManager(<initfile>);
    $connexion = $dbm->open();//connexion à la base de données,
    $connexion = $dbm->close();//déconnexion de la base de données,

    // where <initfile> is the name of the file
    // with information for the identification
    // it is a text file in line format property: value

    // <=========================== <initfile> example 
    server: localhost
    username: toto
    password: i7klkj5hK4sZER45
    database: totobase

    // ===============================> end of example

    // end of usage -> begin code php */

    private $server;
    private $username;
    private $password;
    private $database;

    private static $conn;
    private static $iconn;
    private static $jsonFile = "./connexion.json";
    
    public function __construct($infile) { $this->initFrom($infile); }
    public function get() { return self::$conn; }
    public function close() { echo "\n"; mysqli_close(self::$conn); }
    public function open() {
        $ok = $this->connexion() == $this->error() ? false : true;
        if (!$ok) $this->reconnexion();//hypotetic
        return $this->export();
    }

    private function export() {
        file_put_contents(self::$jsonFile, print_r(json_encode(DBManager::getPropArray(self::$conn)), true));
        return self::$conn;
    }

    private function reconnexion() { self::$iconn ++; }
    private function error() { return mysqli_error(self::$conn); }
    //private function error() { return $this->conn->connect_error; }
    private function initFrom($infile) {

        self::$iconn = 0;
        if ($handle = fopen($infile, 'r'))
            while ($data = fgetcsv($handle, 0, ":")) {
                $property = trim($data[0]);
                $this->$property = trim($data[1]);
            }
        else echo "error while read ".$infile;
    }

    private function connexion() {

        self::$conn = mysqli_connect($this->server,
                                     $this->username,
           /* actual connexion */    $this->password,
                                     $this->database);
        return $this->error() ? $this->error() : self::$conn;
    }

    public static function getPropArray($objet) {//array of properties
        $reflectionClass = new \ReflectionClass(get_class($objet));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($objet);
            $property->setAccessible(false);
        }
        return $array;
    }
/*
    private function importValue($prop) {
        $lines = file($this->initfile);
        foreach($lines as $line) {
            $value = explode(':', $line);
            if(trim($value[0]) == $prop) return trim($value[1]);
        }
        return false;
    }
*/
}

?>
