# Webcore Laravel Generator

Laravel Generator with Additional Form Builder

    - Date Time Picker (htmltype = date-picker, time-picker or datetime-picker)
    - Select2 (all select input will be select2, for multiple use htmltype = multi-select)
    - Two side Multiple Select (htmltype = two-side-select)
    - HTML Text Editor (htmltype = text-editor)
    - File Manager (htmltype = file-manager or files-manager)
    - Nullable field in migration (console option = n, or in json file using dbNullable = true)
    - Logged fields : created_by and updated_by (console option = --logs)
    - Relational support : add model view in controller, related function in model, and related data in select2 form filed (htmltype = select,model-view=show-field=value-field)
    - Component and theme reader generator (htmltype = select,component or select,theme)

For https://github.com/dandisy/webcore

### Installation

    composer require dandisy/laravel-generator
    php artisan vendor:publish --provider="Webcore\Generator\WebcoreGeneratorServiceProvider"

    composer require dandisy/adminlte-templates
    php artisan vendor:publish --provider="Webcore\AdminLTETemplates\AdminLTETemplatesServiceProvider" --tag="public"
    php artisan vendor:publish --provider="Webcore\AdminLTETemplates\AdminLTETemplatesServiceProvider" --tag="views"

(optional)

    composer require yajra/laravel-datatables-oracle:"~9.0"
    php artisan vendor:publish --provider="Yajra\DataTables\DataTablesServiceProvider"

    composer require yajra/laravel-datatables-buttons:^4.0
    php artisan vendor:publish --tag=datatables-buttons --force

    ---

    jlapp/swaggervel

    league/glide-laravel

    dandisy/filemanager
    php artisan vendor:publish --provider="Webcore\FileManager\FileManagerServiceProvider"

### Notes

This package based on https://github.com/InfyOmLabs/laravel-generator

Documentation is located [here](http://labs.infyom.com/laravelgenerator)

for more generic if any artisan command use generate instead infyom
