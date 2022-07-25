<?php

namespace Clsk\Elena\Tools;

class FileReader{
    public static function SettingGetter(){
        return self::JsonFileToArray("Web/Settings/Settings.json");
    }

    public static function RouteSettingGetter(){
        return self::JsonFileToArray("Web/Settings/RoutingSettings.json");
    }

    public static function JsonFileToArray(String $path){
        $cinderella_root = dirname(__FILE__)."/../../../";
        $path = ltrim($path, '/');        
        $path = ltrim($path, '\\');
        if(file_exists($cinderella_root.$path)){
            $json = file_get_contents($cinderella_root.$path);
            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $json = json_decode($json,true);
            return $json;
        }else{
            return false;
        }
    }

    public static function ArrayToJsonFile(String $path, Array $array){
        $cinderella_root = dirname(__FILE__)."/../../../";
        $path = ltrim($path, '/');        
        $path = ltrim($path, '\\');
        if(is_array($array)){
            $json = json_encode($array);
            file_put_contents($cinderella_root.$path , $json);
            return true;
        }else{
            return false;
        }
    }

    public static function FileTypeJudge(String $filename,String $search="", Array $allow = ["sound", "movie", "picture", "app"])
    {
        $clearsky_root = dirname(__FILE__)."/../../../Web/DumpFile/";
        $filename = ltrim($filename, '/');        
        $filename = ltrim($filename, '\\');
        if(file_exists($clearsky_root.$filename)){
            $judge = file_get_contents($clearsky_root.$filename, false, null, 0, 12);
            $judge_hex = implode(' ', str_split(bin2hex($judge), '2'));
            $magic_numbers = self::JsonFileToArray("Core/Elena/Tools/magic_numbers.json");
            $types = $magic_numbers["MAGIC_NUMBER_TYPES"];
            foreach($types as $type_k => $type_v){
                foreach($magic_numbers[$type_v] as $audio_mn => $audio_name){
                    if (strpos($judge_hex, strtolower($audio_mn)) !== false) {
                        if($search != "" && $search == $audio_name){
                            return true;
                        }else if($search != "" && $search != $audio_name){
                            return false;
                        }else{
                            if(in_array($type_k, $allow)){
                                return $audio_name;
                            }else{
                                return false;
                            }
                        }
                    }
                }
            }
            return false;
        }
        return false;
    }
}