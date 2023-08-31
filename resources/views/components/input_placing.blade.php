 @props(['inputRow','value'])
 @php 
   $r=$inputRow;
   $attrs=isset($r['attr'])?$r['attr']:[];
   if(isset($r['event'])){
         $attrs[$r['event']['name']]=$r['event']['function'];
   }
   $y=0;
 @endphp

 @if ($r['tag'] == 'input')
                   @if ($r['type'] == 'text' || $r['type'] == 'number' || $r['type'] == 'file' || $r['type'] == 'date')
                        {!! Form::text($r['name'], $r['label'])->value($value)->type($r['type'])->placeholder($r['placeholder'])->attrs($attrs) !!}
                       
                    @elseif ($r['type'] == 'radio' || $r['type'] == 'checkbox')
                        <p style=" font-weight:600;font-size: 14px;margin:0">{{ $r['label'] }}</p>
                        <div class="align-items-center">
                            @if ($r['multiple'])
                           
                                @foreach($r['value'] as $t)
                                    @php
                                        ++$y;
                                        $checked = count($r['default']) > 0 ? (in_array($t->value, $value) ? true : false) : ($y == 1 ? true : false);
                                    @endphp

                                    @if ($r['type'] == 'checkbox')
                                        @if ($r['inline'])
                                            {!! Form::checkbox($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline() !!}
                                        @else
                                            {!! Form::checkbox($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline(false) !!}
                                        @endif
                                    @endif

                                    @if ($r['type'] == 'radio')
                                        @if ($r['inline'])
                                            {!! Form::radio($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline() !!}
                                        @else
                                            {!! Form::radio($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline(false) !!}
                                        @endif
                                    @endif
                                @endforeach
                            @else
                                @foreach ($r['value'] as $t)
                                    @php
                                        ++$y;
                                        $checked = $r['default'] ? ($t->value == $value ? true : false) : ($y == 1 ? true : false);
                                    @endphp
                                    @if ($r['type'] == 'checkbox')
                                        @if ($r['inline'])
                                            {!! Form::checkbox($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline() !!}
                                        @else
                                            {!! Form::checkbox($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline(false) !!}
                                        @endif
                                    @endif
                                    @if ($r['type'] == 'radio')
                                        @if ($r['inline'])
                                            {!! Form::radio($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline() !!}
                                        @else
                                            {!! Form::radio($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline(false) !!}
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @else
                    @endif
                @elseif($r['tag'] == 'textarea')
                    @php
                        
                        $attrs['id'] = 'summernote-' . $r['name'];
                        
                    @endphp
                    {!! Form::textarea($r['name'], $r['label'], $r['default'])->value($value)->attrs($attrs)->placeholder($r['placeholder']) !!}
                @elseif($r['tag'] == 'select' && !$r['multiple'])
                    @php
                        $attr['class'] = 'select2';
                    @endphp
                    {!! Form::select($r['name'], $r['label'])->options($r['options'], $r['custom_key_for_option'], $r['custom_id_for_option'], 'id')->value($value)->attrs($attrs) !!}
                @elseif($r['tag'] == 'select' && $r['multiple'])
                    {!! Form::select($r['name'] . ($r['multiple'] ? '[]' : ''), $r['label'])->options($r['options'], $r['custom_key_for_option'], $r['custom_id_for_option'], 'id')->value($value)->attrs($attrs)->multiple() !!}
                @else
                @endif