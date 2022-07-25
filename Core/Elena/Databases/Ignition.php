<?php
/*
* Ignition Class
* 2021/12/22 Made by mary(Tajiri-PekoPekora-April 3rd).
* This class is creating SQL for creating tables.
* Method name is paired the SQL datatypes.
*/
namespace Clsk\Elena\Databases;

class Ignition
{
    
    private $builded = array();

    public function Created_At(){
        $this->builded["created_at"] = "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP";
        return $this;
    }

    public function Updated_At(){
        $this->builded["updated_at"] = "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,";
        return $this;
    }

    public function AutoIncrement(String $colname)
    {
        $this->builded[$colname] = "BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT";
        return $this;
    }

    public function Char(String $colname, Int $length=255, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "CHAR($length) $null";
        return $this;
    }

    public function VarChar(String $colname, Int $length=255, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "VARCHAR($length) $null";
        return $this;
    }

    public function nChar(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "nCHAR($length) $null";
        return $this;
    }

    public function nVarChar(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "nVARCHAR($length) $null";
        return $this;
    }

    public function nText(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "nTEXT($length) $null";
        return $this;
    }

    public function Binary(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "BINARY($length) $null";
        return $this;
    }

    public function VarBinary(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "VARBINARY($length) $null";
        return $this;
    }

    public function Image(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "IMAGE($length) $null";
        return $this;
    }

    public function Int(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "INT($length) $null";
        return $this;
    }

    public function SmallInt(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "SMALLINT($length) $null";
        return $this;
    }

    public function BigInt(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "BIGINT($length) $null";
        return $this;
    }

    public function Bit(String $colname, Int $length=11, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "BIT($length) $null";
        return $this;
    }

    public function Real(String $colname, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "REAL $null";
        return $this;
    }

    public function Float(String $colname, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "FLOAT $null";
        return $this;
    }

    public function Date(String $colname, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "DATE $null";
        return $this;
    }

    public function DateTime(String $colname, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "DATETIME $null";
        return $this;
    }

    public function Time(String $colname, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "TIME $null";
        return $this;
    }

    public function Text(String $colname, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "TEXT $null";
        return $this;
    }

    public function LongText(String $colname, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "LONGTEXT $null";
        return $this;
    }

    public function Blob(String $colname, Bool $notnull=true)
    {
        $null = "";
        if($notnull){
            $null = "NOT NULL";
        }
        $this->builded[$colname] = "BLOB $null";
        return $this;
    }

    public function Get()
    {
        return $this->builded;
    }
}