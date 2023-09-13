<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverRequest;
use App\Models\Driver;
use File;
use Maatwebsite\Excel\Facades\Excel;
use \Illuminate\Http\Request;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('drivers.index');
        $this->module = 'Driver';
        $this->view_folder = 'drivers';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 1;
        $this->is_multiple_upload = 0;
        $this->has_export = 1;
        $this->pagination_count = 100;

        $this->table_columns = [
            [
                'column' => 'name',
                'label' => 'Name',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'phone_no',
                'label' => 'Phone No',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'address',
                'label' => 'Address',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'image',
                'label' => 'Photo',
                'sortable' => 'No',
            ],

            [
                'column' => 'vehicle_id',
                'label' => 'Assigned Vehicle',
                'sortable' => 'No',
            ],
            [
                'column' => 'created_at',
                'label' => 'Join Date',
                'sortable' => 'Yes',
            ],
        ];
        $this->form_image_field_name = [
            [
                'field_name' => 'image',
                'single' => true,
            ],
            [
                'field_name' => 'adhar_image',
                'single' => true,
            ],
            [
                'field_name' => 'dl_image',
                'single' => true,
            ],
        ];
        $this->repeating_group_inputs = [];
        $this->toggable_group = [];
        $this->model_relations = [
            [
                'name' => 'vehicle',
                'class' => 'App\\Models\\Driver',
                'type' => 'BelongsTo',
            ],
        ];

    }
    public function buildFilter(Request $r, $query)
    {
        $get = $r->all();
        if (count($get) > 0 && $r->isMethod('get')) {
            foreach ($get as $key => $value) {
                if ((!is_array($value) && strlen($value) > 0) || (is_array($value) && count($value) > 0)) {
                    if (strpos($key, 'start') !== false) {
                        $field_name = explode('_', $key);

                        $x = array_shift($field_name);
                        $field_name = implode('_', $field_name);

                        $query = $query->whereDate($field_name, '>=', \Carbon\Carbon::parse($value));
                    } elseif (strpos($key, 'end') !== false) {
                        $field_name = explode('_', $key);
                        $x = array_shift($field_name);
                        $field_name = implode('_', $field_name);
                        $query = $query->whereDate($field_name, '<=', \Carbon\Carbon::parse($value));
                    } else {
                        if (!is_array($value)) {
                            $query = $query->where($key, $value);
                        } else {
                            //dd($value);
                            $query = $query->whereIn($key, $value);
                        }
                    }
                }
            }
        }
        return $query;
    }
    public function index(Request $request)
    {

        $searchable_fields = [
            [
                'name' => 'name',
                'label' => 'Name',
            ],
            [
                'name' => 'phone_no',
                'label' => 'Phone No',
            ],
            [
                'name' => 'address',
                'label' => 'Address',
            ],
            [
                'name' => 'adhar_number',
                'label' => 'Adhar Number',
            ],
            [
                'name' => 'dl_number',
                'label' => 'DL Number',
            ],
            [
                'name' => 'vehicle_id',
                'label' => 'Vehicle',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'options' => getListFromIndexArray(['Active', 'In-Active']),
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'type' => 'date',
            ],
        ];
        $table_columns = $this->table_columns;
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $search_by = $request->get('search_by');

            $query = $request->get('query');

            $search_val = str_replace(" ", "%", $query);
            if (empty($search_by)) {
                $search_by = 'name';
            }

            $list = Driver::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
                return $query->where($search_by, 'like', '%' . $search_val . '%');
            })
                ->when(!empty($sort_by), function ($query) use ($sort_by, $sort_type) {
                    return $query->orderBy($sort_by, $sort_type);
                })->paginate($this->pagination_count);
            $data = [
                'table_columns' => $table_columns,
                'list' => $list,
                'sort_by' => $sort_by,
                'sort_type' => $sort_type,
                'storage_folder' => $this->storage_folder,
                'plural_lowercase' => 'drivers',
                'module' => $this->module,
                'has_image' => $this->has_upload,
                'model_relations' => $this->model_relations,
                'image_field_names' => $this->form_image_field_name,
                'storage_folder' => $this->storage_folder,
            ];
            return view('admin.' . $this->view_folder . '.page', with($data));
        } else {

            $query = null;
            if (count($this->model_relations) > 0) {
                $query = Driver::with(array_column($this->model_relations, 'name'));
            } else {
                $query = Driver::query();
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->paginate($this->pagination_count);
            $view_data = [
                'list' => $list,
                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'All Drivers',
                'module' => $this->module, 'model_relations' => $this->model_relations,
                'searchable_fields' => $searchable_fields,
                'filterable_fields' => $filterable_fields,
                'storage_folder' => $this->storage_folder,
                'table_columns' => $table_columns,
                'plural_lowercase' => 'drivers',
                'has_image' => $this->has_upload,

                'image_field_names' => $this->form_image_field_name,
                'storage_folder' => $this->storage_folder,
                'has_export' => $this->has_export,
            ];
            return view('admin.' . $this->view_folder . '.index', $view_data);
        }
    }

    public function create()
    {
        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'name',
                        'label' => 'Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter phone_no',
                        'name' => 'phone_no',
                        'label' => 'Phone No',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->phone_no : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter address',
                        'name' => 'address',
                        'label' => 'Address',
                        'tag' => 'textarea',
                        'type' => 'textarea',
                        'default' => isset($model) ? $model->address : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter image',
                        'name' => 'image',
                        'label' => 'Image',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => isset($model) ? $model->image : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter adhar_number',
                        'name' => 'adhar_number',
                        'label' => 'Adhar Number',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->adhar_number : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter adhar_image',
                        'name' => 'adhar_image',
                        'label' => 'Adhar Image',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => isset($model) ? $model->adhar_image : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter dl_number',
                        'name' => 'dl_number',
                        'label' => 'DL Number',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => isset($model) ? $model->dl_number : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter dl_image',
                        'name' => 'dl_image',
                        'label' => 'DL Image',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => isset($model) ? $model->dl_image : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'status',
                        'label' => 'Status',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) ? ($is_multiple ? formatDefaultValueForCheckbox($model, 'status') : $model->status) : ($is_multiple ? [] : 'Active'),
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Active',
                                'value' => 'Active',
                            ],
                            (object) [
                                'label' => 'In-Active',
                                'value' => 'In-Active',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                    ],
                    [
                        'name' => 'vehicle_id',
                        'label' => 'Vehicle',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'vehicle_id', true) : getList('Vehicle')[0]->id,
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Vehicle'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                ],
            ]
        ];

        if (count($this->form_image_field_name) > 0) {

            foreach ($this->form_image_field_name as $g) {
                if ($model->field_name) {
                    $y = [
                        'placeholder' => '',
                        'name' => $g['single'] ? $g['field_name'] : $g['field_name'] . '[]',
                        'label' => $g['single'] ? $g['field_name'] : \Str::plural($g['field_name']),
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => '',
                        'attr' => $g['single'] ? [] : ['multiple' => 'multiple'],
                    ];
                    array_push($data[0]['inputs'], $y);
                }
            }
        }

        $view_data = [
            'data' => $data,

            'dashboard_url' => $this->dashboard_url,
            'index_url' => $this->index_url,
            'title' => 'Create ' . $this->module,
            'module' => $this->module,
            'plural_lowercase' => 'drivers',
            'image_field_names' => $this->form_image_field_name,
            'has_image' => $this->has_upload,
            'model_relations' => $this->model_relations,

            'repeating_group_inputs' => $this->repeating_group_inputs,
            'toggable_group' => $this->toggable_group,
            'storage_folder' => $this->storage_folder,
        ];
        return view('admin.' . $this->view_folder . '.add', with($view_data));
    }
    public function store(DriverRequest $request)
    {
        try {
            $post = $request->all();

            $post = formatPostForJsonColumn($post);
            if (count($this->model_relations) > 0 && in_array('BelongsToMany', array_column($this->model_relations, 'type'))) {
                foreach (array_keys($post) as $key) {
                    if (isFieldBelongsToManyToManyRelation($this->model_relations, $key) >= 0) {
                        $post->$key->sync($post[$key]);
                    }
                }
            }
            $driver = Driver::create($post);

            if ($this->has_upload) {
                foreach ($this->form_image_field_name as $item) {
                    $field_name = $item['field_name'];
                    $single = $item['single'];

                    if ($request->hasfile($field_name)) {
                        if (is_array($request->file($field_name))) {
                            $image_model_name = modelName($item['table_name']);
                            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                            $this->upload($request->file($field_name), $driver->id, $image_model_name, $parent_table_field);
                        } else {
                            $image_name = $this->upload($request->file($field_name));
                            if ($image_name) {
                                $driver->{$field_name} = $image_name;
                                $driver->save();
                            }
                        }

                    }

                }

            }
            return createResponse(true, $this->module . ' created successfully', $this->index_url);
        } catch (\Exception $ex) {
            return createResponse(false, $ex->getMessage());
        }
    }
    public function edit($id)
    {

        $model = Driver::findOrFail($id);

        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'placeholder' => 'Enter name',
                        'name' => 'name',
                        'label' => 'Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter phone_no',
                        'name' => 'phone_no',
                        'label' => 'Phone No',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->phone_no : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter address',
                        'name' => 'address',
                        'label' => 'Address',
                        'tag' => 'textarea',
                        'type' => 'textarea',
                        'default' => isset($model) ? $model->address : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter image',
                        'name' => 'image',
                        'label' => 'Image',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => isset($model) ? $model->image : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter adhar_number',
                        'name' => 'adhar_number',
                        'label' => 'Adhar Number',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->adhar_number : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter adhar_image',
                        'name' => 'adhar_image',
                        'label' => 'Adhar Image',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => isset($model) ? $model->adhar_image : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter dl_number',
                        'name' => 'dl_number',
                        'label' => 'DL Number',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => isset($model) ? $model->dl_number : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter dl_image',
                        'name' => 'dl_image',
                        'label' => 'DL Image',
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => isset($model) ? $model->dl_image : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'status',
                        'label' => 'Status',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) ? ($is_multiple ? formatDefaultValueForCheckbox($model, 'status') : $model->status) : ($is_multiple ? [] : 'Active'),
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Active',
                                'value' => 'Active',
                            ],
                            (object) [
                                'label' => 'In-Active',
                                'value' => 'In-Active',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                    ],
                    [
                        'name' => 'vehicle_id',
                        'label' => 'Vehicle',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'vehicle_id', true) : getList('Vehicle')[0]->id,
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Vehicle'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                ],
            ]
        ];
        if (count($this->form_image_field_name) > 0) {
            foreach ($this->form_image_field_name as $g) {
                if ($model->field_name) {
                    $y = [
                        'placeholder' => '',
                        'name' => $g['single'] ? $g['field_name'] : $g['field_name'] . '[]',
                        'label' => $g['single'] ? $g['field_name'] : \Str::plural($g['field_name']),
                        'tag' => 'input',
                        'type' => 'file',
                        'default' => $g['single'] ? $this->storage_folder . '/' . $model->field_name : json_encode($this->getImageList($id, $g['table_name'], $g['parent_table_field'])),
                        'attr' => $g['single'] ? [] : ['multiple' => 'multiple'],
                    ];
                    array_push($data[0]['inputs'], $y);
                }
            }
        }
        $view_data = [
            'data' => $data,

            'dashboard_url' => $this->dashboard_url,
            'index_url' => $this->index_url,
            'title' => 'Edit ' . $this->module,
            'module' => $this->module,
            'has_image' => $this->has_upload,
            'is_multiple' => $this->is_multiple_upload,
            'image_field_names' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'repeating_group_inputs' => $this->repeating_group_inputs,
            'toggable_group' => $this->toggable_group,
            'plural_lowercase' => 'drivers', 'model' => $model,
        ];
        if ($this->has_upload && $this->is_multiple_upload) {
            $view_data['image_list'] = $this->getImageList($id);
        }

        return view('admin.' . $this->view_folder . '.edit', with($view_data));

    }
    public function show($id)
    {

        $data['row'] = null;
        if (count($this->model_relations) > 0) {
            $data['row'] = Driver::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = Driver::findOrFail($id);
        }

        $data['has_image'] = $this->has_upload;
        $data['model_relations'] = $this->model_relations;
        $data['is_multiple'] = $this->is_multiple_upload;
        $data['storage_folder'] = $this->storage_folder;
        $data['table_columns'] = $this->table_columns;
        $data['plural_lowercase'] = 'drivers';
        $data['module'] = $this->module;
        if ($data['is_multiple']) {

            $data['image_list'] = $this->getImageList($id);
        }
        return createResponse(true, view('admin.' . $this->view_folder . '.view_modal', with($data))->render());

    }
    public function view(Request $request)
    {
        $id = $request->id;
        $data['row'] = null;
        if (count($this->model_relations) > 0) {
            $data['row'] = Driver::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = Driver::findOrFail($id);
        }
        $data['has_image'] = $this->has_upload;
        $data['model_relations'] = $this->model_relations;
        $data['storage_folder'] = $this->storage_folder;
        $data['image_field_names'] = $this->form_image_field_name;
        $data['table_columns'] = $this->table_columns;
        $data['module'] = $this->module;
        $html = view('admin.' . $this->view_folder . '.view', with($data))->render();
        return createResponse(true, $html);
    }
    public function update(DriverRequest $request, $id)
    {
        try
        {
            $post = $request->all();

            $driver = Driver::findOrFail($id);

            $post = formatPostForJsonColumn($post);
            if (count($this->model_relations) > 0 && in_array('BelongsToMany', array_column($this->model_relations, 'type'))) {
                foreach (array_keys($post) as $key) {
                    if (isFieldBelongsToManyToManyRelation($this->model_relations, $key) >= 0) {
                        $post->$key->sync($post[$key]);
                    }
                }
            }
            $driver->update($post);
            if ($this->has_upload) {
                foreach ($this->form_image_field_name as $item) {
                    $field_name = $item['field_name'];
                    $single = $item['single'];

                    if ($request->hasfile($field_name)) {
                        if (is_array($request->file($field_name))) {
                            $image_model_name = modelName($item['table_name']);
                            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                            $this->upload($request->file($field_name), $driver->id, $image_model_name, $parent_table_field);
                        } else {
                            $image_name = $this->upload($request->file($field_name));
                            if ($image_name) {
                                $driver->{$field_name} = $image_name;
                                $driver->save();
                            }
                        }

                    }

                }

            }
            return createResponse(true, $this->module . ' updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try
        {
            Driver::destroy($id);

            if ($this->has_upload) {
                $this->deleteFile($id);
            }
            return createResponse(true, $this->module . ' Deleted successfully');
        } catch (\Exception $ex) {
            return createResponse(false, 'Failed to  Delete Properly');
        }

    }
    public function deleteFile($id)
    {

        foreach ($this->form_image_field_name as $item) {
            $field_name = $item['field_name'];
            $single = $item['single'];

            $table_name = !empty($item['table_name']) ? $item['table_name'] : null;
            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
            if ($single) {
                $model = $this->module;
                $mod = app("App\\Models\\$model");
                $filerow = $mod->findOrFail($id);
                $image_name = $filerow->{$field_name};
                $path = storage_path('app/public/' . $this->storage_folder . '/' . $image_name);
                if (\File::exists($path)) {
                    unlink($path);

                }
            } else {
                $list = \DB::table($table_name)->where($parent_table_field, $id)->get(['name']);
                if (count($list) > 0) {
                    foreach ($list as $t) {
                        try {
                            $path = storage_path('app/public/' . $this->storage_folder . '/' . $t->name);
                            if (\File::exists($path)) {
                                unlink($path);

                            }
                        } catch (\Exception $ex) {

                        }
                    }
                }

            }

        }

    }
    public function upload($request_files, $parent_table_id = null, $image_model_name = null, $parent_table_field = null)
    {

        $uploaded_filename = null;
        if ($request_files != null) {

            $uploaded_filename = is_array($request_files) && $parent_table_id ?
            storeMultipleFile($this->storage_folder, $request_files, $image_model_name, $parent_table_id, $parent_table_field)
            : storeSingleFile($this->storage_folder, $request_files);
            if (!is_array($uploaded_filename)) {
                return $uploaded_filename;
            }

        }
        return $uploaded_filename;

    }

    public function loadAjaxForm(Request $request)
    {
        $data = [];

        $form_type = $request->form_type;
        $id = $request->id;
        if ($form_type == 'add') {
            $data1 = [
                [
                    'label' => null,
                    'inputs' => [
                        [
                            'placeholder' => 'Enter name',
                            'name' => 'name',
                            'label' => 'Name',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->name : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter phone_no',
                            'name' => 'phone_no',
                            'label' => 'Phone No',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->phone_no : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter address',
                            'name' => 'address',
                            'label' => 'Address',
                            'tag' => 'textarea',
                            'type' => 'textarea',
                            'default' => isset($model) ? $model->address : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter image',
                            'name' => 'image',
                            'label' => 'Image',
                            'tag' => 'input',
                            'type' => 'file',
                            'default' => isset($model) ? $model->image : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter adhar_number',
                            'name' => 'adhar_number',
                            'label' => 'Adhar Number',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->adhar_number : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter adhar_image',
                            'name' => 'adhar_image',
                            'label' => 'Adhar Image',
                            'tag' => 'input',
                            'type' => 'file',
                            'default' => isset($model) ? $model->adhar_image : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter dl_number',
                            'name' => 'dl_number',
                            'label' => 'DL Number',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->dl_number : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter dl_image',
                            'name' => 'dl_image',
                            'label' => 'DL Image',
                            'tag' => 'input',
                            'type' => 'file',
                            'default' => isset($model) ? $model->dl_image : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'status',
                            'label' => 'Status',
                            'tag' => 'input',
                            'type' => 'radio',
                            'default' => isset($model) ? $model->status : 'Active',
                            'attr' => [],
                            'value' => [
                                (object) [
                                    'label' => 'Active',
                                    'value' => 'Active',
                                ],
                                (object) [
                                    'label' => 'In-Active',
                                    'value' => 'In-Active',
                                ],
                            ],
                            'has_toggle_div' => [],
                            'multiple' => false,
                            'inline' => true,

                        ],
                        [
                            'name' => 'vehicle_id',
                            'label' => 'Vehicle',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => '',
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('Vehicle'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                    ],
                ],
            ];

            $data = [
                'data' => $data1,

                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'Create ' . $this->module,
                'module' => $this->module,
                'plural_lowercase' => 'drivers',
                'image_field_names' => $this->form_image_field_name,
                'has_image' => $this->has_upload,

                'repeating_group_inputs' => $this->repeating_group_inputs,
                'toggable_group' => $this->toggable_group,
                'storage_folder' => $this->storage_folder,
            ];

        }
        if ($form_type == 'edit') {
            $model = Driver::findOrFail($id);

            $data1 = [
                [
                    'label' => null,
                    'inputs' => [
                        [
                            'placeholder' => 'Enter name',
                            'name' => 'name',
                            'label' => 'Name',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->name : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter phone_no',
                            'name' => 'phone_no',
                            'label' => 'Phone No',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->phone_no : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter address',
                            'name' => 'address',
                            'label' => 'Address',
                            'tag' => 'textarea',
                            'type' => 'textarea',
                            'default' => isset($model) ? $model->address : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter image',
                            'name' => 'image',
                            'label' => 'Image',
                            'tag' => 'input',
                            'type' => 'file',
                            'default' => isset($model) ? $model->image : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter adhar_number',
                            'name' => 'adhar_number',
                            'label' => 'Adhar Number',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->adhar_number : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter adhar_image',
                            'name' => 'adhar_image',
                            'label' => 'Adhar Image',
                            'tag' => 'input',
                            'type' => 'file',
                            'default' => isset($model) ? $model->adhar_image : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter dl_number',
                            'name' => 'dl_number',
                            'label' => 'DL Number',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->dl_number : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter dl_image',
                            'name' => 'dl_image',
                            'label' => 'DL Image',
                            'tag' => 'input',
                            'type' => 'file',
                            'default' => isset($model) ? $model->dl_image : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'status',
                            'label' => 'Status',
                            'tag' => 'input',
                            'type' => 'radio',
                            'default' => isset($model) ? $model->status : 'Active',
                            'attr' => [],
                            'value' => [
                                (object) [
                                    'label' => 'Active',
                                    'value' => 'Active',
                                ],
                                (object) [
                                    'label' => 'In-Active',
                                    'value' => 'In-Active',
                                ],
                            ],
                            'has_toggle_div' => [],
                            'multiple' => false,
                            'inline' => true,
                        ],
                        [
                            'name' => 'vehicle_id',
                            'label' => 'Vehicle ',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'vehicle_id', true) : getList('Vehicle')[0]->id,
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('Vehicle'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                    ],
                ],
            ];

            $data = [
                'data' => $data1,

                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'Edit ' . $this->module,
                'module' => $this->module,
                'has_image' => $this->has_upload,

                'image_field_names' => $this->form_image_field_name,
                'storage_folder' => $this->storage_folder,
                'repeating_group_inputs' => $this->repeating_group_inputs,
                'toggable_group' => $this->toggable_group,
                'plural_lowercase' => 'drivers', 'model' => $model,
            ];
            if ($this->has_upload) {
                $ar = [];
                if (count($this->form_image_field_name) > 0) {foreach ($this->form_image_field_name as $item) {
                    if (!$item['single']) {
                        $model_name = modelName($item['table_name']);
                        $ar['image_list'][$item['field_name']] = getImageList($id, $model_name, $item['parent_table_field']);
                    }
                }
                    $data['image_list'] = $ar; /***$data['image_list'] will have fieldnames as key and corrsponsing list of image models */
                }
            }

        }
        if ($form_type == 'view') {
            $data['row'] = null;
            if (count($this->model_relations) > 0) {
                $data['row'] = Driver::with(array_column($this->model_relations, 'name'))->findOrFail($id);
            } else {
                $data['row'] = Driver::findOrFail($id);
            }
            $data['has_image'] = $this->has_upload;
            $data['model_relations'] = $this->model_relations;
            $data['storage_folder'] = $this->storage_folder;
            $data['table_columns'] = $this->table_columns;
            $data['plural_lowercase'] = 'drivers';
            $data['module'] = $this->module;
            $data['image_field_names'] = $this->form_image_field_name;
            $table = getTableNameFromModel($this->module);
            $columns = \DB::getSchemaBuilder()->getColumnListing($table);
            //natcasesort($columns);

            $cols = [];
            $exclude_cols = ['id', 'updated_at'];
            foreach ($columns as $col) {

                $label = ucwords(str_replace('_', ' ', $col));
                $label = str_replace(' Id', '', $label);

                if (!in_array($col, $exclude_cols)) {
                    array_push($cols, ['column' => $col, 'label' => $label, 'sortable' => 'No']);
                }

            }

            $data['table_columns'] = $cols;

        }
        if ($form_type == 'view') {
            $html = view('admin.' . $this->view_folder . '.' . $form_type . '_modal', with($data))->render();
            return createResponse(true, $html);
        } else {
            $html = view('admin.' . $this->view_folder . '.modal.' . $form_type, with($data))->render();
            return createResponse(true, $html);
        }
    }
    public function exportDriver(Request $request, $type)
    {
        $filter = [];
        $filter_date = [];
        $date_field = null;
        foreach ($_GET as $key => $val) {
            if (str_contains($key, 'start_')) {
                $date_field = str_replace('start_', '', $key);
                $filter_date['min'] = $val;
            } else if (str_contains($key, 'end_')) {
                $date_field = str_replace('end_', '', $key);
                $filter_date['max'] = $val;
            } else {
                $filter[$key] = $val;
            }

        }
        if ($type == 'excel') {
            return Excel::download(new \App\Exports\DriverExport($this->model_relations, $filter, $filter_date, $date_field), 'drivers' . date("Y-m-d H:i:s") . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($type == 'csv') {
            return Excel::download(new \App\Exports\DriverExport($this->model_relations, $filter, $filter_date, $date_field), 'drivers' . date("Y-m-d H:i:s") . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type == 'pdf') {
            return Excel::download(new \App\Exports\DriverExport($this->model_relations, $filter, $filter_date, $date_field), 'drivers' . date("Y-m-d H:i:s") . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
        }

    }
    public function load_toggle(Request $r)
    {
        $value = trim($r->val);
        $rowid = $r->has('row_id') ? $r->row_id : null;
        $row = null;
        if ($rowid) {
            $model = app("App\\Models\\" . $this->module);
            $row = $model::where('id', $rowid)->first();
        }
        $index_of_val = 0;
        $is_value_present = false;
        $i = 0;
        foreach ($this->toggable_group as $val) {

            if ($val['onval'] == $value) {

                $is_value_present = true;
                $index_of_val = $i;
                break;
            }
            $i++;
        }
        if ($is_value_present) {
            if ($row) {
                $this->toggable_group = [];

            }
            $data['inputs'] = $this->toggable_group[$index_of_val]['inputs'];

            $v = view('admin.attribute_families.toggable_snippet', with($data))->render();
            return createResponse(true, $v);
        } else {
            return createResponse(true, "");
        }

    }
    public function getImageList($id, $table, $parent_field_name)
    {

        $ar = \DB::table($table)->where($parent_field_name, $id)->get(['id', 'name'])->map(function ($val) use ($table) {

            $val->table = $table;
            $val->folder = $this->storage_folder;
            return $val;
        })->toArray();
        return $ar;
    }
}
