@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
       

        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between flex-wrap">
                    <h5>All {{ properPluralName($plural_lowercase) }}</h5>
                    <div class="d-flex">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            @if (auth()->user()->hasRole(['Admin']) ||
                                    auth()->user()->can('create_' . $plural_lowercase))
                                <button type="button" class="btn btn-primary text-white">
                                    <a href="{{ route($plural_lowercase . '.create') }}"
                                        class="text-decoration-none text-white">
                                        <i class="bx bx-plus me-sm-2"></i>Add
                                        {{ properSingularName($plural_lowercase) }}</a>
                                </button>
                            @endif

                            @if ($has_export)
                                <button type="button"
                                    class="dt-button buttons-collection btn btn-label-primary dropdown-toggle me-2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span><i class="bx bx-export me-sm-2"></i> <span
                                            class="d-none d-sm-inline-block">Export</span></span>
                                </button>
                                <ul class="dropdown-menu">

                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route(strtolower($module) . '.export', ['type' => 'excel']) }}?{{ http_build_query($_GET) }}"><span><i
                                                    class="bx bx-printer me-2"></i>XLS</span></a>
                                        <a class="dropdown-item"
                                            href="{{ route(strtolower($module) . '.export', ['type' => 'csv']) }}?{{ http_build_query($_GET) }}"><span><i
                                                    class="bx bx-file me-2"></i>CSV</span></a>
                                        <a class="dropdown-item"
                                            href="{{ route(strtolower($module) . '.export', ['type' => 'pdf']) }}?{{ http_build_query($_GET) }}"><span><i
                                                    class="bx bxs-file-pdf me-2"></i>PDF</span></a>

                                    </li>

                                </ul>
                            @endif

                        </div>

                    </div>
                </div>
                <br>
                <div class="d-flex justify-content-between flex-wrap">

                    <div class="dropdown mb-2">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            Update Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="javascript:multiSelectCheckBoxAction('Approved','status','{!! route('table_filed_update') !!}','{!! $plural_lowercase !!}')">Approve</a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="javascript:multiSelectCheckBoxAction('Rejected','status','{!! route('table_filed_update') !!}','{!! $plural_lowercase !!}')">Reject</a>
                            </li>

                        </ul>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="tbody">
                            @include('admin.' . $plural_lowercase . '.page')
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
