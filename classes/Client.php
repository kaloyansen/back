<?php

class Client {
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
}

?>