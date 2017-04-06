<?php
include 'column.php';

abstract class HtmlTemplater
{

    var $baseUrl;
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
        $this->baseUrl = $properties['base'];
        $this->mysqli = new mysqli($properties['db_host'], $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');
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

        return $this->mysqli->query($query_string);
    }


    abstract function execute();
}


class ReadTemplater extends HtmlTemplater
{

    var $allowDelete;
    var $allowEdit;
    var $createEditUrl;
    var $detailLink;
    var $referrer;
    var $createActionName;


    public function __construct($tableName, $columns, $id, $selectBy, $allowDelete, $allowEdit, $CreateEditUrl, $detailLink, $referrer, $createActionName)
    {
        parent::__construct($tableName, $columns, $id, $selectBy);
        $this->allowDelete = $allowDelete;
        $this->allowEdit = $allowEdit;
        $this->createEditUrl = $CreateEditUrl;
        $this->detailLink = $detailLink;
        $this->referrer = $referrer;
        $this->createActionName = $createActionName;
    }


    function execute()
    {
        $output = '';
        if ($this->createEditUrl != null && $this->createActionName != null) {
            $output .= "<h4><a href='$this->createEditUrl'>Create $this->createActionName</a></h4>";
        }
        $output .= $this->display_all_records($this->find_records());

        return $output;
    }


    function display_all_records($result)
    {

        $output = $this->start_table();

        //The header
        $output .= $this->display_header();

        //The values
        if ($result->num_rows > 0) {
            while ($record = $result->fetch_assoc()) {
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

        $values = array();
        $counter = 0;
        foreach ($record as &$value) {
            if ($counter + 1 == count($record)) { // This is the id, we don't want to display it
                $current_id = $value;
            } else {
                $output .= "<td>$value</td>";
            }
            $values[$counter] = $value;

            $counter++;
        }

        //The Actions
        if ($this->detailLink != null) {
            $output .= "<td><a href='/$this->baseUrl$this->detailLink?id=$current_id'>details</a></td>";
        }
        if ($this->allowDelete) {
            $href = $this->baseUrl . '/delete-record.php?table=' . "$this->tableName&id=$current_id&referrer=$this->referrer";
            $output .= "<td><a href='$href'>delete</a></td>";
        }
        if ($this->allowEdit) {
            $params = '';
            $counter = 0;
            foreach ($this->columns as &$column) {
                $name = $column->getName();
                $params .= "&$name=$values[$counter]";
                $counter++;
            }
            $output .= "<td><a href='/$this->baseUrl$this->createEditUrl?is-edit=true&table=$this->tableName&id=$current_id$params'>edit</td>";
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


class CreateEditTemplater extends HtmlTemplater {

    var $redirectUrl;
    var $isEdit;

    public function __construct($tableName, $columns, $id, $redirectUrl, $isEdit)
    {
        parent::__construct($tableName, $columns, $id, null);
        $this->redirectUrl = $redirectUrl;
        $this->isEdit = $isEdit;
    }


    function execute()
    {
        $formAction = 'create-edit-record.php';
        $output = "<div class='row'><form class='form' method='get' action='$formAction'></div>";

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
        $value = $column->getValue();

        return "<label for='$name'>$displayName</label><input class='form-control' type='$fieldType' name='$name' id='$name' value='$value' />";
    }

    function create_hidden_fields()
    {
        $column_names = '';
        $column_types = '';
        foreach ($this->columns as &$column) {
            if ($column == end($this->columns)) {
                $column_names .= $column->getName();
                $column_types .= $column->getType();
            } else {
                $column_names .= $column->getName() . ',';
                $column_types .= $column->getType() . ',';
            }
        }

        $output = "<input type='hidden' name='table' value='$this->tableName'/>";
        $output .= "<input type='hidden' name='columns' value='$column_names'/>";
        $output .= "<input type='hidden' name='column-types' value='$column_types'/>";
        $output .= "<input type='hidden' name='redirectUrl' value='$this->redirectUrl'/>";
        $output .= "<input type='hidden' name='is-edit' value='$this->isEdit'/>";
        $output .= "<input type='hidden' name='id' value='$this->id'/>";

        return $output;
    }

}