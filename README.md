# Webcore Laravel Generator

Laravel Generator with Additional Form Builder

    - Date Time Picker (htmltype = date-picker, time-picker or datetime-picker)
    - Select2 (all select input will be select2, for multiple use htmltype = multi-select)
    - Two side Multiple Select (htmltype = two-side-select)
    - HTML Text Editor (htmltype = text-editor)
    - File Manager (htmltype = file-manager or files-manager)
    - Nullable field in migration (console option = n, or in json file using dbNullable = true)
    - Logged fields : created_by and updated_by (console option = --logs)
    - Related Dropdown (in console, use --relations option) : add view model in controller, and relational input form in view (htmltype = select,relation:{view-model}={field-to-show}={field-as-value})
    - Related Form (in console, use --relations option) : add view model in controller, and relational input form in view (dbtype = table and htmltype = related-form,related-field1,related-field2,related-field3,...)
    - Component and theme directory reader generator (htmltype = select,component or select,theme)
    - Model directory reader generator (htmltype = select,model)

For https://github.com/dandisy/webcore

### Installation

    composer require dandisy/laravel-generator
    php artisan vendor:publish --provider="Webcore\Generator\WebcoreGeneratorServiceProvider"
    php artisan migrate
    if needed copy & paste all files related for roles, permissions and settings, and dxdatagrid, and exceptions handler from samples folder in this package

    composer require dandisy/adminlte-templates
    php artisan vendor:publish --provider="Webcore\AdminLTETemplates\AdminLTETemplatesServiceProvider" --tag="public"
    php artisan vendor:publish --provider="Webcore\AdminLTETemplates\AdminLTETemplatesServiceProvider" --tag="views"

    if using laravel >= 8, add route namespace in RouteServiceProvider
    Route::middleware('web')
        ->namespace('App\Http\Controllers')
        ...

(optional)

    https://github.com/yajra/laravel-datatables
    composer require yajra/laravel-datatables-oracle:"~9.0"
    php artisan vendor:publish --provider="Yajra\DataTables\DataTablesServiceProvider"

    https://github.com/yajra/laravel-datatables-buttons
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
