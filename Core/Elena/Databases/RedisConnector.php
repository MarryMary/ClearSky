<?php
namespace Clsk\Elena\Databases;

require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Tools\FileReader;
use Redis;

class RedisConnector{
    public static function RedisReader(String $key){
        $redis_settings = self::RedisSettingReader();
        $redis = new Redis();
        $redis->connect($redis_settings["RedisHost"], $redis_settings["RedisPort"]);
        if($redis_settings["RedisPass"] != ""){
            $redis->auth(['pass' => $redis_settings["RedisPass"]]);
        }
         
        $value = $redis->get($key);
        $redis->close();

        return $value;
    }

    public static function RedisAllKeyGet(){
        $redis_settings = self::RedisSettingReader();
        $redis = new Redis();
        $redis->connect($redis_settings["RedisHost"], $redis_settings["RedisPort"]);
        if($redis_settings["RedisPass"] != ""){
            $redis->auth(['pass' => $redis_settings["RedisPass"]]);
        }
        
        $allKeys = $redis->keys('*');

        return $allKeys;
    }

    public static function RedisAllReader(){
        $redis_settings = self::RedisSettingReader();
        $redis = new Redis();
        $redis->connect($redis_settings["RedisHost"], $redis_settings["RedisPort"]);
        if($redis_settings["RedisPass"] != ""){
            $redis->auth(['pass' => $redis_settings["RedisPass"]]);
        }
        
        $allKeys = $redis->keys('*');
        $allArray = array();
        foreach($allKeys as $aK){
            $value = $redis->get($aK);
            $allArray[$aK] = $value;
        }
        $redis->close();

        return $allArray;
    }

    public static function RedisSetter(String $key, String $value){
        $redis_settings = self::RedisSettingReader();
        $redis = new Redis();
        $redis->connect($redis_settings["RedisHost"], $redis_settings["RedisPort"]);
        if($redis_settings["RedisPass"] != ""){
            $redis->auth(['pass' => $redis_settings["RedisPass"]]);
        }
        
        $redis->set($key, $value);
        
        $redis->close();
    }

    public static function RedisDropper(String $key){
        $redis_settings = self::RedisSettingReader();
        $redis = new Redis();
        $redis->connect($redis_settings["RedisHost"], $redis_settings["RedisPort"]);
        if($redis_settings["RedisPass"] != ""){
            $redis->auth(['pass' => $redis_settings["RedisPass"]]);
        }
        $redis->delete([$key]);

        $redis->close();
    }

    private static function RedisSettingReader(){
        $pass = FileReader::SettingGetter();
        if(array_key_exists("RedisPass", $pass)){
            $password = $pass["RedisPass"];
        }else{
            $password = "";
        }

        if(array_key_exists("RedisHost", $pass)){
            $host = $pass["RedisHost"];
        }else{
            $host = "127.0.0.1";
        }

        if(array_key_exists("RedisPort", $pass)){
            $port = $pass["RedisPort"];
        }else{
            $port = "6379";
        }

        return ["RedisPass" => $password, "RedisHost" => $host, "RedisPort" => $port];
    }
}