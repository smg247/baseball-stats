<?php


abstract class Action
{
    public $mysqli;
    public $id;
    public $tableName;

    public function __construct($tableName, $id)
    {
        $this->tableName = $tableName;
        $this->id = $id;

        $properties = include 'properties.php';
        $this->mysqli = new mysqli('stepheng.sgedu.site', $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');
    }

    abstract function execute();
}


class DeleteAction extends Action
{

    function execute()
    {
        $query_string = "delete from $this->tableName where id = $this->id";
        $this->mysqli->query($query_string);
    }
}