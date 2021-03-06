<?php

class Column
{
    var $name;
    var $displayName;
    var $value;
    var $type;
    var $selectOptions;


    public static function createEditColumn($name, $displayName, $type, $value)
    {
        return new Column($name, $displayName, $value, $type, null);
    }

    public static function simpleDisplayColumn($name, $displayName)
    {
        return new Column($name, $displayName, null, 'text', null);
    }

    public static function valuedColumn($name, $value, $type)
    {
        return new Column($name, null, $value, $type, null);
    }

    public function __construct($name, $displayName, $value, $fieldType, $selectOptions)
    {
        $this->name = $name;
        $this->displayName = $displayName;
        $this->value = $value;
        $this->type = $fieldType;
        $this->selectOptions = $selectOptions;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSelectOptions()
    {
        return $this->selectOptions;
    }

    function __toString()
    {
        return $this->name . ' ' . $this->displayName;
    }
}