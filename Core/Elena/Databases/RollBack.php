<?php
namespace Clsk\Elena\Databases;

class RollBack{
    public static function Migration()
    {
        $cinderella_root = dirname(__FILE__)."/../../../Web/Migrates";
        if(file_exists($cinderella_root)){
            foreach (glob($cinderella_root."/*") as $filename) {
                require $filename;
                $class = explode("/", $filename);
                $classname = rtrim($class[count($class)-1], ".php");
                $instance = new $classname();
                $instance->Rollback();
            }
        }
    }
}