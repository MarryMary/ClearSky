<?php
/*
* Migrator Class
* 2021/12/22 Made by mary(Tajiri-PekoPekora-April 3rd).
* This class is read and executing migration file.
*/
namespace Clsk\Elena\Databases;

class Migrator{
    // Migration method is executing "Execution" method in migration file.
    public static function Migration(bool $extension=false, string $dir = "")
    {
        // $extension variable is trigger of extension or user created.
        if($extension){
            // If using database in extension, extension provider is must saved migration file in "<ExtensionBaseDir>/Initializer/TableCreator.php".
            $extension_root = rtrim(dirname(__FILE__)."/../../../vendor/".$dir, "/")."/Initializer/TableCreator.php";
            if(file_exists($extension_root)){
                require $extension_root;
                $class = explode("/", $extension_root);
                $classname = rtrim($class[count($class)-1], ".php");
                $instance = new $classname();
                $instance->Execution();
            }
        }else{
            $cinderella_root = dirname(__FILE__)."/../../../Web/Migrates";
            if(file_exists($cinderella_root)){
                // If executing migration based on user writen file, this method is executing all file of saved in "<ProjectRoot>/Web/Migrates/".
                foreach (glob($cinderella_root."/*") as $filename) {
                    require $filename;
                    $class = explode("/", $filename);
                    $classname = rtrim($class[count($class)-1], ".php");
                    $instance = new $classname();
                    $instance->Execution();
                }
            }
        }
    }

    //RollBack method is executing "RollBack" method in migration file.
    public static function RollBack(bool $extension=false, string $dir = "")
    {
        if($extension){
            $extension_root = rtrim(dirname(__FILE__)."/../../../vendor/".$dir, "/")."/Initializer/TableCreator.php";
            if(file_exists($extension_root)){
                require $extension_root;
                $class = explode("/", $extension_root);
                $classname = rtrim($class[count($class)-1], ".php");
                $instance = new $classname();
                $instance->RollBack();
            }
        }else{
            $cinderella_root = dirname(__FILE__)."/../../../Web/Migrates";
            if(file_exists($cinderella_root)){
                foreach (glob($cinderella_root."/*") as $filename) {
                    require $filename;
                    $class = explode("/", $filename);
                    $classname = rtrim($class[count($class)-1], ".php");
                    $instance = new $classname();
                    $instance->RollBack();
                }
            }
        }
    }

    public static function SystemMigration()
    {
        $cinderella_root = dirname(__FILE__)."/../../../Core/Migration";
        if(file_exists($cinderella_root)){
            // If executing migration based on user writen file, this method is executing all file of saved in "<ProjectRoot>/Web/Migrates/".
            foreach (glob($cinderella_root."/*") as $filename) {
                require $filename;
                $class = explode("/", $filename);
                $classname = rtrim($class[count($class)-1], ".php");
                $instance = new $classname();
                $instance->Execution();
            }
        }
    }

    public static function SystemRollBack()
    {
        $cinderella_root = dirname(__FILE__)."/../../../Core/Migration";
        if(file_exists($cinderella_root)){
            foreach (glob($cinderella_root."/*") as $filename) {
                require $filename;
                $class = explode("/", $filename);
                $classname = rtrim($class[count($class)-1], ".php");
                $instance = new $classname();
                $instance->RollBack();
            }
        }
    }
}