<?php
namespace Clsk\Elena\Router;

class StarNavigator extends GateWay
{
    public static function Get(String $route, $function){
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            GateWay::RoutingEntrance($route, $function);
        }else{
            if(GateWay::RoutingJudgement($route)){
                GateWay::NotAllowedMethod();
            }
        }
    }

    public static function Resource(String $route, $function){
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            GateWay::RoutingEntrance($route, $function);
        }else{
            if(GateWay::RoutingJudgement($route)){
                GateWay::NotAllowedMethod();
            }
        }
    }

    public static function Post(String $route, $function){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            GateWay::RoutingEntrance($route, $function);
        }else{
            if(GateWay::RoutingJudgement($route)){
                GateWay::NotAllowedMethod();
            }
        }
    }

    public static function Put(String $route, $function){
        if($_SERVER["REQUEST_METHOD"] == "PUT"){
            GateWay::RoutingEntrance($route, $function);
        }else{
            if(GateWay::RoutingJudgement($route)){
                GateWay::NotAllowedMethod();
            }
        }
    }

    public static function Patch(String $route, $function){
        if($_SERVER["REQUEST_METHOD"] == "PATCH"){
            GateWay::RoutingEntrance($route, $function);
        }else{
            if(GateWay::RoutingJudgement($route)){
                GateWay::NotAllowedMethod();
            }
        }
    }

    public static function Delete(String $route, $function){
        if($_SERVER["REQUEST_METHOD"] != "DELETE"){
            GateWay::RoutingEntrance($route, $function);
        }else{
            if(GateWay::RoutingJudgement($route)){
                GateWay::NotAllowedMethod();
            }
        }
    }

    public static function All(String $route, $function){
        GateWay::RoutingEntrance($route, $function);
    }
}