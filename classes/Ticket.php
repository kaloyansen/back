<?php

/********************************/
/* code php by Kaloyan KRASTEV */
/* kaloyansen@gmail.com       */
/*****************************/

class Ticket {/* classic
                 ticket
                 container */
    private $id;
    private $title;
    private $body;
    private $position;
    private $status;
    private $color;

    public function __construct($body, $id = false) {//create from object
        if ($id) $this->setId($id);
        $this->setTitle($body->title);
        $this->setBody($body->body);
        $this->setPosition($body->position);
        $this->setStatus($body->status);
        $this->setColor($body->color);
    }

    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getBody() { return $this->body; }
    public function getPosition() { return $this->position; }
    public function getStatus() { return $this->status; }
    public function getColor() { return $this->color; }
    public function setId($id) { if (is_int(intval($id))) $this->id = $id; }
    public function setTitle($title) { if (is_string($title)) $this->title = $title; }
    public function setBody($body) { if (is_string($body)) $this->body = $body; }
    public function setPosition($position) { if (is_string($position)) $this->position = $position; }
    public function setStatus($status) { if (is_string($status)) $this->status = $status; }
    public function setColor($color) { if (is_string($color)) $this->color = $color; }
}

?>
