<?php
namespace Clsk\Elena\Exception;

use Clsk\Elena\TemplateEngines\GregorioTemplateEngine;
use Clsk\Elena\Tools\FileReader;

class ClskException
{
    public static function ExceptionViewer($e)
    {
        if (PHP_SAPI == 'cli'){
            $str = "ClearSky Reading Exception";
            echo "\033[41m$str\033[0m";
            echo "\n";
            echo "\n";
            echo "Exception is:";
            echo "\n";
            echo $e->getMessage();
            echo "\n";
            echo "Traceback is:";
            echo "\n";
            echo $e->getTraceAsString();
            exit;
        }else{
            $settings = FileReader::SettingGetter();
            if(array_key_exists("IsDebug", $settings) && $settings["IsDebug"]) {
                $template = file_get_contents(dirname(__FILE__) . "/ExceptionTemplate.html");
                $escaped_message = str_replace('"', '', $e->getMessage());
                $escaped_message = str_replace("'", "", $escaped_message);
                $template = str_replace("{{MESSAGE_ESCAPED}}", htmlspecialchars($escaped_message), $template);
                $template = str_replace("{{MESSAGE}}", htmlspecialchars($e->getMessage()), $template);
                $template = str_replace("{{LINE}}", htmlspecialchars($e->getLine()), $template);
                $template = str_replace("{{TRACEBACK}}", trim(htmlspecialchars($e->getTraceAsString())), $template);
                if (file_exists($e->getFile())) {
                    $highlight = explode("\n", file_get_contents(htmlspecialchars($e->getFile())));
                    $template = str_replace("{{HIGHLIGHT}}", trim(htmlspecialchars($highlight[$e->getLine() - 1])), $template);
                    $template = str_replace("{{FILE}}", htmlspecialchars($e->getFile()), $template);
                } else {
                    $template = str_replace("{{HIGHLIGHT}}", "FAILED TO CHASE THE CORRESPONDING FILE.", $template);
                    $template = str_replace("{{FILE}}", "FAILED TO CHASE THE CORRESPONDING FILE.", $template);
                }
                echo $template;
                exit;
            }else{
                http_response_code( 403 );
                echo Irregular("Forbidden");
            }
        }
    }
}