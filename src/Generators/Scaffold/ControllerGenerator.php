<?php

namespace Webcore\Generator\Generators\Scaffold;

use Webcore\Generator\Common\CommandData;
use Webcore\Generator\Generators\BaseGenerator;
use Webcore\Generator\Utils\FileUtil;

class ControllerGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $templateType;

    /** @var string */
    private $fileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathController;
        $this->templateType = config('webcore.laravel_generator.templates', 'core-templates');
        $this->fileName = $this->commandData->modelName.'Controller.php';
    }

    public function generate()
    {
        // added by dandisy
        $components = FALSE;
        $themes = FALSE;
        $models = FALSE;        
        $this->commandData->addDynamicVariable('$RELATION_QUERY$', '');
        $this->commandData->addDynamicVariable('$RELATION_VIEW$', '');
        $this->commandData->addDynamicVariable('$COMPONENT_QUERY$', '');
        $this->commandData->addDynamicVariable('$COMPONENT_VIEW$', '');
        $this->commandData->addDynamicVariable('$THEME_QUERY$', '');
        $this->commandData->addDynamicVariable('$THEME_VIEW$', '');
        $this->commandData->addDynamicVariable('$MODEL_QUERY$', '');
        $this->commandData->addDynamicVariable('$MODEL_VIEW$', '');
        if($this->commandData->relations) {
            $relationQuery = '';
            $relationView = '';

            foreach($this->commandData->relations as $relation) {
                $relationQuery .= "$".strtolower($relation->inputs[0])." = \App\Models\\".$relation->inputs[0]."::all();".infy_nl_tab(1,2);
                $relationView .= infy_nl_tab(1,3)."->with('".strtolower($relation->inputs[0])."', $".strtolower($relation->inputs[0]).")";
            }

            $this->commandData->addDynamicVariable('$RELATION_QUERY$', $relationQuery);
            $this->commandData->addDynamicVariable('$RELATION_VIEW$', $relationView);
        }

        // added by dandisy
        foreach($this->commandData->fields as $field) {
            if ('select,component' == $field->htmlInput) {
                $components = TRUE;
            }
        }
        foreach($this->commandData->fields as $field) {
            if ('select,theme' == $field->htmlInput) {
                $themes = TRUE;
            }
        }
        foreach($this->commandData->fields as $field) {
            if ('select,model' == $field->htmlInput) {
                $models = TRUE;
            }
        }
        if($components) {
            $componentQuery = "\n".'$components = array_map(function ($file) {'.infy_nl_tab(1,3).
                '$fileName = explode(\'.\', $file);'.infy_nl_tab(1,3).
                'if(count($fileName) > 0) {'.infy_nl_tab(1,4).
                    'return $fileName[0];'.infy_nl_tab(1,3).
                "}".infy_nl_tab(1,2).
            "}, Storage::disk('component')->allFiles());".infy_nl_tab(2,2).
            '$components = array_combine($components, $components);';
            $this->commandData->addDynamicVariable('$COMPONENT_QUERY$', $componentQuery);
            $this->commandData->addDynamicVariable('$COMPONENT_VIEW$', infy_nl_tab(1,3).'->with(\'components\', $components)');
        }
        if($themes) {
            $themeQuery = "\n".'$themes = array_map(function ($file) {'.infy_nl_tab(1,3).
                '$fileName = explode(\'.\', $file);'.infy_nl_tab(1,3).
                'if(count($fileName) > 0) {'.infy_nl_tab(1,4).
                    'return $fileName[0];'.infy_nl_tab(1,3).
                "}".infy_nl_tab(1,2).
            "}, Storage::disk('theme')->allFiles());".infy_nl_tab(2,2).
            '$themes = array_combine($themes, $themes);';
            $this->commandData->addDynamicVariable('$THEME_QUERY$', $themeQuery);
            $this->commandData->addDynamicVariable('$THEME_VIEW$', infy_nl_tab(1,3).'->with(\'themes\', $themes)');
        }
        if($models) {
            $modelQuery = "\n".'$models = array_map(function ($file) {'.infy_nl_tab(1,3).
                '$fileName = explode(\'.\', $file);'.infy_nl_tab(1,3).
                'if(count($fileName) > 0) {'.infy_nl_tab(1,4).
                    'return $fileName[0];'.infy_nl_tab(1,3).
                "}".infy_nl_tab(1,2).
            "}, Storage::disk('model')->allFiles());".infy_nl_tab(2,2).
            '$models = array_combine($models, $models);';
            $this->commandData->addDynamicVariable('$MODEL_QUERY$', $modelQuery);
            $this->commandData->addDynamicVariable('$MODEL_VIEW$', infy_nl_tab(1,3).'->with(\'models\', $models)');
        }

        if ($this->commandData->getAddOn('datatables')) {
            // edited by dandisy
            // $templateData = get_template('scaffold.controller.datatable_controller', 'laravel-generator');
            if($this->commandData->getOption('logs')) {
                $templateData = get_template('scaffold.controller.logged_datatable_controller', 'laravel-generator');
            } else {
                $templateData = get_template('scaffold.controller.datatable_controller', 'laravel-generator');
            }

            $this->generateDataTable();
        } else {
            // edited by dandisy
            // $templateData = get_template('scaffold.controller.controller', 'laravel-generator');
            if($this->commandData->getOption('logs')) {
                if($this->commandData->getOption('queryToAPI')) {
                    $templateData = get_template('scaffold.controller.query_to_api_logged_controller', 'laravel-generator');
                } else {
                    $templateData = get_template('scaffold.controller.logged_controller', 'laravel-generator');
                }
            } else {
                if($this->commandData->getOption('queryToAPI')) {
                    $templateData = get_template('scaffold.controller.query_to_api_controller', 'laravel-generator');
                } else {
                    $templateData = get_template('scaffold.controller.controller', 'laravel-generator');
                }
            }

            $paginate = $this->commandData->getOption('paginate');

            if ($paginate) {
                $templateData = str_replace('$RENDER_TYPE$', 'paginate('.$paginate.')', $templateData);
            } else {
                $templateData = str_replace('$RENDER_TYPE$', 'all()', $templateData);
            }
        }

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nController created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    private function generateDataTable()
    {
        $templateData = get_template('scaffold.datatable', 'laravel-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        $headerFieldTemplate = get_template('scaffold.views.datatable_column', $this->templateType);

        $headerFields = [];

        foreach ($this->commandData->fields as $field) {
            if (!$field->inIndex) {
                continue;
            }
            $headerFields[] = $fieldTemplate = fill_template_with_field_data(
                $this->commandData->dynamicVars,
                $this->commandData->fieldNamesMapping,
                $headerFieldTemplate,
                $field
            );
        }

        $path = $this->commandData->config->pathDataTables;

        $fileName = $this->commandData->modelName.'DataTable.php';

        $fields = implode(','.infy_nl_tab(1, 3), $headerFields);

        $templateData = str_replace('$DATATABLE_COLUMNS$', $fields, $templateData);

        FileUtil::createFile($path, $fileName, $templateData);

        $this->commandData->commandComment("\nDataTable created: ");
        $this->commandData->commandInfo($fileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('Controller file deleted: '.$this->fileName);
        }

        if ($this->commandData->getAddOn('datatables')) {
            if ($this->rollbackFile($this->commandData->config->pathDataTables, $this->commandData->modelName.'DataTable.php')) {
                $this->commandData->commandComment('DataTable file deleted: '.$this->fileName);
            }
        }
    }
}
