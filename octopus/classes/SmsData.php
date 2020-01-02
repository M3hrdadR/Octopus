<?php


class SmsData{
    public $code = null;
    public $generation_time = null;
    function __construct($code){
        $this->code = $code;
        $this->generation_time = time();
    }
}