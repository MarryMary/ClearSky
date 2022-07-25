<?php
namespace Clsk\Elena\Tools;

class TimeToTime{
    public static function HourToSec(Int $time){
        $HourToSec = 3600;
        if(is_numeric($time)){
            return $HourToSec * $time;
        }else{
            return $HourToSec;
        }
    }

    public static function MinToSec(Int $time){
        $MinToSec = 60;
        if(is_numeric($time)){
            return $MinToSec * $time;
        }else{
            return $MinToSec;
        }
    }
}