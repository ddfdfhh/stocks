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
                @elseif($t == 'role')
                    <td>{{ showArrayInColumn($row->getRoleNames()) }}</td>
                @elseif($t == 'country')
                    <td>{{ $row->withCountry->name }}</td>
                @elseif(str_contains($t, '_at') || str_contains($t, 'date'))
                    <td>{{ formateDate($row->{$t}) }}</td>
                @elseif(
                    $row->{$t} &&
                        (str_contains($t, '_image') ||
                            str_contains($t, 'image') ||
                            str_contains($t, 'picture') ||
                            str_contains($t, 'picture')))
                    <td>
                        @if (!$is_multiple)
                            @if (str_contains($row->{$t}, '.jpg') ||
                                    str_contains($row->{$t}, '.png') ||
                                    str_contains($row->{$t}, '.gif') ||
                                    str_contains($row->{$t}, '.jpeg'))
                                @php
                                    
                                    $path = storage_path('app/public/' . $storage_folder . '/' . $row->{$t});
                                    if (!\File::exists($path)) {
                                        $path = null;
                                    } else {
                                        $path = asset('storage/' . $storage_folder . '/' . $row->{$t});
                                    }
                                @endphp
                                @if ($path)
                                    <img style="width:100px;height:100px;margin:10px" src="{{ $path }}" />
                                @endif
                            @else
                                @php
                                    
                                    $path = storage_path('app/public/' . $storage_folder . '/' . $row->{$t});
                                    if (!\File::exists($path)) {
                                        $path = null;
                                    } else {
                                        $path = asset('storage/' . $storage_folder . '/' . $row->{$t});
                                    }
                                @endphp
                                @if ($path)
                                    <br>
                                    <i class="bx bx-download"></i> <a href="{{ $path }}"
                                        download>{{ $row->{$image_field_name} }}</a>
                                @endif
                            @endif
                        @else
                            {{-- if multiple --}}
                            @if (count($image_list) > 0)
                                @foreach ($image_list as $image)
                                    @php
                                        $path = storage_path('app/public/' . $storage_folder . '/' . $image->name);
                                        if (!\File::exists($path)) {
                                            $path = null;
                                        } else {
                                            $path = asset('storage/' . $storage_folder . '/' . $image->name);
                                        }
                                    @endphp
                                    @if ($path)
                                        <x-image :name="$image->name" :path="$path" :id="$image->id" />
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </td>
                @else
                    <td>{{ $row->{$t} }}</td>
                @endif
            </tr>
        @endforeach

    </tbody>
</table>
