<?php

namespace Webcore\Generator\Utils;

use Webcore\Generator\Common\GeneratorField;

class GeneratorFieldsInputUtil
{
    public static function validateFieldInput($fieldInputStr)
    {
        $fieldInputs = explode(' ', $fieldInputStr);

        if (count($fieldInputs) < 2) {
            return false;
        }

        return true;
    }

    /**
     * @param $fieldInput
     * @param $validations
     *
     * @return GeneratorField
     */
    public static function processFieldInput($fieldInput, $validations)
    {
        /*
         * Field Input Format: field_name <space> db_type <space> html_type(optional) <space> options(optional)
         * Options are to skip the field from certain criteria like searchable, fillable, not in form, not in index
         * and nullable field in DB table // added by dandisy
         * Searchable (s), Fillable (f), In Form (if), In Index (ii)
         * Nullable field (n) // added by dandisy
         * Sample Field Inputs
         *
         * title string text
         * body text textarea
         * name string,20 text
         * post_id integer:unsigned:nullable
         * post_id integer:unsigned:nullable:foreign,posts,id
         * password string text if,ii,s - options will skip field from being added in form, in index and searchable
         */

        $fieldInputsArr = explode(' ', $fieldInput);

        $field = new GeneratorField();
        $field->name = $fieldInputsArr[0];
        if($fieldInputsArr[1] !== 'table') {
            $field->parseDBType($fieldInputsArr[1]);
        }
        // $field->parseDBType($fieldInputsArr[1]);

        if (count($fieldInputsArr) > 2) {
            $field->parseHtmlInput($fieldInputsArr[2]);
        }

        if (count($fieldInputsArr) > 3) {
            $field->parseOptions($fieldInputsArr[3]);
        }

        $field->validations = $validations;

        return $field;
    }

    public static function prepareKeyValueArrayStr($arr)
    {
        // added by dandisy
        if(array_key_exists('relation', $arr)) {
            $related = explode('=', $arr['relation']);

            return '$'.$related[0].'->pluck(\''.$related[1].'\', \''.$related[2].'\')';
        }

        // added by dandisy
        if(array_key_exists('component', $arr)) {
            // $components = array_map(function ($file) {
            //     $fileName = explode('.', $file);
            //     if(count($fileName) > 0) {
            //         return $fileName[0];
            //     }
            // }, Storage::disk('component')->allFiles());

            // $arr = $components;
            // $arr = array_combine($arr, $arr);
            return '$components';
        }

        // added by dandisy
        if(array_key_exists('theme', $arr)) {
            return '$themes';
        }

        // added by dandisy
        if(array_key_exists('model', $arr)) {
            return '$models';
        }

        $arrStr = '[';
        foreach ($arr as $key => $item) {
            $arrStr .= "'$item' => '$key', ";
        }

        $arrStr = substr($arrStr, 0, strlen($arrStr) - 2);

        $arrStr .= ']';

        return $arrStr;
    }

    public static function prepareValuesArrayStr($arr)
    {
        $arrStr = '[';
        foreach ($arr as $item) {
            $arrStr .= "'$item', ";
        }

        $arrStr = substr($arrStr, 0, strlen($arrStr) - 2);

        $arrStr .= ']';

        return $arrStr;
    }

    public static function prepareKeyValueArrFromLabelValueStr($values)
    {
        $arr = [];

        foreach ($values as $value) {
            $labelValue = explode(':', $value);

            if (count($labelValue) > 1) {
                $arr[$labelValue[0]] = $labelValue[1];
            } else {
                $arr[$labelValue[0]] = $labelValue[0];
            }
        }

        return $arr;
    }
}
