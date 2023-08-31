@extends('layouts.admin.app')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 breadcrumb-wrapper mb-4">
              <span class="text-muted fw-light">{{$module}} /</span> List
            </h4>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel" >
            <div class="offcanvas-header">
              <h5 id="offcanvasEndLabel" class="offcanvas-title" style="text-transform:capitalize;">{{$module}}</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0">
              <p class="text-center">Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Cicero's De Finibus Bonorum et Malorum for use in a type specimen book.</p>
              <button type="button" class="btn btn-primary mb-2 d-grid w-100">Continue</button>
              <button type="button" class="btn btn-label-secondary d-grid w-100" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
          </div>
            <!-- Basic Bootstrap Table -->
            <div class="card">
              <div class="card-header">
                    <div class="d-flex justify-content-between">
                       <h5>All {{$module}}</h5>
                       <div class="d-flex"> 
                             
                              <div> 
                             {{--    <a href="{{route($plural_lowercase.'.create')}}" class="btn btn-primary">Create {{$module}}</a>--}}
                            
                              @if(auth()->user()->hasRole(['Admin']) || auth()->user()->can('create_'.$plural_lowercase))
                 <button class="btn btn-primary" type="button" onclick="load_form('{!!$module!!}','add','{!!route(strtolower($module).'.loadAjaxForm')!!}',null)" aria-controls="offcanvasEnd">Create {{$module}}</button>
                          @endif
                              </div>
                      </div>
                    </div>
                     <br>
                     <div class="d-flex justify-content-between">
                  
                    <x-search  :searchableFields="$searchable_fields" /> 
                    </div> 
                  
                        
                 
              </div>
              <div class="card-body">
                  <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th >#</th> 
                          @foreach($table_columns as $t)
                            @if($t['sortable']=='Yes')
                              <x-row column="{{$t['column']}}" label="{{$t['label']}}"/> 
                            @else 
                            <th>{{$t['label']}}</th>
                            @endif
                        @endforeach
                         <th>Action</th>
                        </tr>
                      </thead>
                      <tbody class="table-border-bottom-0" id="tbody">
                       @include('admin.'.$plural_lowercase.'.page')
                      </tbody>
                    </table>
                  </div>
                    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="" />
                    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
                    <input type="hidden" name="search_by" id="search_by" value="" />
                  
              </div>
            </div>
</div>
@endsection 
          

