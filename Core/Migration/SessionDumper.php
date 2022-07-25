<?php
require dirname(__FILE__)."/../../vendor/autoload.php";

use Clsk\Elena\Databases\Migration;
use Clsk\Elena\Databases\Ignition;

class SessionDumper extends Migration{
    
    public function Execution()
    {
        Migration::Create("CSess", function(Ignition $ignition){
            $ignition->LongText("SessionId");
            $ignition->LongText("Variable");
        });
    }

    public function Rollback()
    {
        Migration::Reverse("CSess");
    }
}