 {!! Form::open()->put()->route($plural_lowercase . '.update', [\Str::singular($plural_lowercase) => $model->id])->id(strtolower($module) . '_form') !!}
 <x-forms :data="$data"  column='1' />
 @php 
                    $ar=[];
                    foreach($permissions as $perm_row){
                       
                        $t=explode('_',$perm_row->name);
                        $actions=$t[0];
                        array_shift($t);
                        //$t=array_map(function($v){return ucfirst($v)},$t);
                        $module=implode('_',$t);
                        if(!in_array($module,$ar)){
                              $ar[]=$module;
                        }
                        
                    }
                   
                    @endphp
                   
                    <div class="row mb-3 mt-2">
                            <div class="col-md-5">
                            <b>Module Name</b>
                            </div>
                            <div class="col-md-1">
                            <b>Add</b>
                            </div>
                            <div class="col-md-1">
                           <b>Edit</b>
                            </div>
                            <div class="col-md-1">
                           <b>View</b>
                            </div>
                             <div class="col-md-1">
                           <b>List</b>
                            </div>
                            <div class="col-md-1">
                           <b>Delete</b>
                            </div>
                    </div>
                     @if(count($ar)>0)
                      @foreach($ar as $module)
                    <div class="row mb-3 mt-2">
                   
                         @php  
                         $t=explode('_',$module);
                         $t=array_map(function($v){return ucfirst($v);},$t);
                          $module_name=implode(' ',$t);
                         @endphp
                            <div class="col-md-5">
                            {{$module_name}}
                            </div>
                            <div class="col-md-1">
                            <input type="checkbox" @if(in_array('create_'.$module,$role_permissions)) checked  @endif name="create_{{$module}}" class="form-check-input" />
                            </div>
                            <div class="col-md-1">
                            <input type="checkbox" @if(in_array('edit_'.$module,$role_permissions)) checked  @endif name="edit_{{$module}}" class="form-check-input" />
                            </div>
                            <div class="col-md-1">
                             <input type="checkbox" @if(in_array('view_'.$module,$role_permissions)) checked  @endif name="view_{{$module}}" class="form-check-input" />
                            </div>
                            <div class="col-md-1">
                             <input type="checkbox" @if(in_array('list_'.$module,$role_permissions)) checked  @endif name="list_{{$module}}" class="form-check-input" />
                            </div>
                            <div class="col-md-1">
                              <input type="checkbox" @if(in_array('delete_'.$module,$role_permissions)) checked  @endif name="delete_{{$module}}" class="form-check-input" />
                            </div>
                    </div>
                    @endforeach
                    @endif

 <div class="row">
     <div class="col-sm-10">
         @php
             $r = 'Submit';
         @endphp
         {!! Form::submit($r)->id(strtolower($module) . '_btn')->primary() !!}
     </div>
 </div>


 {!! Form::close() !!}
