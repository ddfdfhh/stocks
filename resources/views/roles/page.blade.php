
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
                @elseif(str_contains($t, '_at') || str_contains($t, 'date'))
                    <td>{{ formateDate($r->{$t}) }}</td>
                @elseif(str_contains($t, 'image') || str_contains($t, '_image')  || str_contains($t, 'picture'))
                    <td>
                    @if($r->images)
                     @foreach($r->images as $image)
                    
                           @php
                             $path=storage_path('app/public/'.$storage_folder.'/'. $image->name);
                            if(!\File::exists($path))
                                    $path=null;
                             else
                                 $path=asset('storage/'.$storage_folder.'/'. $image->name);
                            @endphp
                    
                            @if($path)
                            <img style="width:100px;height:100px;margin:10px" src="{{$path}}" />
                            @endif
                     @endforeach
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


