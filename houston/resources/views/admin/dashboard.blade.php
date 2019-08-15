@extends('admin.index')
@section('style')
    <!-- Ionicons 2.0.0 -->
    {!! Html::style('assets/admin/assets/dist/icons/ionicons.min.css') !!}
@endsection

@section('page-content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Statistics</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">

                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $users }}</h3>
                            <p>Users </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-people"></i>
                        </div>
                        <a href="{{ route('users.index') }}" class="small-box-footer">
                            Show Details
                            <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->

                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{ $categories }}</h3>
                            <p>Categories </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-folder"></i>
                        </div>
                        <a href="{{ route('categories.index') }}" class="small-box-footer">
                            Show Details <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->

                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>{{ $media }}</h3>
                            <p>Media </p>
                        </div>
                        <div class="icon">
                            <i class="ion-paper-airplane"></i>
                        </div>
                        <a href="{{ route('medias.index') }}" class="small-box-footer">
                            Show Details <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div><!-- ./col -->

                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3>{{ $comments }}</h3>
                            <p>Comments </p>
                        </div>
                        <div class="icon">
                            <i class="ion-chatboxes"></i>
                        </div>
                        <a href="{{ route('comments.index') }}" class="small-box-footer">
                            Show Details <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div><!-- ./col -->
                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ $pages }}</h3>
                            <p>Pages </p>
                        </div>
                        <div class="icon">
                            <i class="ion-document-text"></i>
                        </div>
                        <a href="{{ route('pages.index') }}" class="small-box-footer">
                            Show Details <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $flags }}</h3>
                            <p>Flags </p>
                        </div>
                        <div class="icon">
                            <i class="ion-flag"></i>
                        </div>
                        <a href="{{ route('flags.index') }}" class="small-box-footer">
                            Show Details <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div><!-- ./col -->

                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-white">
                        <div class="inner">
                            <h3>&nbsp;</h3>
                            <p>Recommendations</p>
                        </div>
                        <div class="icon">
                            <i class="ion-information-circled"></i>
                        </div>
                        <a href="{{ route('advanced.recommended') }}" class="small-box-footer">
                            Show Details <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div><!-- ./col -->

                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3>{{ $archives }}</h3>
                            <p>Archives</p>
                        </div>
                        <div class="icon">
                            <i class="ion-android-folder-open"></i>
                        </div>
                        <a href="{{ route('admin.sports.index') }}" class="small-box-footer">
                            Show Details <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $teams }}</h3>
                            <p>Teams</p>
                        </div>
                        <div class="icon">
                            <i class="ion-flag"></i>
                        </div>
                        <a href="{{ route('admin.teams.index') }}" class="small-box-footer">
                            Show Details <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div><!-- ./col -->

                <div class="col-md-2 col-sm-3 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ $sports }}</h3>
                            <p>Sports</p>
                        </div>
                        <div class="icon">
                            <i class="ion-ios-basketball"></i>
                        </div>
                        <a href="{{ route('admin.sports.index') }}" class="small-box-footer">
                            Show Details <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>
            </div><!-- /.row -->

            <div class="row">
                @if (config('local_environment'))
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <label for="general">
                                    <i style="font-size:16px;" class="fa fa-info-circle"></i> Heads Up!
                                </label>
                            </div>
                            <div class="panel-body">
                                <div class="callout callout-info" role="callout">
                                    <p>You are currently set up to be on a local environment. If you are in production, change your settings here: <a href="{{ route('settings.general') }}">Website Settings</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label for="general">
                                <i style="font-size:16px;" class="fa fa-info-circle"></i> Current Version
                            </label>
                        </div>
                        <div class="panel-body">
                            <div class="callout callout-info" role="callout">
                                <p>You are currently on version {{ $version }}.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section><!-- /.content -->
    </div>
@endsection
