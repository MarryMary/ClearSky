<?php

namespace Clsk\Elena\TemplateEngines\Orthia\Core;

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