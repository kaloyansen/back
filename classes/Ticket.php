<?php

class Ticket {

    private $id;
    private $title;
    private $body;
    private $ActualPosition;
    private $status;
    private $color;

    public function __construct($body) {
        $this->setTitle($body->title);
        $this->setBody($body->body);
        $this->setAP($body->ActualPosition);
        $this->setStatus($body->status);
        $this->setColor($body->color);
    }

    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getBody() { return $this->body; }
    public function getAP() { return $this->ActualPosition; }
    public function getStatus() { return $this->status; }
    public function getColor() { return $this->color; }

    public function setId($id) { if (is_int(intval($id))) $this->id = $id; }
    public function setTitle($title) { if (is_string($title)) $this->title = $title; }
    public function setBody($body) { if (is_string($body)) $this->body = $body; }
    public function setAP($ap) { if (is_string($ap)) $this->ActualPosition = $ap; }
    public function setStatus($status) { if (is_string($status)) $this->status = $status; }
    public function setColor($color) { if (is_string($color)) $this->color = $color; }
}

?>