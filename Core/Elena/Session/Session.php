<?php
namespace Clsk\Elena\Session;

require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Tools;
use Clsk\Elena\Databases\RedisConnector;
use Clsk\Elena\Databases\QueryBuilder;
use Clsk\Elena\Tools\FileReader;
use Clsk\Elena\Tools\UUIDFactory;
use Clsk\Elena\Tools\TimeToTime;

class Session{
    private static $cookie_sub;

    public static function Start(){
        if(self::Breath()){
            return true;
        }else{
            $settings = FileReader::SettingGetter();
            $session_dumper = $settings["SessionDumper"];
            $sid = UUIDFactory::Generate();
            self::Delete();
            $destroy = time()+60*60*TimeToTime::HourToSec(12);
            header("Set-Cookie: cndrl_sID=$sid");
            $session_data = json_encode(["cndrl_sLim" => $destroy]);
            self::$cookie_sub = $sid;

            if($session_dumper == "RDB"){
                $system_array = [
                    "SessionId" => $sid,
                    "Variable" => $session_data
                ];
                QueryBuilder::Table("CSess")->Insert($system_array, False);
            }else if($session_dumper == "Redis"){
                RedisConnector::RedisSetter($sid, $session_data);
            }else if($session_dumper == "File"){
                file_put_contents(dirname(__FILE__)."/SessionDumper/".$sid , $session_data);
            }
            return true;
        }
    }

    public static function Unset($title = "")
    {
        if($title == ""){
            $settings = FileReader::SettingGetter();
            $session_dumper = $settings["SessionDumper"];
            $sid = $_COOKIE["cndrl_sID"];
            if($session_dumper == "RDB"){
                $system_array = [
                    "SessionId" => $sid,
                    "Variable" => array()
                ];
                QueryBuilder::Table("CSess")->Insert($system_array, False);
            }else if($session_dumper == "Redis"){
                RedisConnector::RedisSetter($sid, array());
            }else if($session_dumper == "File") {
                $data = file_get_contents(dirname(__FILE__) . "/SessionDumper/" . $sid);
                $datas = json_decode($data, true);
                $datas = array();
                file_put_contents(dirname(__FILE__) . "/SessionDumper/" . $sid, json_encode($datas));
                $pass = dirname(__FILE__)."/SessionDumper/".$sid;
                unlink($pass);
            }
        }else{
            $sid = $_COOKIE["cndrl_sID"];
            if(file_exists(dirname(__FILE__)."/SessionDumper/".$sid)){
                $data = file_get_contents(dirname(__FILE__)."/SessionDumper/".$sid);
                $data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                $data = json_decode($data, true);
                unset($data[$title]);
                file_put_contents(dirname(__FILE__)."/SessionDumper/".$sid , json_encode($data));
            }
        }
    }


    public static function Delete(){
        $settings = FileReader::SettingGetter();
        $session_dumper = $settings["SessionDumper"];
        if($session_dumper == "RDB"){
            $Session = QueryBuilder::Table("Csess")->Fetch(True)[0];
        }else if($session_dumper == "Redis"){
            $Session = RedisConnector::RedisAllReader();
        }else if($session_dumper == "File"){
            $Session = glob('SessionDumper/*');
        }
        foreach($Session as $MS){
            $data = self::AllBack($MS);
            if(is_array($data)){
                if(isset($data["cndrl_sLim"]) && is_numeric($data["cndrl_sLim"])){
                    if($data["cndrl_sLim"] < time()){
                        $data = file_get_contents(dirname(__FILE__)."/SessionDumper/".$MS);
                        $datas = json_decode($data, true);
                        $datas = array();
                        file_put_contents(dirname(__FILE__)."/SessionDumper/".$MS, json_encode($datas));
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }
        }
    }

    public static function Breath(){
        self::Delete();
        $settings = FileReader::SettingGetter();
        $session_dumper = $settings["SessionDumper"];
        if($session_dumper == "RDB"){
            if(isset($_COOKIE["cndrl_sID"]) && QueryBuilder::Table("Csess")->Where("cndrl_sID", "=", $_COOKIE["cndrl_sID"])->CountRow() != 0){
                return true;
            }else{
                return false;
            }
        }else if($session_dumper == "Redis"){
            if(isset($_COOKIE["cndrl_sID"]) && count(RedisConnector::RedisReader($_COOKIE["cndrl_sID"])) != 0){
                return true;
            }else{
                return false;
            }
        }else if($session_dumper == "File"){
            if(isset($_COOKIE["cndrl_sID"]) && file_exists(dirname(__FILE__)."/SessionDumper/".$_COOKIE["cndrl_sID"]) || self::$cookie_sub){
                return true;
            }else{

                return false;
            }
        }
    }

    public static function Insert($sessName, $sessData){
        $settings = FileReader::SettingGetter();
        $session_dumper = $settings["SessionDumper"];
        if(self::Breath()){
            if($session_dumper == "RDB"){
                $session_id = "";
                if(isset($_COOKIE["cndrl_sID"])){
                    $session_id = $_COOKIE["cndrl_sID"];
                }else{
                    $session_id = self::$cookie_sub;
                }
                $data = self::AllBack($$session_id);
                if(is_array($data)){
                    $data[$sessName] = $sessData;
                    QueryBuilder::Table("Csess")->Where("cndrl_sID", "=", $session_id)->Update([$session_id => json_encode($data)]);
                }
            }else if($session_dumper == "Redis"){
                $session_id = "";
                if(isset($_COOKIE["cndrl_sID"])){
                    $session_id = $_COOKIE["cndrl_sID"];
                }else{
                    $session_id = self::$cookie_sub;
                }
                $data = self::AllBack($session_id);
                if(is_array($data)){
                    $data[$sessName] = $sessData;
                    RedisConnector::RedisSetter($session_id, json_encode($data));
                }
            }else if($session_dumper == "File"){
                $session_id = "";
                if(isset($_COOKIE["cndrl_sID"])){
                    $session_id = $_COOKIE["cndrl_sID"];
                }else{
                    $session_id = self::$cookie_sub;
                }
                $data = self::AllBack($session_id);
                if(is_array($data)){
                    $data[$sessName] = $sessData;
                    file_put_contents(dirname(__FILE__)."/SessionDumper/".$session_id ,json_encode($data));
                }
            }
        }else{
            return false;
        }
    }

    public static function OnceInsert(Array $sessData = array()){
        if(self::Breath()){
            $data = self::AllBack($_COOKIE["cndrl_sID"]);
            $settings = FileReader::SettingGetter();
            $session_dumper = $settings["SessionDumper"];
            foreach($sessData as $sData => $sValue){
                $data[$sData] = $sValue;
            }
            if($session_dumper == "RDB"){
                $session_id = "";
                if(isset($_COOKIE["cndrl_sID"])){
                    $session_id = $_COOKIE["cndrl_sID"];
                }else{
                    $session_id = self::$cookie_sub;
                }
                if(is_array($data)){
                    QueryBuilder::Table("Csess")->Where("cndrl_sID", "=", $session_id)->Update([$session_id => json_encode($data)]);
                }
                return true;
            }else if($session_dumper == "Redis"){
                $session_id = "";
                if(isset($_COOKIE["cndrl_sID"])){
                    $session_id = $_COOKIE["cndrl_sID"];
                }else{
                    $session_id = self::$cookie_sub;
                }
                if(is_array($data)){
                    RedisConnector::RedisSetter($session_id, json_encode($data));
                }
                return true;
            }else if($session_dumper == "File"){
                $session_id = "";
                if(isset($_COOKIE["cndrl_sID"])){
                    $session_id = $_COOKIE["cndrl_sID"];
                }else{
                    $session_id = self::$cookie_sub;
                }
                if(is_array($data)){
                    file_put_contents(dirname(__FILE__)."/SessionDumper/".$session_id, json_encode($data));
                }
                return true;
            }
        }else{
            return false;
        }
    }

    public static function Reader(String $String){
        $settings = FileReader::SettingGetter();
        $session_dumper = $settings["SessionDumper"];
        if(self::Breath()){
            if($session_dumper == "RDB"){
                $session_id = "";
                if(isset($_COOKIE["cndrl_sID"])){
                    $session_id = $_COOKIE["cndrl_sID"];
                }else{
                    $session_id = self::$cookie_sub;
                }
                $data = QueryBuilder::Table("Csess")->Where("cndrl_sID", "=", $session_id)->Fetch()[0];
                $data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                return json_encode($data, true)[$String];
            }else if($session_dumper == "Redis"){
                $session_id = "";
                if(isset($_COOKIE["cndrl_sID"])){
                    $session_id = $_COOKIE["cndrl_sID"];
                }else{
                    $session_id = self::$cookie_sub;
                }
                $data = RedisConnector::RedisReader($session_id);
                $data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                return json_decode($data, true)[$String];
            }else if($session_dumper == "File"){
                $session_id = "";
                if(isset($_COOKIE["cndrl_sID"])){
                    $session_id = $_COOKIE["cndrl_sID"];
                }else{
                    $session_id = self::$cookie_sub;
                }
                if(file_exists(dirname(__FILE__)."/SessionDumper/".$session_id)){
                    $data = file_get_contents(dirname(__FILE__)."/SessionDumper/".$session_id);
                    $data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                    return json_decode($data, true)[$String];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    public static function AllBack(String $Key){
        $settings = FileReader::SettingGetter();
        $session_dumper = $settings["SessionDumper"];
        if(self::Breath()){
            if($session_dumper == "RDB"){
                $data = QueryBuilder::Table("Csess")->Where("cndrl_sID", "=", $Key)->Fetch()[0];
                $data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                return json_encode($data, true);
            }else if($session_dumper == "Redis"){
                $data = RedisConnector::RedisReader($Key);
                $data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                return json_decode($data, true);
            }else if($session_dumper == "File"){
                if(file_exists(dirname(__FILE__)."/SessionDumper/".$Key)){
                    $data = file_get_contents(dirname(__FILE__)."/SessionDumper/".$Key);
                    $data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
                    return json_decode($data, true);
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    public static function Explore(String $String){
        if(self::Breath()){
            $session_id = "";
            if(isset($_COOKIE["cndrl_sID"])){
                $session_id = $_COOKIE["cndrl_sID"];
            }else{
                $session_id = self::$cookie_sub;
            }
            $data = self::AllBack($session_id);
            if(is_array($data) && array_key_exists($String, $data)){
                return $data[$String];
            }else{
                return false;
            }
        }
    }

    public static function IsIn(String $String){
        if(self::Breath()){
            $session_id = "";
            if(isset($_COOKIE["cndrl_sID"])){
                $session_id = $_COOKIE["cndrl_sID"];
            }else{
                $session_id = self::$cookie_sub;
            }
            $data = self::AllBack($session_id);
            if(is_array($data) && array_key_exists($String, $data)){
                return true;
            }else{
                return false;
            }
        }
    }

    public static function SIDChange(){
        //TODO
    }

    public static function SIDSpecify(){
        //TODO
    }
}