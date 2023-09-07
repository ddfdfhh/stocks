             {!! Form::open()->route($plural_lowercase.'.store')->id(strtolower($module).'_form')->multipart()!!}

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
                            <input type="checkbox" name="create_{{$module}}" class="form-check-input" />
                            </div>
                            <div class="col-md-1">
                            <input type="checkbox" name="edit_{{$module}}" class="form-check-input" />
                            </div>
                            <div class="col-md-1">
                             <input type="checkbox" name="view_{{$module}}" class="form-check-input" />
                            </div>
                            <div class="col-md-1">
                             <input type="checkbox" name="list_{{$module}}" class="form-check-input" />
                            </div>
                            <div class="col-md-1">
                              <input type="checkbox" name="delete_{{$module}}" class="form-check-input" />
                            </div>
                    </div>
                    @endforeach
                    @endif
                   
                    <div class="row">
                        <div class="col-sm-10">
                            @php
                            $r='Submit';
                            @endphp
                            {!!Form::submit($r)->id(strtolower($module).'_btn')->primary()!!}
                        </div>
                    </div>

                    {!! Form::close()!!}
                  
             