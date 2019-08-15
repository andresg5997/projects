@if ( $errors->any() )
   <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
    <ul>
        @foreach ( $errors->all() as $error )
        <li><b>{{ $error }}</b></li>
        @endforeach
    </ul>
  </div>
@endif