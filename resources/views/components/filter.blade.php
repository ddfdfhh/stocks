@props(['data'])
<!--Data should be array each item['name','label','type='select ,data,input ',options=['key'=>'',value=>''] -->
@php
    $data = $data;
@endphp
@if(!empty($data))
<div class="btn-group dropleft show" id="filter" >
    <button type="button" class="rounded-0 btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="true">
        <i class="bx bx-filter-alt"></i>&nbsp;&nbsp;Filter
    </button>
    <div class="dropdown-menu dropleft p-3 shadow-lg" x-placement="left-start" id="dropdown_div"
        style="position: absolute; transform: translate3d(-202px, 0px, 0px); top: 0px; left: -32px;min-width:300px; will-change: transform;">
        <b>Filter List</b><a class="ml-2 btn btn-sm btn-primary" style="margin-left:5px"
            href="{{ request()->url() }}">Reset </a>
        <div class="dropdown-divider"></div>
        <form id="try">
          <div style="max-height:300px;overflow-y:scroll;">
            @foreach ($data as $t)
                @if ($t['type'] == 'date')
                  <b style="font-weight: 600;font-size: 13px;">{{ $t['label'] }}</b>
                    <div class="d-flex">
                        <div class="form-group mr-2" style="width: 50%;">
                            <label style="font-size: 12px;" for="start_{{ $t['name'] }}">Start</label>
                            <input type="date" placeholder="Start Date" class="form-control" name="start_{{ $t['name'] }}">
                        </div>
                        <div class="form-group" style="width: 50%;">
                            <label style="font-size: 12px;" for="end-{{ $t['name'] }}">End</label>
                            <input type="date" placeholder="End Date" class="form-control" name="end_{{ $t['name'] }}">
                        </div>
                    </div>
                 @elseif ($t['type'] == 'number')
                    <b style="font-weight: 600;font-size: 13px;">{{ $t['label'] }}</b>
                    <div class="d-flex">
                        <div class="form-group mr-2" style="width: 50%;">
                            <label style="font-size: 12px;" for="min_{{ $t['name'] }}">Min</label>
                            <input type="number" value="0" class="form-control" name="min_{{ $t['name'] }}">
                        </div>
                        <div class="form-group" style="width: 50%;">
                            <label style="font-size: 12px;" for="max-{{ $t['name'] }}">Max</label>
                            <input type="number" value="0" class="form-control" name="max_{{ $t['name'] }}">
                        </div>
                    </div>
                @elseif($t['type'] == 'select')
                  <b style="font-weight: 600;font-size: 13px;">{{ $t['label'] }}</b>
                    <div class="form-group">
                        <select class="form-control" name="{{ $t['name'] }}">
                            <option value="">Select {{ $t['label'] }}</option>
                            @foreach ($t['options'] as $p)
                                <option value="{{ $p->id }}" @if (isset($t['default']) && $p->id == $t['default']) selected @endif>
                                    {{ $p->name }}</option>
                            @endforeach

                        </select>

                    </div>
                @elseif($t['type'] == 'text')
                  <b style="font-weight: 600;font-size: 13px;">{{ $t['label'] }}</b>
                    <div class="form-group">
                        <input class="form-control" name="{{ $t['name'] }}" type="text" />
                    </div>
                @endif
            @endforeach
            </div>
            <div class="form-group mt-2">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endif
