@extends('admin.settings.layouts.advanced')

@section('settings-content')
    <section class="content-header">
        <h1>
            Log Manager
            <small>Preview, download and delete logs</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('advanced.logs') }}"><i class="fa fa-angle-double-left"></i> Back to all logs</a>
                <br>
                <br>
                <!-- Default box -->
                <div class="box">
                    <div class="box-body">
                        <h3>{{ \Carbon\Carbon::createFromTimeStamp($log['last_modified'])->formatLocalized('%d %B %Y') }}:</h3>
                        <pre>
                            <code>
                              {{ $log['content'] }}
                            </code>
                        </pre>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
@endsection
