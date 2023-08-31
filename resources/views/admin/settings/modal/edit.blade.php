 {!! Form::open()->put()->route($plural_lowercase . '.update', [\Str::singular($plural_lowercase) => $model->id])->id(strtolower($module) . '_form') !!}
 <x-forms :data="$data"  column='1' />
 
 @if (count($repeating_group_inputs) > 0)
     @foreach ($repeating_group_inputs as $grp)
         <x-repeatable :data="$grp['inputs']" :label="$grp['label']" :values="$model->{$grp['colname']}" :index="$loop->index" />
     @endforeach
 @endif
  <div id="toggle_div"></div>
 <div class="row">
     <div class="col-sm-10">
         @php
             $r = 'Submit';
         @endphp
         {!! Form::submit($r)->id(strtolower($module) . '_btn')->primary() !!}
     </div>
 </div>


 {!! Form::close() !!}
