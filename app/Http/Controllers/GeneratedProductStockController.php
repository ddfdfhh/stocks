<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratedProductStockRequest;
use App\Models\GeneratedProductStock;
use Maatwebsite\Excel\Facades\Excel;
use \Illuminate\Http\Request;
use \DB;
class GeneratedProductStockController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('generated_product_stocks.index');
        $this->module = 'GeneratedProductStock';
        $this->view_folder = 'generated_product_stocks';
        $this->storage_folder = $this->view_folder;
        $this->has_upload = 0;
        $this->is_multiple_upload = 0;
        $this->has_export = 1;
        $this->pagination_count = 100;

        $this->table_columns = [
            [
                'column' => 'raw_materials',
                'label' => 'Raw Materials',
                'sortable' => 'No',
            ],
            [
                'column' => 'product_id',
                'label' => 'Product',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'quantity_produced',
                'label' => 'Quantity Produced',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'created_at',
                'label' => 'Created Date',
                'sortable' => 'Yes',
            ],
        ];
        $this->form_image_field_name = [];
        $this->repeating_group_inputs = [
            [
                'colname' => 'raw_materials',
                'label' => 'Enter Raw Material',
                'inputs' => [
                    [
                        'name' => 'raw_materials__json__material_id[]',
                        'label' => 'Material',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => '',
                        'attr' => [
                            'class' => 'prod_sel', 'onChange' => 'calculateProductPrice()',
                        ],
                        'custom_key_for_option' => 'name',
                        'options' => getListMaterialWithQty(),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter quantity',
                        'name' => 'raw_materials__json__quantity[]',
                        'label' => 'Quantity',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => '',
                        'attr' => ['onChange' => 'calculateProductPrice()'],
                    ],
                ],
            ],
        ];
        $this->toggable_group = [];
        $this->model_relations = [
            [
                'name' => 'product',
                'class' => 'App\\Models\\GeneratedProductStock',
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
                'name' => 'quantity_produced',
                'label' => 'Quantity Produced',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'product_id',
                'label' => 'Product',
                'type' => 'select',
                'options' => getList('Product'),
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

            $list = GeneratedProductStock::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
                'plural_lowercase' => 'generated_product_stocks',
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
                $query = GeneratedProductStock::with(array_column($this->model_relations, 'name'));
            } else {
                $query = GeneratedProductStock::query();
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->paginate($this->pagination_count);
            $view_data = [
                'list' => $list,
                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'All GeneratedProductStocks',
                'module' => $this->module, 'model_relations' => $this->model_relations,
                'searchable_fields' => $searchable_fields,
                'filterable_fields' => $filterable_fields,
                'storage_folder' => $this->storage_folder,
                'table_columns' => $table_columns,
                'plural_lowercase' => 'generated_product_stocks',
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
                'label' => 'Final Product',
                'inputs' => [
                    [
                        'name' => 'product_id',
                        'label' => 'Product',
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
                        'placeholder' => 'Enter quantity_produced',
                        'name' => 'quantity_produced',
                        'label' => 'Quantity Produced',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->quantity_produced : "",
                        'attr' => [],
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
            'plural_lowercase' => 'generated_product_stocks',
            'image_field_names' => $this->form_image_field_name,
            'has_image' => $this->has_upload,
            'model_relations' => $this->model_relations,

            'repeating_group_inputs' => $this->repeating_group_inputs,
            'toggable_group' => $this->toggable_group,
            'storage_folder' => $this->storage_folder,
        ];
        return view('admin.' . $this->view_folder . '.add', with($view_data));
    }
     public function upsertAdminProductStock($post)
    {
        $qty = $post['quantity_produced'];
        $update = [
            'total_quantity' => DB::raw('total_quantity+' . $qty),
            'current_quantity' => DB::raw('current_quantity+' . $qty),
            'generated_quantity' => DB::raw('generated_quantity+' . $qty),
           
        ];
        

        if (\DB::table('admin_product_stocks')->where(['product_id' => $post['product_id']])->exists()) {
            \DB::table('admin_product_stocks')->where(['product_id' => $post['product_id']])->update($update);
        } else {
            \DB::table('admin_product_stocks')->insert(array_merge($update, ['product_id' => $post['product_id']]));
        }
    }
    public function store(GeneratedProductStockRequest $request)
    {
        \DB::beginTransaction();
        try {
            $post = $request->all();
            // dd($post);
            $material_ids = $post['raw_materials__json__material_id'];
            $material_names_array = \DB::table('input_material')->whereIn('id', $material_ids)->pluck('name', 'id')->toArray();
            $material_unit_array = \DB::table('input_material')->whereIn('id', $material_ids)->pluck('unit_id', 'id')->toArray();
            $unit_name_array = \DB::table('unit')->whereIn('id', array_values($material_unit_array))->pluck('name', 'id')->toArray();
            $material_qty_array = \DB::table('material_stocks')->whereIn('material_id', $material_ids)->pluck('current_stock', 'material_id')->toArray();
            // dd( $material_ids);
            $post = formatPostForJsonColumn($post);
            $ar = json_decode($post['raw_materials']);

            $ar = array_map(function ($v) use ($material_names_array, $unit_name_array, $material_unit_array) {
                $unit_id = $material_unit_array[$v->material_id];
                $name = isset($material_names_array[$v->material_id]) ? $material_names_array[$v->material_id] : '';
                $v->name = $name;
                $v->unit = isset($unit_name_array[$unit_id]) ? $unit_name_array[$unit_id] : '';
                $v->quantity=$v->quantity;
                return $v;
            }, $ar);
            //dd($material_qty_array);
            unset($post['raw_materials']);
            if (count($material_qty_array) < 1) {
                return createResponse(false, 'Please add stock for raw materials');

            }
           //print_r($material_qty_array);
         //  dd($ar);
            if (count($ar) > 0) {
                //   print_r($material_qty_array);
                //   dd($ar);
                foreach ($ar as $item) {
                    if ($item->material_id) {
                     
                        if (isset($material_qty_array[$item->material_id])) { /***Mterial has stock addedd */
                            if ($material_qty_array[$item->material_id] < $item->quantity) {
                                return createResponse(false, 'Insufficent quantity for ' . $item->name);
                            }
                        } else {
                            return createResponse(false, 'Please add stock for raw material ' . $item->name);

                        }
                        \DB::table('material_stocks')->where('material_id', $item->material_id)
                            ->decrement('current_stock', $item->quantity,['total_outgoing'=>\DB::raw('total_outgoing+'. $item->quantity)]);
                    }

                }
            }

            $post['raw_materials'] = json_encode($ar);
            $this->upsertAdminProductStock($post);
            $generatedproductstock = GeneratedProductStock::create($post);
            \DB::commit();
            //dd('ok');
            return createResponse(true, 'Product Stock created successfully', $this->index_url);
        } catch (\Exception $ex) {
            \DB::rollback();
            return createResponse(false, $ex->getLine() . '==' . $ex->getMessage());
        }
    }
    public function edit($id)
    {

        $model = GeneratedProductStock::findOrFail($id);

        $data = [
            [
                'label' => 'Final Product',
                'inputs' => [
                    [
                        'name' => 'product_id',
                        'label' => 'Product',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'product_id', true) : getList('Product')[0]->id,
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('Product'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter quantity_produced',
                        'name' => 'quantity_produced',
                        'label' => 'Quantity Produced',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->quantity_produced : "",
                        'attr' => [],
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
            'plural_lowercase' => 'generated_product_stocks', 'model' => $model,
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
            $data['row'] = GeneratedProductStock::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = GeneratedProductStock::findOrFail($id);
        }

        $data['has_image'] = $this->has_upload;
        $data['model_relations'] = $this->model_relations;
        $data['is_multiple'] = $this->is_multiple_upload;
        $data['storage_folder'] = $this->storage_folder;
        $data['table_columns'] = $this->table_columns;
        $data['plural_lowercase'] = 'generated_product_stocks';
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
            $data['row'] = GeneratedProductStock::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = GeneratedProductStock::findOrFail($id);
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
    public function update(GeneratedProductStockRequest $request, $id)
    {
        try
        {
            $post = $request->all();

            $generatedproductstock = GeneratedProductStock::findOrFail($id);
            $material_ids = $post['raw_materials__json__material_id'];
            $material_names_array = \DB::table('input_material')->whereIn('id', $material_ids)->pluck('name', 'id')->toArray();

            $post = formatPostForJsonColumn($post);
            $ar = json_decode($post['raw_materials']);

            $ar = array_map(function ($v) use ($material_names_array) {
                $name = isset($material_names_array[$v->material_id]) ? $material_names_array[$v->material_id] : '';
                $v->name = $name;
                return $v;
            }, $ar);
            unset($post['raw_materials']);

            $post['raw_materials'] = json_encode($ar);

            $generatedproductstock->update($post);

            return createResponse(true, ' Product stock updated successfully', $this->index_url);
        } catch (\Exception $ex) {
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try
        {
            GeneratedProductStock::destroy($id);

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
                    'label' => 'Final Product',
                    'inputs' => [
                        [
                            'name' => 'product_id',
                            'label' => 'Product',
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
                            'placeholder' => 'Enter quantity_produced',
                            'name' => 'quantity_produced',
                            'label' => 'Quantity Produced',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->quantity_produced : "",
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
                'plural_lowercase' => 'generated_product_stocks',
                'image_field_names' => $this->form_image_field_name,
                'has_image' => $this->has_upload,

                'repeating_group_inputs' => $this->repeating_group_inputs,
                'toggable_group' => $this->toggable_group,
                'storage_folder' => $this->storage_folder,
            ];

        }
        if ($form_type == 'edit') {
            $model = GeneratedProductStock::findOrFail($id);

            $data1 = [
                [
                    'label' => 'Final Product',
                    'inputs' => [
                        [
                            'name' => 'product_id',
                            'label' => 'Product',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'product_id', true) : getList('Product')[0]->id,
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('Product'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'placeholder' => 'Enter quantity_produced',
                            'name' => 'quantity_produced',
                            'label' => 'Quantity Produced',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->quantity_produced : "",
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
                'plural_lowercase' => 'generated_product_stocks', 'model' => $model,
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
                $data['row'] = GeneratedProductStock::with(array_column($this->model_relations, 'name'))->findOrFail($id);
            } else {
                $data['row'] = GeneratedProductStock::findOrFail($id);
            }
            $data['has_image'] = $this->has_upload;
            $data['model_relations'] = $this->model_relations;
            $data['storage_folder'] = $this->storage_folder;
            $data['table_columns'] = $this->table_columns;
            $data['plural_lowercase'] = 'generated_product_stocks';
            $data['module'] = $this->module;
            $data['image_field_names'] = $this->form_image_field_name;
            /***if columns shown in view is difrrent from table_columns jet
        $columns=\DB::getSchemaBuilder()->getColumnListing('generated_product_stocks');
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
    public function exportGeneratedProductStock(Request $request, $type)
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
            return Excel::download(new \App\Exports\GeneratedProductStockExport($this->model_relations, $filter, $filter_date, $date_field), 'generated_product_stocks' . date("Y-m-d H:i:s") . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($type == 'csv') {
            return Excel::download(new \App\Exports\GeneratedProductStockExport($this->model_relations, $filter, $filter_date, $date_field), 'generated_product_stocks' . date("Y-m-d H:i:s") . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type == 'pdf') {
            return Excel::download(new \App\Exports\GeneratedProductStockExport($this->model_relations, $filter, $filter_date, $date_field), 'generated_product_stocks' . date("Y-m-d H:i:s") . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
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
    public function calculateProductPrice(Request $r)
    {
        if ($r->ajax()) {
            $post = $r->all();
            $sum = 0;
            $material_ids = $post['raw_materials__json__material_id'];
            $material_qty = array_values($post['raw_materials__json__quantity']);

            $material_ids = array_values($material_ids);
            $t = \DB::table('input_material')->whereIn('id', $material_ids)->pluck('rate', 'id')->toArray();
            $i = 0;

            foreach ($material_ids as $id) {

                $sum += $t[$id] * $material_qty[$i];

                $i++;
            }
            return createResponse(true, $sum*$post['quantity_produced']);
        }
    }
}
