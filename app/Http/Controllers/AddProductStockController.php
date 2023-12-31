<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductStockRequest;
use App\Models\AddProductStock;
use DB;
use File;
use Maatwebsite\Excel\Facades\Excel;
use \Illuminate\Http\Request;

class AddProductStockController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('add_product_stocks.index');
        $this->module = 'AddProductStock';
        $this->view_folder = 'add_product_stocks';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 0;
        $this->is_multiple_upload = 0;
        $this->has_export = 1;
        $this->pagination_count = 100;

        $this->table_columns = [
            [
                'column' => 'title',
                'label' => 'Title',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'store_id',
                'label' => 'Added To Store',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'product_id',
                'label' => 'Product',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'location',
                'label' => 'Source Location',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'quantity',
                'label' => 'Quantity',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'total_cost',
                'label' => 'Total Cost',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'created_at',
                'label' => 'Date',
                'sortable' => 'Yes',
            ],
        ];
        $this->form_image_field_name = [];
        $this->repeating_group_inputs = [];
        $this->toggable_group = [];
        $this->model_relations = [
            [
                'name' => 'product',
                'class' => 'App\\Models\\Product',
                'type' => 'BelongsTo',
            ],
            [
                'name' => 'store',
                'class' => 'App\\Models\\Store',
                'type' => 'BelongsTo',
            ],
            [
                'name' => 'created_by',
                'class' => 'App\\Models\\User',
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
                    } elseif (strpos($key, 'min') !== false) {
                        $field_name = explode('_', $key);
                        $x = array_shift($field_name);
                        $field_name = implode('_', $field_name);
                        $query = $query->where($field_name, '>=', $value);
                    } elseif (strpos($key, 'max') !== false) {
                        $field_name = explode('_', $key);
                        $x = array_shift($field_name);
                        $field_name = implode('_', $field_name);
                        $query = $query->where($field_name, '<=', $value);
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
        $store_id = null;
        if (auth()->user()->hasRole(['Store Incharge'])) {
            $store_row = \DB::table('stores')->whereOwnerId(auth()->id())->first();
            if (!is_null($store_row)) {
                $store_id = $store_row->id;
            }

        }
        if (!can('list_add_product_stocks')) {
            return redirect(route('admin.unauthorized'));
        }
        $searchable_fields = [
            [
                'name' => 'title',
                'label' => 'Title',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'type' => 'date',
            ],
            [
                'name' => 'product_id',
                'label' => 'Product',
                'type' => 'select',
                'options' => getList('Product'),
            ],
            [
                'name' => 'quantity',
                'label' => 'Quantity',
                'type' => 'number',
            ],
           
            [
                'name' => 'total_cost',
                'label' => 'Total Cost',
                'type' => 'number',
            ],
        ];
        if(is_admin()){
            array_push($filterable_fields, [
                'name' => 'store_id',
                'label' => 'Store',
                'type' => 'select',
                'options' => getList('Store'),
            ]);
        }
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

            $list = AddProductStock::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
                return $query->where($search_by, 'like', '%' . $search_val . '%');
            })
                ->when(!empty($sort_by), function ($query) use ($sort_by, $sort_type) {
                    return $query->orderBy($sort_by, $sort_type);
                })
                ->when($store_id, function ($query) use ($store_id) {
                    return $query->whereStoreId($store_id);

                })
                ->latest()->paginate($this->pagination_count);
            $data = [
                'table_columns' => $table_columns,
                'list' => $list,
                'sort_by' => $sort_by,
                'sort_type' => $sort_type,
                'storage_folder' => $this->storage_folder,
                'plural_lowercase' => 'add_product_stocks',
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
                $query = AddProductStock::with(array_column($this->model_relations, 'name'))->when($store_id, function ($query) use ($store_id) {
                    return $query->whereStoreId($store_id);
                });
            } else {
                $query = AddProductStock::when($store_id, function ($query) use ($store_id) {
                    return $query->whereStoreId($store_id);
                });
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->latest()->paginate($this->pagination_count);
            $view_data = [
                'list' => $list,
                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'All AddProductStocks',
                'module' => $this->module, 'model_relations' => $this->model_relations,
                'searchable_fields' => $searchable_fields,
                'filterable_fields' => $filterable_fields,
                'storage_folder' => $this->storage_folder,
                'table_columns' => $table_columns,
                'plural_lowercase' => 'add_product_stocks',
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
                        'placeholder' => 'Enter title',
                        'name' => 'title',
                        'label' => 'Title',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->title : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'store_id',
                        'label' => 'Assign To Store',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Store'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'product_id',
                        'label' => 'Product Received',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Product'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter quantity',
                        'name' => 'quantity',
                        'label' => 'Quantity Recieved',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->quantity : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter location',
                        'name' => 'location',
                        'label' => 'Source Location',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->location : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter total_cost',
                        'name' => 'total_cost',
                        'label' => 'Total Cost',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->total_cost : "",
                        'attr' => [],
                    ],
                ],
            ],
        ];

        if (count($this->form_image_field_name) > 0) {

            foreach ($this->form_image_field_name as $g) {

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

        $view_data = [
            'data' => $data,

            'dashboard_url' => $this->dashboard_url,
            'index_url' => $this->index_url,
            'title' => 'Create ' . $this->module,
            'module' => $this->module,
            'plural_lowercase' => 'add_product_stocks',
            'image_field_names' => $this->form_image_field_name,
            'has_image' => $this->has_upload,
            'model_relations' => $this->model_relations,

            'repeating_group_inputs' => $this->repeating_group_inputs,
            'toggable_group' => $this->toggable_group,
            'storage_folder' => $this->storage_folder,
        ];
        return view('admin.' . $this->view_folder . '.add', with($view_data));
    }
    public function view(Request $request)
    {
        $id = $request->id;
        $data['row'] = null;
        if (count($this->model_relations) > 0) {
            $data['row'] = AddProductStock::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = AddProductStock::findOrFail($id);
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
    public function upsertStoreProductStock($post)
    {
        $qty = $post['quantity'];

        $update = [
            'total_quantity' => DB::raw('total_quantity+' . $qty),
            'current_quantity' => DB::raw('current_quantity+' . $qty),
        ];
        if ($post['location']) {
            $update['other_location_recieved_quantity'] = DB::raw('other_location_recieved_quantity+' . $qty);

        }

        if (\DB::table('store_assigned_product_stocks')->where(['store_id' => $post['store_id'], 'product_id' => $post['product_id']])->exists()) {
            \DB::table('store_assigned_product_stocks')->where(['store_id' => $post['store_id'], 'product_id' => $post['product_id']])->update($update);
        } else {
            \DB::table('store_assigned_product_stocks')->insert(array_merge($update, ['store_id' => $post['store_id'], 'product_id' => $post['product_id']]));
        }
    }
    public function upsertAdminProductStock($post)
    {
        $qty = $post['quantity'];
        $update = [
            'total_quantity' => DB::raw('total_quantity+' . $qty),
            'current_quantity' => DB::raw('current_quantity+' . $qty),
        ];
        if ($post['location']) {
            $update['other_location_quantity'] = DB::raw('other_location_quantity+' . $qty);

        }

        if (\DB::table('admin_product_stocks')->where(['product_id' => $post['product_id']])->exists()) {
            \DB::table('admin_product_stocks')->where(['product_id' => $post['product_id']])->update($update);
        } else {
            \DB::table('admin_product_stocks')->insert(array_merge($update, ['product_id' => $post['product_id']]));
        }
    }
    public function store(AddProductStockRequest $request)
    {
        if (!can('create_add_product_stocks')) {
            return createResponse(false, 'Dont have permission');
        }
        \DB::beginTransaction();
        try {
            $post = $request->all();
            $store_id = $post['store_id'];

            if (empty($store_id) && auth()->user()->hasRole(['Store Incharge'])) {
                $assigned_store = \App\Models\Store::whereOwnerId(auth()->id())->first();
                $post['store_id'] = !is_null($assigned_store) ? $assigned_store->id : null;
            }
            $post['created_by_id'] = auth()->id();
            $addproductstock = AddProductStock::create($post);
            if ($post['store_id']) {

                $this->upsertStoreProductStock($post);

            } else {
                $this->upsertAdminProductStock($post);
            }
            \DB::table('company_ledger')->insert(
                [
                    'name' => 'Product Purchase Expense',
                    'amount' => $post['total_cost'],
                    'order_id' => null,
                    'mode' => 'Spent',
                    'receive_payment_id' => null,

                ]
            );
            \DB::commit();
            return createResponse(true, 'Stock added successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function edit($id)
    {

        $model = AddProductStock::findOrFail($id);

        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'placeholder' => 'Enter title',
                        'name' => 'title',
                        'label' => 'Title',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->title : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'store_id',
                        'label' => 'Assign To Store',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'store_id', false) : (!empty(getList('Store')) ? getList('Store')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Store'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'product_id',
                        'label' => 'Product Received',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'product_id', false) : (!empty(getList('Product')) ? getList('Product')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Product'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter quantity',
                        'name' => 'quantity',
                        'label' => 'Quantity Recieved',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->quantity : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter location',
                        'name' => 'location',
                        'label' => 'Source Location',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->location : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter total_cost',
                        'name' => 'total_cost',
                        'label' => 'Total Cost',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->total_cost : "",
                        'attr' => [],
                    ],
                ],
            ],
        ];
        if (count($this->form_image_field_name) > 0) {
            foreach ($this->form_image_field_name as $g) {
                $field_name = $g['field_name'];

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
            'plural_lowercase' => 'add_product_stocks', 'model' => $model,
        ];
        if ($this->has_upload && $this->is_multiple_upload) {
            $view_data['image_list'] = $this->getImageList($id);
        }

        return view('admin.' . $this->view_folder . '.edit', with($view_data));

    }
    public function show($id)
    {
        if (!can('view_add_product_stocks')) {
            return createResponse(false, 'Dont have permission for this action');
        }

        $data['row'] = null;
        if (count($this->model_relations) > 0) {
            $data['row'] = AddProductStock::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = AddProductStock::findOrFail($id);
        }

        $data['has_image'] = $this->has_upload;
        $data['model_relations'] = $this->model_relations;
        $data['is_multiple'] = $this->is_multiple_upload;
        $data['storage_folder'] = $this->storage_folder;
        $data['table_columns'] = $this->table_columns;
        $data['plural_lowercase'] = 'add_product_stocks';
        $data['module'] = $this->module;
        if ($data['is_multiple']) {

            $data['image_list'] = $this->getImageList($id);
        }
        $table = getTableNameFromModel($this->module);
        $columns = \DB::getSchemaBuilder()->getColumnListing($table);
        //natcasesort($columns);

        $cols = [];
        $exclude_cols = ['updated_at', 'id'];
        foreach ($columns as $col) {

            $label = ucwords(str_replace('_', ' ', $col));

            if (!in_array($col, $exclude_cols)) {
                array_push($cols, ['column' => $col, 'label' => $label, 'sortable' => 'No']);
            }

        }
        $data['table_columns'] = $cols;
        return createResponse(true, view('admin.' . $this->view_folder . '.view_modal', with($data))->render());

    }

    public function update(AddProductStockRequest $request, $id)
    {
        if (!can('edit_add_product_stocks')) {
            return createResponse(false, 'Dont have permission');
        }
        \DB::beginTransaction();
        try
        {
            $post = $request->all();

            $addproductstock = AddProductStock::findOrFail($id);

            $post = formatPostForJsonColumn($post);
            if (count($this->model_relations) > 0 && in_array('BelongsToMany', array_column($this->model_relations, 'type'))) {
                foreach (array_keys($post) as $key) {
                    if (isFieldBelongsToManyToManyRelation($this->model_relations, $key) >= 0) {
                        $post->$key->sync($post[$key]);
                    }
                }
            }
            $addproductstock->update($post);
            if ($this->has_upload) {
                foreach ($this->form_image_field_name as $item) {
                    $field_name = $item['field_name'];
                    $single = $item['single'];

                    if ($request->hasfile($field_name)) {
                        if (is_array($request->file($field_name))) {
                            $image_model_name = modelName($item['table_name']);
                            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                            $this->upload($request->file($field_name), $addproductstock->id, $image_model_name, $parent_table_field);
                        } else {
                            $image_name = $this->upload($request->file($field_name));
                            if ($image_name) {
                                $addproductstock->{$field_name} = $image_name;
                                $addproductstock->save();
                            }
                        }

                    }

                }

            }
            \DB::commit();
            return createResponse(true, $this->module . ' updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!can('delete_add_product_stocks')) {
            return createResponse(false, 'Dont have permission to delete');
        }
        \DB::beginTransaction();
        try
        {
            AddProductStock::destroy($id);

            if ($this->has_upload) {
                $this->deleteFile($id);
            }
            \DB::commit();
            return createResponse(true, $this->module . ' Deleted successfully');
        } catch (\Exception $ex) {
            \DB::rollback();
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
            if (!can('create_add_product_stocks')) {
                return createResponse(false, 'Dont have permission to create ');
            }
            $data1 = [
                [
                    'label' => null,
                    'inputs' => [
                        [
                            'placeholder' => 'Enter title',
                            'name' => 'title',
                            'label' => 'Title',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->title : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'store_id',
                            'label' => 'Assign To Store',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => '',
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('Store'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'name' => 'product_id',
                            'label' => 'Product Received',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => '',
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('Product'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'placeholder' => 'Enter quantity',
                            'name' => 'quantity',
                            'label' => 'Quantity Recieved',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->quantity : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter location',
                            'name' => 'location',
                            'label' => 'Source Location',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->location : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter total_cost',
                            'name' => 'total_cost',
                            'label' => 'Total Cost',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->total_cost : "",
                            'attr' => [],
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
                'plural_lowercase' => 'add_product_stocks',
                'image_field_names' => $this->form_image_field_name,
                'has_image' => $this->has_upload,

                'repeating_group_inputs' => $this->repeating_group_inputs,
                'toggable_group' => $this->toggable_group,
                'storage_folder' => $this->storage_folder,
            ];

        }
        if ($form_type == 'edit') {
            if (!can('edit_add_product_stocks')) {
                return createResponse(false, 'Dont have permission to update');
            }
            $model = AddProductStock::findOrFail($id);

            $data1 = [
                [
                    'label' => null,
                    'inputs' => [
                        [
                            'placeholder' => 'Enter title',
                            'name' => 'title',
                            'label' => 'Title',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->title : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'store_id',
                            'label' => 'Assign To Store',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'store_id', false) : (!empty(getList('Store')) ? getList('Store')[0]->id : ''),
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('Store'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'name' => 'product_id',
                            'label' => 'Product Received',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'product_id', false) : (!empty(getList('Product')) ? getList('Product')[0]->id : ''),
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('Product'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'placeholder' => 'Enter quantity',
                            'name' => 'quantity',
                            'label' => 'Quantity Recieved',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->quantity : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter location',
                            'name' => 'location',
                            'label' => 'Source Location',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->location : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter total_cost',
                            'name' => 'total_cost',
                            'label' => 'Total Cost',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->total_cost : "",
                            'attr' => [],
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
                'plural_lowercase' => 'add_product_stocks', 'model' => $model,
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
                $data['row'] = AddProductStock::with(array_column($this->model_relations, 'name'))->findOrFail($id);
            } else {
                $data['row'] = AddProductStock::findOrFail($id);
            }
            $data['has_image'] = $this->has_upload;
            $data['model_relations'] = $this->model_relations;
            $data['storage_folder'] = $this->storage_folder;
            $data['table_columns'] = $this->table_columns;
            $data['plural_lowercase'] = 'add_product_stocks';
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
            if (!can('view_add_product_stocks')) {
                return createResponse(false, 'Dont have permission to view');
            }
            $html = view('admin.' . $this->view_folder . '.' . $form_type . '_modal', with($data))->render();
            return createResponse(true, $html);
        } else {
            $html = view('admin.' . $this->view_folder . '.modal.' . $form_type, with($data))->render();
            return createResponse(true, $html);
        }
    }
    public function exportAddProductStock(Request $request, $type)
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
            return Excel::download(new \App\Exports\AddProductStockExport($this->model_relations, $filter, $filter_date, $date_field), 'add_product_stocks' . date("Y-m-d H:i:s") . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($type == 'csv') {
            return Excel::download(new \App\Exports\AddProductStockExport($this->model_relations, $filter, $filter_date, $date_field), 'add_product_stocks' . date("Y-m-d H:i:s") . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type == 'pdf') {
            return Excel::download(new \App\Exports\AddProductStockExport($this->model_relations, $filter, $filter_date, $date_field), 'add_product_stocks' . date("Y-m-d H:i:s") . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
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
