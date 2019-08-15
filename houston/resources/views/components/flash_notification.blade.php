@if (session()->has('flash_notification.message'))
<br><br>
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-xs-12">
            <div class="header">
                <div class="alert alert-{{ session('flash_notification.level') }}">
                    @if(! session()->has('flash_notification.important'))
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    @endif

                    {!! session('flash_notification.message') !!}
                </div>
            </div>
        </div>
    </div>
@endif
