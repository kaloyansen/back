<?php

class Client {

    private static $manager;
    protected $req_met;
    protected $req_id;
    protected $req_body;

    protected static function setManager($man) { self::$manager = $man; }
    protected static function getManager() { return self::$manager; }
    public function getMethod() { return $this->req_met; }
    protected function getId() { return $this->req_id; }
    protected function getBody() { return $this->req_body; }

    protected static function success($message, $status = 200, $body = false) {

        $arr = array('status' => $status, 'message' => 'success '.$message);
        if ($body) $arr["body"] = $body;
        return $arr;
    }
    
    protected static function badId($id) {
        return array('status' => 404, 'message' => 'no ticket id '.$id);
    }
    
    protected static function badRequest($message) {
        return array('status' => 400, 'message' => 'bad request '.$message);
    }
    
    protected static function queryError($error) {
        return array('status' => 500, 'message' => 'query error', 'error' => $error);
    }
    
    public static function send($repo) {
        http_response_code($repo['status']);
        echo json_encode($repo);
    }

    public function headerMethod($origin = "*", $contentype = "application/json; charset=UTF-8") {
        header('Access-Contol-Allow-Origin: '.$origin);
        header('Content-Type: '.$contentype);
        header('Access-Contol-Allow-Methods: '.$this->getMethod());
        header('Access-Contol-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, x-Requestet-With');
    }

}

?>