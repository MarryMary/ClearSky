<?php
namespace Clsk\Elena\Executer;
require dirname(__FILE__)."/../../../vendor/autoload.php";

use ReflectionNamedType;

class AliveFactor
{
    public static function Analyzer(string $class, string $function)
    {
        $controller_ns = "Web\Programs\Controllers\\".$class;
        $userController = new $controller_ns;
        $reflect = new \ReflectionClass($userController);
        $method = $reflect->getMethod($function);
        $parameters = $method->getParameters();
        if(count($parameters) != 0){
            $instances = array();
            for($i = 0; $i < count($parameters); $i++){
                $parameter = $parameters[$i];
                $pcn = $parameter->getType();
        
                assert($pcn instanceof ReflectionNamedType);
                $SearchedInstance = $pcn->getName();
                $CreatedInstance = new $SearchedInstance();
                array_push($instances, $CreatedInstance);
            }
            return $instances;
        }else{
            return array();
        }
    }
}