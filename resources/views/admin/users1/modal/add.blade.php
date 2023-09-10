
                    {!! Form::open()->route($plural_lowercase.'.store')->id(strtolower($module).'_form')->multipart()!!}

                    <x-forms :data="$data" :radio='$radio' column='1' />
                    @if($has_image)
                    @php
                    $multiple=$is_multiple?'multiple':'';

                    @endphp
                    <div class="row mb-3">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="inp-discount" class='form-label'>Upload {{ucfirst($image_field_name)}}</label>
                                @if($is_multiple)
                                <input type="file" class="form-control" multiple name="{{$image_field_name}}[]" id="{{$image_field_name}}" />
                                @else
                                <input type="file" class="form-control" name="{{$image_field_name}}" id="{{$image_field_name}}" />
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12" id="gallery1"></div>
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
             