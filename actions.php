<?php
include 'column.php';

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


abstract class ConstructiveAction extends Action
{

    var $columns;

    public function __construct($columns, $tableName, $id)
    {
        parent::__construct($tableName, $id);
        $this->columns = $columns;
    }
}


class CreateAction extends ConstructiveAction
{
    function execute()
    {
        $column_names = '';
        $column_values = '';
        foreach ($this->columns as &$column) {
             $column_names .= $column->getName();
             $column_values .= '\'' . $column->getValue() . '\'';

             if ($column != end($this->columns)) {
                $column_names .=  ', ';
                $column_values .=  ', ';
            }
        }

        $query_string = "insert into $this->tableName ($column_names) values ($column_values);";
        $this->mysqli->query($query_string);
        return $this->mysqli->insert_id;
    }

}