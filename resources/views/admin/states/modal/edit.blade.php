 
                    {!! Form::open()->put()->route($plural_lowercase.'.update',[\Str::singular($plural_lowercase)=>$model->id])->id(strtolower($module).'_form')!!}
                        <x-forms :data="$data" :radio='$radio' column='1' />
                    @if($has_image)
                        @php
                        $multiple=$is_multiple?'multiple':'';

                        @endphp
                    <div class="row mb-3">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="inp-discount" class="form-label">Upload {{ucfirst($image_field_name)}}</label>
                                @if($is_multiple)
                                <input type="file" class="form-control" multiple name="{{$image_field_name}}[]" id="{{$image_field_name}}" />
                                @else
                                <input type="file" class="form-control" name="{{$image_field_name}}" id="{{$image_field_name}}" />
                                @endif
                            </div>
                        </div>

                    <div class="col-md-12">
                         <div class="row" id="gallery1">
                         
                            @if(!$is_multiple)
                                @php

                                        $path=storage_path('app/public/'.$storage_folder.'/'. $model->{$image_field_name});
                                        if(!\File::exists($path))
                                            $path=null;
                                        else
                                        $path=asset('storage/'.$storage_folder.'/'. $model->{$image_field_name});
                                @endphp
                                @if($path)
                                 <x-image  :name="$model->{$image_field_name}" :path="$path" id="" />
                                @endif
                               
                            @else {{--if  multiple --}}
                                
                                @if(count($image_list)>0)
                                   @foreach($image_list as $image)
                                    @php 
                                        $path=storage_path('app/public/'.$storage_folder.'/'. $image->name);
                                        if(!\File::exists($path))
                                           $path=null;
                                        else
                                           $path=asset('storage/'.$storage_folder.'/'. $image->name);
                                    @endphp
                                     @if($path)
                                        <x-image  :name="$image->name" :path="$path" :id="$image->id" />
                                     @endif
                                   @endforeach
                                @endif
                            @endif
                            </div>
                        </div>
                    </div>
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
               