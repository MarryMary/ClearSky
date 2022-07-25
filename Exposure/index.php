<?php
require dirname(__FILE__)."/../vendor/autoload.php";

use Clsk\Elena\Router\ResourceReturn;
use Clsk\Elena\Exception\ClskException;
use Clsk\Elena\Request\RequestReceptor;
use Clsk\Elena\Tools\FileReader;
use Clsk\Elena\Session\Session;

$cinderella_root = dirname(__FILE__)."/../Web/Settings/Routing.php";
include dirname(__FILE__)."/../Core/Elena/Executer/GateExecuter.php";

if(file_exists($cinderella_root)){
    try{
        $setting = FileReader::SettingGetter();
        if(array_key_exists("TimeZone", $setting)){
            date_default_timezone_set($setting["TimeZone"]);
        }else{
            date_default_timezone_set('Asia/Tokyo');
        }
        if(!array_key_exists("IsDebug", $setting) || array_key_exists("IsDebug", $setting) && !$setting["IsDebug"]){
            error_reporting(0);
        }
        $receptor = new RequestReceptor();
        Session::Start();
        if($receptor->IsSend() && !$receptor->RequestSearch("csrf_token") || $receptor->IsSend() && !Session::IsIn("csrf_token") || $receptor->IsSend() && Session::IsIn("csrf_token") && $receptor->RequestSearch("csrf_token") && Session::Reader("csrf_token") != $receptor->csrf_token){
            echo Irregular("Forbidden");
            Session::Unset("csrf_token");
            exit;
        }
        if(Session::IsIn("csrf_token")) {
            Session::Unset("csrf_token");
        }
        include $cinderella_root;
        if(array_key_exists("IsDebug", $setting) && $setting["IsDebug"]){
            $message = "アクセスしたURLに対応するルーティング設定が見つかりませんでした。正しくルーティング設定が行われているかを確認してください。<br>もしルーティング設定が行われている場合、設定ファイルの「APPURL」の項目が誤っている可能性があります。現在のURLに書き換えてください。";
            $array = [
                "err" => $message,
                "IsDebug" => $setting["IsDebug"]
            ];
            echo Irregular("NotFound", $array);
        }else{
            echo Irregular("NotFound");
        }
    }catch(\Throwable $e){
        ClskException::ExceptionViewer($e);
    }
}else{
    echo "Fatal error: routing file can't read.";
}