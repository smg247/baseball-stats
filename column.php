<?php

class Column
{
    var $name;
    var $displayName;
    var $value;
    var $type;
    var $valuesQuery;

    public static function complexDisplayColumn($name, $displayName, $type)
    {
        return new Column($name, $displayName, null, $type, null);
    }

    public static function complexValuedDisplayColumn($name, $displayName, $type, $value)
    {
        return new Column($name, $displayName, $value, $type, null);
    }

    public static function simpleDisplayColumn($name, $displayName)
    {
        return new Column($name, $displayName, null, 'text', null);
    }

    public static function simpleValuedColumn($name, $value)
    {
        return new Column($name, null, $value, 'text', null);
    }

    public static function complexValuedColumn($name, $value, $type)
    {
        return new Column($name, null, $value, $type, null);
    }

    public function __construct($name, $displayName, $value, $fieldType, $valuesQuery)
    {
        $this->name = $name;
        $this->displayName = $displayName;
        $this->value = $value;
        $this->type = $fieldType;
        $this->valuesQuery = $valuesQuery;
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

    public function getValuesQuery()
    {
        return $this->valuesQuery;
    }

    function __toString()
    {
        return $this->name . ' ' . $this->displayName;
    }
}