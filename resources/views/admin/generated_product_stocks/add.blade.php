@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y pt-5">


        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Generate Product</h5>

                    </div>
                    <p class="badge bg-label-success ml-3 p-2"
                        style="width: 367px;
    background: #5a8dee!important;
    color: white!important;
    padding: 21px 15px!important;
    font-size: 14px;">
                        Per Unit Product Cost-<span id="cost">Rs.0</span></p>
                    <div class="card-body">
                        <!--modalable content-->
                        {!! Form::open()->route($plural_lowercase . '.store')->id(strtolower($module) . '_form')->multipart()->attrs(['data-module' => $module]) !!}
                        <input type="hidden" id="total_cost" name="total_cost" value="0" />
                        <x-forms :data="$data" column='2' />

                        @if (count($repeating_group_inputs) > 0)
                            @foreach ($repeating_group_inputs as $grp)
                                <x-repeatable :data="$grp['inputs']" :label="$grp['label']" values="" :index="$loop->index" />
                            @endforeach
                        @endif
                        <div class="row">
                            <div class="col-sm-10">
                                @php
                                    $r = 'Submit';
                                @endphp
                                {!! Form::submit($r)->id(strtolower($module) . '_btn')->primary() !!}
                            </div>
                        </div>

                        {!! Form::close() !!}

                        <!--modal ends here-->
                    </div><br>
                </div>
            </div>
        </div>
    </div>

@endsection
