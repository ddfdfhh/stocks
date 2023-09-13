<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\CreateOrder;
use File;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use \App\Jobs\SendInvoiceMail;
use \Illuminate\Http\Request;

class CreateOrderController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('create_orders.index');
        $this->module = 'CreateOrder';
        $this->view_folder = 'create_orders';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 0;
        $this->is_multiple_upload = 0;
        $this->has_export = 0;
        $this->pagination_count = 100;

        $this->table_columns = [
            [
                'column' => 'items',
                'label' => 'Items',
                'sortable' => 'No',
            ],
            [
                'column' => 'customer_id',
                'label' => 'Customer',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'driver_id',
                'label' => 'Driver',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'total',
                'label' => "Total Amount",
                'sortable' => 'Yes',
            ],
            [
                'column' => 'dispatch_date',
                'label' => "Dispatched Date",
                'sortable' => 'Yes',
            ],
            [
                'column' => 'status',
                'label' => "Delivery Status",
                'sortable' => 'Yes',
            ],
            [
                'column' => 'created_at',
                'label' => 'Created At',
                'sortable' => 'Yes',
            ],
        ];
        $this->form_image_field_name = [];

        $this->toggable_group = [];
        $this->model_relations = [
            [
                'name' => 'customer',
                'class' => 'App\\Models\\CreateOrder',
                'type' => 'BelongsTo',
            ],
            [
                'name' => 'driver',
                'class' => 'App\\Models\\CreateOrder',
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
      /*  $order_id = 1;
        $row = \DB::table('create_order')->whereId($order_id)->first();
        $customer = \App\Models\Customer::with(['state', 'city'])->whereId($row->customer_id)->first();
        $settings = \DB::table('setting')->whereId(1)->first();
        if ($row) {
            $data['row'] = $row;
            $data['settings'] = $settings;
            $data['customer'] = $customer;
            return view('admin.create_orders.invoice', with($data));
            // return createResponse(true,$h591tml);
        } else {
            return createResponse(false, 'Order Not found');
        }
*/
        $searchable_fields = [
            [
                'name' => 'customer_id',
                'label' => 'Customer Id',
            ],
            [
                'name' => 'driver_id',
                'label' => 'Driver Id',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'type' => 'date',
            ],
            [
                'name' => 'created_by_id',
                'label' => 'Store orders',
                'type' => 'select',
                'options'=>getUserListWithRoles('Store Incharge')
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

            $list = CreateOrder::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
                return $query->where($search_by, 'like', '%' . $search_val . '%');
            })
                ->when(!empty($sort_by), function ($query) use ($sort_by, $sort_type) {
                    return $query->orderBy($sort_by, $sort_type);
                })->when(auth()->user()->hasRole(['Store Incharge']), function ($query) use ($sort_by, $sort_type) {
                return $query->whereCreatedById(auth()->id());
            })->latest()->paginate($this->pagination_count);
            $data = [
                'table_columns' => $table_columns,
                'list' => $list,
                'sort_by' => $sort_by,
                'sort_type' => $sort_type,
                'storage_folder' => $this->storage_folder,
                'plural_lowercase' => 'create_orders',
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
                $query = CreateOrder::with(array_column($this->model_relations, 'name'))->when(auth()->user()->hasRole(['Store Incharge']), function ($query) {
                    return $query->whereCreatedById(auth()->id());
                });
            } else {
                $query = CreateOrder::when(auth()->user()->hasRole(['Store Incharge']), function ($query) {
                    return $query->whereCreatedById(auth()->id());
                });
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->paginate($this->pagination_count);
            $view_data = [
                'list' => $list,
                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'All CreateOrders',
                'module' => $this->module, 'model_relations' => $this->model_relations,
                'searchable_fields' => $searchable_fields,
                'filterable_fields' => $filterable_fields,
                'storage_folder' => $this->storage_folder,
                'table_columns' => $table_columns,
                'plural_lowercase' => 'create_orders',
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
        $repeating_group_inputs = [
            [
                'colname' => 'items',
                'label' => 'Sold Items',
                'inputs' => [
                    [
                        'name' => 'items__json__product_id[]',
                        'label' => 'Product',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => is_admin() ? getListProductWithQty() : getListAssignedProduct(),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter quantity',
                        'name' => 'items__json__quantity[]',
                        'label' => 'Quantity',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => '',
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
                        'name' => 'title',
                        'label' => 'Title',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->title : '',
                        'attr' => [],
                        'placeholder' => 'Enter title',

                    ],
                    [
                        'name' => 'customer_id',
                        'label' => 'Customer Id',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Customer'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'driver_id',
                        'label' => 'Driver Id',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Driver'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],

                ],
            ],
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
            'plural_lowercase' => 'create_orders',
            'image_field_names' => $this->form_image_field_name,
            'has_image' => $this->has_upload,
            'model_relations' => $this->model_relations,

            'repeating_group_inputs' => $repeating_group_inputs,
            'toggable_group' => $this->toggable_group,
            'storage_folder' => $this->storage_folder,
        ];
        return view('admin.' . $this->view_folder . '.add', with($view_data));
    }
    public function store(CreateOrderRequest $request)
    {
          $store_id=null;
    if(auth()->user()->hasRole(['Store Incharge'])){
        $store_row=\DB::table('stores')->whereOwnerId(auth()->id())->first();
        if(!is_null($store_row))
        $store_id=$store_row->id;
          
    }
        try {
            $post = $request->all();

            $ids = $post['items__json__product_id'];
            $total_qty = array_sum($post['items__json__quantity']);
            //dd($post['items__json__quantity']);
            $product_names_array = \DB::table('product')->whereIn('id', $ids)->get();
            $product_stock_array = [];
            if (is_admin()) {
                $product_stock_array = \DB::table('admin_product_stocks')->whereIn('product_id', $ids)->pluck('current_quantity', 'product_id')->toArray();

            } else {
                $product_stock_array = \DB::table('store_assigned_product_stocks')->whereIn('product_id', $ids)->pluck('product_id', 'current_quantity')->toArray();

            }
            $t = [];
            foreach ($product_names_array as $v) {
                $t[$v->id] = (array)$v;
            }
           // dd($t);
            $post = formatPostForJsonColumn($post);

            $ar = json_decode($post['items']);
            $total = 0;
            $ar = array_map(function ($v) use ($t, $total) {
                $name = isset($t[$v->product_id]) ? $t[$v->product_id]['name'] : '';
                $price = isset($t[$v->product_id]) ? $t[$v->product_id]['price'] : '';
                $sgst = isset($t[$v->product_id]) ? $t[$v->product_id]['sgst'] : '';
                $csgt = isset($t[$v->product_id]) ? $t[$v->product_id]['cgst'] : '';
                $inclusive = isset($t[$v->product_id]) ? $t[$v->product_id]['tax_inclusive'] : 'Yes';
                $v->name = $name;
                $v->price = $price;
                $v->sgst = $sgst;
                $v->csgt = $price;
                $v->tax_inclusive = $inclusive;
                $total += $price * $v->quantity;
                return $v;
            }, $ar);
            foreach ($ar as $g) {
                $total += $g->price * $g->quantity;
            }

            unset($post['items']);
            //   dd($ar);
            $update_string = 'SET current_quantity=( CASE  ';
            $update_string1 = ',sold_quantity=( CASE  ';

            if (count($ar) > 0) {

                foreach ($ar as $item) {
                    $update_string .= 'WHEN product_id=' . $item->product_id . ' THEN current_quantity-' . $item->quantity;
                    $update_string1 .= 'WHEN product_id=' . $item->product_id . ' THEN sold_quantity+' . $item->quantity;
                    if ($item->product_id) {
                        //  dd($material_qty_array[$item->material_id]>$item->quantity);
                        if (isset($product_stock_array[$item->product_id])) { /***Mterial has stock addedd */
                            if ($product_stock_array[$item->product_id] < $item->quantity) {
                                return createResponse(false, 'Product <b>' . $item->name . '</b> is out of stock');
                            }
                        } else {
                            return createResponse(false, 'Please add stock for <b style="color:red"> ' . $item->name . '</b>');

                        }

                    }

                }
            }
            $update_string .= ' ELSE current_quantity END)';
            $update_string1 .= ' ELSE sold_quantity END)';
//dd($update_string);
            $post['items'] = json_encode($ar);
            $post['total'] = $total;
            //$post['uid']=
          
            $post['store_id'] = $store_id;

            $createorder = CreateOrder::create($post);

            $createorder->uid = $createorder->id . mt_rand(1000000, 9999999);
            $createorder->save();

            if (is_admin()) {
                \DB::statement('UPDATE admin_product_stocks ' . $update_string . ' ' . $update_string1);
            } else {
                \DB::statement('UPDATE store_assigned_product_stocks ' . $update_string . ' ' . $update_string1);

            }

            if ($this->has_upload) {
                foreach ($this->form_image_field_name as $item) {
                    $field_name = $item['field_name'];
                    $single = $item['single'];

                    if ($request->hasfile($field_name)) {
                        if (is_array($request->file($field_name))) {
                            $image_model_name = modelName($item['table_name']);
                            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                            $this->upload($request->file($field_name), $createorder->id, $image_model_name, $parent_table_field);
                        } else {
                            $image_name = $this->upload($request->file($field_name));
                            if ($image_name) {
                                $createorder->{$field_name} = $image_name;
                                $createorder->save();
                            }
                        }

                    }

                }

            }
            $data['settings'] = \DB::table('setting')->whereId(1)->first(); 
             $customer = \App\Models\Customer::with(['state', 'city'])->whereId($post['customer_id'])->first();
       
       
            $data['item_rows'] = $ar;
            $data['order_id'] =  $createorder->id;
            
            $data['customer'] = $customer;

            $pdf = PDF::loadView('admin.create_orders.invoice', $data);
            $path = storage_path('pdf/');
            $file_name = "invoice-" . $createorder->id . ".pdf";
            $pdf->save($path . $file_name);

            SendInvoiceMail::dispatch($createorder->id, $file_name);

            return createResponse(true, ' Order created successfully', $this->index_url);
        } catch (\Exception $ex) {
            return createResponse(false, $ex->getLine() . '==' . $ex->getMessage());
        }
    }
    public function edit($id)
    {

        $model = CreateOrder::findOrFail($id);

        $data = [
            [
                'label' => null,
                'inputs' => [
                    [
                        'name' => 'title',
                        'label' => 'Title',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->title : '',
                        'attr' => [],
                        'placeholder' => 'Enter title',

                    ],
                    [
                        'name' => 'customer_id',
                        'label' => 'Customer Id',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'customer_id', true) : getList('Customer')[0]->id,
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Customer'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'driver_id',
                        'label' => 'Driver Id',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'driver_id', true) : getList('Driver')[0]->id,
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Driver'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'dispatch_date',
                        'label' => 'Dispatch Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
                        'default' => isset($model) ? $model->dispatch_date : '',
                        'attr' => [],
                        'placeholder' => 'Enter',

                    ],
                    [
                        'name' => 'status',
                        'label' => 'Order Status',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'status', false) : getListFromIndexArray(['Pending', 'Dispatched', 'Cancelled', 'On The Way'])[0]->id,
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getListFromIndexArray(['Pending', 'Dispatched', 'Cancelled', 'On The Way']),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],

                ],
            ],
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
            'plural_lowercase' => 'create_orders', 'model' => $model,
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
            $data['row'] = CreateOrder::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = CreateOrder::findOrFail($id);
        }

        $data['has_image'] = $this->has_upload;
        $data['model_relations'] = $this->model_relations;
        $data['is_multiple'] = $this->is_multiple_upload;
        $data['storage_folder'] = $this->storage_folder;
        $data['table_columns'] = $this->table_columns;
        $data['plural_lowercase'] = 'create_orders';
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
            $data['row'] = CreateOrder::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = CreateOrder::findOrFail($id);
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
    public function update(CreateOrderRequest $request, $id)
    {
        try
        {
            $post = $request->all();

            $createorder = CreateOrder::findOrFail($id);

            $post = formatPostForJsonColumn($post);
            if (count($this->model_relations) > 0 && in_array('BelongsToMany', array_column($this->model_relations, 'type'))) {
                foreach (array_keys($post) as $key) {
                    if (isFieldBelongsToManyToManyRelation($this->model_relations, $key) >= 0) {
                        $post->$key->sync($post[$key]);
                    }
                }
            }
            $createorder->update($post);
            if ($this->has_upload) {
                foreach ($this->form_image_field_name as $item) {
                    $field_name = $item['field_name'];
                    $single = $item['single'];

                    if ($request->hasfile($field_name)) {
                        if (is_array($request->file($field_name))) {
                            $image_model_name = modelName($item['table_name']);
                            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                            $this->upload($request->file($field_name), $createorder->id, $image_model_name, $parent_table_field);
                        } else {
                            $image_name = $this->upload($request->file($field_name));
                            if ($image_name) {
                                $createorder->{$field_name} = $image_name;
                                $createorder->save();
                            }
                        }

                    }

                }

            }
            return createResponse(true, 'Order updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try
        {
            CreateOrder::destroy($id);

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

        if ($form_type == 'view') {
            $data['row'] = null;
            if (count($this->model_relations) > 0) {
                $data['row'] = CreateOrder::with(array_column($this->model_relations, 'name'))->findOrFail($id);
            } else {
                $data['row'] = CreateOrder::findOrFail($id);
            }
            $data['has_image'] = $this->has_upload;
            $data['model_relations'] = $this->model_relations;
            $data['storage_folder'] = $this->storage_folder;
            $data['table_columns'] = $this->table_columns;
            $data['plural_lowercase'] = 'create_orders';
            $data['module'] = $this->module;
            $data['image_field_names'] = $this->form_image_field_name;
            /***if columns shown in view is difrrent from table_columns jet
        $columns=\DB::getSchemaBuilder()->getColumnListing('create_orders');
        natcasesort($columns);

        $cols=[];
        $exclude_cols=['id','from_area','branch','to_area','coupon_id','user_id','delivery_type_id','signature','map','otp_code','incentive_checked','franchisee_id'];
        foreach($columns as $col){
        if($col=='order_unique_id')
        $col="order_tracking_id";
        $label=ucwords(str_replace('_',' ',$col));

        if(!in_array($col,$exclude_cols))
        array_push($cols,['column'=>$col,'label'=>$label,'sortable'=>'No']);
        }
        $data['table_columns']=$cols;
         ***/

        }
        if ($form_type == 'view') {
            $html = view('admin.' . $this->view_folder . '.' . $form_type . '_modal', with($data))->render();
            return createResponse(true, $html);
        } else {
            $html = view('admin.' . $this->view_folder . '.modal.' . $form_type, with($data))->render();
            return createResponse(true, $html);
        }
    }
    public function exportCreateOrder(Request $request, $type)
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
            return Excel::download(new \App\Exports\CreateOrderExport($this->model_relations, $filter, $filter_date, $date_field), 'create_orders' . date("Y-m-d H:i:s") . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($type == 'csv') {
            return Excel::download(new \App\Exports\CreateOrderExport($this->model_relations, $filter, $filter_date, $date_field), 'create_orders' . date("Y-m-d H:i:s") . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type == 'pdf') {
            return Excel::download(new \App\Exports\CreateOrderExport($this->model_relations, $filter, $filter_date, $date_field), 'create_orders' . date("Y-m-d H:i:s") . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
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
    public function generateInvoice(Request $r, $id)
    {
        $order_id = $id;
        $row = \DB::table('create_order')->whereId($order_id)->first();
        $customer = \App\Models\Customer::with(['state', 'city'])->whereId($row->customer_id)->first();
        $settings = \DB::table('setting')->whereId(1)->first();
        if ($row) {
            $data['row'] = $row;
            $data['settings'] = $settings;
            $data['customer'] = $customer;
            return view('admin.create_orders.show_invoice', with($data));
            // return createResponse(true,$html);
        } else {
            return createResponse(false, 'Order Not found');
        }

    }
    public function mailInvoice(Request $r)
    {
        if ($r->ajax()) {
            $order_id = $r->order_id;
            $row = \DB::table('create_order')->whereId($order_id)->first();

            $customer = \App\Models\Customer::with(['state', 'city'])->whereId($row->customer_id)->first();
            $settings = \DB::table('setting')->whereId(1)->first();

            $data['row'] = $row;
            $data['settings'] = $settings;
            $data['customer'] = $customer;
            if ($customer->email) {
                $pdf = PDF::loadView('admin.create_orders.invoice', $data);
                $file_name = "invoice-" . $order_id . ".pdf";

                $path = storage_path('pdf/');
                $pdf->save($path . $file_name);

                $str = view('emails.invoice', with($data))->render();
                try {
                    $resp = $this->mail($customer->email, 'Invoice Email', $str, storage_path('pdf/' . $file_name), 'invoice.pdf');
                    $createorder->is_mail_Sent = 'Yes';
                    $createorder->save();

                } catch (\Exception $ex) {
                    $createorder->mail_resp = $ex->getMessage();
                    $createorder->save();

                    dd($ex->getMessage());
                }
            }
        }

    }
    public function getOrderTotalAmount(Request $r)
    {
        if ($r->ajax()) {
            $order_id = $r->order_id;
            $row = \DB::table('create_order')->whereId($order_id)->first();

            return createResponse(true, json_encode(['total' => $row->total, 'paid' => $row->paid_amount, 'due_amount' => $row->due_amount]));

        } else {
            return createResponse(false, 'Invalid Request');
        }
    }
}
