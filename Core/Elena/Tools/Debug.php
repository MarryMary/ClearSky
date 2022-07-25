<?php
namespace Clsk\Elena\Tools;

class Debug{

    public static function VarDumpBeautify($data, Bool $only=false)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        if($only){
            exit;
        }
    }

    public static function ForceException()
    {
        throw new \Exception('ClearSky Harmless Debug Exception.');
    }
}