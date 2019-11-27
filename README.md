AdminLTE templates for Webcore
==============================

For https://github.com/dandisy/webcore

This package based on https://github.com/InfyOmLabs/adminlte-templates

Installation steps are located [here](http://labs.infyom.com/laravelgenerator/docs/master/adminlte-templates)

Note :
for more generic if any artisan command use generate instead infyom

### Installation

    composer require dandisy/adminlte-templates

    php artisan vendor:publish --provider="Webcore\AdminLTETemplates\AdminLTETemplatesServiceProvider" --tag="public"

    php artisan vendor:publish --provider="Webcore\AdminLTETemplates\AdminLTETemplatesServiceProvider" --tag="views"

    or

    php artisan vendor:publish --provider="Webcore\AdminLTETemplates\AdminLTETemplatesServiceProvider" --tag="viewa-all"

### Usage (if this package is installed manually)

0. unzip adminlte-templates.zip to vendor/dandisy

1. add autoload classmap in composer.json

    {
        . . .
        "autoload": {
            "classmap": [
                . . .
                "vendor/dandisy"
            ],
            . . .

2. register this package in config/app.php

    /*
    * Package Service Providers...
    */
    . . .    
    Webcore\AdminLTETemplates\AdminLTETemplatesServiceProvider::class,

3. composer dump-autoload
4. publish the templates package to webcore project

    php artisan vendor:publish --provider="Webcore\AdminLTETemplates\AdminLTETemplatesServiceProvider" --force

5. ubah configurasi templates di file config/webcore/laravel_generator.php ke elmer-templates

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    */

    'templates'         => 'adminlte-templates',
