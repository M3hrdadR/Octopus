<?php

class AccessToken{
    public $token = null;
    public $mac_addresss = null;
    function __construct($token, $mac_addresss){
        $this->token = $token;
        $this->mac_addresss = $mac_addresss;
    }
}