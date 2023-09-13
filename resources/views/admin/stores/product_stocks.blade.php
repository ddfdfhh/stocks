@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasEndLabel" class="offcanvas-title" style="text-transform:capitalize;">
                    Store Products</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0">
                <p class="text-center">Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out
                    print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century
                    who is thought to have scrambled parts of Cicero's De Finibus Bonorum et Malorum for use in a type
                    specimen book.</p>
                <button type="button" class="btn btn-primary mb-2 d-grid w-100">Continue</button>
                <button type="button" class="btn btn-label-secondary d-grid w-100"
                    data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </div>
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between flex-wrap">
                    <h5>Store Products</h5>

                </div>
                <br>
                <div class="d-flex justify-content-between flex-wrap mt-3">
                    <div class="d-flex flex-wrap justify-content-between " style="align-items: start;max-width:660px; ">

                        <x-filter :data="$filterable_fields" />
                    </div>
                   
                </div>




            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
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
                                        <td>{{$r->product->name}}</td>
                                         <td>{{$r->product->price}}</td>
                                       
                                        <td>{{ $r->total_quantity  }}
                                        </td>
                                        <td>{{ $r->current_quantity  }}
                                        </td>
                                        <td>{{ $r->other_location_recieved_quantity  }}
                                        </td>
                                        
                                        <td>{{ $r->admin_recieved_quantity  }}
                                        </td>
                                       


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
