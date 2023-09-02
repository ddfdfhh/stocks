<?php

if (!function_exists('baseUrl')) {
    function baseUrl()
    {
        return config('app.base_url');
    }
}
if (!function_exists('adminUrl')) {
    function adminUrl()
    {
        return config('app.admin_subdomain') . '.' . baseUrl();
    }
}
if (!function_exists('formateDate')) {
    function formateDate($v)
    {
        return date('j F,Y', strtotime($v));
    }
}
function getFieldById($model, $id, $field)
{
    $mod = app("App\\Models\\$model");
    $model = $mod->find($id);
    return $model->{$field};

}
function getCount($table, $where = [])
{
    return count($where) > 0 ? \DB::table($table)->where($where)->count() : \DB::table($table)->count();

}
function getArrayFromModel($model, $fields_label = [])
{
    $mod = app("App\\Models\\$model");
    $fields = $mod->getFillable();
    return array_combine($fields_label, $fields);

}
function getNameToIdPairFromModel($model, $array_names = [])
{
    $mod = app("App\\Models\\$model");
    return $mod->whereIn('name', $array_names)->get(['id', 'name'])->toArray();

}
function extractNameOnlyAsArray($val = [])
{

    return array_map(function ($v) {
        return explode("-", $v)[1];

    }, $val);

}
function convertToKeyValPair($val = [])
{

    return array_map(function ($v) {
        $r = explode("-", $v);
        return ["id" => $r[0], "key" => $r[1]];

    }, $val);

}
function renderSelectOptionsFromJsonCOlumnOfIdNamePair($json_val = [])
{

    return array_map(function ($v) {
        $r = explode("-", $v);
        return [$r[0] => $r[1]];

    }, $val);

}
function properPluralName($str)
{

    return ucwords(str_replace('_', ' ', $str));

}
function properSingularName($str)
{

    return ucwords(str_replace('_', ' ', \Str::singular($str)));

}
function modelName($str)
{
    $str = \Str::singular($str);
    $spl = explode('_', $str);
    $spl = array_map(function ($v) {
        return ucfirst($v);
    }, $spl);
    $new_str = implode('', $spl);
    return $new_str;

}
function isFieldBelongsToManyToManyRelation($rel_ar, $field)
{
    $found = -1;
    $i = 0;
    foreach ($rel_ar as $item) {
        if ($field == $item['name'] && $item['type'] == 'BelongsToMany') {
            $found = $i;
            break;
        }
        $i++;
    }
    return $found;
}
function isFieldPresentInRelation($rel_ar, $field)
{
    $found = -1;
    $i = 0;
    foreach ($rel_ar as $item) {
        if (($item['name'] == $field) || ($item['name'] . '_id' == $field)) {
            $found = $i;

            break;
        }
        $i++;
    }
    return $found;
}

function getForeignKeyFieldValue($rel_ar, $row, $field, $key_toget_as_per_relation = ['BelongsTo' => 'name'])
{
    // dd($field);
    $resp = '';
    $item['name'] = 'category';
   // $field = 'category_id';
    foreach ($rel_ar as $item) {
          $field = $field == $item['name'] . '_id' ? $item['name'] : $field;
        //dd($field);
        $get_by_field = isset($key_toget_as_per_relation[$item['type']]) ? $key_toget_as_per_relation[$item['type']] : 'name';
        //dd($field.'==='.$item['name']);
        if ($field == $item['name']) {

            if ($item['type'] == 'BelongsTo' || $item['type'] == 'HasOne') {
                $resp = $row->{$field}->{$get_by_field};
            } elseif ($item['type'] == 'HasMany' || $item['type'] == 'ManyToMany') {

                if ($row->{$field}) {
                    $val_ar = array_column($row->{$field}->toArray(), $get_by_field);
                    $resp = showArrayInColumn($val_ar);

                }

            }
        }
    }
    return $resp;
}

function getModelRelations($model)
{
    $model = app("App\\Models\\$model");
    $reflector = new \ReflectionClass($model);
    $relations = [];
    foreach ($reflector->getMethods() as $reflectionMethod) {
        $returnType = $reflectionMethod->getReturnType();

        if ($returnType) {
            $type = class_basename($returnType->getName());
            if (in_array($type, ['HasOne', 'HasMany', 'BelongsTo', 'BelongsToMany', 'MorphToMany', 'MorphTo'])) {
                $t = (array) $reflectionMethod;
                $t['type'] = $type;
                $relations[] = $t;
            }
        }
    }
    return $relations;
}

function getAllModels()
{
    $path = app_path() . "/Models";
    $out = [];
    $results = scandir($path);
    foreach ($results as $result) {
        if ($result === '.' or $result === '..') {
            continue;
        }

        $filename = $result;
        $out[] = substr($filename, 0, -4);

    }
    $out[] = 'Self';
    return $out;

}
function getTables()
{
    return array_map('current', \DB::select('SHOW TABLES'));
}
function getPivotTableName($model1, $model2)
{
    $t = [$model1, $model2];
    sort($t);

    return implode('_', $t);

}
function formatPostForJsonColumn($post)
{

    $json_cols = [];/****like storing first part in json field */
    $json_keys = [];/****like storing last part in json field */
    $ar_val = [];
    $no_of_values_in_arr = 0;
    foreach ($post as $key => $val) {
        if (is_array($post[$key])) {
            if (count($post[$key]) > 0) {
                if (str_contains($key, '__json__')) {

                    $spl = explode("__json__", $key);
                     $col_name = $spl[0];
                     $key_name = $spl[1];

                   // $no_of_values_in_arr = count($post[$key]);
                    if(!isset($post[$col_name])){
                       
                        $json_cols[] = $col_name;
                        

                        $json_keys[] = $key_name; /***like storing size */
                    }
                    else{/****kyunki json column toggle mein bhi hota hai to unko unset karo psot se  */
                        $val=$post[$key];
                        unset($post[$key]);
                        $post[$key_name]=$val[0];/*toggable div ke case mein ham kewal first val store kara rahe hai not array u can change***/
                    }

                } else { /***if key is index array  */
                    $post[$key] = json_encode($post[$key]);
                }
            } else { /***if key val is empty  */
                $post[$key] = null;
            }

        }
    }

    $json_cols = array_unique($json_cols);
  
    foreach ($json_cols as $colname) {
        if (count($json_keys) > 0) {

           $p=[];
                foreach ($json_keys as $key) {
                   
                    if(isset($post[$colname . '__json__' . $key])){
                        $values = $post[$colname . '__json__' . $key];
                        $p[$key] = $values;
                    }
                }
                $ar_val[$colname][] = $p;

            
        }
      
    }
    if (count($ar_val) > 0) {
        foreach ($ar_val as $key => $val) {
             $keys=array_keys($val[0]);
             $val_count=count($val[0][$keys[0]]);
           
             $t=[];
             for($i=0;$i<($val_count);$i++){
                $x = [];

                   foreach ($keys as $k) {
                           $x[$k]=$val[0][$k][$i];
                            

                        }
             $t[]=$x;
             }
            // dd($t);
            $post[$key] = json_encode($t);

        }
    }
//dd($post);
    return $post;
}
function showArrayInColumn($arr = [], $row_index = 0)
{
    if (!empty($arr)) {
        if (!is_array($arr)) {
            $arr = $arr->toArray();
        }

        if (!is_array($arr[0])) {

            return implode(',', $arr);
        }
        elseif (isset($arr[0]) && !is_array($arr[0])) {

            return implode(',', $arr);
        }
         elseif (!isArrayEmpty($arr)) {

            $keys = array_keys($arr[0]);
            $header = '<tr>';
            foreach ($keys as $k) {
                $header .= '<th>' . $k . '</th>';
            }
            $header .= '</th>';
            $body = '';
            foreach ($arr as $val) {
                $body .= '<tr>';
                foreach ($val as $k => $v) {
                    $body .= '<td>' . $v . '</td>';
                }
                $body .= '</tr>';
            }

            $str = '<button type="button" class="btn-sm btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal' . $row_index . '">
          View
        </button>
        <div class="modal" id="myModal' . $row_index . '">
          <div class="modal-dialog">
            <div class="modal-content">

              <div class="modal-body">
              <table class="table table-bordered">
              <thead>
              ' . $header . '
              </thead>
              <tbody>' . $body . '</tbody>
              </table>
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
                <button type="button" class="btn-sm btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>

            </div>
          </div>
        </div>';
            return $str;
        }
    } else {
        return '';
    }

}
function fieldExist($model, $field_name, $value)
{
    $mod = app("App\\Models\\$model");
    return $mod->where($field_name, $value)->exists();
}
/***Storage app folder default location for storeAS */
function storeSingleFile($folder, $filerequest)
{
    $filenameWithExt = $filerequest->getClientOriginalName();
    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    $extension = $filerequest->getClientOriginalExtension();
    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
    $path = $filerequest->storeAs('public/' . $folder, $fileNameToStore);

    return $fileNameToStore;
}
function getValidation()
{
    return [
        (object) ['label' => 'Required', 'value' => 'required'],
        (object) ['label' => 'Image', 'value' => 'image'],
        (object) ['label' => 'Numeric', 'value' => 'numeric'],
        (object) ['label' => 'Nullable', 'value' => 'nullable'],
        (object) ['label' => 'String', 'value' => 'string'],
        (object) ['label' => 'Email', 'value' => 'email'],
    ];
}
function getInputs()
{
    return [
        (object) ['label' => 'Text', 'value' => 'text'],
        (object) ['label' => 'Email', 'value' => 'email'],
        (object) ['label' => 'Textarea', 'value' => 'textarea'],
        (object) ['label' => 'Number', 'value' => 'number'],
        (object) ['label' => 'File', 'value' => 'file'],
        (object) ['label' => 'Select', 'value' => 'select'],
        (object) ['label' => 'Radio', 'value' => 'radio'],
        (object) ['label' => 'Checkbox', 'value' => 'checkbox'],

    ];
}

function storeMultipleFile($folder, $filerequest, $imagemodel, $parent_id, $parent_key_fieldname)
{
    $mod = app("App\\Models\\$imagemodel");
    $files = $filerequest;
    $i = 0;
    $ar_files = [];
    $data = [];
    foreach ($files as $file) {
        ++$i;
        $filenameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
        $path = $file->storeAs('public/' . $folder, $fileNameToStore);

        array_push($ar_files, $fileNameToStore);
        //  dd($ar_files);
        $data[] = [
            'name' => $fileNameToStore,
            '' . $parent_key_fieldname . '' => $parent_id, 'created_at' => date("Y-m-d H:i:s")];
    }
    $mod->insert($data);
    return $ar_files;
}
function createResponse($success, $message, $redirect_url = null)
{
    return response()->json(['success' => $success, 'message' => $message, 'redirect_url' => $redirect_url]);

}
function isArrayEmpty($ar)
{
    $keys = array_keys($ar[0]);

    $is_empty = false;
    foreach ($keys as $key) {
        if (empty($ar[0][$key])) {
            $is_empty = true;
        }

    }
    return $is_empty;
}
function getTableNameFromImageFieldList($list, $fieldname)
{

    $table_name = null;
    if (count($list) > 0) {
        foreach ($list as $item) {
            if ($item['field_name'] == $fieldname) {
                $table_name = !empty($item['table_name']) ? $item['table_name'] : '';

                break;
            }
        }
    }
    return $table_name;
}
function deleteSingleFileFromRelatedTable($folder, $fileid, $filemodel)
{
    $mod = app("App\\Models\\$filemodel");
    $filerow = $mod->findOrFail($fileid);
    $path = storage_path('app/public/' . $folder . '/' . $filerow->name);
    if (\File::exists($path)) {
        unlink($path);

    }
}
function deleteAllFilesFromRelatedTable($folder, $parent_field_name, $parent_id, $filemodel)
{
    $mod = app("App\\Models\\$filemodel");
    $rows = $mod->where($parent_field_name, $parent_id);
    if ($rows->count() > 0) {
        foreach ($rows as $t) {
            $path = storage_path('app/public/' . $folder . '/' . $t->name);
            if (\File::exists($path)) {
                unlink($path);
            }

        }

    }
}
function deleteSingleFileOwnTable($folder, $model, $model_field, $rowid)
{
    $mod = app("App\\Models\\$model");
    $row = $mod->findOrFail($rowid);
    $path = storage_path('app/public/' . $folder . '/' . $row->{$model_field});
    $mod->findOrFail($rowid)->update(['' . $model_field . '' => null]);
    if (\File::exists($path)) {
        unlink($path);
    }
}
function getImageList($id, $image_model, $parent_field_name)
{
    $model = "App\\Models\\$image_model";
    return $model::where($parent_field_name, $id)->get(['id', 'name']);
}
function getFieldValuesFromModelAsArray($model, $field, $where = [])
{
    $model_class = "\App\Models" . '\\' . $model;
    $lists = $model_class::query();
    if (count($where) > 0) {
        $lists = $lists->where('status', 'Active')->where($where);
    }
    $lists = $lists->get([$field]);

    $list4 = [];
    foreach ($lists as $list) {
        $list4[] = $list[$field];

    }
    return $list4;
}
function getRadioOptions($model, $where = [], $by_field = 'name')
{
    $model_class = "\App\Models" . '\\' . $model;
    $lists = $model_class::query();
    if (count($where) > 0) {
        $lists = $lists->where('status', 'Active')->where($where);
    }
    $field_to_get = !empty($by_field) ? $by_field : 'name';
    $lists = $lists->get(['id', $field_to_get]);
    $alist = [];
    foreach ($lists as $list) {
        $ar = (object) ['label' => $list[$field_to_get], 'value' => $list['id']];
        array_push($alist, $ar);
    }
    return $alist;
}
function getListFromIndexArray($arr = []) /* for optinos in select not from model but from an array liek ['apple','mango']*/
{

    $list3 = [];
    foreach ($arr as $item) {
        $ar = (object) ['id' => $item, 'name' => $item];
        array_push($list3, $ar);
    }
    return $list3;
}
function getList($model, $where = [], $by_field = 'name')
{
    $model_class = "\App\Models" . '\\' . $model;
    $lists = $model_class::query();
    if (count($where) > 0) {
        $lists = $lists->where('status', 'Active')->where($where);
    }
    $lists = $lists->get(['id', $by_field]);

    $list2 = [];
    foreach ($lists as $list) {
        $ar = (object) ['id' => $list['id'], 'name' => $list[$by_field]];
        array_push($list2, $ar);
    }
    return $list2;
}
function getListOnlyNonIdValue($model, $where = [], $by_field = 'name')
{
    $model_class = "\App\Models" . '\\' . $model;

    $lists = $model_class::query();
    if (count($where) > 0) {
        $lists = $lists->where($where);
    }
    $lists = $lists->pluck($by_field)->toArray();

    return $lists;
}
/******remove below thing any time  */

function getValOfArraykey($ar, $key, $is_array = true)
{
    return isset($ar[$key]) ? $ar[$key] : ($is_array ? [] : null);
}

function isThisATableColumn($table, $field)
{
    $fields_ar = \Illuminate\Support\Facades\Schema::getColumnListing($table);
    return in_array($field, $fields_ar);

}

function isIndexedArray($ar)
{

    return !is_array($ar[0]);
}
function getColumnType($table, $column)
{
    \Schema::getColumnType($table, $column);
}
function isFieldInRelationsArray($rel_ar, $field)
{
    $value_fetchable_field = str_contains($field, '_id') ? explode('_', $field)[0] : $field;
    return in_array($value_fetchable_field, array_column($rel_ar, 'name'));
}

function formatDefaultValueForSelect($model_relations, $model, $field, $is_multiple, $get_json_by_key_for_show_in_default = 'name')
{

    $table = $model->getTable();
    $value_fetchable_field = str_contains($field, '_id') ? explode('_', $field)[0] : $field;
    // dd($field);
    $field_type = getColumnType($table, $field);
    if ($is_multiple) {
        $field_value = ($field_type == 'json' || $field_type == 'Json') ? json_decode($model->{$value_fetchable_field}, true) : $model->{$value_fetchable_field};
        $isTableColumn = isThisATableColumn($table, $field);
        if ($isTableColumn) {
            return isIndexedArray($field_value) ? $field_value : (isFieldInRelationsArray($model_relations, $field) ? array_column($field_value, 'id') : array_column($field_value, $get_json_by_key_for_show_in_default));
        } else {
            return $model->{$value_fetchable_field} ? array_column($model->{$value_fetchable_field}->toArray(), 'id') : [];
        }
    } else {
        return $model->{$value_fetchable_field};
    }
    function formatDefaultValueForCheckbox($model, $field)
    {

        return $model->{$field} ? json_decode($model->{$field}, true) : [];

    }

}
// function showRelationalColumn($model_relations, $model, $field)
// {

//     $table = $model->getTable();
//     $value_fetchable_field = str_contains($field, '_id') ? explode('_', $field)[0] : $field;
//    $resp=null;
//    $isTableColumn = isThisATableColumn($table, $field);
//    if( $isTableColumn){
//      $field_type = getColumnType($table, $field);
//       $field_value= json_decode($model->{$value_fetchable_field}, true);
//    return isIndexedArray($field_value) ? $field_value : (isFieldInRelationsArray($model_relations, $value_fetchable_field) ? array_column($field_value, $get_json_by_key_for_show_in_default) : array_column($field_value, $get_json_by_key_for_show_in_default));

//    }

//     if ($field_type == 'json' || $field_type == 'Json') {
//         $field_value= json_decode($model->{$value_fetchable_field}, true);
//         $isTableColumn = isThisATableColumn($table, $field);
//         if ($isTableColumn) {
//             return isIndexedArray($field_value) ? $field_value : (isFieldInRelationsArray($model_relations, $value_fetchable_field) ? array_column($field_value, $get_json_by_key_for_show_in_default) : array_column($field_value, $get_json_by_key_for_show_in_default));
//         } else {
//             return $model->{$field} ? array_column($model->{$field}->toArray(), 'id') : [];
//         }
//     } else {
//         return $model->{$value_fetchable_field};
//     }
//     function formatDefaultValueForCheckbox($model, $field)
//     {

//         return $model->{$field} ? json_decode($model->{$field}, true) : [];

//     }

// }

function formatDefaultValueForSelectEdit($model, $field, $is_multiple)
{

    if ($is_multiple) {
        return json_decode($model->{$field}, true);

    } else {
        return $model->{$field};
    }
}
function formatDefaultValueForCheckbox($model, $field)
{

    return $model->{$field} ? json_decode($model->{$field}, true) : [];

}
