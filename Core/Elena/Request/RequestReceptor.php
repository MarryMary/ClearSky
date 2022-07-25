<?php
namespace Clsk\Elena\Request;

use Clsk\Elena\Session\Session;

class RequestReceptor
{
    private $urlparams = array();

    public function __construct(){
        if(Session::IsIn("URLParams") && is_array(Session::Reader("URLParams")))
        {
            $this->urlparams = Session::Reader("URLParams");
        }else{
            $this->urlparams = array();
        }
        foreach($_POST as $post => $val){
            $this->$post = $val;
        }
        foreach($_GET as $get => $val){
            $this->$get = $val;
        }
        foreach($this->urlparams as $url => $val){
            $this->$url = $val;
        }
    }

    public function RequestGive(){
        $request = array();
        foreach($_POST as $post => $val){
            $request[$post] = $val;
        }
        foreach($_GET as $get => $val){
            $request[$get] = $val;
        }
        foreach($this->urlparams as $url => $val){
            $request[$url] = $val;
        }
        return (object)$request;
    }

    public function IsSend(String $type="all")
    {
        if($type=="all"){
            if(count($_GET) != 0 || count($_POST) != 0){
                return true;
            }else{
                return false;
            }
        }else if($type=="post"){
            if(count($_POST) != 0){
                return true;
            }else{
                return false;
            }
        }else if($type="get"){
            if(count($_GET) != 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function RequestSearch($needle, Bool $type = true){
        if($type){
            if(is_array($needle)){
                $flag = false;
                foreach($needle as $need){
                    if(array_key_exists($need, $_POST)){
                        $flag = true;
                    }
                }
                return $flag;
            }
            else{
                if(array_key_exists($needle, $_POST) || array_key_exists($needle, $_GET)){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            if(is_array($needle)){
                $flag = false;
                foreach($needle as $need){
                    if(array_key_exists($need, $_GET)){
                        $flag = true;
                    }
                }
                return $flag;
            }else{
                if(array_key_exists($needle, $_GET)){
                    return true;
                }
            }
        }
    }

    public function PostGive(){
        $request = array();
        foreach($_POST as $post => $val){
            $request[$post] = $val;
        }
        return (object)$request;
    }

    public function GetGive(){
        $request = array();
        foreach($_GET as $get => $val){
            $request[$get] = $val;
        }
        return (object)$request;
    }

    public function UriGive(){
        $request = array();
        foreach($this->urlparams as $url => $val){
            $request[$url] = $val;
        }
        return (object)$request;
    }
}