<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadsRequest;
use App\Models\Leads;
use File;
use Maatwebsite\Excel\Facades\Excel;
use \Illuminate\Http\Request;

class LeadsController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('leads.index');
        $this->module = 'Leads';
        $this->view_folder = 'leads';
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
                'column' => 'lead_name',
                'label' => 'Lead Name',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'lead_phone_no',
                'label' => 'PhoneNo',
                'sortable' => 'Yes',
            ],
           
            [
                'column' => 'product_id',
                'label' => 'Product/Service',
                'sortable' => 'No',
            ],
            [
                'column' => 'status',
                'label' => 'Status',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'conversations',
                'label' => 'Conversations',
                'sortable' => 'Yes',
            ],
            [
                'column' => 'created_at',
                'label' => 'Created At',
                'sortable' => 'Yes',
            ],
           
            [
                'column' => 'followup_date',
                'label' => 'Follow Up Date',
                'sortable' => 'No',
            ],
        ];
        $this->form_image_field_name = [];
       //$this->repeating_group_inputs = [];

      $this->repeating_group_inputs=[
    [
        'colname' => 'enquired_products_detail',
        'label' => 'More Detail About Products/Services Enquired',
        'inputs' => [
            [
                'name' => 'enquired_products_detail__json__product_id[]',
                'label' => 'Select  Product',
                'tag' => 'select',
                'type' => 'select',
                'default' => '',
                'attr' => [],
                'custom_key_for_option' => 'name',
                'options' => getList('Product'),
                'custom_id_for_option' => 'id',
                'multiple' => false
            ],
            [
                'placeholder' => 'Enter quantity',
                'name' => 'enquired_products_detail__json__quantity[]',
                'label' => 'Quantity Requested',
                'tag' => 'input',
                'type' => 'number',
                'default' => '',
                'attr' => []
            ],
            [
                'placeholder' => 'Enter price',
                'name' => 'enquired_products_detail__json__price[]',
                'label' => 'Price Requested',
                'tag' => 'input',
                'type' => 'number',
                'default' => '',
                'attr' => []
            ]
        ]
    ]];
        $this->toggable_group = [];
        $this->model_relations = [
            [
                'name' => 'source',
                'class' => 'App\\Models\\Leads',
                'type' => 'BelongsTo',
            ],
            [
                'name' => 'assigned_to',
                'class' => 'App\\Models\\Leads',
                'type' => 'BelongsTo',
            ],
            [
                'name' => 'product',
                'class' => 'App\\Models\\Leads',
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

        if (!can('list_leads')) {
            return redirect(route('admin.unauthorized'));
        }
        $searchable_fields = [
            [
                'name' => 'address',
                'label' => 'Address',
            ],
            [
                'name' => 'company_name',
                'label' => 'Company Name',
            ],
            [
                'name' => 'designation',
                'label' => 'Designation',
            ],
            [
                'name' => 'email',
                'label' => 'Email',
            ],
            [
                'name' => 'lead_name',
                'label' => 'Lead Name',
            ],
            [
                'name' => 'lead_phone_no',
                'label' => 'Lead Phone No',
            ],
            [
                'name' => 'whatsapp_no',
                'label' => 'Whatsapp No',
            ],
        ];
        $filterable_fields = [
            [
                'name' => 'assigned_id',
                'label' => 'Assigned To',
                'type' => 'select',
                'options' => getList('User')
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'type' => 'date',
            ],
            [
                'name' => 'followup_date',
                'label' => 'Followup Date',
                'type' => 'date',
            ],
            [
                'name' => 'product_id',
                'label' => 'Product',
                'type' => 'select',
                'options' => getList('Product')
            ],
            [
                'name' => 'source_id',
                'label' => 'Lead Source  ',
                'type' => 'select',
                  'options' => getList('LeadSource')
            ],
         
    [
        'name' => 'status',
        'label' => 'Status',
        'type' => 'select',
        'options' => getListFromIndexArray(['Failed','Working'])
    ],
    [
        'name' => 'type',
        'label' => 'Lead Type',
        'type' => 'select',
        'options' => getListFromIndexArray(['Cold','Warm','Hot'])
    ]
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

            $list = Leads::when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
                'plural_lowercase' => 'leads',
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
                $query = Leads::with(array_column($this->model_relations, 'name'));
            } else {
                $query = Leads::query();
            }
            $query = $this->buildFilter($request, $query);
            $list = $query->paginate($this->pagination_count);
            $view_data = [
                'list' => $list,
                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'All Leadss',
                'module' => $this->module, 'model_relations' => $this->model_relations,
                'searchable_fields' => $searchable_fields,
                'filterable_fields' => $filterable_fields,
                'storage_folder' => $this->storage_folder,
                'table_columns' => $table_columns,
                'plural_lowercase' => 'leads',
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
                        'placeholder' => 'Enter lead_name',
                        'name' => 'lead_name',
                        'label' => 'Lead Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->lead_name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter lead_phone_no',
                        'name' => 'lead_phone_no',
                        'label' => 'Phone No',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->lead_phone_no : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter email',
                        'name' => 'email',
                        'label' => 'Email',
                        'tag' => 'input',
                        'type' => 'email',
                        'default' => isset($model) ? $model->email : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter company_name',
                        'name' => 'company_name',
                        'label' => 'Company Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->company_name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter designation',
                        'name' => 'designation',
                        'label' => 'Designation',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->designation : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter whatsapp_no',
                        'name' => 'whatsapp_no',
                        'label' => 'Whatsapp No',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->whatsapp_no : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'source_id',
                        'label' => 'Lead Source',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'source_id', false) : (!empty(getList('LeadSource')) ? getList('LeadSource')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('LeadSource'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'product_id',
                        'label' => 'Product/Service',
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
                        'placeholder' => 'Enter address',
                        'name' => 'address',
                        'label' => 'Address',
                        'tag' => 'textarea',
                        'type' => 'text',
                        'default' => isset($model) ? $model->address : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'status',
                        'label' => 'Lead Status',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'status', false) : 'Working',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => [
                            (object) [
                                'id' => 'Working',
                                'name' => 'Working',
                            ],
                            (object) [
                                'id' => 'Contacted',
                                'name' => 'Contacted',
                            ],
                            (object) [
                                'id' => 'Failed',
                                'name' => 'Failed',
                            ],
                            (object) [
                                'id' => 'Converted',
                                'name' => 'Converted',
                            ],
                        ],
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'type',
                        'label' => 'Lead Type',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) ? $model->type : 'Cold',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Cold',
                                'value' => 'Cold',
                            ],
                            (object) [
                                'label' => 'Warm',
                                'value' => 'Warm',
                            ],
                            (object) [
                                'label' => 'Hot',
                                'value' => 'Hot',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter followup_date',
                        'name' => 'followup_date',
                        'label' => 'Follow Up Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
                        'default' => isset($model) ? $model->followup_date : "",
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
            'plural_lowercase' => 'leads',
            'image_field_names' => $this->form_image_field_name,
            'has_image' => $this->has_upload,
            'model_relations' => $this->model_relations,

            'repeating_group_inputs' => $this->repeating_group_inputs,
            'toggable_group' => $this->toggable_group,
            'storage_folder' => $this->storage_folder,
        ];
        return view('admin.' . $this->view_folder . '.add', with($view_data));
    }
    public function store(LeadsRequest $request)
    {
        if (!can('add_leads')) {
            return createResponse(false, 'Dont have permission');
        }
        \DB::beginTransaction();
        try {
            $post = $request->all();

            
             $ids = $post['enquired_products_detail__json__product_id'];
            $material_names_array = \DB::table('product')->whereIn('id', $ids)->get(['name','id']);
            $t=[];
            foreach($material_names_array as $v){
                 $t[$v->id]=['name'=>$v->name]; 
            }
            
            $post = formatPostForJsonColumn($post);
            
            $ar = json_decode($post['enquired_products_detail']);
            $total=0;
            $ar = array_map(function ($v) use ($t) {
                $name = isset($t[$v->product_id]) ? $t[$v->product_id]['name'] : '';
            
                $v->name = $name;
               
                return $v;
            }, $ar);
            
           
            unset($post['enquired_products_detail']);

            $post['enquired_products_detail'] = json_encode($ar);
            $post['assigned_id'] = auth()->id();
            $leads = Leads::create($post);

            if ($this->has_upload) {
                foreach ($this->form_image_field_name as $item) {
                    $field_name = $item['field_name'];
                    $single = $item['single'];

                    if ($request->hasfile($field_name)) {
                        if (is_array($request->file($field_name))) {
                            $image_model_name = modelName($item['table_name']);
                            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                            $this->upload($request->file($field_name), $leads->id, $image_model_name, $parent_table_field);
                        } else {
                            $image_name = $this->upload($request->file($field_name));
                            if ($image_name) {
                                $leads->{$field_name} = $image_name;
                                $leads->save();
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

        $model = Leads::findOrFail($id);

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
                        'placeholder' => 'Enter lead_name',
                        'name' => 'lead_name',
                        'label' => 'Lead Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->lead_name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter lead_phone_no',
                        'name' => 'lead_phone_no',
                        'label' => 'Phone No',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->lead_phone_no : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter email',
                        'name' => 'email',
                        'label' => 'Email',
                        'tag' => 'input',
                        'type' => 'email',
                        'default' => isset($model) ? $model->email : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter company_name',
                        'name' => 'company_name',
                        'label' => 'Company Name',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->company_name : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter designation',
                        'name' => 'designation',
                        'label' => 'Designation',
                        'tag' => 'input',
                        'type' => 'text',
                        'default' => isset($model) ? $model->designation : "",
                        'attr' => [],
                    ],
                    [
                        'placeholder' => 'Enter whatsapp_no',
                        'name' => 'whatsapp_no',
                        'label' => 'Whatsapp No',
                        'tag' => 'input',
                        'type' => 'number',
                        'default' => isset($model) ? $model->whatsapp_no : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'source_id',
                        'label' => 'Lead Source',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'source_id', false) : (!empty(getList('LeadSource')) ? getList('LeadSource')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('LeadSource'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'product_id',
                        'label' => 'Product/Service',
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
                        'name' => 'assigned_id',
                        'label' => 'Assigned To',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'assigned_id', false) : (!empty(getList('User')) ? getList('User')[0]->id : ''),
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => getList('User'),
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'placeholder' => 'Enter address',
                        'name' => 'address',
                        'label' => 'Address',
                        'tag' => 'textarea',
                        'type' => 'text',
                        'default' => isset($model) ? $model->address : "",
                        'attr' => [],
                    ],
                    [
                        'name' => 'status',
                        'label' => 'Lead Status',
                        'tag' => 'select',
                        'type' => 'select',
                        'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'status', false) : 'Working',
                        'attr' => [],
                        'custom_key_for_option' => 'name',
                        'options' => [
                            (object) [
                                'id' => 'Working',
                                'name' => 'Working',
                            ],
                            (object) [
                                'id' => 'Contacted',
                                'name' => 'Contacted',
                            ],
                            (object) [
                                'id' => 'Failed',
                                'name' => 'Failed',
                            ],
                            (object) [
                                'id' => 'Converted',
                                'name' => 'Converted',
                            ],
                        ],
                        'custom_id_for_option' => 'id',
                        'multiple' => false,
                    ],
                    [
                        'name' => 'type',
                        'label' => 'Lead Type',
                        'tag' => 'input',
                        'type' => 'radio',
                        'default' => isset($model) ? $model->type : 'Cold',
                        'attr' => [],
                        'value' => [
                            (object) [
                                'label' => 'Cold',
                                'value' => 'Cold',
                            ],
                            (object) [
                                'label' => 'Warm',
                                'value' => 'Warm',
                            ],
                            (object) [
                                'label' => 'Hot',
                                'value' => 'Hot',
                            ],
                        ],
                        'has_toggle_div' => [],
                        'multiple' => false,
                        'inline' => true,
                    ],
                    [
                        'placeholder' => 'Enter followup_date',
                        'name' => 'followup_date',
                        'label' => 'Follow Up Date',
                        'tag' => 'input',
                        'type' => 'datetime-local',
                        'default' => isset($model) ? $model->followup_date : "",
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
            'plural_lowercase' => 'leads', 'model' => $model,
        ];
        if ($this->has_upload && $this->is_multiple_upload) {
            $view_data['image_list'] = $this->getImageList($id);
        }

        return view('admin.' . $this->view_folder . '.edit', with($view_data));

    }
    public function show($id)
    {
        if (!can('view_leads')) {
            return createResponse(false, 'Dont have permission for this action');
        }

        $data['row'] = null;
        if (count($this->model_relations) > 0) {
            $data['row'] = Leads::with(array_column($this->model_relations, 'name'))->findOrFail($id);
        } else {
            $data['row'] = Leads::findOrFail($id);
        }
//dd($data['row']->toArray());
        $data['has_image'] = $this->has_upload;
        $data['model_relations'] = $this->model_relations;
        $data['is_multiple'] = $this->is_multiple_upload;
        $data['storage_folder'] = $this->storage_folder;
        $data['table_columns'] = $this->table_columns;
        $data['plural_lowercase'] = 'leads';
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
        return view('admin.' . $this->view_folder . '.view', with($data));

    }

    public function update(LeadsRequest $request, $id)
    {
        if (!can('edit_leads')) {
            return createResponse(false, 'Dont have permission');
        }
        \DB::beginTransaction();
        try
        {
            $post = $request->all();

            $leads = Leads::findOrFail($id);

            $ids = $post['enquired_products_detail__json__product_id'];
            $material_names_array = \DB::table('product')->whereIn('id', $ids)->get(['name','id']);
            $t=[];
            foreach($material_names_array as $v){
                 $t[$v->id]=['name'=>$v->name]; 
            }
            
            $post = formatPostForJsonColumn($post);
            
            $ar = json_decode($post['enquired_products_detail']);
            $total=0;
            $ar = array_map(function ($v) use ($t) {
                $name = isset($t[$v->product_id]) ? $t[$v->product_id]['name'] : '';
            
                $v->name = $name;
               
                return $v;
            }, $ar);
            
           
            unset($post['enquired_products_detail']);

            $post['enquired_products_detail'] = json_encode($ar);
            $leads->update($post);
            if ($this->has_upload) {
                foreach ($this->form_image_field_name as $item) {
                    $field_name = $item['field_name'];
                    $single = $item['single'];

                    if ($request->hasfile($field_name)) {
                        if (is_array($request->file($field_name))) {
                            $image_model_name = modelName($item['table_name']);
                            $parent_table_field = !empty($item['parent_table_field']) ? $item['parent_table_field'] : null;
                            $this->upload($request->file($field_name), $leads->id, $image_model_name, $parent_table_field);
                        } else {
                            $image_name = $this->upload($request->file($field_name));
                            if ($image_name) {
                                $leads->{$field_name} = $image_name;
                                $leads->save();
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
        if (!can('delete_leads')) {
            return createResponse(false, 'Dont have permission to delete');
        }
        \DB::beginTransaction();
        try
        {
            Leads::destroy($id);

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
            if (!can('create_leads')) {
                return createResponse(false, 'Dont have permission to create ');
            }
            $data1 = [
                [
                    'label' => null,
                    'inputs' => [
                        [
                            'placeholder' => 'Enter lead_name',
                            'name' => 'lead_name',
                            'label' => 'Lead Name',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->lead_name : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter lead_phone_no',
                            'name' => 'lead_phone_no',
                            'label' => 'Phone No',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->lead_phone_no : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter email',
                            'name' => 'email',
                            'label' => 'Email',
                            'tag' => 'input',
                            'type' => 'email',
                            'default' => isset($model) ? $model->email : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter company_name',
                            'name' => 'company_name',
                            'label' => 'Company Name',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->company_name : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter designation',
                            'name' => 'designation',
                            'label' => 'Designation',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->designation : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter whatsapp_no',
                            'name' => 'whatsapp_no',
                            'label' => 'Whatsapp No',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->whatsapp_no : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'source_id',
                            'label' => 'Lead Source',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'source_id', false) : (!empty(getList('LeadSource')) ? getList('LeadSource')[0]->id : ''),
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('LeadSource'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'name' => 'product_id',
                            'label' => 'Product/Service',
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
                            'placeholder' => 'Enter address',
                            'name' => 'address',
                            'label' => 'Address',
                            'tag' => 'textarea',
                            'type' => 'text',
                            'default' => isset($model) ? $model->address : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'status',
                            'label' => 'Lead Status',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'status', false) : 'Working',
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => [
                                (object) [
                                    'id' => 'Working',
                                    'name' => 'Working',
                                ],
                                (object) [
                                    'id' => 'Contacted',
                                    'name' => 'Contacted',
                                ],
                                (object) [
                                    'id' => 'Failed',
                                    'name' => 'Failed',
                                ],
                                (object) [
                                    'id' => 'Converted',
                                    'name' => 'Converted',
                                ],
                            ],
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'name' => 'type',
                            'label' => 'Lead Type',
                            'tag' => 'input',
                            'type' => 'radio',
                            'default' => isset($model) ? $model->type : 'Cold',
                            'attr' => [],
                            'value' => [
                                (object) [
                                    'label' => 'Cold',
                                    'value' => 'Cold',
                                ],
                                (object) [
                                    'label' => 'Warm',
                                    'value' => 'Warm',
                                ],
                                (object) [
                                    'label' => 'Hot',
                                    'value' => 'Hot',
                                ],
                            ],
                            'has_toggle_div' => [],
                            'multiple' => false,
                            'inline' => true,
                        ],
                        [
                            'placeholder' => 'Enter followup_date',
                            'name' => 'followup_date',
                            'label' => 'Follow Up Date',
                            'tag' => 'input',
                            'type' => 'datetime-local',
                            'default' => isset($model) ? $model->followup_date : "",
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
                'plural_lowercase' => 'leads',
                'image_field_names' => $this->form_image_field_name,
                'has_image' => $this->has_upload,

                'repeating_group_inputs' => $this->repeating_group_inputs,
                'toggable_group' => $this->toggable_group,
                'storage_folder' => $this->storage_folder,
            ];

        }
        if ($form_type == 'edit') {
            if (!can('edit_leads')) {
                return createResponse(false, 'Dont have permission to update');
            }
            $model = Leads::findOrFail($id);

            $data1 = [
                [
                    'label' => null,
                    'inputs' => [
                        [
                            'placeholder' => 'Enter lead_name',
                            'name' => 'lead_name',
                            'label' => 'Lead Name',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->lead_name : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter lead_phone_no',
                            'name' => 'lead_phone_no',
                            'label' => 'Phone No',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->lead_phone_no : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter email',
                            'name' => 'email',
                            'label' => 'Email',
                            'tag' => 'input',
                            'type' => 'email',
                            'default' => isset($model) ? $model->email : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter company_name',
                            'name' => 'company_name',
                            'label' => 'Company Name',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->company_name : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter designation',
                            'name' => 'designation',
                            'label' => 'Designation',
                            'tag' => 'input',
                            'type' => 'text',
                            'default' => isset($model) ? $model->designation : "",
                            'attr' => [],
                        ],
                        [
                            'placeholder' => 'Enter whatsapp_no',
                            'name' => 'whatsapp_no',
                            'label' => 'Whatsapp No',
                            'tag' => 'input',
                            'type' => 'number',
                            'default' => isset($model) ? $model->whatsapp_no : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'source_id',
                            'label' => 'Lead Source',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'source_id', false) : (!empty(getList('LeadSource')) ? getList('LeadSource')[0]->id : ''),
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => getList('LeadSource'),
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'name' => 'product_id',
                            'label' => 'Product/Service',
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
                            'placeholder' => 'Enter address',
                            'name' => 'address',
                            'label' => 'Address',
                            'tag' => 'textarea',
                            'type' => 'text',
                            'default' => isset($model) ? $model->address : "",
                            'attr' => [],
                        ],
                        [
                            'name' => 'status',
                            'label' => 'Lead Status',
                            'tag' => 'select',
                            'type' => 'select',
                            'default' => isset($model) ? formatDefaultValueForSelectEdit($model, 'status', false) : 'Working',
                            'attr' => [],
                            'custom_key_for_option' => 'name',
                            'options' => [
                                (object) [
                                    'id' => 'Working',
                                    'name' => 'Working',
                                ],
                                (object) [
                                    'id' => 'Contacted',
                                    'name' => 'Contacted',
                                ],
                                (object) [
                                    'id' => 'Failed',
                                    'name' => 'Failed',
                                ],
                                (object) [
                                    'id' => 'Converted',
                                    'name' => 'Converted',
                                ],
                            ],
                            'custom_id_for_option' => 'id',
                            'multiple' => false,
                        ],
                        [
                            'name' => 'type',
                            'label' => 'Lead Type',
                            'tag' => 'input',
                            'type' => 'radio',
                            'default' => isset($model) ? $model->type : 'Cold',
                            'attr' => [],
                            'value' => [
                                (object) [
                                    'label' => 'Cold',
                                    'value' => 'Cold',
                                ],
                                (object) [
                                    'label' => 'Warm',
                                    'value' => 'Warm',
                                ],
                                (object) [
                                    'label' => 'Hot',
                                    'value' => 'Hot',
                                ],
                            ],
                            'has_toggle_div' => [],
                            'multiple' => false,
                            'inline' => true,
                        ],
                        [
                            'placeholder' => 'Enter followup_date',
                            'name' => 'followup_date',
                            'label' => 'Follow Up Date',
                            'tag' => 'input',
                            'type' => 'datetime-local',
                            'default' => isset($model) ? $model->followup_date : "",
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
                'plural_lowercase' => 'leads', 'model' => $model,
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
                $data['row'] = Leads::with(array_column($this->model_relations, 'name'))->findOrFail($id);
            } else {
                $data['row'] = Leads::findOrFail($id);
            }
            $data['has_image'] = $this->has_upload;
            $data['model_relations'] = $this->model_relations;
            $data['storage_folder'] = $this->storage_folder;
            $data['table_columns'] = $this->table_columns;
            $data['plural_lowercase'] = 'leads';
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
            if (!can('view_leads')) {
                return createResponse(false, 'Dont have permission to view');
            }
            $html = view('admin.' . $this->view_folder . '.' . $form_type . '_modal', with($data))->render();
            return createResponse(true, $html);
        } else {
            $html = view('admin.' . $this->view_folder . '.modal.' . $form_type, with($data))->render();
            return createResponse(true, $html);
        }
    }
    public function exportLeads(Request $request, $type)
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
            return Excel::download(new \App\Exports\LeadsExport($this->model_relations, $filter, $filter_date, $date_field), 'leads' . date("Y-m-d H:i:s") . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($type == 'csv') {
            return Excel::download(new \App\Exports\LeadsExport($this->model_relations, $filter, $filter_date, $date_field), 'leads' . date("Y-m-d H:i:s") . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type == 'pdf') {
            return Excel::download(new \App\Exports\LeadsExport($this->model_relations, $filter, $filter_date, $date_field), 'leads' . date("Y-m-d H:i:s") . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
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
    public function addEditRemark(\App\Http\Requests\LeadConversationRequest $r)
    {
        if ($r->ajax()) {
            \DB::beginTransaction();
            try {
                $msg = $r->conversation;
                $lead_id = $r->lead_id;
                $t = \DB::table('leads')->whereId($lead_id)->first();
                if (is_null($t)) {
                    return createResponse(false, 'Please refresh the page and try again');
                }
                $existing_conversations = $t->conversations?json_decode($t->conversations, true):[];
                $new_conversation = [
                    'by_user_id' => auth()->id(),
                    'name' => auth()->user()->name,
                    'message' => $msg,
                    'date' => date("Y-m-d H:i:s"),
                ];
                array_push($existing_conversations, $new_conversation);
                $convetsations = json_encode($existing_conversations);
                \App\Models\Leads::whereId($lead_id)->update(['conversations' => $convetsations]);
                \DB::commit();
                return createResponse(true, 'Conversation added successfullly');
            } catch (\Exception $ex) {
                \DB::rollback();
                return createResponse(false, $ex->getMessage());

            }
        } else {
            return createResponse(false, 'Invalid Request');
        }

    }
   
}
