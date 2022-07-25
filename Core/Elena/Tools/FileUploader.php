<?php
namespace Clsk\Elena\Tools;

use Clsk\Elena\Tools\UUIDFactory;
use Clsk\Elena\Tools\FileReader;

class FileUploader
{
    public function UploadCore(String $form_name,Bool $not_think = true, Array $allow = ["sound", "movie", "picture", "app"])
    {
        $clearsky_root = dirname(__FILE__)."/../../../Web/DumpFile/";
        $tempfile = $_FILES[$form_name]['tmp_name'];
        $filename = UUIDFactory::Generate().$_FILES[$form_name]['name'];
        $this->filename = $filename;
        if (is_uploaded_file($tempfile)) {
            if ( move_uploaded_file($tempfile , $clearsky_root.$filename )) {
                $result = FileReader::FileTypeJudge($filename, "", $allow);
                if(is_bool($result) && !$result && !$not_think) {
                    unlink($clearsky_root.$filename);
                    return false;
                }else{
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        } 
    }

    public function UploadMusic(String $form_name,Bool $not_think = true, Array $allow = ["sound", "movie", "picture", "app"])
    {
        $clearsky_root = dirname(__FILE__)."/../../../Web/Programs/Controllers/MusicDump/";
        $tempfile = $_FILES[$form_name]['tmp_name'];
        $filename = UUIDFactory::Generate().$_FILES[$form_name]['name'];
        $this->musicname = $filename;
        if (is_uploaded_file($tempfile)) {
            if ( move_uploaded_file($tempfile , $clearsky_root.$filename )) {
                $result = FileReader::FileTypeJudge($filename, "", $allow);
                if(is_bool($result) && !$result && !$not_think) {
                    unlink($clearsky_root.$filename);
                    return false;
                }else{
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function BenefitUpload(String $form_name,Bool $not_think = true, Array $allow = ["sound", "movie", "picture", "app"])
    {
        $clearsky_root = dirname(__FILE__)."/../../../Web/Programs/Controllers/Benefits/";
        $tempfile = $_FILES[$form_name]['tmp_name'];
        $filename = UUIDFactory::Generate().$_FILES[$form_name]['name'];
        $this->filename = $filename;
        if (is_uploaded_file($tempfile)) {
            if ( move_uploaded_file($tempfile , $clearsky_root.$filename )) {
                $result = FileReader::FileTypeJudge($filename, "", $allow);
                if(is_bool($result) && !$result && !$not_think) {
                    unlink($clearsky_root.$filename);
                    return false;
                }else{
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}