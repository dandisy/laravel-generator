<?php

namespace Webcore\Generator\Utils;

use Webcore\Generator\Common\GeneratorField;

class HTMLFieldGenerator
{
    public static function generateHTML(GeneratorField $field, $templateType)
    {
        $fieldTemplate = '';

        switch ($field->htmlType) {
            case 'text':
            case 'textarea':

            // added by dandisy
            case 'text-editor':

            case 'date':

            // added by dandisy
            case 'date-picker':
            case 'time-picker':
            case 'datetime-picker':

            case 'file':

            // added by dandisy
            case 'file-manager':
            case 'files-manager':

            case 'email':
            case 'password':
                $fieldTemplate = get_template('scaffold.fields.'.$field->htmlType, $templateType);
                break;
            case 'number':
                $fieldTemplate = get_template('scaffold.fields.'.$field->htmlType, $templateType);
                break;
            case 'select':

            // added by dandisy
            case 'multi-select':
                $fieldTemplate = get_template('scaffold.fields.'.$field->htmlType, $templateType);
                $optionLabels = GeneratorFieldsInputUtil::prepareKeyValueArrFromLabelValueStr($field->htmlValues);

                $fieldTemplate = str_replace(
                    '$INPUT_ARR$',
                    GeneratorFieldsInputUtil::prepareKeyValueArrayStr($optionLabels),
                    $fieldTemplate
                );
                break;

            // added by dandisy
            case 'two-side-select':

            case 'enum':
                $fieldTemplate = get_template('scaffold.fields.select', $templateType);
                $radioLabels = GeneratorFieldsInputUtil::prepareKeyValueArrFromLabelValueStr($field->htmlValues);

                $fieldTemplate = str_replace(
                    '$INPUT_ARR$',
                    GeneratorFieldsInputUtil::prepareKeyValueArrayStr($radioLabels),
                    $fieldTemplate
                );
                break;
            case 'checkbox':
                $fieldTemplate = get_template('scaffold.fields.checkbox', $templateType);
                if (count($field->htmlValues) > 0) {
                    $checkboxValue = $field->htmlValues[0];
                } else {
                    $checkboxValue = 1;
                }
                $fieldTemplate = str_replace('$CHECKBOX_VALUE$', $checkboxValue, $fieldTemplate);
                break;
            case 'radio':
                $fieldTemplate = get_template('scaffold.fields.radio_group', $templateType);
                $radioTemplate = get_template('scaffold.fields.radio', $templateType);

                $radioLabels = GeneratorFieldsInputUtil::prepareKeyValueArrFromLabelValueStr($field->htmlValues);

                $radioButtons = [];
                foreach ($radioLabels as $label => $value) {
                    $radioButtonTemplate = str_replace('$LABEL$', $label, $radioTemplate);
                    $radioButtonTemplate = str_replace('$VALUE$', $value, $radioButtonTemplate);
                    $radioButtons[] = $radioButtonTemplate;
                }
                $fieldTemplate = str_replace('$RADIO_BUTTONS$', implode("\n", $radioButtons), $fieldTemplate);
                break;

            // added by dandisy
            case 'related-form':
                $fieldTemplate = get_template('scaffold.fields.related-form', $templateType);

                $relatedName = $field->name; // in this context is related table name
                $relatedFields = $field->htmlValues; // in this context is fields in related table name

                $relatedInputsTitle = '';
                $relatedInputs = '';
                foreach ($relatedFields as $field) {
                    $relatedInputsTitle .= '<th>'.$field.'</th>';
                    $relatedInputs .= '<td class="form-group">';
                    $relatedInputs .= '{!! Form::text(\''.$relatedName.'['.$relatedName.'\'.$index.\']['.$field.']\', null, [\'class\' => \'form-control\']) !!}';
                    $relatedInputs .= '</td>';
                }                
                $fieldTemplate = str_replace('$INPUT_FORM_TITLE$', $relatedInputsTitle, $fieldTemplate);
                $fieldTemplate = str_replace('$INPUT_FORM$', $relatedInputs, $fieldTemplate);
                $fieldTemplate = str_replace('$INPUT_FIELDS$', implode(',',$relatedFields), $fieldTemplate);
                $fieldTemplate = str_replace('$RELATED_FORM_COLUMNS_COUNT$', count($relatedFields), $fieldTemplate);
                break;
        }

        return $fieldTemplate;
    }
}
