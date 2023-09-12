<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractorWorkRequest;
use App\Models\ContractorWork;
use File;
use Maatwebsite\Excel\Facades\Excel;
use \Illuminate\Http\Request;

class ContractorWorkController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('contractor_works.index');
        $this->module = 'ContractorWork';
        $this->view_folder = 'contractor_works';
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
                'column' => 'driver_id',
                'label' => 'Driver',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'destination_address',
                'label' => 'Work Location',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'total_cost',
                'label' => 'Total Cost',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'payment_received',
                'label' => 'Payment Received',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'payment_due',
                'label' => 'Payment Due',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'loaded_products',
                'label' => 'Loaded Products',
                'sortable' => 'Yes',
            ],
        ];
        $this->form_image_field_name = [];
        $this->repeating_group_inputs = [
            [
                'colname' => 'loaded_products',
                'label' => 'Loaded Products Detail',
                'inputs' => [
                    [
                        'name' => 'loaded_products__json__product_id[]',
                        'label' => 'Select Product_id',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'product_id', false) : (!empty(getListProductWithQty()) ? getListProductWithQty()[0]->id : ''),
                        'attr' =>[],
                        'custom_key_for_option' => 'name',
                        'options' => getListProductWithQty(),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter sent quantity',
                        'name' => 'loaded_products__json__send_quantity[]',
                        'label' => 'Sent Quantity',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => 0,
                        'attr' => isset($model)?['readonly'=>true]:[],
                    ],
                    [
                        'placeholder' => 'Enter unused quantity',
                        'name' => 'loaded_products__json__unused_quantity[]',
                        'label' => 'Unused Quantity',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => 0,
                        'attr' => [],
                    ],
                ],
            ],
        ];
        $this->toggable_group = [];
        $this->model_relations = [
            [
                'name' => 'driver',
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

        if (!can('list_contractor_works')) {
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
                'name' => 'driver_id',
                'label' => 'Driver Id',
                'type' => 'select',
                'options' => getList('Driver'),

            ],
            [
                'name' => 'payment_mode',
                'label' => 'Payment Mode',
                'type' => 'select',
                'options' => getListFromIndexArray(['Cash', 'Online']),
            ],
            [
                'name' => 'total_cost',
                'label' => 'Total Cost',
                'type' => 'number',
            ],
            [
                'name' => 'work_date',
                'label' => 'Work Date',
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

            $list = ContractorWork::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
                'plural_lowercase' => 'contractor_works',
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
                $query = ContractorWork::with(array_column($this->model_relations, 'name'));
            } else {
                $query = ContractorWork::query();
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->paginate($this->pagination_count);
            $view_data = [
                'list' => $list,
                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'All ContractorWorks',
                'module' => $this->module, 'model_relations' => $this->model_relations,
                'searchable_fields' => $searchable_fields,
                'filterable_fields' => $filterable_fields,
                'storage_folder' => $this->storage_folder,
                'table_columns' => $table_columns,
                'plural_lowercase' => 'contractor_works',
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
                        'name' => 'driver_id',
                        'label' => 'Select Driver',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Driver'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter destination_address',
                        'name' => 'destination_address',
                        'label' => 'Work Location',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->destination_address : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter total_cost',
                        'name' => 'total_cost',
                        'label' => 'Total Product Cost',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->total_cost : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter payment_received',
                        'name' => 'payment_received',
                        'label' => 'Payment Recieved',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->payment_received : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter payment_due',
                        'name' => 'payment_due',
                        'label' => 'Payment Due',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->payment_due : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter due_date',
                        'name' => 'due_date',
                        'label' => 'Due Date',
                        'tag' => 'input',
                        'type' => 'date',
                        'default' => isset($model) ? $model->due_date : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'payment_mode',
                        'label' => 'Payment Mode',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'payment_mode', false) : 'Cash',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getListFromIndexArray(['Cash', 'Online']),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter transport_cost',
                        'name' => 'transport_cost',
                        'label' => 'Transportation Fare',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->transport_cost : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter area done(Sqft.)',
                        'name' => 'work_done',
                        'label' => 'Area Done ',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->work_done : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter work_date',
                        'name' => 'work_date',
                        'label' => 'Installation Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
                        'default' => isset($model) ? $model->work_date : "",
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
            'plural_lowercase' => 'contractor_works',
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
            $data['row'] = ContractorWork::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = ContractorWork::findOrFail($id);
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
    public function store(ContractorWorkRequest $request)
    {
        if (!can('create_contractor_works')) {
            return createResponse(false, 'Dont have permission');
        }
        \DB::beginTransaction();
        try {
            $post = $request->all();
            $pro_ids = $post['loaded_products__json__product_id'];

            $product_names_array = \DB::table('product')->whereIn('id', $pro_ids)->pluck('name', 'id')->toArray();
            $product_stock_array = [];
            if (is_admin()) {
                $product_stock_array = \DB::table('admin_product_stocks')->whereIn('product_id', $pro_ids)->pluck('current_quantity', 'product_id')->toArray();

            } else {
                $product_stock_array = \DB::table('store_assigned_product_stocks')->whereIn('product_id', $pro_ids)->pluck('product_id', 'current_quantity')->toArray();

            }

            $post = formatPostForJsonColumn($post);
            if (count($this->model_relations) > 0 && in_array('BelongsToMany', array_column($this->model_relations, 'type'))) {
                foreach (array_keys($post) as $key) {
                    if (isFieldBelongsToManyToManyRelation($this->model_relations, $key) >= 0) {
                        $post->$key->sync($post[$key]);
                    }
                }
            }
            $ar = json_decode($post['loaded_products']);
            $update_string = 'SET current_quantity=( CASE  ';
            $update_string1 = ',sold_quantity=( CASE  ';

            if (count($ar) > 0) {

                foreach ($ar as $item) {
                    $update_string .= ' WHEN product_id=' . $item->product_id . ' THEN current_quantity-' . $item->send_quantity;
                    $update_string1 .= ' WHEN product_id=' . $item->product_id . ' THEN sold_quantity+' . $item->send_quantity;
                    if ($item->product_id) {
                        //  dd($material_qty_array[$item->material_id]>$item->quantity);
                        if (isset($product_stock_array[$item->product_id])) { /***Mterial has stock addedd */
                            if ($product_stock_array[$item->product_id] < $item->send_quantity) {
                                return createResponse(false, 'Product <b>' . $item->name . '</b> is out of stock');
                            }
                        } else {
                            return createResponse(false, 'Please add stock for <b style="color:red"> ' . $item->name . '</b>');

                        }

                    }

                }
                $update_string .= ' ELSE current_quantity END)';
                $update_string1 .= ' ELSE sold_quantity END)';
                if (is_admin()) {
                    \DB::statement('UPDATE admin_product_stocks ' . $update_string . ' ' . $update_string1);
                } else {
                    \DB::statement('UPDATE store_assigned_product_stocks ' . $update_string . ' ' . $update_string1);

                }
            }

            $ar = array_map(function ($v) use ($product_names_array) {

                $name = isset($product_names_array[$v->product_id]) ? $product_names_array[$v->product_id] : '';
                $v->name = $name;
                $v->unused_quantity = is_null($v->unused_quantity) ? 0.0 : $v->unused_quantity;
                return $v;
            }, $ar);
            unset($post['loaded_products']);
            $post['loaded_products'] = json_encode($ar);

            $contractorwork = ContractorWork::create($post);

            if ($this->has_upload) {
                foreach ($this->form_image_field_name as $item) {

                    $field_name = $item['field_name'];
                    $single = $item['single'];

                    if ($request->hasfile($field_name)) {
                        if (is_array($request->file($field_name))) {
                            $image_model_name = modelName($item['table_name']);
                            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                            $this->upload($request->file($field_name), $contractorwork->id, $image_model_name, $parent_table_field);
                        } else {
                            $image_name = $this->upload($request->file($field_name));
                            if ($image_name) {
                                $contractorwork->{$field_name} = $image_name;
                                $contractorwork->save();
                            }
                        }

                    }

                }

            }
            \DB::commit();
            return createResponse(true, $this->module . ' created successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function edit($id)
    {

        $model = ContractorWork::findOrFail($id);
$this->repeating_group_inputs = [
    [
        'colname' => 'loaded_products',
        'label' => 'Loaded Products Detail',
        'inputs' => [
            [
                'name' => 'loaded_products__json__product_id[]',
                'label' => 'Select Product_id',
                'tag' => 'select',
                'type' => 'select',
                'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'product_id', false) : (!empty(getListProductWithQty()) ? getListProductWithQty()[0]->id : ''),
                'attr' => [],
                'custom_key_for_option' => 'name',
                'options' => getListProductWithQty(),
                'custom_id_for_option' => 'id',
                'multiple' => false,
            ],
            [
                'placeholder' => 'Enter sent quantity',
                'name' => 'loaded_products__json__send_quantity[]',
                'label' => 'Sent Quantity',
                'tag' => 'input',
                'type' => 'number',
                'default' => 0,
                'attr' =>  ['readonly' => true],
            ],
            [
                'placeholder' => 'Enter unused quantity',
                'name' => 'loaded_products__json__unused_quantity[]',
                'label' => 'Unused Quantity',
                'tag' => 'input',
                'type' => 'number',
                'default' => 0,
                'attr' => [],
            ],
        ],
    ],
];

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
                        'name' => 'driver_id',
                        'label' => 'Select Driver',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'driver_id', false) : (!empty(getList('Driver')) ? getList('Driver')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Driver'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter destination_address',
                        'name' => 'destination_address',
                        'label' => 'Work Location',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->destination_address : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter total_cost',
                        'name' => 'total_cost',
                        'label' => 'Total Product Cost',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->total_cost : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter payment_received',
                        'name' => 'payment_received',
                        'label' => 'Payment Recieved',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->payment_received : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter payment_due',
                        'name' => 'payment_due',
                        'label' => 'Payment Due',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->payment_due : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter due_date',
                        'name' => 'due_date',
                        'label' => 'Due Date',
                        'tag' => 'input',
                        'type' => 'date',
                        'default' => isset($model) ? $model->due_date : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'payment_mode',
                        'label' => 'Payment Mode',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'payment_mode', false) : 'Cash',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getListFromIndexArray(['Cash', 'Online']),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter transport_cost',
                        'name' => 'transport_cost',
                        'label' => 'Transportation Fare',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->transport_cost : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter area done(Sqft.)',
                        'name' => 'work_done',
                        'label' => 'Area Done ',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->work_done : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter work_date',
                        'name' => 'work_date',
                        'label' => 'Installation Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
                        'default' => isset($model) ? $model->work_date : "",
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
            'plural_lowercase' => 'contractor_works', 'model' => $model,
        ];
        if ($this->has_upload && $this->is_multiple_upload) {
            $view_data['image_list'] = $this->getImageList($id);
        }

        return view('admin.' . $this->view_folder . '.edit', with($view_data));

    }
    public function show($id)
    {
        if (!can('view_contractor_works')) {
            return createResponse(false, 'Dont have permission for this action');
        }

        $data['row'] = null;
        if (count($this->model_relations) > 0) {
            $data['row'] = ContractorWork::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = ContractorWork::findOrFail($id);
        }

        $data['has_image'] = $this->has_upload;
        $data['model_relations'] = $this->model_relations;
        $data['is_multiple'] = $this->is_multiple_upload;
        $data['storage_folder'] = $this->storage_folder;
        $data['table_columns'] = $this->table_columns;
        $data['plural_lowercase'] = 'contractor_works';
        $data['module'] = $this->module;
        if ($data['is_multiple']) {

            $data['image_list'] = $this->getImageList($id);
        }
        $table = getTableNameFromModel($this->module);
        $columns = \DB::getSchemaBuilder()->getColumnListing($table);
        //natcasesort($columns);

        $cols = [];
        $exclude_cols = ['updated_at', 'id', 'deleted_at'];
        foreach ($columns as $col) {

            $label = ucwords(str_replace('_', ' ', $col));

            if (!in_array($col, $exclude_cols)) {
                array_push($cols, ['column' => $col, 'label' => $label, 'sortable' => 'No']);
            }

        }
        $data['table_columns'] = $cols;
        return view('admin.' . $this->view_folder . '.view', with($data));

    }

    public function update(ContractorWorkRequest $request, $id)
    {
        if (!can('edit_contractor_works')) {
            return createResponse(false, 'Dont have permission');
        }
        \DB::beginTransaction();
        try
        {
            $post = $request->all();

            $contractorwork = ContractorWork::findOrFail($id);

            $post = formatPostForJsonColumn($post);
            $pro_ids = $post['loaded_products__json__product_id'];

            $product_names_array = \DB::table('product')->whereIn('id', $pro_ids)->pluck('name', 'id')->toArray();

            $post = formatPostForJsonColumn($post);

            $ar = json_decode($post['loaded_products']);

            $ar = array_map(function ($v) use ($product_names_array) {

                $name = isset($product_names_array[$v->product_id]) ? $product_names_array[$v->product_id] : '';
                $v->name = $name;

                return $v;
            }, $ar);
            unset($post['loaded_products']);
            $post['loaded_products'] = json_encode($ar);

            if (count($this->model_relations) > 0 && in_array('BelongsToMany', array_column($this->model_relations, 'type'))) {
                foreach (array_keys($post) as $key) {
                    if (isFieldBelongsToManyToManyRelation($this->model_relations, $key) >= 0) {
                        $post->$key->sync($post[$key]);
                    }
                }
            }
            $contractorwork->update($post);
            if ($this->has_upload) {
                foreach ($this->form_image_field_name as $item) {
                    $field_name = $item['field_name'];
                    $single = $item['single'];

                    if ($request->hasfile($field_name)) {
                        if (is_array($request->file($field_name))) {
                            $image_model_name = modelName($item['table_name']);
                            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                            $this->upload($request->file($field_name), $contractorwork->id, $image_model_name, $parent_table_field);
                        } else {
                            $image_name = $this->upload($request->file($field_name));
                            if ($image_name) {
                                $contractorwork->{$field_name} = $image_name;
                                $contractorwork->save();
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
        if (!can('delete_contractor_works')) {
            return createResponse(false, 'Dont have permission to delete');
        }
        \DB::beginTransaction();
        try
        {
            ContractorWork::destroy($id);

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
            if (!can('create_contractor_works')) {
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
                            'name' => 'driver_id',
                            'label' => 'Select Driver',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'driver_id', false) : (!empty(getList('Driver')) ? getList('Driver')[0]->id : ''),
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('Driver'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'placeholder' => 'Enter destination_address',
                            'name' => 'destination_address',
                            'label' => 'Work Location',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->destination_address : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter total_cost',
                            'name' => 'total_cost',
                            'label' => 'Total Product Cost',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->total_cost : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter payment_received',
                            'name' => 'payment_received',
                            'label' => 'Payment Recieved',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->payment_received : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter payment_due',
                            'name' => 'payment_due',
                            'label' => 'Payment Due',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->payment_due : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter due_date',
                            'name' => 'due_date',
                            'label' => 'Due Date',
                            'tag' => 'input',
                            'type' => 'date',
                            'default' => isset($model) ? $model->due_date : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'payment_mode',
                            'label' => 'Payment Mode',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'payment_mode', false) : 'Cash',
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getListFromIndexArray(['Cash', 'Online']),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'placeholder' => 'Enter transport_cost',
                            'name' => 'transport_cost',
                            'label' => 'Transportation Fare',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->transport_cost : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter work_date',
                            'name' => 'work_date',
                            'label' => 'Installation Date',
                            'tag' => 'input',
                            'type' => 'datetime-local',
                            'default' => isset($model) ? $model->work_date : "",
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
                'plural_lowercase' => 'contractor_works',
                'image_field_names' => $this->form_image_field_name,
                'has_image' => $this->has_upload,

                'repeating_group_inputs' => $this->repeating_group_inputs,
                'toggable_group' => $this->toggable_group,
                'storage_folder' => $this->storage_folder,
            ];

        }
        if ($form_type == 'edit') {
            if (!can('edit_contractor_works')) {
                return createResponse(false, 'Dont have permission to update');
            }
            $model = ContractorWork::findOrFail($id);

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
                            'name' => 'driver_id',
                            'label' => 'Select Driver',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'driver_id', false) : (!empty(getList('Driver')) ? getList('Driver')[0]->id : ''),
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('Driver'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'placeholder' => 'Enter destination_address',
                            'name' => 'destination_address',
                            'label' => 'Work Location',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->destination_address : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter total_cost',
                            'name' => 'total_cost',
                            'label' => 'Total Product Cost',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->total_cost : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter payment_received',
                            'name' => 'payment_received',
                            'label' => 'Payment Recieved',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->payment_received : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter payment_due',
                            'name' => 'payment_due',
                            'label' => 'Payment Due',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->payment_due : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter due_date',
                            'name' => 'due_date',
                            'label' => 'Due Date',
                            'tag' => 'input',
                            'type' => 'date',
                            'default' => isset($model) ? $model->due_date : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'payment_mode',
                            'label' => 'Payment Mode',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'payment_mode', false) : 'Cash',
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getListFromIndexArray(['Cash', 'Online']),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'placeholder' => 'Enter transport_cost',
                            'name' => 'transport_cost',
                            'label' => 'Transportation Fare',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->transport_cost : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter work_date',
                            'name' => 'work_date',
                            'label' => 'Installation Date',
                            'tag' => 'input',
                            'type' => 'datetime-local',
                            'default' => isset($model) ? $model->work_date : "",
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
                'plural_lowercase' => 'contractor_works', 'model' => $model,
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
                $data['row'] = ContractorWork::with(array_column($this->model_relations, 'name'))->findOrFail($id);
            } else {
                $data['row'] = ContractorWork::findOrFail($id);
            }
            $data['has_image'] = $this->has_upload;
            $data['model_relations'] = $this->model_relations;
            $data['storage_folder'] = $this->storage_folder;
            $data['table_columns'] = $this->table_columns;
            $data['plural_lowercase'] = 'contractor_works';
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
            if (!can('view_contractor_works')) {
                return createResponse(false, 'Dont have permission to view');
            }
            $html = view('admin.' . $this->view_folder . '.' . $form_type . '_modal', with($data))->render();
            return createResponse(true, $html);
        } else {
            $html = view('admin.' . $this->view_folder . '.modal.' . $form_type, with($data))->render();
            return createResponse(true, $html);
        }
    }
    public function exportContractorWork(Request $request, $type)
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
            return Excel::download(new \App\Exports\ContractorWorkExport($this->model_relations, $filter, $filter_date, $date_field), 'contractor_works' . date("Y-m-d H:i:s") . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($type == 'csv') {
            return Excel::download(new \App\Exports\ContractorWorkExport($this->model_relations, $filter, $filter_date, $date_field), 'contractor_works' . date("Y-m-d H:i:s") . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type == 'pdf') {
            return Excel::download(new \App\Exports\ContractorWorkExport($this->model_relations, $filter, $filter_date, $date_field), 'contractor_works' . date("Y-m-d H:i:s") . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
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
