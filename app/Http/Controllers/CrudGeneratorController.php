<?php

namespace App\Http\Controllers;

use Brick\VarExporter\VarExporter;
use File;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Http\Request;

class CrudGeneratorController extends Controller
{
    public function __construct()
    {
        $this->dashboard_url = \URL::to('/admin');
        // $this->index_url = route('crud_generate.index');
        $this->module = 'CrudGenerate';
        $this->view_folder = 'crud';
        $this->storage_folder = $this->view_folder;
        $this->relation_array_manymany = [];
        $this->relation_array = [];
    }
    public function index()
    {
        return view('admin.crud.index');
    }
    public function generateModule(Request $r)
    {
        if (count($r->all()) > 0) {
            try
            {
                $post = $r->all();
                //  dd($post);
                $ar = [];
                if ($post['index_page_cols'][0]) {
                    $ar['index_page_config'] = $this->formatForIndexPage($post);
                }

                if ($post['has_upload'] == 'Yes') {
                    $ar['image_config'] = $this->formatForImage($post);
                }

                if ($post['create_fields'][0]) {
                    $ar['create_input_config'] = $this->pickCreateInputs($post);
                }
                if ($post['toggalbe_fields'][0]) {
                    $ar['toggable_input_config'] = $this->formatForToggable($post);
                }

                //   dd($ar);
                if ($post['validation_fields'][0][0]) {
                    $ar['validation_config'] = $this->formatForArrayValidation($post);
                }

                $ar['has_repeating_group'] = isset($post['has_repeating_group']) ? $post['has_repeating_group'][0] : false;
                if ($ar['has_repeating_group']) {
                    $ar['repeatable_config'] = $this->formatForRepeatable($post);
                }

                if (count($ar['repeatable_config']) > 0) {
                    foreach ($ar['repeatable_config'] as $item) {
                        $input_set = $item['inputs'];
                        if (count($input_set) > 0) {
                            $input_names = array_column($input_set, 'name');
                            foreach ($input_names as $name) {
                                $name = rtrim($name, '[]');
                                $colname = explode('__json__', $name)[0];
                                $ar['validation_config'][$name] = 'nullable';
                                if (!array_key_exists($colname, $ar['validation_config'])) {
                                    $ar['validation_config'][$colname] = 'nullable';
                                }

                            }

                        }
                    }
                }
                if (isset($ar['toggable_input_config']) && count($ar['toggable_input_config']) > 0) {
                    $toggable_columns = array_column($ar['toggable_input_config'], 'colname');
                    $create_inputs = array_column($ar['create_input_config'], 'inputs');

                    $ar['create_input_config'] = array_map(function ($item) use ($toggable_columns, $post) {
                        $inputs = $item['inputs'];
                        $index_found = 0;
                        foreach ($inputs as $inp) {
                            if (in_array($inp['name'], $toggable_columns)) {
                                $inp['has_toggle_div'] = ['toggle_div_id' => $inp['name'] . '_toggle', 'inputidforvalue' => '', 'plural_lowercase' => $post['table'], 'rowid' => ''];
                                $inp['attr']['onChange'] = 'toggleDivDisplay(this.value,' . $post['table'] . ',"' . $inp['name'] . '_toggle")';
                                break;
                            }
                            $index_found++;
                        }
                        $item['inputs'][$index_found] = $inp;
                        return $item;

                    }, $ar['create_input_config']);

                }
                if (isset($ar['image_config']) && count($ar['repeatable_config']) > 0) {
                    $add_in_grp = 0;
                    $inputs = $ar['create_input_config'][$add_in_grp]['inputs'];
                    foreach ($ar['image_config'] as $item) {
                        $p = ["placeholder" => "Enter name",
                            "name" => $item['single'] ? $item["field_name"] : $item["field_name"] . ' []\'',
                            "label" => $item['single'] ? properSingularName($item["field_name"]) : properPluralName($item["field_name"]),
                            "tag" => "input",
                            "type" => "file",
                            "default" => "",
                            "attr" => $item['single'] ? [] : ['multiple' => 'multiple'],
                        ];
                        array_push($inputs, $p);
                    }
                    $ar['create_input_config'][$add_in_grp]['inputs'] = $inputs;
                }
                // dd($ar);
                $ar['filterable_fields'] = getValOfArraykey($post, 'filterable_fields', true);
                $ar['searchable_fields'] = getValOfArraykey($post, 'searchable_fields', true);
                $ar['searchable_fields'] = array_map(function ($item) {
                    return ['name' => $item, 'label' => properSingularName($item)];
                }, $ar['searchable_fields']);
                $ar['filterable_fields'] = array_map(function ($item) {
                    return ['name' => $item, 'label' => properSingularName($item), 'type' => str_contains($item, '_at') ? 'date' : 'select'];
                }, $ar['filterable_fields']);
                $ar['table'] = getValOfArraykey($post, 'table', false);

                $ar['module'] = getValOfArraykey($post, 'module', false);
                $ar['modal'] = getValOfArraykey($post, 'modal', false);
                $ar['plural'] = getValOfArraykey($post, 'plural', false);
                $ar['export'] = getValOfArraykey($post, 'export', false);
                $ar['has_upload'] = getValOfArraykey($post, 'has_upload', false);

                $ar['exportable_fields'] = getValOfArraykey($post, 'export_fields', false);
                //  dd($ar);
                $this->createFile($ar);

                return redirect()->back()->with('success', 'Successfully created');
            } catch (\Exception $ex) {
                echo ($ex->getLine());

                dd($ex->getMessage());
            }
        }
        $data['tables'] = getTables();
        $data['module'] = 'Crud';
        return view('admin.crud.add', with($data));

    }

    public function generateTable(Request $r)
    {

        if (count($r->all()) > 0) {
            //dd($r->all());
            try {
                $post = $r->all();
                //       dd($post);
                foreach ($post as $key => $val) {
                    if (str_contains($key, 'col_name')) {
                        $spl = explode('__', $key);
                        $index = !isset($spl[1]) ? 0 : $spl[1];
                        $group_indexes[] = $index;
                    }
                }
                // dd($group_indexes);
                if (count($group_indexes) > 0) {
                    $ar = ['table' => $post['table'], 'timestamps' => $post['timestamps'],
                        'many_to_many_model' => $post['many_to_many_models'],
                        'many_to_many_relationship_name' => $post['many_to_many_relationship_name'],
                        'has_many_relationship_name' => $post['has_many_relationship_name'],
                        'has_one_relationship_name' => $post['has_one_relationship_name'],
                        'has_many_model' => $post['has_many_model'],
                        'has_one_model' => $post['has_one_model'],
                        'has_one_fk' => $post['has_one_fk'],
                        'has_many_fk' => $post['has_many_fk'],

                    ];
                    foreach ($group_indexes as $index) {
                        $col_name = '';
                        $p['relationship_table'] = '';
                        $p['relationship_name'] = '';
                        $p['relationship_type'] = '';
                        $p['relationship_foreign_table_key'] = '';
                        $p['relationship_my_key'] = '';
                        $p['props'] = [];
                        $p['data_type'] = '';
                        $p['enums'] = '';

                        $append = $index > 0 ? '__' . $index : '';
                        $p['col_name'] = $post['col_name' . $append];
                        $p['relationship_model'] = $post['relationship_model' . $append];
                        $p['relationship_type'] = $post['relationship_type' . $append];
                        if ($p['relationship_type'] && empty($p['relationship_model'])) {
                            $parent_model = explode('_', $p['col_name'])[0];
                            $p['relationship_model'] = ucwords($parent_model);
                        }
                        if ($p['relationship_type']) {
                            $p['relationship_name'] = !empty($post['relationship_name' . $append]) ? $post['relationship_name' . $append] : strtolower($p['relationship_model']);
                            $p['relationship_foreign_table_key'] = !empty($post['relationship_foreign_table_key' . $append]) ? $post['relationship_foreign_table_key' . $append] : $p['col_name'];
                            $p['relationship_my_key'] = !empty($post['relationship_my_key' . $append]) ? $post['relationship_my_key' . $append] : 'id';
                        }
                        $p['constraints'] = isset($post['contraints' . $append]) ? $post['contraints' . $append] : [];
                        $p['data_type'] = $post['data_type' . $append];
                        $p['enums'] = $post['enums' . $append];

                        $ar['columns'][] = $p;
                    }
                    // dd($ar);
                }
                $this->makeModel($ar);
                $this->makeTable($ar);
                if (!empty($ar)) {
                    //$this->makeModel($ar);
                    if ($ar['many_to_many_model'][0]) {
                        $model_path = app_path("/Models/" . modelName($ar['table']) . ".php");
                        $this->makeManyToMany($ar, $model_path);
                    }
                    if ($ar['has_many_model'][0]) {
                        $model_path = app_path("/Models/" . modelName($ar['table']) . ".php");
                        $this->makeHasMany($ar, $model_path);
                    }
                    if ($ar['has_one_model'][0]) {
                        $model_path = app_path("/Models/" . modelName($ar['table']) . ".php");
                        $this->makeHasOne($ar, $model_path);
                    }
                }
                return redirect()->back()->with('success', 'Successfully genertated table/model');
            } catch (\Exception $ex) {
                dd($ex->getMessage());
            }
        }
        $data['models'] = getAllModels();
        $data['module'] = 'Crud';
        return view('admin.crud.generate_table', with($data));

    }
    public function addTableRelationship(Request $r)
    {
        if (count($r->all()) > 0) {
            try {
                $post = $r->all();
                $model = $post['model'];
                $post['many_to_many_model'] = $post['many_to_many_models'];
                $model_path = app_path("/Models/" . $model . ".php");
                // dd($post);
                if ($post['many_to_many_models'][0]) {
                    $this->makeManyToMany($post, $model_path, $model);
                }

                if ($post['has_many_model'][0]) {
                    $this->makehasMany($post, $model_path);
                }

                if ($post['has_one_model'][0]) {
                    $this->makehasOne($post, $model_path);
                }
                return redirect()->back()->with('success', 'Successfully added relationship');
            } catch (\Exception $ex) {
                dd($ex->getMessage());
            }
        }
        $data['models'] = getAllModels();
        $data['module'] = 'Crud';
        return view('admin.crud.add_relationship', with($data));

    }
    public function pickCreateInputs($post)
    {
        $ar = [];
        $model_relations = getModelRelations($post['module'][0]);

        $group_indexes = [];
        $group = [];
        $ar_keys = array_keys($post);
        // dd($post);
        if ($post["create_fields"][0]) {
            $val = $post['create_fields'];
            $group['Grp0']['fields'] = $post['create_fields'];

            $group['Grp0']['fieldset_label'] = $post['fieldset_label'][0];

            $label = [];
            $attrs = [];
            $options = [];
            $multiple = [];
            $input_types = [];
            foreach ($val as $field) {
                $lab = '';
                if (!empty($post[$field . '_label_create_0'][0])) {$lab = $post[$field . '_label_create_0'][0];
                    $label[$field] = $lab;
                } else {
                    dd('Enter label for column ');
                }

                $inptype = $post[$field . '_inputtype_create_0'][0];
                if ($inptype) {
                    $input_types[$field] = $inptype;
                } else {
                    dd('Enter input type for create inputs');
                }

                $option_string = $post[$field . '_options_create_0'][0];

                if (!empty($option_string)) {

                    if (!str_contains($option_string, 'getList') && !str_contains($option_string, 'getRadioOptions')) {
                        $optar = explode(',', $option_string);

                        $opti = array_map(function ($it) {
                            return (object) ['id' => $it, 'name' => $it];
                        }, $optar);
                        $options[$field] = $opti;
                    } else {
                        $options[$field] = $option_string;
                    }

                }

                $attr_string = $post[$field . '_attributes_create_0'][0];
                if (!empty($attr_string)) {
                    $attrp = explode(',', $attr_string);
                    foreach ($attrp as $x) {
                        $spl = explode('=>', $x);
                        $k = str_replace("'", '', $spl[0]);
                        $attrs[$field][$k] = !empty($spl[1]) ? $spl[1] : '';
                    }
                }
                if (!empty($post[$field . '_multiple_create_0'][0])) {
                    $multiple[$field] = $post[$field . '_multiple_create_0'][0];
                } else {
                    //  dd('Choose multiple option please');
                }

            }
            $group['Grp0']['labels'] = $label;
            $group['Grp0']['options'] = $options;
            $group['Grp0']['multiple'] = $multiple;
            $group['Grp0']['types'] = $input_types;
            $group['Grp0']['attrs'] = $attrs;

        }

        foreach ($post as $key => $val) {
            if (str_contains($key, 'create_fields_')) {
                $index = str_replace('create_fields_', '', $key);
                $group_indexes[] = $index;
            }
        }
        // dd($group_indexes);
        if (count($group_indexes) > 0) {

            foreach ($group_indexes as $index) {
                $val = $post['create_fields_' . $index];
                $group['Grp' . $index]['fields'] = $val;
                $group['Grp' . $index]['fieldset_label'] = $post['fieldset_label'][0];
                $label = [];
                $attrs = [];
                $options = [];

                $multiple = [];
                $input_types = [];
                foreach ($val as $field) {

                    $lab = '';
                    if (!empty($post[$field . '_label_create_' . $index][0])) {$lab = $post[$field . '_label_create_' . $index][0];
                        $label[$field] = $lab;
                    } else {
                        dd("Enter label for column d");
                    }

                    $inptype = '';
                    if (!empty($post[$field . '_inputtype_create_' . $index][0])) {
                        $inptype = $post[$field . '_inputtype_create_' . $index][0];
                    } else {
                        dd("Enter input type for column");
                    }

                    $input_types[$field] = $inptype;

                    $option_string = $post[$field . '_options_create_' . $index][0];

                    if (!empty($option_string)) {
                        if (!str_contains($option_string, 'getList') || !str_contains($option_string, 'getRadioOptions')) {
                            $optar = explode(',', $option_string);

                            $opti = array_map(function ($it) {
                                return (object) ['id' => $it, 'name' => $it];
                            }, $optar);
                            $options[$field] = $opti;
                        } else {
                            $options[$field] = $option_string;
                        }

                    }

                    $attr_string = $post[$field . '_attributes_create_' . $index][0];
                    if (!empty($attr_string)) {
                        $attrp = explode(',', $attr_string);
                        foreach ($attrp as $x) {
                            $spl = explode('=>', $x);
                            $k = str_replace("'", '', $spl[0]);
                            $attrs[$field][$k] = !empty($spl[1]) ? $spl[1] : '';
                        }
                    }
                    if (!empty($post[$field . '_multiple_create_' . $index][0])) {
                        $multiple[$field] = $post[$field . '_multiple_create_' . $index][0];
                    } else {
                        dd("Choose  multiple check vvalue  for column");
                    }

                }
                $group['Grp' . $index]['labels'] = $label;
                $group['Grp' . $index]['options'] = $options;
                $group['Grp' . $index]['multiple'] = $multiple;
                $group['Grp' . $index]['types'] = $input_types;
                $group['Grp' . $index]['attrs'] = $attrs;
            }
        }

        foreach ($group as $grp) {

            $field_keys = $grp['fields'];
            $select_box_options = $grp['options']; /****this for options for all radio,checkbox and select  */
            // dd( $select_box_options );
            $attr_p = $grp['attrs'];
            $labels_p = $grp['labels'];
            $is_multiple_p = $grp['multiple'];
            $input_types = $grp['types'];
            $input_ar = [];
            $i = 0;
            //  echo "<pre>";
            // print_r($input_types);
            // dd($model);
            foreach ($field_keys as $field) {

                $input = $input_types[$field];
                $attr = [];
                $options = null;
                $input_label = $labels_p[$field];
                $options_for_select = [];
                $options_for_radio = [];
                // echo $field;

                $attr = !empty($attr_p[$field]) ? $attr_p[$field] : [];
                $default = isset($model) ? $model->{$field} : "";
                $options = !empty($select_box_options[$field]) ? $select_box_options[$field] : [];
                $options_first_value = null;

                if (!empty($options) && is_array($options)) {
                    if ($input == 'radio' || $input == 'checkbox') {
                        $options = array_map(function ($it) {
                            return (object) ['label' => $it->name, 'value' => $it->id];
                        }, $options);
                        $options_first_value = isset($options[0]) ? "'" . $options[0]->value . "'" : '';

                    } else /***for select */
                    {
                        $options_first_value = isset($options[0]) ? "'" . $options[0]->id . "'" : '';
                    }
/***for settign default  in create form for select,radio */
                } else {
                    if (!empty($options)) {
                        if ($input == 'radio' || $input == 'checkbox') {

                            $options_first_value = '(!empty(' . $option_string . ')?' . $option_string . '[0]->value:\'\')';
                        } else {
                            $options_first_value = rtrim($options, ';') . "[0]->id";
                            $options_first_value = '(!empty(' . $option_string . ')?' . $option_string . '[0]->id:\'\')';
                        }

                    }
                }
                $label = $input_label;
                $default = 'isset($model) ? $model->' . $field . ' : ""';

                if ($input == 'text' || $input == 'file' || $input == 'email' || $input == 'number' || $input == 'date' || $input == 'datetime-local') {

                    $p = ['placeholder' => 'Enter ' . $field, 'name' => $field, 'label' => $label, 'tag' => 'input', 'type' => $input, 'default' => $default, 'attr' => $attr];
                } elseif ($input == 'textarea') {
                    $p = ['placeholder' => 'Enter ' . $field, 'name' => $field, 'label' => $label, 'tag' => 'textarea', 'type' => 'textarea', 'default' => $default, 'attr' => $attr];
                } elseif ($input == 'select') {
                    $is_multiple = isset($is_multiple_p[$field]) && $is_multiple_p[$field] == 'Yes' ? true : false;

                    $o = 'formatDefaultValueForSelectEdit($model,\'' . $field . '\', false)';
                    $field_val = '$model->' . $field;
                    //  $default = 'isset($model) ?('.$is_multiple ?$o:$field_val.'): ('.$is_multiple ?.' [] : ' . $options_first_value . ')';
                    $default = 'isset($model) ?' . $o . ':' . $options_first_value;

                    if ($is_multiple) {
                        $o = 'formatDefaultValueForSelectEdit($model,\'' . $field . '\', true)';

                        $default = 'isset($model) ?' . $o . ':' . $options_first_value;
                    }

                    $p = ['name' => $field, 'label' => $label, 'tag' => 'select', 'type' => 'select', 'default' => $default, 'attr' => $attr, 'custom_key_for_option' => 'name', 'options' => $options, 'custom_id_for_option' => 'id', 'multiple' => $is_multiple];
                } elseif ($input == 'radio' || $input == 'checkbox') {
                    $is_multiple = isset($is_multiple_p[$field]) && $is_multiple_p[$field] == 'Yes' ? true : false;
                    $o = 'formatDefaultValueForCheckbox($model,\'' . $field . '\')';
                    $field_val = '$model->' . $field;
                    //  $default = 'isset($model) ?('.$is_multiple ?$o:$field_val.'): ('.$is_multiple ?.' [] : ' . $options_first_value . ')';
                    $default = 'isset($model) ?' . $field_val . ':' . $options_first_value;
                    if ($is_multiple) {
                        $default = 'isset($model) ?' . $o . ':' . $options_first_value;
                    }

                    //$options_for_radio = $is_multiple ? (count($options) > 0 ? $options : []) : (count($options) > 0 ? $options[0] : '');
                    $p = ['name' => $field, 'label' => $label, 'tag' => 'input', 'type' => $input, 'default' => $default, 'attr' => $attr, 'value' => $options, 'has_toggle_div' => [], 'multiple' => $is_multiple, 'inline' => true];
                }

                $input_ar[] = $p;

                $i++;
            }
            $ar[] = ['label' => $grp['fieldset_label'], 'inputs' => $input_ar];
            //dd($ar);
        };
        return $ar;
    }
    public function formatForImage($post)
    {
        $ar = [];
        $ar_keys = array_keys($post);
        if ($post['has_upload'] == 'Yes') {
            $index = 0;
            $field_names = array_merge([], array_filter($post['image_col_name']));
            if (empty($field_names)) {
                dd('Select columns for image setting');
            }

            $parent_table_ids = getValOfArraykey($post, 'parent_table_id', true);
            $image_tables = getValOfArraykey($post, 'image_table', true);
            $model_names = getValOfArraykey($post, 'model_name', true);
            $types = getValOfArraykey($post, 'image_type', true);

            if (count($types) > 0) {
                foreach ($types as $type) {
                    //dd($type);
                    $p = [];
                    $image_type = $type;
                    if ($type == 'Single') {
                        if (!empty($field_names)) {
                            $p['field_name'] = $field_names[$index];
                        } else {
                            dd('Select columns for single image ');
                        }

                        $p['single'] = true;
                    } else {
                        //dd($post);
                        if (isset($field_names[$index])) {
                            $p['field_name'] = $field_names[$index];
                        } else {
                            dd("Select column for multiple image setting");
                        }

                        $p['single'] = false;
                        if (!empty($parent_table_ids[$index])) {
                            $p['parent_table_field'] = $parent_table_ids[$index];
                        } else {
                            dd("Parent table id when mltiple image upload");
                        }

                        if (!empty($image_tables[$index])) {
                            $p['table_name'] = $image_tables[$index];
                        } else
                        if (!empty($model_names[$index])) {
                            $p['image_model_name'] = $model_names[$index];
                        } else {
                            dd("Parent table id when mltiple image upload");
                        }

                    }
                    $ar[] = $p;
                    $index++;
                }
            }
            return $ar;

        }

    }
    public function formatForIndexPage($post)
    {
        $ar = [];
        $index_cols = getValOfArraykey($post, 'index_page_cols', true);
        $index_labels = getValOfArraykey($post, 'index_label', true);
        $index_sortable = getValOfArraykey($post, 'sortable', true);
        $index = 0;
        if (count($index_cols) > 0) {
            foreach ($index_cols as $cols) {
                //dd($type);
                $ar[] = ['column' => $cols, 'label' => $index_labels[$index], 'sortable' => $index_sortable[$index]];

                $index++;
            }
        }
        return $ar;

    }
    public function formatForArrayValidation($post)
    {

        $validation_fields = getValOfArraykey($post, 'validation_fields', true);
        $validation_ar = [];
        if (count($validation_fields) > 0) {
            foreach ($validation_fields as $item) {
                $item = $item[0];
                if ($item) {

                    if (array_key_exists($item . '_rules', $post)) {
                        $cur_field_rules = $post[$item . '_rules'];
                        $rule_string = '';
                        foreach ($cur_field_rules as $rule) {
                            if ($rule) {
                                $rule_string .= $rule . '|';
                            }
                        }
                        $rule_string = rtrim($rule_string, '|');
                        if ($rule_string) {
                            $validation_ar[$item] = $rule_string;
                        }
                    }

                }
            }
        }
        return $validation_ar;
    }
    public function formatForRepeatable($post)
    {

        $repeating_group = [];
        $repeating_cols = getValOfArraykey($post, 'repeatable_cols', false);

        if ($repeating_cols) {
            //dd($repeating_cols);
            foreach ($repeating_cols as $item) {
                $item = $item[0];
                // dd($post);
                if ($item) {
                    $col_name = $item;
                    if (empty($post[$item . '_label'][0])) {
                        dd('Enter label in repeating group for ' . $item);
                    }

                    $label = $post[$item . '_label'][0];

                    $input_types = $post[$item . '_inputtype'];

                    $field_keys = $post[$item . '_keys'];
                    // dd($field_keys);
                    $select_box_options = $post[$item . '_options'];
                    $input_attrs = $post[$item . '_attributes'];
                    $is_multipl_p = $post[$item . '_multiple'];
                    // if (empty($is_multiple)) {
                    //     dd('Please select multiple checkbox in repeatable column for ' . $item);
                    // }

                    $input_ar = [];
                    $i = 0;
                    foreach ($input_types as $input) {
                        if ($input) {
                            if (empty($field_keys[$i])) {
                                dd('Please add key name   in repeatable column for ' . $item);
                            }

                            $attr = [];
                            $options = null;
                            $options_for_select = [];
                            $options_for_radio = [];
                            $attr_string = $input_attrs[$i];
                            $is_multiple = $is_multipl_p[$i];
                            if (!empty($attr_string)) {
                                $attrp = explode(',', $attr_string);
                                foreach ($attrp as $x) {
                                    $spl = explode('=>', $x);
                                    $k = str_replace("'", '', $spl[0]);
                                    $attr[$k] = !empty($spl[1]) ? $spl[1] : '';
                                }
                            }
                            $option_string = $select_box_options[$i];

                            $options_first_value = null;
                            if (!empty($option_string)) {

                                if (!str_contains($option_string, 'getList') && !str_contains($option_string, 'getRadioOptions')) {
                                    $options = explode(',', $option_string);
                                    $options_for_select = array_map(function ($it) {
                                        return (object) ['id' => $it, 'name' => $it];
                                    }, $options);
                                    $options_for_radio = array_map(function ($it) {
                                        return (object) ['label' => $it, 'value' => $it];
                                    }, $options);
                                    $options_first_value = !empty($options_for_radio) ? $options_for_radio[0]->value : '';
                                } else {
                                    $options_for_select = $option_string;
                                    $options_for_radio = $option_string;
                                    $option_string = rtrim($option_string, ';');

                                    if ($input == 'radio' || $input == 'checkbox') {
                                        $options_first_value = '(!empty(' . $option_string . ')?' . $option_string . '[0]->value:\'\')';
                                    } else {
                                        //  dd('here');
                                        $options_first_value = '(!empty(' . $option_string . ')?' . $option_string . '[0]->id:\'\')';
                                    }

                                };

                            }
                            if ($input == 'text' || $input == 'file' || $input == 'number' || $input == 'date' || $input == 'datetime-local') {
                                $default = 'isset($model)?$model->' . $field_keys[$i] . ':\'\'';
                                $p = ['placeholder' => 'Enter ' . $field_keys[$i], 'name' => $item . '__json__' . $field_keys[$i] . '[]\'', 'label' => ucfirst($field_keys[$i]), 'tag' => 'input', 'type' => $input, 'default' => '', 'attr' => $attr];
                            } elseif ($input == 'textarea') {
                                $default = 'isset($model)?$model->' . $field_keys[$i] . ':\'\'';

                                $p = ['placeholder' => 'Enter ' . $field_keys[$i], 'name' => $item . '__json__' . $field_keys[$i] . '[]\'', 'label' => ucfirst($field_keys[$i]), 'tag' => 'textarea', 'type' => 'textarea', 'default' => '', 'attr' => $attr];
                            } elseif ($input == 'select') {

                                $is_multiple = $is_multiple == 'Yes' ? true : false;
                                $o = 'formatDefaultValueForSelectEdit($model,\'' . $field_keys[$i] . '\', false)';
                                $field_val = '$model->' . $field_keys[$i];
                                //  $default = 'isset($model) ?('.$is_multiple ?$o:$field_val.'): ('.$is_multiple ?.' [] : ' . $options_first_value . ')';
                                $default = 'isset($model) ?' . $o . ':' . $options_first_value;

                                if ($is_multiple) {
                                    $o = 'formatDefaultValueForMultipleSelectEdit($model,\'' . $field_keys[$i] . '\', true)';

                                    $default = 'isset($model) ?' . $o . ':' . $options_first_value;
                                }
                                $p = ['name' => $item . '__json__' . $field_keys[$i] . '[]\'', 'label' => 'Select ' . ucfirst($field_keys[$i]), 'tag' => 'select', 'type' => 'select', 'default' => $default, 'attr' => $attr, 'custom_key_for_option' => 'name', 'options' => $options_for_select, 'custom_id_for_option' => 'id', 'multiple' => $is_multiple];
                            } elseif ($input == 'radio' || $input == 'checkbox') {
                                $is_multiple = $is_multiple == 'Yes' ? true : false;
                                $o = 'formatDefaultValueForCheckbox($model,\'' . $field_keys[$i] . '\')';
                                $field_val = '$model->' . $field_keys[$i];
                                //  $default = 'isset($model) ?('.$is_multiple ?$o:$field_val.'): ('.$is_multiple ?.' [] : ' . $options_first_value . ')';
                                $default = 'isset($model) ?' . $field_val . ':' . $options_first_value;
                                if ($is_multiple) {
                                    $default = 'isset($model) ?' . $o . ':' . $options_first_value;
                                }
                                $p = ['name' => $item . '__json__' . $field_keys[$i] . '[]\'', 'label' => 'Choose ' . ucfirst($field_keys[$i]), 'tag' => 'input', 'type' => 'radio', 'default' => $options_first_value, 'attr' => $attr, 'value' => $options_for_radio, 'has_toggle_div' => [], 'multiple' => $is_multiple];
                            }

                            array_push($input_ar, $p);
                            $i++;
                        } else {
                            dd('Please select input type in repeatable group for ' . $item);
                        }

                    }
                    $repeating_group[] = ['colname' => $col_name, 'label' => $label, 'inputs' => $input_ar];

                }
            }
        }
        // dd($repeating_group);
        return $repeating_group;
    }
    public function formatForToggable($post)
    {

        $toggable_group = [];
        //  dd($post);
        $toggable_fields = !empty($post['toggalbe_fields']) ? $post['toggalbe_fields'] : [];
//dd($toggable_fields);
        if (count($toggable_fields) > 0) {
            foreach ($toggable_fields as $col) {
                $item = $col;

                if ($item) {
                    $col_name = $item;
                    $conditional_val = $post[$item . '_toggable_val'][0];
                    $field_keys = $post[$item . '_fields'];
                    // dd($input_fields);
                    $input_types = $post[$item . '_inputtype'];
                    $select_box_options = $post[$item . '_options'];
                    $input_attrs = $post[$item . '_attributes'];
                    // $is_multiple = isset($post[$item . '_multiple']) ? $post[$item . '_multiple'] : [];
                    $is_multiple = false;
                    // if (empty($is_multiple)) {
                    //     dd("Please choose multiple checkbox value");
                    // }

                    $input_ar = [];
                    $i = 0;

                    foreach ($input_types as $input) {
                        if ($input) {
                            $attr = [];
                            $options = null;
                            $options_for_select = [];
                            $options_for_radio = [];
                            $attr_string = $input_attrs[$i];
                            if (!empty($attr_string)) {
                                $attrp = explode(',', $attr_string);
                                foreach ($attrp as $x) {
                                    if ($x) {
                                        $spl = explode('=>', $x);
                                        $k = str_replace("'", '', $spl[0]);
                                        $attr[$k] = !empty($spl[1]) ? $spl[1] : '';
                                    }
                                }
                            }
                            $option_string = $select_box_options[$i];

                            if (!empty($option_string)) {
                                if (is_array($option_string)) {
                                    $options = explode(',', $option_string);
                                    $options_for_select = array_map(function ($it) {
                                        return (object) ['id' => $it, 'name' => $it];
                                    }, $options);
                                    $options_for_radio = array_map(function ($it) {
                                        return (object) ['label' => $it, 'value' => $it];
                                    }, $options);
                                } else {
                                    $options_for_select = $option_string;
                                    $options = $option_string;
                                    $options_for_radio = $option_string;
                                }

                            }
                            $input_label = ucwords(str_replace('_', ' ', $field_keys[$i]));
                            if ($input == 'text' || $input == 'file' || $input == 'email' || $input == 'number' || $input == 'date') {
                                $p = ['placeholder' => 'Enter ' . $field_keys[$i], 'name' => $item . '__json__' . $field_keys[$i] . '[]\'', 'label' => $input_label, 'tag' => 'input', 'type' => $input, 'default' => '', 'attr' => $attr];
                            } elseif ($input == 'textarea') {
                                $p = ['placeholder' => 'Enter ' . $field_keys[$i], 'name' => $item . '__json__' . $field_keys[$i] . '[]\'', 'label' => $input_label, 'tag' => 'textarea', 'type' => 'textarea', 'default' => '', 'attr' => $attr];
                            } elseif ($input == 'select') {
                                $is_multiple = false;
                                $p = ['name' => $item . '__json__' . $field_keys[$i] . '[]', 'label' => $input_label, 'tag' => 'select', 'type' => 'select', 'default' => '', 'attr' => $attr, 'custom_key_for_option' => 'name', 'options' => $options_for_select, 'custom_id_for_option' => 'id', 'multiple' => $is_multiple];
                            } elseif ($input == 'radio' || $input == 'checkbox') {
                                $is_multiple = false;
                                $options_for_radio = is_array($options_for_radio) && !empty($options_for_radio[0]) ? $options_for_radio[0]->value : $options_for_radio . '[0]->value';
                                $p = ['name' => $item . '__json__' . $field_keys[$i] . '[]', 'label' => $input_label, 'tag' => 'input', 'type' => $Input, 'default' => '', 'attr' => $attr, 'value' => $options_for_radio, 'has_toggle_div' => [], 'multiple' => $is_multiple];
                            }

                            array_push($input_ar, $p);
                            $i++;
                        }

                    }
                    $toggable_group[] = ['colname' => $col_name, 'conditional_val' => $conditional_val, 'label' => ucwords(str_replace('_', ' ', $col_name)), 'inputs' => $input_ar];

                }
            }
        }
//dd($toggable_group);
        return $toggable_group;
    }
    protected function makeModel($data)
    {
        $model_name = modelName($data['table']);
        $model_path = app_path("/Models/" . $model_name . ".php");

        $tableName = $data['table'];
        $timestamps = $data['timestamps'] == 'Yes' ? 1 : 0;

        $modelTemplate = str_replace(
            ['{{modelName}}', '{{tableName}}', '{{timestamps}}'],
            [$model_name, $tableName, $timestamps],
            file_get_contents(resource_path("stubs/Model.stub"))
        );

        file_put_contents($model_path, $modelTemplate);
        $this->appendModelRelationCode($data, $model_path, $model_name);
        //dd($data);
    }
    public function applyRule($col, $rule)
    {
        if ($rule == 'unique') {
            return $col->unique();
        } elseif ($rule == 'unsigned') {
            return $col->unsigned();
        } elseif ($rule == 'index') {
            return $col->index();
        } elseif ($rule == 'nullable') {
            return $col->nullable();
        }

    }
    public function makeTable($data)
    {
        // dd($data);
        Schema::dropIfExists($data['table']);
        //  $type = ['varchar(300)', 'smallText', 'longText','Int', 'smallInt', 'mediumInt','tinyInt','enum', 'json', 'decimal(10,2)','date','timestamp','current_timestamp'];
        //  $props = ['unsigned', 'nullable', 'unique', 'index'];

        Schema::create($data['table'], function ($table) use ($data) {
            $columns = $data['columns'];

            $table->increments('id');
            foreach ($columns as $col) {
                // dd($col);
                $enums = !empty($col['enums']) ? explode(',', $col['enums']) : [];
                // dd($enums);
                $constraints = $col['constraints'];
                if ($col['data_type'] == 'varchar(300)') {
                    $column = $table->string($col['col_name'], 300);

                } elseif ($col['data_type'] == 'smallText') {
                    $column = $table->mediumText($col['col_name']);
                } elseif ($col['data_type'] == 'longText') {
                    $column = $table->text($col['col_name']);
                } elseif ($col['data_type'] == 'Int') {
                    $column = $table->integer($col['col_name']);
                } elseif ($col['data_type'] == 'mediumInt') {
                    $column = $table->mediumInteger($col['col_name']);
                } elseif ($col['data_type'] == 'decimal(10,2)') {
                    $column = $table->decimal($col['col_name'], 10, 2);
                } elseif ($col['data_type'] == 'smallInt') {
                    $column = $table->smallInteger($col['col_name']);
                } elseif ($col['data_type'] == 'tinyInt') {
                    $column = $table->tinyInteger($col['col_name']);
                } elseif ($col['data_type'] == 'enum') {

                    $column = $table->enum($col['col_name'], $enums)->default($enums[0]);
                } elseif ($col['data_type'] == 'json') {
                    $column = $table->json($col['col_name']);
                } elseif ($col['data_type'] == 'date') {
                    $column = $table->date($col['col_name']);
                } elseif ($col['data_type'] == 'time') {
                    $column = $table->time($col['col_name']);
                } elseif ($col['data_type'] == 'timestamp') {
                    $column = $table->timestamp($col['col_name']);
                } elseif ($col['data_type'] == 'current_timestamp') {
                    $column = $table->timestamp($col['col_name'])->useCurrent();
                } else {}
                if (!empty($constraints)) {
                    foreach ($constraints as $rule) {
                        $column = $this->applyRule($column, $rule);
                    }

                }

            }
            if ($data['timestamps']) {
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            }
        });
    }
    public function _injectCodeInClassDefinition($classString, $appendWith)
    {
        $pos = strrpos($classString, '}');
        $content_without_brace = substr_replace($classString, '', $pos, 1);
        return $content_without_brace . "\n\t" . $appendWith . "\n }";

    }
    public function appendModelRelationCode($data, $model_path, $model)
    {
        // $model_path = app_path("/Models/AttributeFamily.php");

        foreach ($data['columns'] as $item) {

            $relationsip_type = $item['relationship_type'];
            $relationsip_name = $item['relationship_name'];
            $relationsip_model = $item['relationship_model'] ? ($item['relationship_model'] == 'Self' ? $model : $item['relationship_model']) : null;
            $foreign_table_key = $item['relationship_foreign_table_key'];
            $my_key = $item['relationship_my_key'];
            if ($relationsip_type && $relationsip_model) {

                $fn_stub_content = file_get_contents(resource_path("stubs/model_relation/$relationsip_type.stub"));
                $actual_function_code = str_replace(
                    ['{{relationship_name}}', '{{relationship_model}}', '{{foreign_table_key}}', '{{my_key}}'],
                    [$relationsip_name, $relationsip_model, $foreign_table_key, $my_key],
                    $fn_stub_content
                );
                $class_content = file_get_contents($model_path);
                $newModelClassCode = $this->_injectCodeInClassDefinition($class_content, $actual_function_code);
                $this->relation_array[$relationsip_name] = ['type' => 'BelongsTo', 'related_model' => $relationsip_model, 'add_in_grp' => 0];

                file_put_contents($model_path, $newModelClassCode);
            }
        }

    }

    public function makeManyToMany($data, $model_path)
    {

        $models = $data['many_to_many_model'];
        $relationship_names = $data['many_to_many_relationship_name'];
        $i = 0;
        foreach ($models as $model) {
            $cur_model = isset($data['table']) ? modelName($data['table']) : $data['model'];

            $related_model = $model;
            $lowercase_related_model = strtolower($related_model);
            $lowercase_cur_model = strtolower($cur_model);
            $relationsip_name = empty($relationship_names[$i])?\Str::plural(strtolower($model)) : $relationship_names[$i];
            $pivot_table = getPivotTableName($lowercase_related_model, $lowercase_cur_model);
            $fn_stub_content = file_get_contents(resource_path("stubs/model_relation/manytoMany.stub"));
            $actual_function_code = str_replace(
                ['{{many_to_many_name}}', '{{related_model}}', '{{pivot_table}}', '{{lowercase_cur_model}}', '{{lowercase_related_model}}'],
                [$relationsip_name, $related_model, $pivot_table, $lowercase_cur_model, $lowercase_related_model],
                $fn_stub_content
            );
            $class_content = file_get_contents($model_path);
            $newModelClassCode = $this->_injectCodeInClassDefinition($class_content, $actual_function_code);

            file_put_contents($model_path, $newModelClassCode);
            $this->generatePivotTable($pivot_table, strtolower($cur_model), strtolower($related_model));
            $create_group_no = 0;
            $this->relation_array_manymany[$relationsip_name] = ['type' => 'manyToMany', 'related_model' => $related_model, 'add_in_grp' => $create_group_no];
        }
        \Session::put('relationships_manymany', $this->relation_array_manymany);
        //  dd('ok');
    }
    public function makeHasMany($data, $model_path)
    {
        //  dd($data);
        $relationship_names = $data['has_many_relationship_name'];

        $models = $data['has_many_model'];
        $i = 0;
        foreach ($models as $model) {
            $cur_model = isset($data['table']) ? modelName($data['table']) : $data['model'];
            $related_model = $model == 'Self' ? $cur_model : $model;

            // $related_model = $model;
            $lowercase_related_model = strtolower($related_model);
            $lowercase_cur_model = strtolower($cur_model);
            $relationsip_name = empty($relationship_names[$i])?\Str::plural(strtolower($model)) : $relationship_names[$i];

            $fk = $data['has_many_fk'][$i];

            $fn_stub_content = file_get_contents(resource_path("stubs/model_relation/hasMany.stub"));
            $actual_function_code = str_replace(
                ['{{relationship_name}}', '{{relationship_model}}', '{{foreign_table_key}}', '{{my_key}}'],
                [$relationsip_name, $related_model, $fk, 'id'],
                $fn_stub_content
            );
            $class_content = file_get_contents($model_path);
            $newModelClassCode = $this->_injectCodeInClassDefinition($class_content, $actual_function_code);

            file_put_contents($model_path, $newModelClassCode);

            $create_group_no = 0;
            $this->relation_array[$relationsip_name] = ['type' => 'hasMany', 'related_model' => $related_model, 'add_in_grp' => $create_group_no];
        }
        \Session::put('relationships_other', $this->relation_array);
        // dd('ok');
    }
    public function makeHasOne($data, $model_path)
    {

        $models = $data['has_one_model'];

        $relationship_names = $data['has_one_relationship_name'];

        $i = 0;
        foreach ($models as $model) {
            $cur_model = isset($data['table']) ? modelName($data['table']) : $data['model'];

            $related_model = $model == 'Self' ? $cur_model : $model;

            $lowercase_related_model = strtolower($related_model);
            $lowercase_cur_model = strtolower($cur_model);
            $relationsip_name = empty($relationship_names[$i])?\Str::plural(strtolower($model)) : $relationship_names[$i];

            $fk = $data['has_one_fk'][$i];

            $fn_stub_content = file_get_contents(resource_path("stubs/model_relation/hasOne.stub"));
            $actual_function_code = str_replace(
                ['{{relationship_name}}', '{{relationship_model}}', '{{foreign_table_key}}', '{{my_key}}'],
                [$relationsip_name, $related_model, $fk, 'id'],
                $fn_stub_content
            );
            $class_content = file_get_contents($model_path);
            $newModelClassCode = $this->_injectCodeInClassDefinition($class_content, $actual_function_code);

            file_put_contents($model_path, $newModelClassCode);

            $create_group_no = 0;
            $this->relation_array[$relationsip_name] = ['type' => 'hasOne', 'related_model' => $related_model, 'add_in_grp' => $create_group_no];
            \Session::put('relationships_other', $this->relation_array);
        }

    }
    public function generatePivotTable($table_name, $model1, $model2)
    {
        Schema::dropIfExists($table_name);
        Schema::create($table_name, function ($table) use ($model1, $model2) {
            $table->increments('id');
            $table->integer($model1 . '_id');
            $table->integer($model2 . '_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            //$table->foreign($model1 . '_id')->references('id')->on(\Str::plural($model1))->onDelete('cascade');
            //$table->foreign($model2 . '_id')->references('id')->on(\Str::plural($model2))->onDelete('cascade');
        });

    }
    public function createFile($data)
    {
        $p = $data;
        // dd($p);
        $routes_array = [];
        $data['modelName'] = $p['module'][0];
        $data['modelNamePluralLowerCase'] = $p['plural'][0];

        array_push($routes_array, $data['modelNamePluralLowerCase']);
        $data['table_name'] = $p['table'];
        $data['isModal'] = $p['modal'][0];
        $data['export'] = $p['export'][0];
        $data['has_export'] = $data['export'] == 'Yes' ? 1 : 0;
        $data['has_repeating_group'] = $p['has_repeating_group'] == 'Yes' ? 1 : 0;
        $data['repeating_group_inputs'] = [];
        $data['export_fields'] = [];
        if ($data['has_export'] == 1) {
            $data['export_fields'] = !empty($p['exportable_fields']) ? $p['exportable_fields'] : [];
        }
        if ($data['has_repeating_group'] == 1) {
            $data['repeating_group_inputs'] = !empty($p['repeatable_config']) ? $p['repeatable_config'] : [];
        }
// dd($data['repeating_group_inputs']);
        $data['modelNameSinglularLowerCase'] = strtolower($p['module'][0]);
        $data['validation'] = !empty($p['validation_config']) ? $p['validation_config'] : [];
        $data['searchable_fields'] = $p['searchable_fields'];
        $data['toggable_group'] = !empty($p['toggable_input_config']) ? $p['toggable_input_config'] : [];
        $data['toggable_group_edit'] = []; //$this->getEditToggable($p['toggable_input_config']);
        // dd($p['']);
        $data['filterable_fields'] = $p['filterable_fields'];
        $data['form_image_field_name'] = !empty($p['image_config']) ? $p['image_config'] : [];

        $data['create'] = $p['create_input_config'];

        $data['edit'] = []; //$p['edit'];//

        $data['table_columns'] = !empty($p['index_page_config']) ? $p['index_page_config'] : [];
        $data['has_image'] = $p['has_upload'] == 'Yes' ? 1 : 0;
        $data['many_to_many_relation'] = [];
        $data['other_has_relation'] = [];
        // dd($data);
        /****format for sesion rellationship */
        $manymany = \Session::has('relationships_manymany')?\Session::has('relationships_manymany') : null;
        $other_rel = \Session::has('relationships')?\Session::get('relationships') : null;
        if ($manymany) {
            foreach ($manymany as $name => $rel) {
                $grp_no = $rel['add_in_grp'];

                $data['many_to_many_relation'][] = ['field' => $name, 'type' => 'manyToMany'];
                $data['table_columns'][] = ['column' => $name, 'sortable' => false, 'label' => ucwords(str_replace('_', ' ', $name))];
                $data['create'] = array_map(function ($item, $index) {
                    if ($index == $grp_no) {
                        $inputs = $item[$index]['inputs'];
                        $to_add = ['name' => $name, 'label' => 'Select ' . $name, 'tag' => 'select', 'type' => 'select', 'default' => isset($model) ? getListFromIndexArray($model->{$name}) : [], 'custom_key_for_option' => 'name', 'options' => getList($rel['related_model']), 'custom_id_for_option' => 'id', 'multiple' => true, 'attr' => []];
                        array_push($inputs, $to_add);
                        $item[$index]['inputs'] = $inputs;
                    }
                    return $item;
                }, $data['create']);
            }
        }
        if ($other_rel) {
            foreach ($other_rel as $name => $rel) {
                $grp_no = $rel['add_in_grp'];
                $data['other_has_relation'][] = ['field' => $name, 'type' => $rel['type']];
                $data['table_columns'][] = ['column' => $name, 'sortable' => false, 'label' => ucwords(str_replace('_', ' ', $name))];
                $data['create'] = array_map(function ($item, $index) {
                    if ($index == $grp_no) {
                        $inputs = $item[$index]['inputs'];
                        $to_add = ['name' => $name, 'label' => 'Select ' . $name, 'tag' => 'select', 'type' => 'select', 'default' => isset($model) ? getListFromIndexArray($model->{$name}) : [], 'custom_key_for_option' => 'name', 'options' => getList($rel['related_model']), 'custom_id_for_option' => 'id', 'multiple' => true, 'attr' => []];
                        array_push($inputs, $to_add);
                        $item[$index]['inputs'] = $inputs;
                    }
                    return $item;
                }, $data['create']);
            }
        }
        /***** */
        $modelName = $data['modelName'];
        $this->controller($data);
        //   $this->model($data['modelName'], $data['modelNamePluralLowerCase'], $data['table_name']);
        $this->request($data['modelName'], $data['validation']);
        $this->viewFiles($data['modelNamePluralLowerCase'], $data['isModal'], $data['has_export']);
        // $this->makeTablesWithMigration();
        $modelName = $data['modelName'];
        $namespace = 'App\\Http\\Controllers\\';
        File::append(base_path('routes/admin.php'), PHP_EOL . 'Route::resource(\'' . $data['modelNamePluralLowerCase'] . "', '{$modelName}Controller');");
        $t = 'Route::post(\'' . $data['modelNamePluralLowerCase'] . '/view\', [' . $namespace . $modelName . 'Controller::class,\'view\'])->name(\'' . \Str::plural(strtolower($modelName)) . '.view\');';
        File::append(base_path('routes/admin.php'), PHP_EOL . $t);
        if (isset($data['toggable_input_config']) && count($data['toggable_input_config']) > 0) {
            $t = 'Route::post(\'' . $data['modelNamePluralLowerCase'] . '/view\', [' . $namespace . $modelName . 'Controller::class,\'view\'])->name(\'' . \Str::plural(strtolower($modelName)) . '.view\');';
            File::append(base_path('routes/admin.php'), PHP_EOL . $t);
            $t = 'Route::post(\'' . strtolower($modelName) . '/load_snippets\', [' . $namespace . $modelName . 'Controller::class, \'load_toggle\'])->name(\'' . \Str::plural(strtolower($modelName)) . '.load_toggle\');';
            File::append(base_path('routes/admin.php'), PHP_EOL . $t);

        }
        if ($data['isModal'] == 'Yes') {
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
            $perm_label = ucwords(str_replace('_', '  ', $plural));

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
            }
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
        $model_relations = getModelRelations($data['modelName']);
        //  dd( $model_relations);
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{model_relations}}',
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
                "'[[", "]]'", '","',
                "'isset", "\"'",
                "'getList", "\'", "')'", "[]'",

            ],
            [
                $data['modelName'], VarExporter::export($model_relations),
                VarExporter::export($data['repeating_group_inputs']),
                VarExporter::export($data['toggable_group']),
                VarExporter::export($data['toggable_group_edit']),
                VarExporter::export($data['form_image_field_name']),
                $data['modelNamePluralLowerCase'],
                $data['modelNameSinglularLowerCase'],
                $data['has_image'],
                $data['has_export'],
                VarExporter::export($data['searchable_fields']),
                VarExporter::export($data['filterable_fields']),
                VarExporter::export($data['table_columns']),
                VarExporter::export($data['create']),
                VarExporter::export($data['create']),
                '[[',
                ']]',
                '[[', ']]', ',',
                "isset", "\"",
                "getList", "'", "')", "[]",
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
            [$name, VarExporter::export($validation)],
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

        if ($is_modal == 'No') {
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
        $from = resource_path('stubs/views/view_modal.blade.php');
        $to = resource_path('views/admin/' . $name . '/view_modal.blade.php');
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
            [$module, VarExporter::export($exportFields)],
            $this->getStub('Export')
        );

        if (!file_exists($path = app_path('/Exports'))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(app_path("/Exports/{$module}Export.php"), $requestTemplate);
    }
}
