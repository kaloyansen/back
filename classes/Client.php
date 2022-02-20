<?php

class Client {

    private $manager;
    public function getManager() { return $this->manager; }
    public function setManager($man) { $this->manager = $man; }

    public static function success($message, $status = 200, $body = false) {
        $arr = array('status' => $status, 'message' => 'success '.$message);
        if ($body) $arr["body"] = $body;
        return $arr;
    }
    
    public static function badId($id) {
        return array('status' => 404, 'message' => 'no ticket id '.$id);
    }
    
    public static function badRequest($message) {
        return array('status' => 400, 'message' => 'bad request '.$message);
    }
    
    public static function queryError($error) {
        return array('status' => 500, 'message' => 'query error', 'error' => $error);
    }
    
    public static function send($repo) {
        http_response_code($repo['status']);
        echo json_encode($repo);
    }

    public static function validateRequestBody($body) {
        $ok = true;
        if (!$body) $ok = false;
        elseif (!isset($body->title)) $ok = false;
        elseif (!isset($body->body)) $ok = false;
        elseif (!isset($body->position)) $ok = false;
        elseif (!isset($body->status)) $ok = false;
        elseif (!isset($body->color)) $ok = false;
        return $ok ? $body : false;
    }

}

?>