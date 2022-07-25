<?php
/*
* Migration Class
* 2021/12/22 Made by mary(Tajiri-PekoPekora-April 3rd).
* This class is providing function for migration files.
*/

namespace Clsk\Elena\Databases;
require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Databases\QueryBuilder;
use Clsk\Elena\Databases\Ignition;
use Clsk\Elena\Executer\AliveFactor;

class Migration
{
    // Create method is creating table based on anonymous function written by user.
    public static function Create(String $table, $function)
    {
        // Creating instance of "Ignition" class.
        // Create method's anonymous function is expected to only injecting that instance.
        $ignition = new Ignition();
        $function($ignition);
        QueryBuilder::Table($table)->Create($ignition->Get());
    }

    // Reverce method is drop created table.
    public static function Reverse(String $table, Bool $ifex = true)
    {
        QueryBuilder::Table($table)->Drop($ifex);
    }
}