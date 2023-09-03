<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use File;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use \Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        $this->index_url = route('users.index');
        $this->module = 'User';
        $this->view_folder = 'users';
        $this->storage_folder = $this->view_folder;
        $this->form_image_field_name = 'image';
        $this->is_multiple_upload = false;
        $this->parent_field_name = 'image';
        $this->image_model_name = '';
        $this->has_upload = 1;
        $this->pagination_count = 100;
        $this->columns_with_select_field = [['label' => 'Country', 'field_name' => 'country', 'onChange' => 'showStates(this.value)', 'multiple' => false]];
        $this->table_columns = [['column' => 'name', 'label' => 'Name', 'sortable' => 'Yes'],
            ['column' => 'email', 'label' => 'Email', 'sortable' => 'Yes'],
            ['column' => 'phone', 'label' => 'Phone', 'sortable' => 'Yes'],
            ['column' => 'address', 'label' => 'Address', 'sortable' => 'Yes'],
            ['column' => 'country', 'label' => 'Country', 'sortable' => 'Yes'],
            ['column' => 'status', 'label' => 'Status', 'sortable' => 'Yes'],
            ['column' => 'role', 'label' => 'Role', 'sortable' => 'No'],
            ['column' => 'image', 'label' => 'Profile', 'sortable' => 'No'],
            ['column' => 'created_at', 'label' => 'Date', 'sortable' => 'Yes']];
    }
    public function buildFilter(Request $r, $query)
    {
        $get = $r->all();
        if (count($get) > 0 && $r->isMethod('get')) {
            foreach ($get as $key => $value) {
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
                    $query = $query->where($key, $value);
                }
            }
        }
        return $query;
    }
    public function index(Request $request)
    {

        $searchable_fields = [['name' => 'name', 'label' => 'Name'], ['name' => 'email', 'label' => 'Email'], ['name' => 'phone', 'label' => 'Phone']];
        $filterable_fields = [['name' => 'created_at', 'label' => 'Date', 'type' => 'date'], ['name' => 'country', 'label' => 'Country', 'type' => 'select'], ['name' => 'state', 'label' => 'State', 'type' => 'select']];
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

            $list = User::with('withCountry')->when(!empty($search_val), function ($query) use ($search_val, $search_by) {
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
                'plural_lowercase' => 'users',
                'module' => $this->module,
                'has_image' => $this->has_upload,
                'is_multiple' => $this->is_multiple_upload,
                'image_field_name' => $this->form_image_field_name,
                'storage_folder' => $this->storage_folder,
            ];
            return view('admin.' . $this->view_folder . '.page', with($data));
        } else {
            $query = User::with(['withCountry']);
            $query = $this->buildFilter($request, $query);
            $list = $query->paginate($this->pagination_count);

            $view_data = [
                'list' => $list,
                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'All Users',
                'module' => $this->module,
                'searchable_fields' => $searchable_fields,
                'filterable_fields' => $filterable_fields,
                'storage_folder' => $this->storage_folder,
                'table_columns' => $table_columns,
                'plural_lowercase' => 'users',
                'has_image' => $this->has_upload,
                'is_multiple' => $this->is_multiple_upload,
                'image_field_name' => $this->form_image_field_name,
                'storage_folder' => $this->storage_folder,
            ];
            return view('admin.' . $this->view_folder . '.index', $view_data);
        }
    }

    public function getList($model,$where=[])
    {
        $model_class = "\App\models" . '\\' . $model;
        $lists = $model_class::query();
        if(count($where)>0){
            $lists=$lists->where('status','Active')->where($where);
        }
        $lists=$lists->get(['id', 'name']);

        $list2 = [];
        foreach ($lists as $list) {
            $ar = (object) ['id' => $list['id'], 'name' => $list['name']];
            array_push($list2, $ar);
        }
        return $list2;
    }
    public function getFieldValuesFromModelAsArray($model,$field,$where=[])
    {
        $model_class = "\App\models" . '\\' . $model;
        $lists = $model_class::query();
        if(count($where)>0){
            $lists=$lists->where('status','Active')->where($where);
        }
        $lists=$lists->get([$field]);

        $list4 = [];
        foreach ($lists as $list) {
            $list4[]=$list[$field];
            
        }
        return $list4;
    }
    public function getListFromIndexArray($arr=[])
    {
        /* for optinos in select not from model but from an array liek ['apple','mango']*/

        $list3 = [];
        foreach ($arr as $item) {
            $ar = (object) ['id' => $item, 'name' => $item];
            array_push($list3, $ar);
        }
        return $list3;
    }

    public function create()
    {
        $data = [['placeholder' => 'Enter Name', 'name' => 'name', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''], ['placeholder' => 'Enter Email', 'name' => 'name', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''], ['placeholder' => 'Enter Phone', 'name' => 'name', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''], ['name' => 'state', 'label' => 'State', 'tag' => 'select', 'type' => 'select', 'default' => '', 'custom_key_for_option' => 'name', 'options' => [], 'custom_id_for_option' => 'id', 'multiple' => false], ['name' => 'city', 'label' => 'City', 'tag' => 'select', 'type' => 'select', 'default' => '', 'custom_key_for_option' => 'name', 'options' => [], 'custom_id_for_option' => 'id', 'multiple' => false], ['name' => 'pincode', 'label' => 'Pincode', 'tag' => 'select', 'type' => 'select', 'default' => '', 'custom_key_for_option' => 'name', 'options' => [], 'custom_id_for_option' => 'id', 'multiple' => false], ['placeholder' => 'Enter Address', 'name' => 'address', 'label' => 'Address', 'tag' => 'input', 'type' => 'textarea', 'default' => '']];

        if (count($this->columns_with_select_field) > 0) {
            foreach ($this->columns_with_select_field as $t) {
                $input = ['name' => $t['field_name'], 'label' => $t['label'], 'type' => 'select', 'multiple' => $t['multiple'], 'custom_key_for_option' => 'name', 'options' => $this->getList($t['label']), 'event' => ['name' => 'onChange', 'function' => isset($t['onChange']) ? 'javascript:void(0)' : $t['onChange']]];
                array_push($data, $input);
            }
        }

        $radio_checkbox_group = [['name' => 'role', 'label' => 'Assign Role ', 'type' => 'checkbox', 'multiple' => true, 'value' => $roles, 'inline' => false], ['name' => 'status', 'label' => 'Status ', 'type' => 'checkbox', 'multiple' => true, 'value' => [['label' => 'Yes', 'value' => 'Yes'], ['label' => 'No', 'value' => 'No']], 'inline' => false]];
        $view_data = [
            'data' => $data,
            'radio' => $radio_checkbox_group,
            'dashboard_url' => $this->dashboard_url,
            'index_url' => $this->index_url,
            'title' => 'Create ' . $this->module,
            'module' => $this->module,
            'plural_lowercase' => 'users',
            'image_field_name' => $this->form_image_field_name,
            'has_image' => $this->has_upload,
            'is_multiple' => $this->is_multiple_upload,

            'storage_folder' => $this->storage_folder,
        ];
        return view('admin.' . $this->view_folder . '.add', with($view_data));
    }
    public function store(UserRequest $request)
    {
        \DB::beginTransaction();
        try {
            $user = User::create($request->all());
            $user->assignRole($request->role);
            if ($this->has_upload) {

                if ($this->is_multiple_upload) {
                    $this->upload($request, $user->id);
                } else {
                    $field = $this->form_image_field_name;
                    if($request->hasFile($field)){
                       $image_name = $this->upload($request);
                       if($image_name){
                                $user->{$field} = $image_name;
                                $user->save();
                       }
                    }
                }
            }
            \DB::commit();
            return createResponse(true, $this->module . ' created successfully', $this->index_url);
        } catch (\Exception$ex) {
             \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }
    public function edit($id)
    {

        $model = User::findOrFail($id);

        $data = [['placeholder' => 'Enter Name', 'name' => 'name', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''], ['placeholder' => 'Enter Email', 'name' => 'name', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''], ['placeholder' => 'Enter Phone', 'name' => 'name', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''], ['name' => 'state', 'label' => 'State', 'tag' => 'select', 'type' => 'select', 'default' => '', 'custom_key_for_option' => 'name', 'options' => [], 'custom_id_for_option' => 'id', 'multiple' => false], ['name' => 'city', 'label' => 'City', 'tag' => 'select', 'type' => 'select', 'default' => '', 'custom_key_for_option' => 'name', 'options' => [], 'custom_id_for_option' => 'id', 'multiple' => false], ['name' => 'pincode', 'label' => 'Pincode', 'tag' => 'select', 'type' => 'select', 'default' => '', 'custom_key_for_option' => 'name', 'options' => [], 'custom_id_for_option' => 'id', 'multiple' => false], ['placeholder' => 'Enter Address', 'name' => 'address', 'label' => 'Address', 'tag' => 'input', 'type' => 'textarea', 'default' => '']];
        if (count($this->columns_with_select_field) > 0) {
            foreach ($this->columns_with_select_field as $label => $field_name) {
                $input = ['name' => $field_name, 'label' => $label, 'type' => 'select', 'default' => $model->{field_name}, 'custom_key_for_option' => 'name', 'options' => $this->getList($label)];
                array_push($data, $input);
            }
        }
        $radio_checkbox_group = [['name' => 'role', 'label' => 'Assign Role ', 'type' => 'checkbox', 'multiple' => true, 'value' => $roles, 'inline' => false], ['name' => 'status', 'label' => 'Status ', 'type' => 'checkbox', 'multiple' => true, 'value' => [['label' => 'Yes', 'value' => 'Yes'], ['label' => 'No', 'value' => 'No']], 'inline' => false]];

        $view_data = [
            'data' => $data,
            'radio' => $radio_checkbox_group,
            'dashboard_url' => $this->dashboard_url,
            'index_url' => $this->index_url,
            'title' => 'Edit ' . $this->module,
            'module' => $this->module,
            'has_image' => $this->has_upload,
            'is_multiple' => $this->is_multiple_upload,
            'image_field_name' => $this->form_image_field_name,
            'storage_folder' => $this->storage_folder,
            'plural_lowercase' => 'users', 'model' => $model,
        ];
        if ($this->has_upload && $this->is_multiple_upload) {
            $view_data['image_list'] = $this->getImageList($id);
        }

        return view('admin.' . $this->view_folder . '.edit', with($view_data));

    }
    public function show($id)
    {

        $data['row'] = User::findOrFail($id);
        $data['has_image'] = $this->has_upload;
        $data['is_multiple'] = $this->is_multiple_upload;
        $data['storage_folder'] = $this->storage_folder;
        if ($data['is_multiple']) {

            $data['image_list'] = $this->getImageList($id);
        }
        $html = view('admin.' . $this->view_folder . '.view', with($data))->render();
        return createResponse(true, $html);
    }
    public function view(Request $request)
    {
        $id = $request->id;
        $data['row'] = User::findOrFail($id);
        $data['has_image'] = $this->has_upload;
        $data['is_multiple'] = $this->is_multiple_upload;
        $data['storage_folder'] = $this->storage_folder;
        $data['table_columns'] = $this->table_columns;
        if ($data['is_multiple']) {

            $data['image_list'] = $this->getImageList($id);
        }
        $html = view('admin.' . $this->view_folder . '.view', with($data))->render();
        return createResponse(true, $html);
    }
    public function update(UserRequest $request, $id)
    {
        \DB::beginTransaction();
      
        try
        {
            $user = User::findOrFail($id);
            $user->update($request->all());
            $user->assignRole($request->role);
            if ($this->has_upload) {
                if ($this->is_multiple_upload) {
                    $this->upload($request, $user->id);
                } else {
                    $field = $this->form_image_field_name;
                    $image_name = $this->upload($request);
                    if($image_name){
                        $user->{$field} = $image_name;
                        $user->save();
                     }
                   
                    
                }
            }
            \DB::commit();
            return createResponse(true, $this->module . ' updated successfully', $this->index_url);
        } catch (\Exception$ex) {
            \DB::rollback();
            return createResponse(false, $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try
        {
            User::destroy($id);

            if ($this->has_upload) {
                $this->deleteFile($id);
            }
            return createResponse(true, $this->module . ' Deleted successfully');
        } catch (\Exception$ex) {
            return createResponse(false, 'Failed to  Delete Properly');
        }

    }
    public function upload(Request $request, $parent_table_id = null)
    {
        $form_image_field_name = $this->form_image_field_name;
        $uploaded_filename=null;
        if ($request->file($form_image_field_name)!=null) {
          
            $uploaded_filename = $this->is_multiple_upload ?
            storeMultipleFile($this->storage_folder, $request->file($form_image_field_name), $this->image_model_name, $parent_table_id, $this->parent_field_name)
            : storeSingleFile($this->storage_folder, $request->file($form_image_field_name));
            if (!is_array($uploaded_filename)) {
                return $uploaded_filename;
            }

        }
        return $uploaded_filename;

        
        
    }
    public function deleteFile($id)
    {

        if (!$this->is_multiple_upload) {
            $model = $this->module;
            $model_field = $this->form_image_field_name;
            $rowid = $id;
            deleteSingleFileOwnTable($this->storage_folder, $model, $model_field, $rowid);
        } else {
            $filemodel = $this->image_model_name;
            deleteAllFilesFromRelatedTable($this->storage_folder, $this->parent_field_name, $id, $filemodel);
        }

    }

    public function getImageList($id)
    {
        $image_model = $this->image_model_name;
        $model = "App\\Models\\$image_model";
        return $model::where($this->parent_field_name, $id)->get(['id', 'name']);
    }
    public function getRadioOptions($model)
    {
        $model_class = "\App\models" . '\\' . $model;
        $lists = $model_class::get(['id', 'name']);
        $alist = [];
        foreach ($lists as $list) {
            $ar = (object) ['label' => $list['name'], 'value' => $list['id']];
            array_push($alist, $ar);
        }
        return $alist;
    }
    public function loadAjaxForm(Request $request)
    {
        $data = [];
        $form_type = $request->form_type;
        $id = $request->id;
        $roles = [];
        foreach (Role::all() as $role) {
            array_push($roles, (object) ['label' => $role->name, 'value' => $role->name]);
        }

        if ($form_type == 'add') {
            $data1 = [
                ['placeholder' => 'Enter Name', 'name' => 'name', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' => ''],
                ['placeholder' => 'Enter Email', 'name' => 'email', 'label' => 'Email', 'tag' => 'input', 'type' => 'email', 'default' => ''],
                ['placeholder' => 'Enter Password', 'name' => 'password', 'label' => 'Password', 'tag' => 'input', 'type' => 'password', 'default' => ''],
                ['placeholder' => 'Enter Phone', 'name' => 'phone', 'label' => 'Phone', 'tag' => 'input', 'type' => 'text', 'default' => ''],
            ];

            if (count($this->columns_with_select_field) > 0) {
               
                foreach ($this->columns_with_select_field as $t) {
                    $input = ['name' => $t['field_name'], 'label' => $t['label'], 'tag' => 'select', 'type' => 'select', 'default' =>[1,3],
                        'custom_key_for_option' => 'name', 'options' => $this->getList($t['label']), 'custom_id_for_option' => 'id', 'multiple' => $t['multiple'],
                        'event' => ['name' => 'onChange', 'function' => isset($t['onChange']) ? 'javascript:void(0)' : $t['onChange']],
                    ];

                    array_push($data1, $input);
                }
            }
            $data2 = [['name' => 'state', 'label' => 'State', 'tag' => 'select', 'type' => 'select', 'default' => '', 'custom_key_for_option' => 'name', 'options' => [], 'custom_id_for_option' => 'id', 'multiple' => false],
                ['name' => 'city', 'label' => 'City', 'tag' => 'select', 'type' => 'select', 'default' => '', 'custom_key_for_option' => 'name', 'options' => [], 'custom_id_for_option' => 'id', 'multiple' => false],
                ['name' => 'pincode', 'label' => 'Pincode', 'tag' => 'select', 'type' => 'select', 'default' => '', 'custom_key_for_option' => 'name', 'options' => [], 'custom_id_for_option' => 'id', 'multiple' => false],
                ['placeholder' => 'Enter Address', 'name' => 'address', 'label' => 'Address', 'tag' => 'input', 'type' => 'textarea', 'default' => '']];

            $data1 = array_merge($data1, $data2);
            $radio_checkbox_group = [
                ['name' => 'role', 'label' => 'Assign Role ', 'type' => 'checkbox', 'multiple' => true, 'value' => $roles, 'inline' => false, 'attr' => [], 'default' => []],
                ['name' => 'status', 'label' => 'Status ', 'default' => '', 'type' => 'checkbox', 'multiple' => false, 'value' => [(object) ['label' => 'Yes', 'value' => 'Yes'], (object) ['label' => 'No', 'value' => 'No']], 'inline' => false, 'attr' => []]];
            $data = [
                'data' => $data1,
                'radio' => $radio_checkbox_group,
                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'Create ' . $this->module,
                'module' => $this->module,
                'plural_lowercase' => 'users',
                'image_field_name' => $this->form_image_field_name,
                'has_image' => $this->has_upload,
                'is_multiple' => $this->is_multiple_upload,

                'storage_folder' => $this->storage_folder,
            ];

        }
        if ($form_type == 'edit') {
            $model = User::findOrFail($id);

            $data1 = [
                ['placeholder' => 'Enter Name', 'name' => 'name', 'label' => 'Name', 'tag' => 'input', 'type' => 'text', 'default' =>$model->name],
                ['placeholder' => 'Enter Email', 'name' => 'email', 'label' => 'Email', 'tag' => 'input', 'type' => 'email', 'default' =>$model->email],
                ['placeholder' => 'Enter Phone', 'name' => 'phone', 'label' => 'Phone', 'tag' => 'input', 'type' => 'text', 'default' =>$model->phone],
            ];

            if (count($this->columns_with_select_field) > 0) {
                foreach ($this->columns_with_select_field as $t) {
                    $input = ['name' => $t['field_name'], 'label' => $t['label'], 'tag' => 'select', 'type' => 'select', 'default' =>$model->{$t['field_name']},
                        'custom_key_for_option' => 'name', 'options' => $this->getList($t['label']), 'custom_id_for_option' => 'id', 'multiple' => false,
                        'event' => ['name' => 'onChange', 'function' => isset($t['onChange']) ? 'javascript:void(0)' : $t['onChange']],
                    ];

                    array_push($data1, $input);
                }
            }
            $state_options=$model->state?$this->getList('State',['id'=>$model->state]):[];
            $city_options=$model->city?$this->getList('City',['id'=>$model->city]):[];
            $pin_options=$model->pincode?$this->getList('Pincode',['id'=>$model->city]):[];
           
            $data2 = [['name' => 'state', 'label' => 'State', 'tag' => 'select', 'type' => 'select', 'default' =>$model->state, 'custom_key_for_option' => 'name', 'options' => $state_options, 'custom_id_for_option' => 'id', 'multiple' => false],
                ['name' => 'city', 'label' => 'City', 'tag' => 'select', 'type' => 'select', 'default' =>$model->city, 'custom_key_for_option' => 'name', 'options' =>  $city_options, 'custom_id_for_option' => 'id', 'multiple' => false],
                ['name' => 'pincode', 'label' => 'Pincode', 'tag' => 'select', 'type' => 'select', 'default' =>$model->pincode, 'custom_key_for_option' => 'name', 'options' => $pin_options, 'custom_id_for_option' => 'id', 'multiple' => false],
                ['placeholder' => 'Enter Address', 'name' => 'address', 'label' => 'Address', 'tag' => 'input', 'type' => 'textarea', 'default' =>$model->address]];

            $data1 = array_merge($data1, $data2);
            
            $radio_checkbox_group = [
                ['name' => 'role', 'label' => 'Assign Role ', 'type' => 'checkbox', 'multiple' => true, 'value' => $roles, 'inline' => false,'default'=>$model->getRoleNames()->toArray(),'attr'=>[]],
                ['name'=>'status','label'=>'Status ','type'=>'radio','multiple'=>false,'attr'=>[],'value'=>[(object)['label'=>'Active','value'=>'Active'],(object)['label'=>'In Active','value'=>'In-Active']],'inline'=>false,'default'=>$model->status]
            ];

            $data = [
                'data' => $data1,
                'radio' => $radio_checkbox_group,
                'dashboard_url' => $this->dashboard_url,
                'index_url' => $this->index_url,
                'title' => 'Edit ' . $this->module,
                'module' => $this->module,
                'has_image' => $this->has_upload,
                'is_multiple' => $this->is_multiple_upload,
                'image_field_name' => $this->form_image_field_name,
                'storage_folder' => $this->storage_folder,
                'plural_lowercase' => 'users', 'model' => $model,
            ];
            if ($this->has_upload && $this->is_multiple_upload) {
                $data['image_list'] = $this->getImageList($id);
            }

        }
        if ($form_type == 'view') {
            $data['row'] = User::with('withCountry')->findOrFail($id);
            $data['has_image'] = $this->has_upload;
            $data['is_multiple'] = $this->is_multiple_upload;
            $data['storage_folder'] = $this->storage_folder;
            $data['table_columns'] = $this->table_columns;
            $data['image_field_name'] = $this->form_image_field_name;
            /***if columns shown in view is difrrent from table_columns jet
            $columns=\DB::getSchemaBuilder()->getColumnListing('users');
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
            if ($data['is_multiple']) {

                $data['image_list'] = $this->getImageList($id);
            }
        }
        $html = view('admin.' . $this->view_folder . '.modal.' . $form_type, with($data))->render();
        return createResponse(true, $html);
    }
    public function exportUser(Request $request, $type)
    {
        $filter=[]; $filter_date=[];
        $date_field=null;
        foreach($_GET as $key=>$val){
          if(str_contains($key,'start_')){
             $date_field=str_replace('start_','',$key);
             $filter_date['min']=$val;
          }
          else if(str_contains($key,'end_')){
            $date_field=str_replace('end_','',$key);
            $filter_date['max']=$val;
          }
          else
             $filter[$key]=$val;
        }
        
        if ($type == 'excel') {
            return Excel::download(new \App\Exports\UserExport($filter,$filter_date,$date_field), 'users' . date("Y-m-d H:i:s") . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }

        if ($type == 'csv') {
            return Excel::download(new \App\Exports\UserExport($filter,$filter_date,$date_field), 'users' . date("Y-m-d H:i:s") . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        if ($type == 'pdf') {
            return Excel::download(new \App\Exports\UserExport($filter,$filter_date,$date_field), 'users' . date("Y-m-d H:i:s") . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
        }

    }
    public function pdf_generate_from_html()
    {
        $mpdf = new \Mpdf\Mpdf(['utf-8', 'A4-C']);
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->autoArabic = true;

        $mpdf->autoLangToFont = true;
        $html = "somehtml";
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        $mpdf->Output();

    }
}
