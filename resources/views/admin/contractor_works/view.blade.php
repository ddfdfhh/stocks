@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y pt-5">
       

        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">View {{ properSingularName($plural_lowercase) }}</h5>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>

                                @php
                                    $l = 0;
                                    $table_columns1 = array_column($table_columns, 'column');
                                @endphp
                                @foreach ($table_columns as $t)
                                    @php ++$l; 
                                    $t=$t['column'];@endphp
                                    <tr>
                                        <th>{{ $table_columns[$l - 1]['label']=='Loaded Products'
                                                  ?'Stock Detail'
                                                  :ucwords(str_replace(' Id',' ',$table_columns[$l - 1]['label'])) }}</th>
                                        @if (str_contains($t, 'status'))
                                            <td>
                                                <x-status :status='$row->{$t}' />
                                            </td>
                                        @elseif(str_contains($t, '_at') || str_contains($t, 'date'))
                                            <td>{{ formateDate($row->{$t}) }}</td>
                                        @elseif(isFieldPresentInRelation($model_relations, $t) >= 0)
                                            @if (
                                                $row->{$t} &&
                                                    (preg_match("/image$/", $t) ||
                                                        preg_match("/_image$/", $t) ||
                                                        preg_match("/_doc$/", $t) ||
                                                        preg_match("/_file$/", $t) ||
                                                        preg_match("/_pdf$/", $t)))
                                                <td>

                                                    <x-singleFile :fileName="$row->{$t}" :modelName="$module" :folderName="$storage_folder"
                                                        :fieldName="$t" :rowid="$row->id" />
                                                </td>
                                            @elseif(preg_match("/images$/", $t) ||
                                                    preg_match("/_images$/", $t) ||
                                                    preg_match("/_docs$/", $t) ||
                                                    preg_match("/_files$/", $t) ||
                                                    preg_match("/_pdfs$/", $t))
                                                <td>
                                                    <!-- here image list is list of table row in object form *****-->

                                                    <x-showImages :row=$row :fieldName=$t :storageFolder=$storage_folder
                                                        :tableName="getTableNameFromImageFieldList(
                                                            $image_field_names,
                                                            $t,
                                                        )" />
                                                </td>
                                            @else
                                                <td>{{ getForeignKeyFieldValue($model_relations, $row, $t, ['BelongsTo' => 'name']) }}
                                                </td>
                                            @endif
                                        @elseif(isFieldPresentInRelation($model_relations, $t) < 0 &&
                                                $row->{$t} &&
                                                (preg_match("/image$/", $t) ||
                                                    preg_match("/_image$/", $t) ||
                                                    preg_match("/_doc$/", $t) ||
                                                    preg_match("/_file$/", $t) ||
                                                    preg_match("/_pdf$/", $t)))
                                            <td>

                                                <x-singleFile :fileName="$row->{$t}" :modelName="$module" :folderName="$storage_folder"
                                                    :fieldName="$t" :rowid="$row->id" />
                                            </td>
                                        @elseif(isFieldPresentInRelation($model_relations, $t) < 0 &&
                                                (preg_match("/images$/", $t) ||
                                                    preg_match("/_images$/", $t) ||
                                                    preg_match("/_docs$/", $t) ||
                                                    preg_match("/_files$/", $t) ||
                                                    preg_match("/_pdfs$/", $t)))
                                            <td>
                                                <!-- here image list is list of table row in object form *****-->

                                                <x-showImages :row=$row :fieldName=$t :storageFolder=$storage_folder
                                                    :tableName="getTableNameFromImageFieldList($image_field_names, $t)" />
                                            </td>
                                        @else
                                            <td>
                                                @php
                            if (!is_numeric($row->{$t})) {
                                $tr = json_decode($row->{$t}, true);
                            
                                if ($tr !== null) {
                                        $tr = array_map(function ($v) {
                                       
                                        unset($v['product_id']);
                                        return $v;
                                    }, $tr);
                                  
                                
                                    echo showArrayInColumn($tr, $l);
                                } else {
                                    echo $row->{$t};
                                }
                            } else {
                            $rs='';
                            if(in_array($table_columns[$l - 1]['column'],['total_cost','payment_received','payment_due','transport_cost']))
                                echo '&#8377;'.$row->{$t};
                            elseif(in_array($table_columns[$l - 1]['column'],['running_height','running_length','covered_height','covered_length']))
                                echo $row->{$t}.'(ft)';
                            else
                             echo $row->{$t};
                             }
                            
                        @endphp
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div><br>
                </div>
            </div>
        </div>
    </div>
@endsection
