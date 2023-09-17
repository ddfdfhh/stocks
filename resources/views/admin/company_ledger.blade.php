@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">


        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between flex-wrap">
                    <h5>Company Ledger </h5>
                  <div class="d-flex">

                        <div class="btn-group" role="group" aria-label="Basic example">
                           

                           
                                <button type="button"
                                    class="rounded-0 dt-button buttons-collection btn btn-label-primary dropdown-toggle me-2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span><i class="bx bx-export me-sm-2"></i> <span
                                            class="d-none d-sm-inline-block">Export</span></span>
                                </button>
                                <ul class="dropdown-menu">

                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('ledger.export', ['type' => 'excel']) }}?{{ http_build_query($_GET) }}"><span><i
                                                    class="bx bx-printer me-2"></i>XLS</span></a>
                                        <a class="dropdown-item"
                                            href="{{ route('ledger.export', ['type' => 'csv']) }}?{{ http_build_query($_GET) }}"><span><i
                                                    class="bx bx-file me-2"></i>CSV</span></a>
                                       

                                    </li>

                                </ul>
                           

                        </div>
                    </div>
                </div>
                <br>
                <div class="d-flex justify-content-between flex-wrap mt-3">
                    <div class="d-flex flex-wrap justify-content-between " style="align-items: start;max-width:660px; ">
                     
                        <x-filter :data="$filterable_fields" />
                    </div>
                    <x-search :searchableFields="$searchable_fields" />

                </div>




            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>

                                @foreach ($table_columns as $t)
                                    @if ($t['sortable'] == 'Yes')
                                        <x-row column="{{ $t['column'] }}" label="{{ $t['label'] }}" />
                                    @else
                                        <th>{{ $t['label'] }}</th>
                                    @endif
                                @endforeach

                               
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="tbody">
                            @php
                                
                                $table_columns = array_column($table_columns, 'column');
                            @endphp
                            @if ($list->total() > 0)
                                @php
                                    $i = $list->perPage() * ($list->currentPage() - 1) + 1;
                                    $l = 0;
                                @endphp
                                @foreach ($list as $r)
                                   
                                    <tr id="row-{{ $r->id }}">
                                        <td>
                                            {{ $i++ }}
                                         
                                        </td>
                                        @foreach ($table_columns as $t)
                                            @php   ++$l;@endphp
                                            @if (str_contains($t, 'status'))
                                                <td>
                                                    <x-status :status='$r->{$t}' />
                                                </td>
                                            @else
                                                <td>
                                                    @php
                                                        
                                                        echo $r->{$t};
                                                        
                                                    @endphp
                                                </td>
                                            @endif
                                        @endforeach



                                    </tr>
                                @endforeach
                                <td colspan='7'>{!! $list->links() !!}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="{{ count($table_columns) + 1 }}" align="center">No records</td>
                                </tr>
                            @endif



                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="hidden_page" id="hidden_page"
                    value="{{ !empty($_GET['page']) ? $_GET['page'] : '1' }}" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
                <input type="hidden" name="search_by" id="search_by" value="" />

            </div>
        </div>
    </div>
@endsection
