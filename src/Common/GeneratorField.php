<?php

namespace Webcore\Generator\Common;

use Illuminate\Support\Str;

class GeneratorField
{
    /** @var string */
    public $name;
    public $dbInput;
    public $htmlInput;
    public $htmlType;
    public $fieldType;

    /** @var array */
    public $htmlValues;

    /** @var string */
    public $migrationText;
    public $foreignKeyText;
    public $validations;

    /** @var bool */
    public $isSearchable = true;
    public $isFillable = true;
    public $isPrimary = false;
    public $inForm = true;
    public $inIndex = true;

    public $dbNullable = false; // added by dandisy

    public function parseDBType($dbInput)
    {
        // edited by dandisy
        if($dbInput !== 'table') {
            $this->dbInput = $dbInput;
            $this->prepareMigrationText();
        }
        // $this->dbInput = $dbInput;
        // $this->prepareMigrationText();
    }

    public function parseHtmlInput($htmlInput)
    {
        $this->htmlInput = $htmlInput;
        $this->htmlValues = [];

        if (empty($htmlInput)) {
            $this->htmlType = 'text';

            return;
        }

        $inputsArr = explode(',', $htmlInput);

        // edited by dandisy
        // $this->htmlType = array_shift($inputsArr);

        if (count($inputsArr) > 0) {
            $this->htmlType = array_shift($inputsArr); // added by dandisy
            $this->htmlValues = $inputsArr;
        }
    }

    public function parseOptions($options)
    {
        $options = strtolower($options);
        $optionsArr = explode(',', $options);
        if (in_array('s', $optionsArr)) {
            $this->isSearchable = false;
        }
        if (in_array('p', $optionsArr)) {
            // if field is primary key, then its not searchable, fillable, not in index & form
            $this->isPrimary = true;
            $this->isSearchable = false;
            $this->isFillable = false;
            $this->inForm = false;
            $this->inIndex = false;

            // add by dandisys
            $this->dbNullable = false;
        }
        if (in_array('f', $optionsArr)) {
            $this->isFillable = false;
        }
        if (in_array('if', $optionsArr)) {
            $this->inForm = false;
        }
        if (in_array('ii', $optionsArr)) {
            $this->inIndex = false;
        }

        // added by dandisy
        if (in_array('n', $optionsArr)) {
            $this->dbNullable = true;
        }
    }

    private function prepareMigrationText()
    {
        $inputsArr = explode(':', $this->dbInput);
        $this->migrationText = '$table->';

        $fieldTypeParams = explode(',', array_shift($inputsArr));
        $this->fieldType = array_shift($fieldTypeParams);
        $this->migrationText .= $this->fieldType."('".$this->name."'";

        if ($this->fieldType == 'enum') {
            $this->migrationText .= ', [';
            foreach ($fieldTypeParams as $param) {
                $this->migrationText .= "'".$param."',";
            }
            $this->migrationText = substr($this->migrationText, 0, strlen($this->migrationText) - 1);
            $this->migrationText .= ']';
        } else {
            foreach ($fieldTypeParams as $param) {
                $this->migrationText .= ', '.$param;
            }
        }

        $this->migrationText .= ')';

        //added by dandisy
        if($this->dbNullable) {
            $this->migrationText .= '->nullable()';
        }

        foreach ($inputsArr as $input) {
            $inputParams = explode(',', $input);
            $functionName = array_shift($inputParams);
            if ($functionName == 'foreign') {
                $foreignTable = array_shift($inputParams);
                $foreignField = array_shift($inputParams);
                $this->foreignKeyText .= "\$table->foreign('".$this->name."')->references('".$foreignField."')->on('".$foreignTable."');";
            } else {
                $this->migrationText .= '->'.$functionName;
                $this->migrationText .= '(';
                $this->migrationText .= implode(', ', $inputParams);
                $this->migrationText .= ')';
            }
        }

        $this->migrationText .= ';';
    }

    public static function parseFieldFromFile($fieldInput)
    {
        $field = new self();
        $field->name = $fieldInput['name'];
        $field->parseDBType($fieldInput['dbType']);
        $field->parseHtmlInput(isset($fieldInput['htmlType']) ? $fieldInput['htmlType'] : '');
        $field->validations = isset($fieldInput['validations']) ? $fieldInput['validations'] : '';
        $field->isSearchable = isset($fieldInput['searchable']) ? $fieldInput['searchable'] : false;
        $field->isFillable = isset($fieldInput['fillable']) ? $fieldInput['fillable'] : true;
        $field->isPrimary = isset($fieldInput['primary']) ? $fieldInput['primary'] : false;
        $field->inForm = isset($fieldInput['inForm']) ? $fieldInput['inForm'] : true;
        $field->inIndex = isset($fieldInput['inIndex']) ? $fieldInput['inIndex'] : true;

        // added by dandisy
        $field->dbNullable = isset($fieldInput['dbNullable']) ? $fieldInput['dbNullable'] : false;

        return $field;
    }

    public function __get($key)
    {
        if ($key == 'fieldTitle') {
            return Str::title(str_replace('_', ' ', $this->name));
        }

        return $this->$key;
    }
}
