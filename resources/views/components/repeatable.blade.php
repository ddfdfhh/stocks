@props(['data', 'label', 'values','index'])
@php
    $data = $data;
   
    $values = json_decode($values, true);
  //  dd($values);
    $data = array_map(function ($v) {
        if ($v['tag'] == 'select') {
            $ar = $v['options'];
            $key = $v['custom_key_for_option'];
            $name = str_replace('_', ' ', $v['name']); /***this is for select first item like select event or select package */
            $p = explode(' ', $name);
            $new_ar = [(object) ['id' => '', $key => 'Select ' . ucfirst($p[0])]];
            foreach ($ar as $k) {
                array_push($new_ar, (object) $k);
            }
            $v['options'] = $new_ar;
        }
        return $v;
    }, $data);
    $ar_val = [];
    if ($values) {
        $ar_val = $values;
    }
   // dd($ar_val);
@endphp
<fieldset class="form-group border p-3 fieldset">
    <legend class="w-auto px-2 legend">{{ $label }}</legend>

    <div id="repeatable{{$index}}" class="repeatable" style="margin-bottom:5px">
        <div class="row">

            <div class="col-md-12">
                <div class="d-flex justify-content-end">

                    <button type="button" class="btn btn-success btn-xs mr-5" onclick="addMoreRow()">+</button>


                    <button type="button" class="btn btn-danger btn-xs" onclick="removeRow()">-</button>

                </div>
            </div>
            @if($values && is_array($values))
                    @foreach ($values as $t)
                        <div class="row copy_row">
                            @php
                                $n = floor(12 / count($data));
                            @endphp
                            @foreach ($data as $input)
                                @php
                                  $spl=explode('__json__',$input['name']); 
                                  $key=rtrim($spl[1],'[]');
                                @endphp
                                <div class="col-md-{{ $n }}">
                               
                                    <x-input_placing :inputRow="$input" :value="$t[$key]" />
                                </div>
                            @endforeach
                        </div>
                    @endforeach
            @else
                <div class="row copy_row">
                    @php
                        $n = floor(12 / count($data));
                    @endphp
                    @foreach ($data as $input)
                        <div class="col-md-{{ $n }}">
                            <x-input_placing :inputRow="$input" value="" />
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

</fieldset>
