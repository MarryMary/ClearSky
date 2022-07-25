<?php
/*
* 他言語接続部分
* 他言語に処理を委任する際に呼び出すクラスです。
*/
namespace Clsk\Elena\Bridge;

use Clsk\Elena\Tools\UUIDFactory;
use Clsk\Elena\Tools\FileReader;

class OtherBridge
{
    public function Connect(String $to, String $lang, Array $val = array()){
        // UUIDを生成
        $uuid = UUIDFactory::generate();
        // 多言語を入れるディレクトリを定義
        $cinderella_root = dirname(__FILE__)."/../../../Web/OtherLang";

        // 他言語を入れるディレクトリに変数情報をJSONで保存
        // UUIDをファイル名として保存
        if(is_array($val) && count($val) != 0){
            FileReader::ArrayToJsonFile("Web/OtherLang/Src/".$uuid.".json", $val);
        }

        // exec関数でファイルとファイルの場所だけを呼び出す
        exec($lang." ".$cinderella_root.$to." ".$uuid, $return);

        return $return;
    }
}