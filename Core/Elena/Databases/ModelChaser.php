<?php
/*
* ModelChaser Class
* 2021/12/22 Made by mary(Tajiri-PekoPekora-April 3rd).
* This class is providing model system.
* For example, fetch data from sql based on model class name, fetched data to object, and so on.
*/
namespace Clsk\Elena\Databases;

require dirname(__FILE__)."/../../../vendor/autoload.php";

use Clsk\Elena\Databases\QueryBuilder;

class ModelChaser
{
    private $result;
    private $qb;
    // Table chaser is tracks the table you want to work with from the model class name you decide or the table name you specify in the model.
    private function TableChaser()
    {
        // Here, the caller is searched, and if a table name is specified separately, that is given priority and used.
        $table = get_class($this);
        $table = explode("\\", $table);
        if(isset($this->table)){
            $table = $this->table;
        }else{
            $table = $table[3];
        }
        return $table;
    }

    public function all()
    {
        $get = QueryBuilder::Table($this->TableChaser());
        $this->result = (object)$get->Fetch()[0];
        $this->qb = $get;
    }

    // get from primarykey
    public function find($PrimaryKeyIs)
    {
        //TODO
    }

    public function where($terms, $is)
    {
        $get = $this->qb->Fetch(True)[0];
    }

    public function first()
    {
        //TODO
    }
}