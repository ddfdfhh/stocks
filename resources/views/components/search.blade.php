@props(['searchableFields'])
@php 
$fields=$searchableFields;
@endphp

   <div class="input-group" style="max-width:313px;float:right">
              <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" style="border-color:#d8d4d4" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="">Search By&nbsp;&nbsp;&nbsp;   </span>
              </button>
              <ul class="dropdown-menu">
                @foreach($fields as $r)
                        <li>
                            <div class="radio dropdown-item">
                                    <label>
                                        <input id="search_by" onchange="setSearchBy(this.value)" type="radio" name="search_by" @if($loop->first) checked @endif value="{{$r['name']}}">
                                    &nbsp;&nbsp;{{$r['label']}}
                                </label>
                            </div>
                        </li>
                   @endforeach
              </ul>
              <input type="text" id="search" class="form-control" placeholder="Type to search" aria-label="Text input with segmented dropdown button">
            </div>



