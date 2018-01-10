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
        // add by dandisy
        $this->commandData->addDynamicVariable('$RELATION_QUERY$', '');
        $this->commandData->addDynamicVariable('$RELATION_VIEW$', '');
        $this->commandData->addDynamicVariable('$RELATION_QUERY$', '$'.strtolower($this->commandData->relations[0]->inputs[0]).' = \App\Models\\'.$this->commandData->relations[0]->inputs[0].'::all();');
        $this->commandData->addDynamicVariable('$RELATION_VIEW$', '->with(\''.strtolower($this->commandData->relations[0]->inputs[0]).'\', $'.strtolower($this->commandData->relations[0]->inputs[0]).')');

        if ($this->commandData->getAddOn('datatables')) {
            // edit by dandisy
            //$templateData = get_template('scaffold.controller.datatable_controller', 'laravel-generator');
            if($this->commandData->getOption('logs')) {
                $templateData = get_template('scaffold.controller.logged_datatable_controller', 'laravel-generator');
            } else {
                $templateData = get_template('scaffold.controller.datatable_controller', 'laravel-generator');
            }

            $this->generateDataTable();
        } else {
            // edit by dandisy
            //$templateData = get_template('scaffold.controller.controller', 'laravel-generator');
            if($this->commandData->getOption('logs')) {
                $templateData = get_template('scaffold.controller.logged_controller', 'laravel-generator');
            } else {
                $templateData = get_template('scaffold.controller.controller', 'laravel-generator');
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
