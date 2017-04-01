<?php


abstract class HtmlTemplater {

    var $mysqli;
    var $tableName;
    var $id;
    var $selectBy;
    var $column_names; // If no id is provided it will be assumed we are acting on all values


    public function __construct($tableName, $column_names, $id, $selectBy)
    {
        $this->tableName = $tableName;
        $this->id = $id;
        $this->column_names = $column_names;
        $this->selectBy = $selectBy;

        $properties = include 'properties.php';
        $this->mysqli = new mysqli('stepheng.sgedu.site', $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');
    }

    function find_records() {
        $query_string = 'select ';
        foreach ($this->column_names as &$column_name) {
            if ($column_name == end($this->column_names)) {
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


class ReadTemplater extends HtmlTemplater {

    var $allowDelete;
    var $allowEdit;
    var $detailLink;

    /**
     * ReadTemplater constructor.
     * @param $allowDelete
     */
    public function __construct($tableName, $column_names, $id, $selectBy, $allowDelete, $allowEdit, $detailLink)
    {
        parent::__construct($tableName, $column_names, $id, $selectBy);
        $this->allowDelete = $allowDelete;
        $this->allowEdit = $allowEdit;
        $this->detailLink = $detailLink;
    }


    function execute()
    {
        return $this->display_all_records($this->find_records());
    }


    function display_all_records($records)
    {

        $output = $this->start_table();

        //The header
        $output .= $this->display_header();

        //The values
        foreach ($records as &$record) {
            $output .= $this->display_record($record);
        }

        $output .= $this->end_table();

        return $output;
    }

    function display_record($record) {
        $current_id = -1;
        $output = '<tr>';
        foreach ($record as &$value) {
            if ($value == end($record)) { // This is the id, we don't want to display it
                $current_id = $value;
            } else {
                $output .= "<td>$value</td>";
            }
        }

        //The Actions
        if ($this->detailLink != null) {
            $output .= "<td><a href='/$this->detailLink?id=$current_id'>details</a></td>";
        }
        if ($this->allowDelete) {
            $output .= "<td><a href='/delete-record?table=$this->tableName&id=$current_id'>delete</a></td>";
        }
        if ($this->allowDelete) {
            $output .= "<td><a href='/edit-record?table=$this->tableName&id=$current_id'>edit</td>";
        }

        $output .= '</tr>';

        return $output;
    }

    function start_table() {
        return '<table class="table">';
    }

    function end_table() {
        return '</table>';
    }

    function display_header() {
        $output = '<tr>';
        foreach ($this->column_names as &$column_name) {
            $output .= "<th>$column_name</th>";
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