<?php
include 'column.php';

abstract class HtmlTemplater
{

    var $mysqli;
    var $tableName;
    var $id; // If no id is provided it will be assumed we are acting on all values
    var $selectBy;
    var $columns;


    public function __construct($tableName, $columns, $id, $selectBy)
    {
        $this->tableName = $tableName;
        $this->id = $id;
        $this->columns = $columns;
        $this->selectBy = $selectBy;

        $properties = include 'properties.php';
        $this->mysqli = new mysqli('stepheng.sgedu.site', $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');
    }

    function find_records()
    {
        $query_string = 'select ';
        foreach ($this->columns as &$column) {
            $column_name = $column->getName();
            if ($column == end($this->columns)) {
                $query_string .= "$column_name, id "; //Always return the id as the last column
            } else {
                $query_string .= "$column_name, ";
            }
        }
        $query_string .= "from $this->tableName";

        if ($this->id != null) {
            $query_string .= " where $this->selectBy = $this->id";
        }
        $query_string .= ';';

        return $this->mysqli->query($query_string)->fetch_all();
    }


    abstract function execute();
}


class ReadTemplater extends HtmlTemplater
{

    var $allowDelete;
    var $allowEdit;
    var $creationUrl;
    var $detailLink;
    var $referrer;
    var $createActionName;


    public function __construct($tableName, $columns, $id, $selectBy, $allowDelete, $allowEdit, $creationUrl, $detailLink, $referrer, $createActionName)
    {
        parent::__construct($tableName, $columns, $id, $selectBy);
        $this->allowDelete = $allowDelete;
        $this->allowEdit = $allowEdit;
        $this->creationUrl = $creationUrl;
        $this->detailLink = $detailLink;
        $this->referrer = $referrer;
        $this->createActionName = $createActionName;
    }


    function execute()
    {
        $output = '';
        if ($this->creationUrl != null) {
            $output .= "<h4><a href='$this->creationUrl'>Create $this->createActionName</a></h4>";
        }
        $output .= $this->display_all_records($this->find_records());

        return $output;
    }


    function display_all_records($records)
    {

        $output = $this->start_table();

        //The header
        $output .= $this->display_header();

        //The values
        if (sizeof($records) > 0) {
            foreach ($records as &$record) {
                $output .= $this->display_record($record);
            }
        } else {
            $output .= '<tr><td>No results available.</td></tr>';
        }

        $output .= $this->end_table();

        return $output;
    }

    function display_record($record)
    {
        $current_id = -1;
        $output = '<tr>';

        $counter = 1;
        foreach ($record as &$value) {
            if ($counter == count($record)) { // This is the id, we don't want to display it
                $current_id = $value;
            } else {
                $output .= "<td>$value</td>";
            }

            $counter++;
        }

        //The Actions
        if ($this->detailLink != null) {
            $output .= "<td><a href='/$this->detailLink?id=$current_id'>details</a></td>";
        }
        if ($this->allowDelete) {
            $output .= "<td><a href='/delete-record.php?table=$this->tableName&id=$current_id&referrer=$this->referrer'>delete</a></td>";
        }
        if ($this->allowEdit) {
            $output .= "<td><a href='/edit-record.php?table=$this->tableName&id=$current_id'>edit</td>";
        }

        $output .= '</tr>';

        return $output;
    }

    function start_table()
    {
        return '<table class="table">';
    }

    function end_table()
    {
        return '</table>';
    }

    function display_header()
    {
        $output = '<tr>';
        foreach ($this->columns as &$column) {
            $output .= '<th>' . $column->getDisplayName() . '</th>';
        }

        if ($this->detailLink != null) {
            $output .= '<th></th>';
        }
        if ($this->allowDelete) {
            $output .= '<th></th>';
        }
        if ($this->allowEdit) {
            $output .= '<th></th>';
        }

        $output .= '</tr>';


        return $output;
    }

}


class CreateTemplater extends HtmlTemplater {

    var $redirectUrl;

    public function __construct($tableName, $columns, $redirectUrl)
    {
        parent::__construct($tableName, $columns, null, null);
        $this->redirectUrl = $redirectUrl;
    }


    function execute()
    {
        $output = "<div class='row'><form class='form' method='get' action='/create-record.php'></div>"; //TODO: this is going to have to be smarter

        foreach ($this->columns as &$column) {
            $output .= $this->create_field($column);
        }

        $output .= $this->create_hidden_fields();

        $output .= '<br><input class="btn" type="submit" value="Submit">';
        $output .= '</form>';

        return $output;
    }

    function create_field($column)
    {
        $fieldType = $column->getType();
        $name = $column->getName();
        $displayName = $column->getDisplayName();

        return "<label for='$name'>$displayName</label><input class='form-control' type='$fieldType' name='$name' id='$name' />";
    }

    function create_hidden_fields()
    {
        $column_names = '';
        foreach ($this->columns as &$column) {
            if ($column == end($this->columns)) {
                $column_names .= $column->getName();
            } else {
                $column_names .= $column->getName() . ',';
            }
        }

        $output = "<input type='hidden' name='table' value='$this->tableName'/>";
        $output .= "<input type='hidden' name='columns' value='$column_names'/>";
        $output .= "<input type='hidden' name='redirectUrl' value='$this->redirectUrl'/>";
        return $output;
    }

}