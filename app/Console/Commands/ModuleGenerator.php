<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class ModuleGenerator extends Command
{
    protected $columns = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AhmadOf:Module {name : Class (singular) for example User}
    {--columns= : JSON object of fields with their types.} {--relations= : JSON object of relations with the related field.}';
    //--columns=title:string,description:text,start_date:date,due_date:date

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate module files';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->columns = json_decode($this->option('columns'), true);
        $this->relations = json_decode($this->option('relations'), true) ?: [];

        $this->controller($name);
        $this->model($name);
        $this->transformer($name);
        $this->requests($name);
        $this->contract($name);
        $this->repository($name);
        $this->list_vue($name);
        $this->form_vue($name);
        exec('/usr/bin/yarn lint');

        $schema_fields = '';
        foreach ($this->columns as $column_name => $column_type) {
            $schema_fields .= $column_name.':'.$column_type.' ,';
        }
        $schema_fields = rtrim($schema_fields, ' ,');

        \Artisan::call('make:migration:schema', ['name' => 'create_'.Str::plural(Str::snake($name)).'_table', '--schema' => $schema_fields, '--model' => false]);

        $this->permissions($name);
        $this->routes($name);
        $this->translations($name);
    }

    protected function controller($name)
    {
        $relationStatements = '';
        foreach ($this->relations as $foreign_key => $relation) {
            $relationStatements .= sprintf("\$query->join('%s', '%s.id', '=', '%s.%s');\n",Str::plural($relation), Str::plural($relation), Str::plural(Str::snake($name)),$foreign_key) ;
        }

        if(count($this->relations) > 0) $relationStatements .= sprintf("\t\t\$query->select([\DB::raw('%s.name as %s_name'), '%s.*']);\n",Str::plural($relation), $relation, Str::plural(Str::snake($name)));

        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralLowerCaseWithSpaces}}',
                '{{joinStatements}}',
            ],
            [
                $name,
                Str::plural(Str::snake($name)),
                Str::singular(Str::snake($name)),
                str_replace('_', ' ', Str::plural(Str::snake($name))),
                $relationStatements
            ],
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/Backend/{$name}Controller.php"), $controllerTemplate);
    }

    protected function model($name)
    {
        $fields = '';
        foreach (array_keys($this->columns) as $column_name) {
            $fields .= "\n\t\t'".$column_name."',";
        }
        $relations = '';
        foreach ($this->relations as $foreign_key => $relation) {
            $relations .= sprintf("\tpublic function %s()\n\t{\n\t\treturn \$this->belongsTo(%s::class, '%s');\n\t}\n\n",Str::snake($relation), ucfirst(Str::camel($relation)), $foreign_key);
        }

        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCaseWithSpaces}}',
                '{{fieldList}}',
                '{{relations}}',
            ],
            [
                $name,
                str_replace('_', ' ', Str::plural(Str::snake($name))),
                $fields,
                $relations
            ],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$name}.php"), $modelTemplate);
    }

    protected function transformer($name)
    {
        $fields = '';
        foreach (array_keys($this->columns) as $column_name) {
            $fields .= "\n\t\t\t'".$column_name."' => $".Str::singular(Str::snake($name))."->$column_name,";
        }

        foreach ($this->relations as $foreign_key => $relation) {
            $fields .= sprintf("\n\t\t\t'%s_name' => $%s->%s->name,",$relation, Str::singular(Str::snake($name)),$relation);
            $fields .= sprintf("\n\t\t\t'%s' => ['id' => $%s->%s->id,'name' => $%s->%s->name],",$relation, Str::singular(Str::snake($name)),$relation, Str::singular(Str::snake($name)),$relation);
        }

        $transformerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{fieldList}}',
            ],
            [
                $name,
                Str::plural(Str::snake($name)),
                Str::singular(Str::snake($name)),
                $fields,
            ],
            $this->getStub('Transformer')
        );

        file_put_contents(app_path("/Transformers/{$name}Transformer.php"), $transformerTemplate);
    }

    protected function requests($name)
    {
        $fields = '';
        foreach (array_keys($this->columns) as $column_name) {
            if(!isset($this->relations[$column_name])) $fields .= "\n\t\t\t'".$column_name."' => 'required',";
            else $fields .= "\n\t\t\t'".$this->relations[$column_name]."' => 'required',";
        }

        $storeRequestTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{fieldRules}}',
            ],
            [
                $name,
                Str::plural(Str::snake($name)),
                Str::singular(Str::snake($name)),
                $fields,
            ],
            $this->getStub('StoreRequest')
        );

        $updateRequestTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{fieldRules}}',
            ],
            [
                $name,
                Str::plural(Str::snake($name)),
                Str::singular(Str::snake($name)),
                $fields,
            ],
            $this->getStub('UpdateRequest')
        );

        file_put_contents(app_path("/Http/Requests/Store{$name}Request.php"), $storeRequestTemplate);
        file_put_contents(app_path("/Http/Requests/Update{$name}Request.php"), $updateRequestTemplate);
    }

    protected function contract($name)
    {
        $contractTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $name,
                Str::plural(Str::snake($name)),
                Str::singular(Str::snake($name)),
            ],
            $this->getStub('Contract')
        );

        file_put_contents(app_path("/Repositories/Contracts/{$name}Repository.php"), $contractTemplate);
    }

    protected function repository($name)
    {
        $relations = '';
        foreach ($this->relations as $foreign_key => $relation) {
            $relations .= sprintf("\$input['%s_id'] = \$input['%s']['id'];\n\t\t",$relation, $relation);
        }

        $repositoryTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{relations}}',
            ],
            [
                $name,
                Str::plural(Str::snake($name)),
                Str::singular(Str::snake($name)),
                $relations
            ],
            $this->getStub('Repository')
        );

        file_put_contents(app_path("/Repositories/Eloquent{$name}Repository.php"), $repositoryTemplate);
    }

    protected function list_vue($name)
    {
        $fields = '';
        foreach ($this->columns as $column_name => $column_type) {
            if ('text' === $column_type) {
                continue;
            }
            $fields .= sprintf(",\n        {\n          key: '%s',\n          label: this.\$t('validation.attributes.%s'),\n          class: 'text-center',\n          sortable: true\n        }",
                !isset($this->relations[$column_name]) ? $column_name : $this->relations[$column_name].'_name',!isset($this->relations[$column_name]) ? $column_name : $this->relations[$column_name]);
        }
        $listTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralLowerCaseWithSpaces}}',
                '{{fieldList}}',
            ],
            [
                $name,
                Str::plural(Str::snake($name)),
                Str::singular(Str::snake($name)),
                str_replace('_', ' ', Str::plural(Str::snake($name))),
                $fields,
            ],
            $this->getStub('List')
        );

        file_put_contents(app_path("../resources/js/backend/views/{$name}List.vue"), $listTemplate);
    }

    protected function form_vue($name)
    {
        $fieldList = '';
        $modelFieldList = '';
        $config = '';
        $hasConfig = false;
        $relations = [];
        $axiosImport = '';
        $createdHook = '';
        $relationsOnCreatedHook = '';
        $relationsOptions = '';

        foreach ($this->columns as $column_name => $column_type) {
            if ('date' === $column_type) {
                $hasConfig = true;
            }
            $is_relation_column = false;
            if(isset($this->relations[$column_name])){
                array_push($relations, $this->relations[$column_name]);
                $is_relation_column = true;
            }

            $fieldList .= str_replace(
                [
                    '{{fieldName}}',
                    '{{modelNamePluralLowerCase}}',
                ],
                [
                    $is_relation_column ? $this->relations[$column_name] : $column_name,
                    $is_relation_column ? Str::plural(Str::snake($this->relations[$column_name])) : ''
                ],
                $this->getStub('form/'.($is_relation_column ? 'Select' : $column_type))
            );
            $modelFieldList .= "\n        ".($is_relation_column ? $this->relations[$column_name] : $column_name).': null,';
        }

        $modelFieldList = rtrim($modelFieldList, ',');

        if ($hasConfig) {
            $config = "\n      config: {
        wrap: true,
        allowInput: true
      },";
        }

        if(count($relations)){
            $axiosImport = "import axios from 'axios'\nimport Multiselect from 'vue-multiselect'";
            $index = 0;
            $relationsOptions = "\n";
            foreach ($this->relations as $relation) {
                $relationsOptions .= "\t\t\t".Str::plural(Str::snake($relation)).": [],";
                $index++;
                if($index == 1)
                $relationsOnCreatedHook .= sprintf("    let { data } = await axios.get(this.\$app.route(`%s.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    })
    this.%s = data.data

    this.%s = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
",Str::plural($relation),Str::plural($relation),Str::plural($relation));
                else
                    $relationsOnCreatedHook .= sprintf("    ;({ data } = await axios.get(this.\$app.route(`%s.search`), {
      params: {
        page: 1,
        perPage: 100,
        column: 'name'
      }
    }))
    this.%s = data.data

    this.%s = data.data.map(item => {
      return { id: item.id, name: item.name }
    })
",Str::plural($relation),Str::plural($relation),Str::plural($relation));

            }
            $createdHook = sprintf(",\n  async created() {\n%s  }",$relationsOnCreatedHook);

        }

        $formTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralLowerCaseWithSpaces}}',
                '{{fieldList}}',
                '{{modelFieldList}}',
                '{{config}}',
                '{{axiosImport}}',
                '{{Multiselect}}',
                '{{createdHook}}',
                '{{relationsOptions}}'
            ],
            [
                $name,
                Str::plural(Str::snake($name)),
                Str::singular(Str::snake($name)),
                str_replace('_', ' ', Str::plural(Str::snake($name))),
                $fieldList,
                $modelFieldList,
                $config,
                $axiosImport,
                count($relations) ? ' Multiselect ' : '',
                $createdHook,
                $relationsOptions
            ],
            $this->getStub('Form')
        );

        file_put_contents(app_path("../resources/js/backend/views/{$name}Form.vue"), $formTemplate);
    }

    protected function permissions($name)
    {
        $permissions = config('permissions');

        $permissions['view '.str_replace('_', ' ', Str::plural(Str::snake($name)))] = [
            'display_name' => 'permissions.view.'.Str::plural(Str::snake($name)).'.display_name',
            'description'  => 'permissions.view.'.Str::plural(Str::snake($name)).'.description',
            'category'     => 'permissions.categories.'.Str::plural(Str::snake($name)),
        ];

        $permissions['create '.str_replace('_', ' ', Str::plural(Str::snake($name)))] = [
            'display_name' => 'permissions.create.'.Str::plural(Str::snake($name)).'.display_name',
            'description'  => 'permissions.create.'.Str::plural(Str::snake($name)).'.description',
            'category'     => 'permissions.categories.'.Str::plural(Str::snake($name)),
        ];

        $permissions['edit '.str_replace('_', ' ', Str::plural(Str::snake($name)))] = [
            'display_name' => 'permissions.edit.'.Str::plural(Str::snake($name)).'.display_name',
            'description'  => 'permissions.edit.'.Str::plural(Str::snake($name)).'.description',
            'category'     => 'permissions.categories.'.Str::plural(Str::snake($name)),
        ];

        $permissions['delete '.str_replace('_', ' ', Str::plural(Str::snake($name)))] = [
            'display_name' => 'permissions.delete.'.Str::plural(Str::snake($name)).'.display_name',
            'description'  => 'permissions.delete.'.Str::plural(Str::snake($name)).'.description',
            'category'     => 'permissions.categories.'.Str::plural(Str::snake($name)),
        ];

        ksortRecursive($permissions);
        $config =
            preg_replace('/  /',"\t",
                preg_replace('/],\n\n(\s*)],/',"],\n$1],",
                    preg_replace('/\d+ => /','',
                        preg_replace('/],\n/',"],\n\n",
                            preg_replace('/=> \n/','=>',preg_replace('/\)/',']',preg_replace('/array \(/','[',var_export( $permissions, true ))))))));

        $config = "<?php\nreturn ".$config.";";

        Storage::disk('config')->move('permissions.php','permissions_'.time().'.php');
        Storage::disk('config')->put('permissions.php', $config);
    }

    protected function routes($name)
    {
        $routesTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNamePluralLowerCaseWithSpaces}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $name,
                Str::plural(Str::snake($name)),
                str_replace('_', ' ', Str::plural(Str::snake($name))),
                Str::singular(Str::snake($name)),
            ],
            $this->getStub('Routes')
        );

        $this->info('Please add the following lines to routes/admin.php file:');
        $this->warn($routesTemplate);
    }

    protected function translations($name)
    {
        $alerts = trans('alerts');
        $buttons = trans('buttons');
        $exceptions = trans('exceptions');
        $labels = trans('labels');
        $permissions = trans('permissions');

        $alerts['backend'][Str::plural(Str::snake($name))] = [
            'created' => str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' created',
            'updated' => str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' updated',
            'deleted' => str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' deleted',
        ];

        ksortRecursive($alerts);
        $translations =
            preg_replace('/  /',"\t",
                preg_replace('/],\n\n(\s*)],/',"],\n$1],",
                    preg_replace('/\d+ => /','',
                        preg_replace('/],\n/',"],\n\n",
                            preg_replace('/=> \n/','=>',preg_replace('/\)/',']',preg_replace('/array \(/','[',var_export( $alerts, true ))))))));

        $translations = "<?php\nreturn ".$translations.";";

        Storage::disk('translations')->move('en/alerts.php','backup/alerts_'.time().'.php');
        Storage::disk('translations')->put('en/alerts.php', $translations);

        $buttons[Str::plural(Str::snake($name))] = [
            'create' => 'Create '.str_replace('_', ' ', Str::singular(Str::snake($name))),
        ];

        ksortRecursive($buttons);
        $translations =
            preg_replace('/  /',"\t",
                preg_replace('/],\n\n(\s*)],/',"],\n$1],",
                    preg_replace('/\d+ => /','',
                        preg_replace('/],\n/',"],\n\n",
                            preg_replace('/=> \n/','=>',preg_replace('/\)/',']',preg_replace('/array \(/','[',var_export( $buttons, true ))))))));

        $translations = "<?php\nreturn ".$translations.";";

        Storage::disk('translations')->move('en/buttons.php','backup/buttons_'.time().'.php');
        Storage::disk('translations')->put('en/buttons.php', $translations);

        $exceptions['backend'][Str::plural(Str::snake($name))] = [
            'create' => 'Error on '.str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' creation',
            'update' => 'Error on '.str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' updating',
            'delete' => 'Error on '.str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' deletion',
        ];

        ksortRecursive($exceptions);
        $translations =
            preg_replace('/  /',"\t",
                preg_replace('/],\n\n(\s*)],/',"],\n$1],",
                    preg_replace('/\d+ => /','',
                        preg_replace('/],\n/',"],\n\n",
                            preg_replace('/=> \n/','=>',preg_replace('/\)/',']',preg_replace('/array \(/','[',var_export( $exceptions, true ))))))));

        $translations = "<?php\nreturn ".$translations.";";

        Storage::disk('translations')->move('en/exceptions.php','backup/exceptions_'.time().'.php');
        Storage::disk('translations')->put('en/exceptions.php', $translations);

        $labels['backend'][Str::plural(Str::snake($name))]['titles'] = [
            'main'   => str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' management',
            'index'  => str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' list',
            'create' => str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' creation',
            'edit'   => str_replace('_', ' ', ucfirst(Str::singular(Str::snake($name)))).' modification',
        ];

        ksortRecursive($labels);
        $translations =
            preg_replace('/  /',"\t",
                preg_replace('/],\n\n(\s*)],/',"],\n$1],",
                    preg_replace('/\d+ => /','',
                        preg_replace('/],\n/',"],\n\n",
                            preg_replace('/=> \n/','=>',preg_replace('/\)/',']',preg_replace('/array \(/','[',var_export( $labels, true ))))))));

        $translations = "<?php\nreturn ".$translations.";";

        Storage::disk('translations')->move('en/labels.php','backup/labels_'.time().'.php');
        Storage::disk('translations')->put('en/labels.php', $translations);

        $permissions['view'][Str::plural(Str::snake($name))] = [
            'display_name' => 'View '.str_replace('_', ' ', Str::plural(Str::snake($name))),
            'description'  => 'Can view '.str_replace('_', ' ', Str::plural(Str::snake($name))).'.',
        ];

        $permissions['create'][Str::plural(Str::snake($name))] = [
            'display_name' => 'Create '.str_replace('_', ' ', Str::plural(Str::snake($name))),
            'description'  => 'Can create '.str_replace('_', ' ', Str::plural(Str::snake($name))).'.',
        ];

        $permissions['edit'][Str::plural(Str::snake($name))] = [
            'display_name' => 'Update '.str_replace('_', ' ', Str::plural(Str::snake($name))),
            'description'  => 'Can update '.str_replace('_', ' ', Str::plural(Str::snake($name))).'.',
        ];

        $permissions['delete'][Str::plural(Str::snake($name))] = [
            'display_name' => 'Delete '.str_replace('_', ' ', Str::plural(Str::snake($name))),
            'description'  => 'Can delete '.str_replace('_', ' ', Str::plural(Str::snake($name))).'.',
        ];

        ksortRecursive($permissions);
        $translations =
            preg_replace('/  /',"\t",
                preg_replace('/],\n\n(\s*)],/',"],\n$1],",
                    preg_replace('/\d+ => /','',
                        preg_replace('/],\n/',"],\n\n",
                            preg_replace('/=> \n/','=>',preg_replace('/\)/',']',preg_replace('/array \(/','[',var_export( $permissions, true ))))))));

        $translations = "<?php\nreturn ".$translations.";";

        Storage::disk('translations')->move('en/permissions.php','backup/permissions_'.time().'.php');
        Storage::disk('translations')->put('en/permissions.php', $translations);

    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }
}
