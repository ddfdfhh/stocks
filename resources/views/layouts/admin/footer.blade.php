<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<!-- <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script> -->
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->
<!-- Vendors JS -->
{{-- <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script> --}}
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<!-- Main JS -->
<script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>

  <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- Page JS -->
{{-- <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script> --}}
<!----my own js-->

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'sdsdf': 'shashi'
        }
    });
    var baseurl = window.location.origin + '/admin/';
</script>

{{-- Include below file in all themes anywhere not related to any theme --}}
<script src="{{ asset('commonjs/jquery.validate.min.js') }}"></script>
<script src="{{ asset('commonjs/jquery.filer.min.js') }}"></script>
<script src="{{ asset('commonjs/generate.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

<script src="{{ asset('assets/js/bootstrap-filestyle.min.js') }}"></script>
<script src="{{ asset('commonjs/formvalidationcommon.js') }}?v=2"></script>

<script src="{{ asset('commonjs/custom_form_validation.js') }}?v=2"></script>
<script src="{{ asset('commonjs/commonjs_functions.js') }}"></script>
<script src="{{ asset('commonjs/index_table_sort_pagination.js') }}"></script>
<script src="{{ asset('commonjs/summernote.min.js') }}"></script>
<script src="{{ asset('commonjs/custom.js') }}"></script>
<script src="{{ asset('assets/js/lightbox.min.js') }}"></script>

@stack('scripts')
