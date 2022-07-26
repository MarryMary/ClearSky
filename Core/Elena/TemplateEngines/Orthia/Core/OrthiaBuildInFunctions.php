<?php
namespace Clsk\Elena\TemplateEngines\Orthia\Core;

use Clsk\Elena\TemplateEngines\Orthia\Core\ClearSkyOrthiaException;
use Clsk\Elena\Session\Session;
use Clsk\Elena\Tools\UUIDFactory;
use Clsk\Elena\Tools\FileReader;

class OrthiaBuildInFunctions
{
    public $params;
    public $parsemode;
    public $isHTMLSC;
    
    public function __construct(Array $params, String $mode = "phper", Bool $isHTMLSC = True)
    {
        $this->params = $params;
        $this->parsemode = $mode;
        $this->isHTMLSC = $isHTMLSC;
    }

    public function SettingReader(String $key)
    {
        $setting = FileReader::SettingGetter();
        return $setting[$key];
    }

    public function csrf_token()
    {
        $token = UUIDFactory::generate();
        Session::Start();
        if(Session::IsIn("csrf_token")){
            Session::Insert("csrf_token", Session::Reader("csrf_token"));
            $token = Session::Reader("csrf_token");
        }else {
            Session::Insert("csrf_token", $token);
        }
        return "<input type='hidden' name='csrf_token' value='".$token."'>";
    }

    public function JSEmb_csrf_token()
    {
        $token = UUIDFactory::generate();
        Session::Start();
        if(Session::IsIn("csrf_token")){
            Session::Insert("csrf_token", Session::Reader("csrf_token"));
            $token = Session::Reader("csrf_token");
        }else{
            Session::Insert("csrf_token", $token);
        }
        return $token;
    }

    public function break()
    {
        return "ORTHIASIGNAL@STOP";
    }

    public function debug()
    {
        $config_json = file_get_contents(dirname(__FILE__)."/config.json");
        $config_array = mb_convert_encoding($config_json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $config_array = json_decode($config_array,true);
        $version = $config_array['version'];
        $engine_name = $config_array['engine_name'];
        $dtype = $config_array['type'];
        $codename = $config_array['version_name'];
        $mode = $this->parsemode;
        $params = $this->params;
        $AnalyzerInstance = new Analyzer();
        $template = file_get_contents(dirname(__FILE__)."/systemplate/debug_table.html");
        return $AnalyzerInstance->Main($template, compact("version", "engine_name", "dtype", "codename", "mode", "params"), False, "phper");
    }

    public function ArrayAnalyzer(Array $array, String $keywords, Bool $isHTMLSC = True)
    {
        $parsemode = $this->parsemode;
        if(strtolower($parsemode) == "phper"){
            $exploded_keyword = explode("->", $keywords);
        }else{
            $exploded_keyword = explode(".", $keywords);
        }
        if(count($exploded_keyword) == 1 || count($exploded_keyword) == 0){
            ob_start();
            var_dump($array);
            $line = "<pre><code>".ob_get_contents()."</code></pre>";
            ob_end_clean();
            return $line;
        }else{
            $based_array = $array;
            $key = 1;
            while(True){
                if(array_key_exists($key, $exploded_keyword) && array_key_exists($exploded_keyword[$key], $based_array) && is_array($based_array)){
                    $based_array = $based_array[$exploded_keyword[$key]];
                    $key++;
                }else{
                    break;
                }
            }
            if(is_array($based_array) && count($based_array) != 0){
                ob_start();
                var_dump($based_array);
                $line = "<pre><code>".ob_get_contents()."</code></pre>";
                ob_end_clean();
                return $line;
            }else{
                if(is_array($based_array) && count($based_array) == 0){
                    return "";
                }else {
                    if ($isHTMLSC) {
                        return htmlspecialchars($based_array);
                    } else {
                        return $based_array;
                    }
                }
            }
        }
    }

    public function paste_here(String $parts_name)
    {
        if(array_key_exists("[ORTHIABLOCKOBJECT]".trim(trim(trim($parts_name), "'"), '"'), $this->params)){
            return $this->params["[ORTHIABLOCKOBJECT]".$parts_name];
        }else{
            return "";
        }
    }

    public function convey(String $path)
    {
        $path = dirname(__FILE__)."/../../../../../Web/Templates/".trim(trim(trim(trim(trim($path), "/"), "\\"), "'"), '"');
        if(file_exists($path)){
            $template = file_get_contents($path);
            $AnalyzerInstance = new Analyzer();
            $template = $AnalyzerInstance->Main($template, $this->params, False, "phper", $this->isHTMLSC);
            return $template;
        }else{
            return "";
        }
    }

    public function resources(String $URL)
    {
        $settings = FileReader::SettingGetter();
        $base_url = str_replace("/Exposure", "",$settings["APPURL"]);
        $url = $base_url."Web/Resources/".trim(trim(trim($URL), "/"), "/");
        return $url;
    }

    public function OL(String $oneLineCode)
    {
        $template = "";
        var_dump($oneLineCode);
        $onelineparse = explode(";", $oneLineCode);
        foreach($onelineparse as $code){
            $codeparsed = explode("|", $code);
            if(count($codeparsed) == 2){
                $template .= "{% ".trim($codeparsed[0])." %}\n";
                $template .= trim($codeparsed[1])."\n";
            }else if(count($codeparsed) == 1 && strpos($codeparsed[0],'end') !== false){
                $template .= "{% ".trim($codeparsed[0])." %}\n";
            }
        }
        $AnalyzerInstance = new Analyzer();
        $template = $AnalyzerInstance->Main($template, $this->params, False, "phper", $this->isHTMLSC);
        return $template;
    }
}