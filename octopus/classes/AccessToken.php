<?php

class AccessToken{
    public $token = null;
    public $ip = null;
    public $id = null;
    function __construct($token, $ip, $id){
        $this->token = $token;
        $this->ip = $ip;
        $this->id = $id;
    }
}