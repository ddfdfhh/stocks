<?php

namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Yaml;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $yamlContents = Yaml::parse(file_get_contents(resource_path('test.yaml')));
        $routes_array = [];
        foreach ($yamlContents as $key => $val) {
            $data = array_values($val);
            $data = $data[0];
            $p = [];
            foreach ($data as $key => $val) {
                $key = array_keys($val);
                $key = $key[0];
                $p[$key] = $val[$key];

            }
            // dd($p);
            $data['modelName'] = $p['module'][0];
            $data['modelNamePluralLowerCase'] = $p['plural'][0];

            array_push($routes_array, $data['modelNamePluralLowerCase']);
            $data['table_name'] = isset($p['tableName']) ? $p['tableName'][0] : $p['plural'][0];
            $data['isModal'] = $p['modal'][0];
            $data['export'] = $p['export'][0];
            $data['has_export'] = $data['export'] ? 1 : 0;
            $data['has_repeating_group'] = $p['has_repeating_group'] ? 1 : 0;
            if ($data['export']) {
                $data['export_fields'] = $p['export_fields'][0];
            }
            if ($data['has_repeating_group']) {
                $data['repeating_group_inputs'] = $p['repeating_group_inputs']?$p['repeating_group_inputs']:[];
            }
    // dd($data['repeating_group_inputs']);
            $data['modelNameSinglularLowerCase'] = strtolower($p['module'][0]);
            $data['validation'] = $p['validation'];
            $data['searchable_fields'] = $p['searchable_fields'];
            $data['toggable_group'] = $p['toggable_group']?$p['toggable_group']:[];
            $data['toggable_group_edit'] = $p['toggable_group_edit']?$p['toggable_group_edit']:[];
            // dd($p['']);
            $data['filterable_fields'] = $p['filterable_fields'];
            $data['form_image_field_name'] = isset($p['form_image_field_name']) && count($p['form_image_field_name'])>0?$p['form_image_field_name'][0]:[];
            
            $data['create'] = $p['create'];
            
            $data['edit'] = $p['edit'];
            
            $data['table_columns'] = $p['index_page'];
            $data['has_image'] = isset($p['form_image_field_name']) && count($p['form_image_field_name'])>0 ? 1 : 0;

            
            $modelName = $data['modelName'];
            $data['create'] = $p['create'];
           
            $this->controller($data);
            $this->model($data['modelName'], $data['modelNamePluralLowerCase'], $data['table_name']);
            $this->request($data['modelName'], $data['validation']);
            $this->viewFiles($data['modelNamePluralLowerCase'], $data['isModal'],$data['has_export']);
            // $this->makeTablesWithMigration();
            $modelName = $data['modelName'];
            $namespace = 'App\\Http\\Controllers\\';
            File::append(base_path('routes/admin.php'), PHP_EOL . 'Route::resource(\'' . $data['modelNamePluralLowerCase'] . "', '{$modelName}Controller');");
            $t = 'Route::post(\'' . $data['modelNamePluralLowerCase'] . '/view\', [' . $namespace . $modelName . 'Controller::class,\'view\'])->name(\'' . \Str::plural(strtolower($modelName)) . '.view\');';
            File::append(base_path('routes/admin.php'), PHP_EOL . $t);
            if ($data['isModal']) {
                $t = 'Route::post("' . strtolower($modelName) . '/load_form", [' . $namespace . $modelName . 'Controller::class,"loadAjaxForm"])->name("' . strtolower($modelName) . '.loadAjaxForm");';
                File::append(base_path('routes/admin.php'), PHP_EOL . $t);
            }
            if ($data['has_image']) {
                if (!file_exists($path = storage_path('/app/public/' . \Str::plural(strtolower($modelName))))) {
                    mkdir($path, 0777, true);
                }

            }
            if ($data['export']) {
                $this->makeExport($modelName, $data['export_fields']);
                $plural = \Str::plural(strtolower($modelName));
                $t = 'Route::get("export_' . $plural . '/{type}", [' . $namespace . $modelName . 'Controller::class,"export' . $modelName . '"])->name("' . strtolower($modelName) . '.export");';
                File::append(base_path('routes/admin.php'), PHP_EOL . $t);
            }
            $this->menu($data, $routes_array);
        }
        File::append(resource_path('views/layouts/admin/menu.blade.php'), PHP_EOL . json_encode($routes_array));
    }
    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }
    protected function model($name, $plural, $tableName)
    {
        if ($name != 'Permission' && $name != 'Role' && $name != 'User') {
            $modelTemplate = str_replace(
                ['{{modelName}}', '{{tableName}}'],
                [$name, $tableName],
                $this->getStub('Model')
            );

            file_put_contents(app_path("/Models/{$name}.php"), $modelTemplate);
            /*$perm_label = ucwords(str_replace('_', '  ', $plural));

            $permissions = [
                ['name' => 'list_' . $plural, 'label' => 'List ' . $perm_label],
                ['name' => 'edit_' . $plural, 'label' => 'Edit ' . $perm_label],
                ['name' => 'create_' . $plural, 'label' => 'Create ' . $perm_label],
                ['name' => 'delete_' . $plural, 'label' => 'Delete ' . $perm_label],
            ];
            \Artisan::call('cache:forget spatie.permission.cache');
            \Artisan::call('cache:clear');
            foreach ($permissions as $perm) {
                Permission::create($perm);
            }*/
        }
    }
    protected function menu($data, $routes_array)
    {
        $menu_stub = $this->getStub('menu');
        $plural_upper_case = ucwords(str_replace('_', '  ', $data['modelNamePluralLowerCase']));

        $menu_content = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralUpperCase}}',
                '{{modelNamePluralLowerCase}}',
            ],
            [$data['modelName'], $plural_upper_case, $data['modelNamePluralLowerCase']],
            $menu_stub
        );
        File::append(resource_path('views/layouts/admin/menu.blade.php'), PHP_EOL . $menu_content);

    }
    protected function controller($data)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{repeating_group_inputs}}',
                '{{toggable_group}}',
                '{{toggable_group_edit}}',
                '{{form_image_field_name}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{has_image}}',
                '{{has_export}}',
                '{{searchFields}}',
                '{{filterFields}}',
                '{{tableColumns}}',
                '{{create}}',
                '{{edit}}',
                '["[',
                ']"]',
                 "'[[", "]]'",'","'

            ],
            [
                $data['modelName'],
                json_encode($data['repeating_group_inputs']),
                json_encode($data['toggable_group']),
                json_encode($data['toggable_group_edit']),
                $data['form_image_field_name'],
                $data['modelNamePluralLowerCase'],
                $data['modelNameSinglularLowerCase'],
                $data['has_image'],
                $data['has_export'],
                json_encode($data['searchable_fields']),
                json_encode($data['filterable_fields']),
                json_encode($data['table_columns']),
                json_encode($data['create']),
                json_encode($data['edit']),
                '[[',
                ']]',
                 '[[', ']]',','
            ],
            $this->getStub('Controller')
        );
        $name = $data['modelName'];
        file_put_contents(app_path("/Http/Controllers/" . $name . "Controller.php"), $controllerTemplate);
    }
    protected function request($name, $validation)
    {
        $re = ",";
        $requestTemplate = str_replace(
            [
                '{{modelName}}',
                '{{validation}}',
            ],
            [$name, json_encode($validation)],
            $this->getStub('Request')
        );
        $requestTemplate = str_replace('["[', "[", $requestTemplate);
        $requestTemplate = str_replace(']"]', "]", $requestTemplate);

        $requestTemplate = str_replace(']","[', $re, $requestTemplate);
        if (!file_exists($path = app_path('/Http/Requests'))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(app_path("/Http/Requests/{$name}Request.php"), $requestTemplate);
    }
    public function viewFiles($name, $is_modal = false, $has_export = false)
    {
        if (!file_exists($path = resource_path('/views/admin/' . $name))) {
            mkdir($path, 0777, true);
        }

        if (!$is_modal) {
            $from = resource_path('stubs/views/add.blade.php');
            $to = resource_path('views/admin/' . $name . '/add.blade.php');
            File::copy($from, $to);
            $from = resource_path('stubs/views/edit.blade.php');
            $to = resource_path('views/admin/' . $name . '/edit.blade.php');
            File::copy($from, $to);
            
            $from = resource_path('stubs/views/index.blade.php');
            $to = resource_path('views/admin/' . $name . '/index.blade.php');
            File::copy($from, $to);
            
            $from = resource_path('stubs/views/page.blade.php');
            $to = resource_path('views/admin/' . $name . '/page.blade.php');
            File::copy($from, $to);
           
        } else {
            if (!file_exists($path = resource_path('/views/admin/' . $name . '/modal'))) {
                mkdir($path, 0777, true);
            }
            $from = resource_path('stubs/views/modal/add.blade.php');
            $to = resource_path('views/admin/' . $name . '/modal/add.blade.php');
            File::copy($from, $to);

            $from = resource_path('stubs/views/modal/edit.blade.php');
            $to = resource_path('views/admin/' . $name . '/modal/edit.blade.php');
            File::copy($from, $to);

            $from = resource_path('stubs/views/modal/index.blade.php');
            $to = resource_path('views/admin/' . $name . '/index.blade.php');
            File::copy($from, $to);
            $from = resource_path('stubs/views/modal/page.blade.php');
            $to = resource_path('views/admin/' . $name . '/page.blade.php');
            File::copy($from, $to);

           
        }
               /*copy view,index and page file ***/
               $from = resource_path('stubs/views/view.blade.php');
               $to = resource_path('views/admin/' . $name . '/view.blade.php');
               File::copy($from, $to);
            
           

        
    }
    public function makeTablesWithMigration()
    {
        \Artisan::call('make:migrations'); /****This comes from a package in package.json mentioned */
        \Artisan::call('migrate');
    }
    public function makeExport($module, $exportFields)
    {
        $requestTemplate = str_replace(
            [
                '{{modelName}}',
                '{{exportFields}}',
            ],
            [$module, $exportFields],
            $this->getStub('Export')
        );

        if (!file_exists($path = app_path('/Exports'))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(app_path("/Exports/{$module}Export.php"), $requestTemplate);
    }
}
