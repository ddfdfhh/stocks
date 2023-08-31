@if($errors->any())
    {!! implode('', $errors->all('<div class="alert alert-danger">&#9888;&nbsp;&nbsp;:message</div>')) !!}
@endif