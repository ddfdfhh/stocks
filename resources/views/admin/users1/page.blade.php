
@php
     
         $table_columns = array_column($table_columns, 'column');
@endphp
    @if ($list->total() > 0)
    @php
        $i = $list->perPage() * ($list->currentPage() - 1) + 1;
      
    @endphp
    @foreach ($list as $r)
     @php
       
        $deleteurl = route($plural_lowercase . '.destroy', [strtolower($module) => $r->id]);
        $viewurl = route($plural_lowercase.'.view');
       
    @endphp
        <tr id="row-{{$r->id}}">
            <td>
                {{ $i++ }}
                 <input  name="ids[]"  class="form-check-input" type="checkbox" id="check_all" value="{{$r->id}}" />
    

            </td>
            @foreach ($table_columns as $t)
                @if (str_contains($t, 'status'))
                    <td>
                        <x-status :status='$r->{$t}' />
                    </td>
                @elseif($t=='role')
                    <td>{{ showArrayInColumn($r->getRoleNames()) }}</td>
                @elseif($t=='country')
                    <td>{{ $r->withCountry->name}}</td>
                @elseif(str_contains($t, '_at') || str_contains($t, 'date'))
                    <td>{{ formateDate($r->{$t}) }}</td>
               
@elseif($r->{$t} && (str_contains($t, 'image') || str_contains($t, '_image')  || str_contains($t, 'picture') ||  str_contains($t, 'images')))
                         <td>
                                @if(!$is_multiple)
                                        @if(str_contains($r->{$t},'.jpg') || str_contains($r->{$t},'.png') || str_contains($r->{$t},'.gif') || str_contains($r->{$t},'.jpeg'))
                                            @php

                                                $path=storage_path('app/public/'.$storage_folder.'/'. $r->{$t});
                                                if(!\File::exists($path))
                                                    $path=null;
                                                else
                                                $path=asset('storage/'.$storage_folder.'/'.$r->{$t});
                                            @endphp
                                            @if($path)
                                            <a href="{{$path}}" data-lightbox="image-1">
                                                <img style="width:100px;height:100px;margin:10px" src="{{$path}}" /></a>
                                            @endif
                                        @else
                                            @php

                                                    $path=storage_path('app/public/'.$storage_folder.'/'. $r->{$t});
                                                    if(!\File::exists($path))
                                                        $path=null;
                                                    else
                                                    $path=asset('storage/'.$storage_folder.'/'. $r->{$t});
                                                @endphp
                                                @if($path)
                                                <br>
                                                    <i class="bx bx-download"></i> <a href="{{$path}}" download >{{ $r->{$image_field_name} }}</a>
                                                @endif
                                        @endif
                                    @else  {{-- if multiple --}}
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
                        </td>
                           
                @else
                    <td>{{ $r->{$t} }}</td>
                @endif
            @endforeach
            <td>
                <a class="btn btn-success btn-icon" title="View" href="javascript:load_form('{!!$module!!}','view','{!!route(strtolower($module).'.loadAjaxForm')!!}','{!!$r->id!!}')">
                <i class="bx bx-dice-4"></i> 
                </a>
               @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('edit_'.$plural_lowercase))
                        <a class="btn  btn-info btn-icon" title="Edit" href="javascript:load_form('{!!$module!!}','edit','{!!route(strtolower($module).'.loadAjaxForm')!!}','{!!$r->id!!}')">
                        <i class="bx bx-edit"></i> </a>
                 @endif 
                    @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('delete_'.$plural_lowercase))
                         <a class="btn  btn-danger btn-icon" title="Delete" href="javascript:deleteRecord('{!! $r->id !!}','{!! $deleteurl !!}');">
                         <i class="bx bx-trash"></i></a>
                   @endif
                    {{-- <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                            class="bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                             <a class="dropdown-item" href="javascript:viewRecord('{!! $r->id !!}','{!! $viewurl !!}','{!!strtolower($module)!!}');"><i class="bx bx-trophy me-2"></i> View</a>
                @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('edit_'.$plural_lowercase))
                        <a class="dropdown-item" href="{{ route($plural_lowercase . '.edit', [strtolower($module) => $r->id]) }}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                     @endif 
                       @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('delete_'.$plural_lowercase))
                          <a class="dropdown-item" href="javascript:deleteRecord('{!! $r->id !!}','{!! $deleteurl !!}');"><i class="bx bx-trash me-2"></i> Delete</a>
                    @endif 
                     </div>
                </div>--}} 
            </td>
           
        </tr>
    @endforeach
    <td colspan='7'>{!! $list->links() !!}</td>
</tr>

@else 
<tr>
    <td colspan="{{count($table_columns)+1}}" align="center">No records</td>
</tr>
@endif
<div id="{{strtolower($module)}}_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
       
        <h4 class="modal-title">View  {{$module}}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
       <div class="spinner-border text-muted"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


