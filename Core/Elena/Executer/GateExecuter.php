<?php
require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Executer\Provide;

function Viewer(String $filename){
    return Provide::Viewer($filename);
}

function Reader(String $filename, Array $params){
    return Provide::Reader($filename, $params);
}

function Controller(String $controller_name, String $function_name){
    return Provide::Controller($controller_name, $function_name);
}

function WebAPI(Array $json){
    Provide::WebAPI($json);
}

function Irregular(String $filename, Array $params = array()){
    return Provide::Irregular($filename, $params);
}

function JumpTo(String $to){
    Provide::JumpTo($to);
}