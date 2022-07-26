<?php

namespace Clsk\Elena\Exception\ExceptionThrower;

class ClearSkyOrthiaException extends \Exception
{
    protected $message;
    function __construct(String $message)
    {
        $this->message = $message;
    }
    function __toString()
    {
        return "Orthia Template Engine Exception:".$this->message;
    }
}