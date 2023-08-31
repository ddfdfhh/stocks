<table class="table table-bordered">
    <tbody>

        @php
            $table_columns = array_column($table_columns, 'column');
        @endphp
        @foreach ($table_columns as $t)
            <tr>
                <th>{{ ucwords(str_replace('_', ' ', $t)) }}</th>
                @if (str_contains($t, 'status'))
                    <td>
                        <x-status :status='$row->{$t}' />
                    </td>
                @elseif(str_contains($t, '_at') || str_contains($t, 'date'))
                    <td>{{ formateDate($row->{$t}) }}</td>
                 @elseif($t=='country')
                    <td>{{ $row->country_row->name }}</td>
                @elseif($row->{$t} && (str_contains($t, 'image') || str_contains($t, '_image')  || str_contains($t, 'picture') ||  str_contains($t, 'images')))
                    <td>
                     <x-showImage :isMultiple=$is_multiple :row=$row :t=$t :storageFolder=$storage_folder :imageList=!empty($image_list)?$image_list:[] />
                    </td>
                @else
                    <td>
                    @php 
                    if(!is_numeric($row->{$t})){
                       $tr=json_decode($row->{$t},true);
                      
                        if($tr !== null)
                           echo showArrayInColumn($tr);
                        else
                         echo $row->{$t};
                    }
                    else
                      echo $row->{$t};
                        
                    @endphp
                    </td>
                @endif
            </tr>
        @endforeach

    </tbody>
</table>
