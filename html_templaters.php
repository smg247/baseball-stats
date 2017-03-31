<?php


abstract class HtmlTemplater {

    var $mysqli;
    var $tableName;
    var $id;
    var $column_names; // If no id is provided it will be assumed we are acting on all values


    public function __construct($tableName, $column_names, $id)
    {
        $this->tableName = $tableName;
        $this->id = $id;
        $this->column_names = $column_names;

        $properties = include 'properties.php';
        $this->mysqli = new mysqli('stepheng.sgedu.site', $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');
    }

    function find_records() {
        $query_string = 'select ';
        foreach ($this->column_names as &$column_name) {
            if ($column_name == end($this->column_names)) {
                $query_string .= "$column_name ";
            } else {
                $query_string .= "$column_name, ";
            }
        }
        $query_string .= "from $this->tableName";

        if ($this->id != null) {
            $query_string .= " where id = $this->id";
        }
        $query_string .= ';';

        return $this->mysqli->query($query_string)->fetch_all();
    }


    abstract function execute();
}


class ReadTemplater extends HtmlTemplater {

    var $allowDelete;
    var $allowEdit;

    /**
     * ReadTemplater constructor.
     * @param $allowDelete
     */
    public function __construct($tableName, $column_names, $id, $allowDelete, $allowEdit)
    {
        parent::__construct($tableName, $column_names, $id);
        $this->allowDelete = $allowDelete;
        $this->allowEdit = $allowEdit;
    }


    function execute()
    {
        if ($this->id == null) {
            return $this->display_all_records($this->find_records());
        } else {
            //TODO: do I even want to be able to do this?
        }

        return null;
    }


    function display_all_records($records)
    {

        $output = '<table class="table"><tr>';

        //The header
        foreach ($this->column_names as &$column_name) {
            $output .= "<th>$column_name</th>";
        }

        if ($this->allowDelete) {
            $output .= '<th></th>';
        }
        if ($this->allowEdit) {
            $output .= '<th></th>';
        }

        $output .= '</tr>';

        //The values
        foreach ($records as &$record) {
            $output .= '<tr>';
            foreach ($record as &$value) {
                $output .= "<td>$value</td>";
            }

            //The Actions
            if ($this->allowDelete) {
                $output .= "<td>delete</td>";
            }
            if ($this->allowDelete) {
                $output .= "<td>edit</td>";
            }

            $output .= '</tr>';
        }



        $output .= '</table>';

        return $output;
    }

    function display_record($record) {

    }

}