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
        $this->mysqli = new mysqli($properties['db_host'], $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');
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

    function format_value($value, $type)
    {
        if ($type == 'number') {
            return $value;
        } else if ($type == 'year') {
            return "'$value-01-01'";
        } else {
            return "'$value'";
        }
    }
}


class CreateAction extends ConstructiveAction
{

    function execute()
    {
        $column_names = '';
        $column_values = '';
        foreach ($this->columns as &$column) {
            $type = $column->getType();
            $value = $column->getValue();

            $column_names .= $column->getName();
            $column_values .= $this->format_value($value, $type);

            if ($column != end($this->columns)) {
                $column_names .=  ', ';
                $column_values .=  ', ';
            }
        }

        $query_string = "insert into $this->tableName ($column_names) values ($column_values);";
        error_log($query_string); // Not really an error, but this is an easy way to see the query in the terminal

        $this->mysqli->query($query_string);
        error_log($this->mysqli->error);
    }

    function get_inserted_id()
    {
        return $this->mysqli->insert_id;
    }

}


class EditAction extends ConstructiveAction
{

    function execute()
    {
        $query_string = "update $this->tableName set ";
        $counter = 1;
        foreach ($this->columns as &$column) {
            $type = $column->getType();
            $value = $column->getValue();
            $name = $column->getName();

            $formatted_value = $this->format_value($value, $type);
            $query_string .= "$name = $formatted_value";

            if ($counter < count($this->columns)) {
                $query_string .= ', ';
            }
            $counter++;
        }

        $query_string .= " where id = $this->id;";
        error_log($query_string); // Not really an error, but this is an easy way to see the query in the terminal

        $this->mysqli->query($query_string);
        error_log($this->mysqli->error);
    }
}