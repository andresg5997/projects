@extends('admin.settings.layouts.advanced')

@section('settings-content')
    <section class="content-header" style="margin-bottom: 25px">
        <h1>
            Recommended
            <small>what should be enabled?</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Default box -->
                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">Recommended Settings</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label for="general">
                                        <i style="font-size:16px;" class="fa fa-info-circle"></i> Required
                                    </label>
                                </div>
                                <div class="panel-body">
                                    <p>PHP version: <b>>= 5.6.4</b></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label for="general">
                                        <i style="font-size:16px;" class="fa fa-info-circle"></i> Required - PHP Extensions
                                    </label>
                                </div>
                                <div class="panel-body">
                                    <p>Fileinfo: <?php echo extension_loaded('fileinfo') == 1 ? '<span style="color: #00a65a"><b>Enabled</b></span>' : '<span style="color: #9c3328"><b>Disabled</b></span>'?></p>
                                    <p>Mbstring: <?php echo extension_loaded('mbstring') == 1 ? '<span style="color: #00a65a"><b>Enabled</b></span>' : '<span style="color: #9c3328"><b>Disabled</b></span>'?></p>
                                    <p>OpenSSL: <?php echo extension_loaded('openssl') == 1 ? '<span style="color: #00a65a"><b>Enabled</b></span>' : '<span style="color: #9c3328"><b>Disabled</b></span>'?></p>
                                    <p>PDO: <?php echo extension_loaded('PDO') == 1 ? '<span style="color: #00a65a"><b>Enabled</b></span>' : '<span style="color: #9c3328"><b>Disabled</b></span>'?></p>
                                    <p>Tokenizer: <?php echo extension_loaded('tokenizer') == 1 ? '<span style="color: #00a65a"><b>Enabled</b></span>' : '<span style="color: #9c3328"><b>Disabled</b></span>'?></p>
                                    <p>XML: <?php echo extension_loaded('xml') == 1 ? '<span style="color: #00a65a"><b>Enabled</b></span>' : '<span style="color: #9c3328"><b>Disabled</b></span>'?></p>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <hr>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label for="general">
                                        <i style="font-size:16px;" class="fa fa-info-circle"></i> Recommended - PHP version
                                    </label>
                                </div>
                                <div class="panel-body">
                                    <p>Current PHP version: <b><?php  echo phpversion() >= 7 ? '<span style="color: #00a65a"><b>'.phpversion().'</b></span>' : '<span style="color: #9c3328"><b>'.phpversion().'</b></span>'; ?></b></p>
                                    <p>Recommended PHP version: <b>>= 7.0</b></p>
                                    @if (phpversion() <= 7)
                                        <p>Please consider upgrading your PHP version in order to utilize all features.</p>
                                    @else
                                        <span style="color: #00a65a"><b>You are all set!</b></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label for="general">
                                        <i style="font-size:16px;" class="fa fa-info-circle"></i> Recommended - MySQL version
                                    </label>
                                </div>
                                <div class="panel-body">
                                    @if ($mysql_version)
                                        <p>Current MySQL version: <b><?php  echo version_compare($mysql_version, '5.7.9') >= 0 ? '<span style="color: #00a65a"><b>'.$mysql_version.'</b></span>' : '<span style="color: #9c3328"><b>'.$mysql_version.'</b></span>'; ?></b></p>
                                        <p>Recommended MySQL version: >= <b>5.7.9</b></p>
                                        @if (version_compare($mysql_version, '5.7.9') >= 0)
                                            <p style="color: #00a65a"><b>You are all set!</b></p>
                                        @else
                                            <p>Please consider upgrading your MySQL version in order to utilize all features.</p>
                                        @endif
                                    @elseif ($mariadb_version)
                                        <p>Current MariaDB version: <b><?php  echo version_compare($mariadb_version, '10.2.2-MariaDB') >= 0 ? '<span style="color: #00a65a"><b>'.$mariadb_version.'</b></span>' : '<span style="color: #9c3328"><b>'.$mariadb_version.'</b></span>'; ?></b></p>
                                        <p>Recommended MariaDB version: >= <b>10.2.2</b></p>
                                        @if (version_compare($mariadb_version, '10.2.2-MariaDB') >= 0)
                                            <p style="color: #00a65a"><b>You are all set!</b></p>
                                        @else
                                            <p>Please consider upgrading your MariaDB version in order to utilize all features.</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label for="general">
                                        <i style="font-size:16px;" class="fa fa-info-circle"></i> Recommended - CLI Tools
                                    </label>
                                </div>
                                <div class="panel-body">
                                    @if(! $ffmpeg)
                                        <p><a style="color: #9c3328" href="https://ffmpeg.org/"><strong>ffmpeg is missing</strong></a> - it needs to be installed in order to quickly create random thumbnails of the videos and quickly web optimize .mp4 files.</p>
                                    @else
                                        <p><a style="color: #309942" href="https://ffmpeg.org/"><strong>ffmpeg</strong></a> - installed correctly.</p>
                                    @endif

                                    @if(! $handbrake)
                                        <p><a style="color: #9c3328" href="https://handbrake.fr/"><strong>HandBrakeCLI is missing</strong></a> - it needs to be installed in order convert non mp4 files to a web optimized format that can be used for streaming.</p>
                                    @else
                                        <p><a style="color: #309942" href="https://handbrake.fr/"><strong>HandBrakeCLI</strong></a> - installed correctly.</p>
                                    @endif

                                    @if(! $youtube_dl && $attributes->remote_uploads)
                                        <p><a style="color: #9c3328" href="https://rg3.github.io/youtube-dl/"><strong>youtube-dl is missing</strong></a> - it needs to be installed in order to use Remote Upload - Media Hosts option.</p>
                                    @else
                                        <p><a style="color: #309942" href="https://rg3.github.io/youtube-dl/"><strong>youtube-dl</strong></a> - installed correctly.</p>
                                    @endif

                                    @if(! $gdrive && $attributes->remote_uploads)
                                        <p><a href="https://github.com/prasmussen/gdrive"><strong>gdrive is missing</strong></a> - it is recommended to be installed in order to retrieve the media information from Google Drive.</p>
                                    @else
                                        <p><a style="color: #309942" href="https://github.com/prasmussen/gdrive"><strong>gdrive</strong></a> - installed correctly.</p>
                                    @endif

                                    @if(! $phantomjs && $attributes->remote_uploads)
                                        <p><a href="http://phantomjs.org/download.html"><strong>phantomjs is missing</strong></a> - it is recommended to be installed in order to remotely download files from Openload.</p>
                                    @else
                                        <p><a style="color: #309942" href="http://phantomjs.org/download.html"><strong>phantomjs</strong></a> - installed correctly.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label for="general">
                                        <i style="font-size:16px;" class="fa fa-info-circle"></i> Recommended - PHP Extensions
                                    </label>
                                </div>
                                <div class="panel-body">
                                    <p>Zip: <?php echo extension_loaded('zip') == 1 ? '<span style="color: #00a65a"><b>Enabled</b></span>' : '<span style="color: #9c3328"><b>Disabled</b> - it is needed for when you are trying create <a href="'.route('advanced.backups').'">backups</a>.</span>'?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <label for="general">
                                        <i style="font-size:16px;" class="fa fa-info-circle"></i> Recommended - PHP functions
                                    </label>
                                </div>
                                <div class="panel-body">
                                    @if(! $shell_exec)
                                        <p><a style="color: #9c3328"><strong>shell_exec is disabled</strong></a> - it needs to be enabled for various media functions.</p>
                                    @else
                                        <p><a style="color: #309942"><strong>shell_exec</strong></a> - enabled correctly.</p>
                                    @endif

                                    @if(! $exec)
                                        <p><a style="color: #9c3328"><strong>exec is disabled</strong></a> - it needs to be enabled for various media functions.</p>
                                    @else
                                        <p><a style="color: #309942"><strong>exec</strong></a> - enabled correctly.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.box -->
            <!-- right column -->
        </div><!-- /.row -->
    </section><!-- /.content -->
@endsection

@section('scripts')
@endsection
