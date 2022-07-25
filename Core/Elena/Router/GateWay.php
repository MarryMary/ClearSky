<?php
namespace Clsk\Elena\Router;

require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Tools\FileReader;
use Clsk\Elena\Session\Session;

class GateWay{
    public $param;
    private static function RoutingCore(Array $user_route, Bool $mode=false){
        // http or https?
        $isHTTPS = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://');

        // domain?
        $host = $_SERVER['HTTP_HOST'];

        // call framework setting reader tool
        $settings = FileReader::SettingGetter();

        $url = $isHTTPS.$host.substr($_SERVER['REQUEST_URI'], 0, strcspn($_SERVER['REQUEST_URI'],'?'));

        // get exclude address pattern from framework setting.

        $exclude = ltrim(rtrim($settings["APPURL"], "/"), "/");


        // get access address after remove exclude address from access address.
        $route = str_replace($exclude, "", $url);
        $route = str_replace("\\", "/", $route);
        $route = str_replace("/", ",", $route);
        $route = explode(",", $route);

        $param_read_flag = false;
        $param_read_count = 2;
        $param_read_max = 0;
        $param_key = "";
        $uri_params = array();
        $i = 0;
        $ri = 0;
        while(true){
            if(count($route) > $i){
                if($i == 0 && array_key_exists($i, $route)){
                    $i++;
                }else if(array_key_exists($ri, $user_route) && strpos($user_route[$ri],'@') !== false && array_key_exists($i, $route) && !$param_read_flag || $mode && !$param_read_flag){
                    $param_check = ltrim($user_route[$ri], "@");
                    $param_read = explode(":", $param_check);
                    if($param_check != "" && count($param_read) >= 1){
                        array_push($uri_params, $route[$i]);
                        if(is_numeric($param_read[1])){
                            $param_read_max = (int)$param_read[1];
                        }else{
                            $param_read_max = 20;
                        }
                    }else{
                        return false;
                    }
                    $param_read_flag = true;
                    $i++;
                    $ri++;
                }else if(array_key_exists($ri, $user_route) && strpos($user_route[$ri],':') !== false && array_key_exists($i, $route) && !$param_read_flag || $mode && !$param_read_flag){
                    $uri_params[ltrim($user_route[$ri], ":")] = $route[$i];
                    $i++;
                    $ri++;
                }else if(array_key_exists($ri, $user_route) && strpos($user_route[$ri],'{') !== false && strpos($user_route[$ri],'}') !== false && array_key_exists($i, $route) && !$param_read_flag || $mode && !$param_read_flag){
                    $param_check = rtrim(ltrim($user_route[$ri], "{"), "}");
                    if($param_check != ""){
                        array_push($uri_params, $route[$i]);
                    }
                    $param_read_flag = true;
                    $i++;
                    $ri++;
                }else if(array_key_exists($ri, $user_route) && array_key_exists($i, $route) && $route[$i] == $user_route[$ri] && !$param_read_flag || $mode && !$param_read_flag){
                    $i++;
                    $ri++;
                }else if($param_read_flag && array_key_exists($i, $route) || $mode && !$param_read_flag){
                    array_push($uri_params, $route[$i]);
                    if(!is_null($param_read_count) && !is_null($param_read_max)){
                        if($param_read_max == $param_read_count){
                            $param_read_flag = false;
                            break;
                        }
                    }
                    $i++;
                    $ri++;
                    $param_read_count++;
                }else if($param_read_flag && !array_key_exists($i, $route) || $mode && !$param_read_flag){
                    $param_read_flag = false;
                    break;
                }else{
                    return false;
                }
            }else{
                break;
            }
        }
        return $uri_params;
    }

    public static function RoutingEntrance(String $route, $function){
        $users_route = str_replace("/", ",", $route);
        $users_route = ltrim($users_route, ",");
        $users_route = explode(",", $users_route);
        $parsed_url = self::RoutingCore($users_route);
        if(is_array($parsed_url)){
            Session::Insert("URLParams", $parsed_url);
            echo $function($parsed_url);
            exit;
        }
    }

    public static function RoutingJudgement(String $route){
        $users_route = str_replace("/", ",", $route);
        $users_route = ltrim($users_route, ",");
        $users_route = explode(",", $users_route);
        $parsed_url = self::RoutingCore($users_route);
        if(is_bool($parsed_url)){
            return false;
        }else{
            return true;
        }
    }

    public static function NotAllowedMethod(){
        http_response_code( 405 ) ;
        echo Irregular("MethodNotAllowed");
        exit;
    }
}