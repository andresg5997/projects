@extends('admin.index')

@section('style')
    <!-- Select2 -->
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css') !!}

@endsection

@section('page-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Teams
                <small>Edit Team</small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-Teal">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-edit"></i> Edit Team | <span
                                        style="color:#3c8dbc">{{$team->name}}</span></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        {!! Form::model($team, ['method' => 'PATCH', 'route' => ['admin.teams.update', $team->id], 'files' => true]) !!}
                        @if (session()->has('flash_notification.message'))
                            <div class="alert alert-{{ session('flash_notification.level') }}">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                {!! session('flash_notification.message') !!}
                            </div>
                        @endif
                        @if ( $errors->any() )
                            <div class="col-md-12" style="margin-top:15px;">
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                    </button>
                                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                    <ul>
                                        @foreach ( $errors->all() as $error )
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="avatar">
                                            <i class="fa fa-picture-o"></i> Picture
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        @if($team->avatar)
                                            @if(\App::environment('production'))
                                            <img src="{{ Storage::disk('s3')->temporaryUrl($team->avatar, Carbon::now()->addMinutes(5)) }}" alt="" class="img-responsive">
                                            @else
                                            <img src="{{ Storage::url($team->avatar) }}" alt="" class="img-responsive">
                                            @endif
                                        @endif
                                        <br>
                                        <div class="form-group">
                                            <label for="avatar">Load a new team picture</label>
                                            {!! Form::file('avatar', ['accept' => 'image/*']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="name">
                                            <i class="fa fa-folder-open"></i> Name
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="user_id">
                                            <i style="font-size:16px;" class="fa fa-user"></i> User
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <select name="user_id" id="user_id" class="form-control">
                                                @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ ($team->user_id === $user->id) ? 'selected': '' }}>{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="sport_id">
                                            <i style="font-size:16px;" class="fa fa-star"></i> Sport
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <select name="sport_id" id="sport_id" class="form-control">
                                                @foreach($sports as $sport)
                                                <option value="{{ $sport->id }}" {{ ($team->sport_id === $sport->id) ? 'selected': '' }}>{{ $sport->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="timezone">
                                            <i class="fa fa-globe"></i> Timezone
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <select name="timezone" class="form-control">
                                                <option {{ ($team->timezone == 'America/Adak' ) ? 'selected' : '' }} value="America/Adak">America/Adak</option>
                                                <option {{ ($team->timezone == 'America/Anchorage' ) ? 'selected' : '' }} value="America/Anchorage">America/Anchorage</option>
                                                <option {{ ($team->timezone == 'America/Anguilla' ) ? 'selected' : '' }} value="America/Anguilla">America/Anguilla</option>
                                                <option {{ ($team->timezone == 'America/Antigua' ) ? 'selected' : '' }} value="America/Antigua">America/Antigua</option>
                                                <option {{ ($team->timezone == 'America/Araguaina' ) ? 'selected' : '' }} value="America/Araguaina">America/Araguaina</option>
                                                <option {{ ($team->timezone == 'America/Aruba' ) ? 'selected' : '' }} value="America/Aruba">America/Aruba</option>
                                                <option {{ ($team->timezone == 'America/Asuncion' ) ? 'selected' : '' }} value="America/Asuncion">America/Asuncion</option>
                                                <option {{ ($team->timezone == 'America/Atikokan' ) ? 'selected' : '' }} value="America/Atikokan">America/Atikokan</option>
                                                <option {{ ($team->timezone == 'America/Atka' ) ? 'selected' : '' }} value="America/Atka">America/Atka</option>
                                                <option {{ ($team->timezone == 'America/Bahia' ) ? 'selected' : '' }} value="America/Bahia">America/Bahia</option>
                                                <option {{ ($team->timezone == 'America/Barbados' ) ? 'selected' : '' }} value="America/Barbados">America/Barbados</option>
                                                <option {{ ($team->timezone == 'America/Belem' ) ? 'selected' : '' }} value="America/Belem">America/Belem</option>
                                                <option {{ ($team->timezone == 'America/Belize' ) ? 'selected' : '' }} value="America/Belize">America/Belize</option>
                                                <option {{ ($team->timezone == 'America/Blanc' ) ? 'selected' : '' }} value="America/Blanc">America/Blanc</option>
                                                <option {{ ($team->timezone == 'America/Boa_Vista' ) ? 'selected' : '' }} value="America/Boa_Vista">America/Boa_Vista</option>
                                                <option {{ ($team->timezone == 'America/Bogota' ) ? 'selected' : '' }} value="America/Bogota">America/Bogota</option>
                                                <option {{ ($team->timezone == 'America/Boise' ) ? 'selected' : '' }} value="America/Boise">America/Boise</option>
                                                <option {{ ($team->timezone == 'America/Buenos_Aires' ) ? 'selected' : '' }} value="America/Buenos_Aires">America/Buenos_Aires</option>
                                                <option {{ ($team->timezone == 'America/Cambridge_Bay' ) ? 'selected' : '' }} value="America/Cambridge_Bay">America/Cambridge_Bay</option>
                                                <option {{ ($team->timezone == 'America/Campo_Grande' ) ? 'selected' : '' }} value="America/Campo_Grande">America/Campo_Grande</option>
                                                <option {{ ($team->timezone == 'America/Cancun' ) ? 'selected' : '' }} value="America/Cancun">America/Cancun</option>
                                                <option {{ ($team->timezone == 'America/Caracas' ) ? 'selected' : '' }} value="America/Caracas">America/Caracas</option>
                                                <option {{ ($team->timezone == 'America/Catamarca' ) ? 'selected' : '' }} value="America/Catamarca">America/Catamarca</option>
                                                <option {{ ($team->timezone == 'America/Cayenne' ) ? 'selected' : '' }} value="America/Cayenne">America/Cayenne</option>
                                                <option {{ ($team->timezone == 'America/Cayman' ) ? 'selected' : '' }} value="America/Cayman">America/Cayman</option>
                                                <option {{ ($team->timezone == 'America/Chicago' ) ? 'selected' : '' }} value="America/Chicago">America/Chicago</option>
                                                <option {{ ($team->timezone == 'America/Chihuahua' ) ? 'selected' : '' }} value="America/Chihuahua">America/Chihuahua</option>
                                                <option {{ ($team->timezone == 'America/Coral_Harbour' ) ? 'selected' : '' }} value="America/Coral_Harbour">America/Coral_Harbour</option>
                                                <option {{ ($team->timezone == 'America/Cordoba' ) ? 'selected' : '' }} value="America/Cordoba">America/Cordoba</option>
                                                <option {{ ($team->timezone == 'America/Costa_Rica' ) ? 'selected' : '' }} value="America/Costa_Rica">America/Costa_Rica</option>
                                                <option {{ ($team->timezone == 'America/Cuiaba' ) ? 'selected' : '' }} value="America/Cuiaba">America/Cuiaba</option>
                                                <option {{ ($team->timezone == 'America/Curacao' ) ? 'selected' : '' }} value="America/Curacao">America/Curacao</option>
                                                <option {{ ($team->timezone == 'America/Danmarkshavn' ) ? 'selected' : '' }} value="America/Danmarkshavn">America/Danmarkshavn</option>
                                                <option {{ ($team->timezone == 'America/Dawson' ) ? 'selected' : '' }} value="America/Dawson">America/Dawson</option>
                                                <option {{ ($team->timezone == 'America/Dawson_Creek' ) ? 'selected' : '' }} value="America/Dawson_Creek">America/Dawson_Creek</option>
                                                <option {{ ($team->timezone == 'America/Denver' ) ? 'selected' : '' }} value="America/Denver">America/Denver</option>
                                                <option {{ ($team->timezone == 'America/Detroit' ) ? 'selected' : '' }} value="America/Detroit">America/Detroit</option>
                                                <option {{ ($team->timezone == 'America/Dominica' ) ? 'selected' : '' }} value="America/Dominica">America/Dominica</option>
                                                <option {{ ($team->timezone == 'America/Edmonton' ) ? 'selected' : '' }} value="America/Edmonton">America/Edmonton</option>
                                                <option {{ ($team->timezone == 'America/Eirunepe' ) ? 'selected' : '' }} value="America/Eirunepe">America/Eirunepe</option>
                                                <option {{ ($team->timezone == 'America/El_Salvador' ) ? 'selected' : '' }} value="America/El_Salvador">America/El_Salvador</option>
                                                <option {{ ($team->timezone == 'America/Ensenada' ) ? 'selected' : '' }} value="America/Ensenada">America/Ensenada</option>
                                                <option {{ ($team->timezone == 'America/Fort_Wayne' ) ? 'selected' : '' }} value="America/Fort_Wayne">America/Fort_Wayne</option>
                                                <option {{ ($team->timezone == 'America/Fortaleza' ) ? 'selected' : '' }} value="America/Fortaleza">America/Fortaleza</option>
                                                <option {{ ($team->timezone == 'America/Glace_Bay' ) ? 'selected' : '' }} value="America/Glace_Bay">America/Glace_Bay</option>
                                                <option {{ ($team->timezone == 'America/Godthab' ) ? 'selected' : '' }} value="America/Godthab">America/Godthab</option>
                                                <option {{ ($team->timezone == 'America/Goose_Bay' ) ? 'selected' : '' }} value="America/Goose_Bay">America/Goose_Bay</option>
                                                <option {{ ($team->timezone == 'America/Grand_Turk' ) ? 'selected' : '' }} value="America/Grand_Turk">America/Grand_Turk</option>
                                                <option {{ ($team->timezone == 'America/Grenada' ) ? 'selected' : '' }} value="America/Grenada">America/Grenada</option>
                                                <option {{ ($team->timezone == 'America/Guadeloupe' ) ? 'selected' : '' }} value="America/Guadeloupe">America/Guadeloupe</option>
                                                <option {{ ($team->timezone == 'America/Guatemala' ) ? 'selected' : '' }} value="America/Guatemala">America/Guatemala</option>
                                                <option {{ ($team->timezone == 'America/Guayaquil' ) ? 'selected' : '' }} value="America/Guayaquil">America/Guayaquil</option>
                                                <option {{ ($team->timezone == 'America/Guyana' ) ? 'selected' : '' }} value="America/Guyana">America/Guyana</option>
                                                <option {{ ($team->timezone == 'America/Halifax' ) ? 'selected' : '' }} value="America/Halifax">America/Halifax</option>
                                                <option {{ ($team->timezone == 'America/Havana' ) ? 'selected' : '' }} value="America/Havana">America/Havana</option>
                                                <option {{ ($team->timezone == 'America/Hermosillo' ) ? 'selected' : '' }} value="America/Hermosillo">America/Hermosillo</option>
                                                <option {{ ($team->timezone == 'America/Indiana' ) ? 'selected' : '' }} value="America/Indiana">America/Indiana</option>
                                                <option {{ ($team->timezone == 'America/Indiana' ) ? 'selected' : '' }} value="America/Indiana">America/Indiana</option>
                                                <option {{ ($team->timezone == 'America/Indiana' ) ? 'selected' : '' }} value="America/Indiana">America/Indiana</option>
                                                <option {{ ($team->timezone == 'America/Indiana' ) ? 'selected' : '' }} value="America/Indiana">America/Indiana</option>
                                                <option {{ ($team->timezone == 'America/Indiana' ) ? 'selected' : '' }} value="America/Indiana">America/Indiana</option>
                                                <option {{ ($team->timezone == 'America/Indiana' ) ? 'selected' : '' }} value="America/Indiana">America/Indiana</option>
                                                <option {{ ($team->timezone == 'America/Indiana' ) ? 'selected' : '' }} value="America/Indiana">America/Indiana</option>
                                                <option {{ ($team->timezone == 'America/Indiana' ) ? 'selected' : '' }} value="America/Indiana">America/Indiana</option>
                                                <option {{ ($team->timezone == 'America/Indianapolis' ) ? 'selected' : '' }} value="America/Indianapolis">America/Indianapolis</option>
                                                <option {{ ($team->timezone == 'America/Inuvik' ) ? 'selected' : '' }} value="America/Inuvik">America/Inuvik</option>
                                                <option {{ ($team->timezone == 'America/Iqaluit' ) ? 'selected' : '' }} value="America/Iqaluit">America/Iqaluit</option>
                                                <option {{ ($team->timezone == 'America/Jamaica' ) ? 'selected' : '' }} value="America/Jamaica">America/Jamaica</option>
                                                <option {{ ($team->timezone == 'America/Jujuy' ) ? 'selected' : '' }} value="America/Jujuy">America/Jujuy</option>
                                                <option {{ ($team->timezone == 'America/Juneau' ) ? 'selected' : '' }} value="America/Juneau">America/Juneau</option>
                                                <option {{ ($team->timezone == 'America/Kentucky' ) ? 'selected' : '' }} value="America/Kentucky">America/Kentucky</option>
                                                <option {{ ($team->timezone == 'America/Kentucky' ) ? 'selected' : '' }} value="America/Kentucky">America/Kentucky</option>
                                                <option {{ ($team->timezone == 'America/Knox_IN' ) ? 'selected' : '' }} value="America/Knox_IN">America/Knox_IN</option>
                                                <option {{ ($team->timezone == 'America/La_Paz' ) ? 'selected' : '' }} value="America/La_Paz">America/La_Paz</option>
                                                <option {{ ($team->timezone == 'America/Lima' ) ? 'selected' : '' }} value="America/Lima">America/Lima</option>
                                                <option {{ ($team->timezone == 'America/Los_Angeles' ) ? 'selected' : '' }} value="America/Los_Angeles">America/Los_Angeles</option>
                                                <option {{ ($team->timezone == 'America/Louisville' ) ? 'selected' : '' }} value="America/Louisville">America/Louisville</option>
                                                <option {{ ($team->timezone == 'America/Maceio' ) ? 'selected' : '' }} value="America/Maceio">America/Maceio</option>
                                                <option {{ ($team->timezone == 'America/Managua' ) ? 'selected' : '' }} value="America/Managua">America/Managua</option>
                                                <option {{ ($team->timezone == 'America/Manaus' ) ? 'selected' : '' }} value="America/Manaus">America/Manaus</option>
                                                <option {{ ($team->timezone == 'America/Marigot' ) ? 'selected' : '' }} value="America/Marigot">America/Marigot</option>
                                                <option {{ ($team->timezone == 'America/Martinique' ) ? 'selected' : '' }} value="America/Martinique">America/Martinique</option>
                                                <option {{ ($team->timezone == 'America/Matamoros' ) ? 'selected' : '' }} value="America/Matamoros">America/Matamoros</option>
                                                <option {{ ($team->timezone == 'America/Mazatlan' ) ? 'selected' : '' }} value="America/Mazatlan">America/Mazatlan</option>
                                                <option {{ ($team->timezone == 'America/Mendoza' ) ? 'selected' : '' }} value="America/Mendoza">America/Mendoza</option>
                                                <option {{ ($team->timezone == 'America/Menominee' ) ? 'selected' : '' }} value="America/Menominee">America/Menominee</option>
                                                <option {{ ($team->timezone == 'America/Merida' ) ? 'selected' : '' }} value="America/Merida">America/Merida</option>
                                                <option {{ ($team->timezone == 'America/Mexico_City' ) ? 'selected' : '' }} value="America/Mexico_City">America/Mexico_City</option>
                                                <option {{ ($team->timezone == 'America/Miquelon' ) ? 'selected' : '' }} value="America/Miquelon">America/Miquelon</option>
                                                <option {{ ($team->timezone == 'America/Moncton' ) ? 'selected' : '' }} value="America/Moncton">America/Moncton</option>
                                                <option {{ ($team->timezone == 'America/Monterrey' ) ? 'selected' : '' }} value="America/Monterrey">America/Monterrey</option>
                                                <option {{ ($team->timezone == 'America/Montevideo' ) ? 'selected' : '' }} value="America/Montevideo">America/Montevideo</option>
                                                <option {{ ($team->timezone == 'America/Montreal' ) ? 'selected' : '' }} value="America/Montreal">America/Montreal</option>
                                                <option {{ ($team->timezone == 'America/Montserrat' ) ? 'selected' : '' }} value="America/Montserrat">America/Montserrat</option>
                                                <option {{ ($team->timezone == 'America/Nassau' ) ? 'selected' : '' }} value="America/Nassau">America/Nassau</option>
                                                <option {{ ($team->timezone == 'America/New_York' ) ? 'selected' : '' }} value="America/New_York">America/New_York</option>
                                                <option {{ ($team->timezone == 'America/Nipigon' ) ? 'selected' : '' }} value="America/Nipigon">America/Nipigon</option>
                                                <option {{ ($team->timezone == 'America/Nome' ) ? 'selected' : '' }} value="America/Nome">America/Nome</option>
                                                <option {{ ($team->timezone == 'America/Noronha' ) ? 'selected' : '' }} value="America/Noronha">America/Noronha</option>
                                                <option {{ ($team->timezone == 'America/North_Dakota' ) ? 'selected' : '' }} value="America/North_Dakota">America/North_Dakota</option>
                                                <option {{ ($team->timezone == 'America/North_Dakota' ) ? 'selected' : '' }} value="America/North_Dakota">America/North_Dakota</option>
                                                <option {{ ($team->timezone == 'America/Ojinaga' ) ? 'selected' : '' }} value="America/Ojinaga">America/Ojinaga</option>
                                                <option {{ ($team->timezone == 'America/Panama' ) ? 'selected' : '' }} value="America/Panama">America/Panama</option>
                                                <option {{ ($team->timezone == 'America/Pangnirtung' ) ? 'selected' : '' }} value="America/Pangnirtung">America/Pangnirtung</option>
                                                <option {{ ($team->timezone == 'America/Paramaribo' ) ? 'selected' : '' }} value="America/Paramaribo">America/Paramaribo</option>
                                                <option {{ ($team->timezone == 'America/Phoenix' ) ? 'selected' : '' }} value="America/Phoenix">America/Phoenix</option>
                                                <option {{ ($team->timezone == 'America/Port' ) ? 'selected' : '' }} value="America/Port">America/Port</option>
                                                <option {{ ($team->timezone == 'America/Port_of_Spain' ) ? 'selected' : '' }} value="America/Port_of_Spain">America/Port_of_Spain</option>
                                                <option {{ ($team->timezone == 'America/Porto_Acre' ) ? 'selected' : '' }} value="America/Porto_Acre">America/Porto_Acre</option>
                                                <option {{ ($team->timezone == 'America/Porto_Velho' ) ? 'selected' : '' }} value="America/Porto_Velho">America/Porto_Velho</option>
                                                <option {{ ($team->timezone == 'America/Puerto_Rico' ) ? 'selected' : '' }} value="America/Puerto_Rico">America/Puerto_Rico</option>
                                                <option {{ ($team->timezone == 'America/Rainy_River' ) ? 'selected' : '' }} value="America/Rainy_River">America/Rainy_River</option>
                                                <option {{ ($team->timezone == 'America/Rankin_Inlet' ) ? 'selected' : '' }} value="America/Rankin_Inlet">America/Rankin_Inlet</option>
                                                <option {{ ($team->timezone == 'America/Recife' ) ? 'selected' : '' }} value="America/Recife">America/Recife</option>
                                                <option {{ ($team->timezone == 'America/Regina' ) ? 'selected' : '' }} value="America/Regina">America/Regina</option>
                                                <option {{ ($team->timezone == 'America/Resolute' ) ? 'selected' : '' }} value="America/Resolute">America/Resolute</option>
                                                <option {{ ($team->timezone == 'America/Rio_Branco' ) ? 'selected' : '' }} value="America/Rio_Branco">America/Rio_Branco</option>
                                                <option {{ ($team->timezone == 'America/Rosario' ) ? 'selected' : '' }} value="America/Rosario">America/Rosario</option>
                                                <option {{ ($team->timezone == 'America/Santa_Isabel' ) ? 'selected' : '' }} value="America/Santa_Isabel">America/Santa_Isabel</option>
                                                <option {{ ($team->timezone == 'America/Santarem' ) ? 'selected' : '' }} value="America/Santarem">America/Santarem</option>
                                                <option {{ ($team->timezone == 'America/Santiago' ) ? 'selected' : '' }} value="America/Santiago">America/Santiago</option>
                                                <option {{ ($team->timezone == 'America/Santo_Domingo' ) ? 'selected' : '' }} value="America/Santo_Domingo">America/Santo_Domingo</option>
                                                <option {{ ($team->timezone == 'America/Sao_Paulo' ) ? 'selected' : '' }} value="America/Sao_Paulo">America/Sao_Paulo</option>
                                                <option {{ ($team->timezone == 'America/Scoresbysund' ) ? 'selected' : '' }} value="America/Scoresbysund">America/Scoresbysund</option>
                                                <option {{ ($team->timezone == 'America/Shiprock' ) ? 'selected' : '' }} value="America/Shiprock">America/Shiprock</option>
                                                <option {{ ($team->timezone == 'America/St_Barthelemy' ) ? 'selected' : '' }} value="America/St_Barthelemy">America/St_Barthelemy</option>
                                                <option {{ ($team->timezone == 'America/St_Johns' ) ? 'selected' : '' }} value="America/St_Johns">America/St_Johns</option>
                                                <option {{ ($team->timezone == 'America/St_Kitts' ) ? 'selected' : '' }} value="America/St_Kitts">America/St_Kitts</option>
                                                <option {{ ($team->timezone == 'America/St_Lucia' ) ? 'selected' : '' }} value="America/St_Lucia">America/St_Lucia</option>
                                                <option {{ ($team->timezone == 'America/St_Thomas' ) ? 'selected' : '' }} value="America/St_Thomas">America/St_Thomas</option>
                                                <option {{ ($team->timezone == 'America/St_Vincent' ) ? 'selected' : '' }} value="America/St_Vincent">America/St_Vincent</option>
                                                <option {{ ($team->timezone == 'America/Swift_Current' ) ? 'selected' : '' }} value="America/Swift_Current">America/Swift_Current</option>
                                                <option {{ ($team->timezone == 'America/Tegucigalpa' ) ? 'selected' : '' }} value="America/Tegucigalpa">America/Tegucigalpa</option>
                                                <option {{ ($team->timezone == 'America/Thule' ) ? 'selected' : '' }} value="America/Thule">America/Thule</option>
                                                <option {{ ($team->timezone == 'America/Thunder_Bay' ) ? 'selected' : '' }} value="America/Thunder_Bay">America/Thunder_Bay</option>
                                                <option {{ ($team->timezone == 'America/Tijuana' ) ? 'selected' : '' }} value="America/Tijuana">America/Tijuana</option>
                                                <option {{ ($team->timezone == 'America/Toronto' ) ? 'selected' : '' }} value="America/Toronto">America/Toronto</option>
                                                <option {{ ($team->timezone == 'America/Tortola' ) ? 'selected' : '' }} value="America/Tortola">America/Tortola</option>
                                                <option {{ ($team->timezone == 'America/Vancouver' ) ? 'selected' : '' }} value="America/Vancouver">America/Vancouver</option>
                                                <option {{ ($team->timezone == 'America/Virgin' ) ? 'selected' : '' }} value="America/Virgin">America/Virgin</option>
                                                <option {{ ($team->timezone == 'America/Whitehorse' ) ? 'selected' : '' }} value="America/Whitehorse">America/Whitehorse</option>
                                                <option {{ ($team->timezone == 'America/Winnipeg' ) ? 'selected' : '' }} value="America/Winnipeg">America/Winnipeg</option>
                                                <option {{ ($team->timezone == 'America/Yakutat' ) ? 'selected' : '' }} value="America/Yakutat">America/Yakutat</option>
                                                <option {{ ($team->timezone == 'America/Yellowknife' ) ? 'selected' : '' }} value="America/Yellowknife">America/Yellowknife</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="country">
                                            <i class="fa fa-globe"></i> Country
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <select class="form-control" name="country">
                                                <option {{ ($team->country == 'AF' ) ? 'selected' : '' }} value="AF">Afghanistan</option>
                                                <option {{ ($team->country == 'AX' ) ? 'selected' : '' }} value="AX">Åland Islands</option>
                                                <option {{ ($team->country == 'AL' ) ? 'selected' : '' }} value="AL">Albania</option>
                                                <option {{ ($team->country == 'DZ' ) ? 'selected' : '' }} value="DZ">Algeria</option>
                                                <option {{ ($team->country == 'AS' ) ? 'selected' : '' }} value="AS">American Samoa</option>
                                                <option {{ ($team->country == 'AD' ) ? 'selected' : '' }} value="AD">Andorra</option>
                                                <option {{ ($team->country == 'AO' ) ? 'selected' : '' }} value="AO">Angola</option>
                                                <option {{ ($team->country == 'AI' ) ? 'selected' : '' }} value="AI">Anguilla</option>
                                                <option {{ ($team->country == 'AQ' ) ? 'selected' : '' }} value="AQ">Antarctica</option>
                                                <option {{ ($team->country == 'AG' ) ? 'selected' : '' }} value="AG">Antigua and Barbuda</option>
                                                <option {{ ($team->country == 'AR' ) ? 'selected' : '' }} value="AR">Argentina</option>
                                                <option {{ ($team->country == 'AM' ) ? 'selected' : '' }} value="AM">Armenia</option>
                                                <option {{ ($team->country == 'AW' ) ? 'selected' : '' }} value="AW">Aruba</option>
                                                <option {{ ($team->country == 'AU' ) ? 'selected' : '' }} value="AU">Australia</option>
                                                <option {{ ($team->country == 'AT' ) ? 'selected' : '' }} value="AT">Austria</option>
                                                <option {{ ($team->country == 'AZ' ) ? 'selected' : '' }} value="AZ">Azerbaijan</option>
                                                <option {{ ($team->country == 'BS' ) ? 'selected' : '' }} value="BS">Bahamas</option>
                                                <option {{ ($team->country == 'BH' ) ? 'selected' : '' }} value="BH">Bahrain</option>
                                                <option {{ ($team->country == 'BD' ) ? 'selected' : '' }} value="BD">Bangladesh</option>
                                                <option {{ ($team->country == 'BB' ) ? 'selected' : '' }} value="BB">Barbados</option>
                                                <option {{ ($team->country == 'BY' ) ? 'selected' : '' }} value="BY">Belarus</option>
                                                <option {{ ($team->country == 'BE' ) ? 'selected' : '' }} value="BE">Belgium</option>
                                                <option {{ ($team->country == 'BZ' ) ? 'selected' : '' }} value="BZ">Belize</option>
                                                <option {{ ($team->country == 'BJ' ) ? 'selected' : '' }} value="BJ">Benin</option>
                                                <option {{ ($team->country == 'BM' ) ? 'selected' : '' }} value="BM">Bermuda</option>
                                                <option {{ ($team->country == 'BT' ) ? 'selected' : '' }} value="BT">Bhutan</option>
                                                <option {{ ($team->country == 'BO' ) ? 'selected' : '' }} value="BO">Bolivia, Plurinational State of</option>
                                                <option {{ ($team->country == 'BQ' ) ? 'selected' : '' }} value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                                <option {{ ($team->country == 'BA' ) ? 'selected' : '' }} value="BA">Bosnia and Herzegovina</option>
                                                <option {{ ($team->country == 'BW' ) ? 'selected' : '' }} value="BW">Botswana</option>
                                                <option {{ ($team->country == 'BV' ) ? 'selected' : '' }} value="BV">Bouvet Island</option>
                                                <option {{ ($team->country == 'BR' ) ? 'selected' : '' }} value="BR">Brazil</option>
                                                <option {{ ($team->country == 'IO' ) ? 'selected' : '' }} value="IO">British Indian Ocean Territory</option>
                                                <option {{ ($team->country == 'BN' ) ? 'selected' : '' }} value="BN">Brunei Darussalam</option>
                                                <option {{ ($team->country == 'BG' ) ? 'selected' : '' }} value="BG">Bulgaria</option>
                                                <option {{ ($team->country == 'BF' ) ? 'selected' : '' }} value="BF">Burkina Faso</option>
                                                <option {{ ($team->country == 'BI' ) ? 'selected' : '' }} value="BI">Burundi</option>
                                                <option {{ ($team->country == 'KH' ) ? 'selected' : '' }} value="KH">Cambodia</option>
                                                <option {{ ($team->country == 'CM' ) ? 'selected' : '' }} value="CM">Cameroon</option>
                                                <option {{ ($team->country == 'CA' ) ? 'selected' : '' }} value="CA">Canada</option>
                                                <option {{ ($team->country == 'CV' ) ? 'selected' : '' }} value="CV">Cape Verde</option>
                                                <option {{ ($team->country == 'KY' ) ? 'selected' : '' }} value="KY">Cayman Islands</option>
                                                <option {{ ($team->country == 'CF' ) ? 'selected' : '' }} value="CF">Central African Republic</option>
                                                <option {{ ($team->country == 'TD' ) ? 'selected' : '' }} value="TD">Chad</option>
                                                <option {{ ($team->country == 'CL' ) ? 'selected' : '' }} value="CL">Chile</option>
                                                <option {{ ($team->country == 'CN' ) ? 'selected' : '' }} value="CN">China</option>
                                                <option {{ ($team->country == 'CX' ) ? 'selected' : '' }} value="CX">Christmas Island</option>
                                                <option {{ ($team->country == 'CC' ) ? 'selected' : '' }} value="CC">Cocos (Keeling) Islands</option>
                                                <option {{ ($team->country == 'CO' ) ? 'selected' : '' }} value="CO">Colombia</option>
                                                <option {{ ($team->country == 'KM' ) ? 'selected' : '' }} value="KM">Comoros</option>
                                                <option {{ ($team->country == 'CG' ) ? 'selected' : '' }} value="CG">Congo</option>
                                                <option {{ ($team->country == 'CD' ) ? 'selected' : '' }} value="CD">Congo, the Democratic Republic of the</option>
                                                <option {{ ($team->country == 'CK' ) ? 'selected' : '' }} value="CK">Cook Islands</option>
                                                <option {{ ($team->country == 'CR' ) ? 'selected' : '' }} value="CR">Costa Rica</option>
                                                <option {{ ($team->country == 'CI' ) ? 'selected' : '' }} value="CI">Côte d'Ivoire</option>
                                                <option {{ ($team->country == 'HR' ) ? 'selected' : '' }} value="HR">Croatia</option>
                                                <option {{ ($team->country == 'CU' ) ? 'selected' : '' }} value="CU">Cuba</option>
                                                <option {{ ($team->country == 'CW' ) ? 'selected' : '' }} value="CW">Curaçao</option>
                                                <option {{ ($team->country == 'CY' ) ? 'selected' : '' }} value="CY">Cyprus</option>
                                                <option {{ ($team->country == 'CZ' ) ? 'selected' : '' }} value="CZ">Czech Republic</option>
                                                <option {{ ($team->country == 'DK' ) ? 'selected' : '' }} value="DK">Denmark</option>
                                                <option {{ ($team->country == 'DJ' ) ? 'selected' : '' }} value="DJ">Djibouti</option>
                                                <option {{ ($team->country == 'DM' ) ? 'selected' : '' }} value="DM">Dominica</option>
                                                <option {{ ($team->country == 'DO' ) ? 'selected' : '' }} value="DO">Dominican Republic</option>
                                                <option {{ ($team->country == 'EC' ) ? 'selected' : '' }} value="EC">Ecuador</option>
                                                <option {{ ($team->country == 'EG' ) ? 'selected' : '' }} value="EG">Egypt</option>
                                                <option {{ ($team->country == 'SV' ) ? 'selected' : '' }} value="SV">El Salvador</option>
                                                <option {{ ($team->country == 'GQ' ) ? 'selected' : '' }} value="GQ">Equatorial Guinea</option>
                                                <option {{ ($team->country == 'ER' ) ? 'selected' : '' }} value="ER">Eritrea</option>
                                                <option {{ ($team->country == 'EE' ) ? 'selected' : '' }} value="EE">Estonia</option>
                                                <option {{ ($team->country == 'ET' ) ? 'selected' : '' }} value="ET">Ethiopia</option>
                                                <option {{ ($team->country == 'FK' ) ? 'selected' : '' }} value="FK">Falkland Islands (Malvinas)</option>
                                                <option {{ ($team->country == 'FO' ) ? 'selected' : '' }} value="FO">Faroe Islands</option>
                                                <option {{ ($team->country == 'FJ' ) ? 'selected' : '' }} value="FJ">Fiji</option>
                                                <option {{ ($team->country == 'FI' ) ? 'selected' : '' }} value="FI">Finland</option>
                                                <option {{ ($team->country == 'FR' ) ? 'selected' : '' }} value="FR">France</option>
                                                <option {{ ($team->country == 'GF' ) ? 'selected' : '' }} value="GF">French Guiana</option>
                                                <option {{ ($team->country == 'PF' ) ? 'selected' : '' }} value="PF">French Polynesia</option>
                                                <option {{ ($team->country == 'TF' ) ? 'selected' : '' }} value="TF">French Southern Territories</option>
                                                <option {{ ($team->country == 'GA' ) ? 'selected' : '' }} value="GA">Gabon</option>
                                                <option {{ ($team->country == 'GM' ) ? 'selected' : '' }} value="GM">Gambia</option>
                                                <option {{ ($team->country == 'GE' ) ? 'selected' : '' }} value="GE">Georgia</option>
                                                <option {{ ($team->country == 'DE' ) ? 'selected' : '' }} value="DE">Germany</option>
                                                <option {{ ($team->country == 'GH' ) ? 'selected' : '' }} value="GH">Ghana</option>
                                                <option {{ ($team->country == 'GI' ) ? 'selected' : '' }} value="GI">Gibraltar</option>
                                                <option {{ ($team->country == 'GR' ) ? 'selected' : '' }} value="GR">Greece</option>
                                                <option {{ ($team->country == 'GL' ) ? 'selected' : '' }} value="GL">Greenland</option>
                                                <option {{ ($team->country == 'GD' ) ? 'selected' : '' }} value="GD">Grenada</option>
                                                <option {{ ($team->country == 'GP' ) ? 'selected' : '' }} value="GP">Guadeloupe</option>
                                                <option {{ ($team->country == 'GU' ) ? 'selected' : '' }} value="GU">Guam</option>
                                                <option {{ ($team->country == 'GT' ) ? 'selected' : '' }} value="GT">Guatemala</option>
                                                <option {{ ($team->country == 'GG' ) ? 'selected' : '' }} value="GG">Guernsey</option>
                                                <option {{ ($team->country == 'GN' ) ? 'selected' : '' }} value="GN">Guinea</option>
                                                <option {{ ($team->country == 'GW' ) ? 'selected' : '' }} value="GW">Guinea-Bissau</option>
                                                <option {{ ($team->country == 'GY' ) ? 'selected' : '' }} value="GY">Guyana</option>
                                                <option {{ ($team->country == 'HT' ) ? 'selected' : '' }} value="HT">Haiti</option>
                                                <option {{ ($team->country == 'HM' ) ? 'selected' : '' }} value="HM">Heard Island and McDonald Islands</option>
                                                <option {{ ($team->country == 'VA' ) ? 'selected' : '' }} value="VA">Holy See (Vatican City State)</option>
                                                <option {{ ($team->country == 'HN' ) ? 'selected' : '' }} value="HN">Honduras</option>
                                                <option {{ ($team->country == 'HK' ) ? 'selected' : '' }} value="HK">Hong Kong</option>
                                                <option {{ ($team->country == 'HU' ) ? 'selected' : '' }} value="HU">Hungary</option>
                                                <option {{ ($team->country == 'IS' ) ? 'selected' : '' }} value="IS">Iceland</option>
                                                <option {{ ($team->country == 'IN' ) ? 'selected' : '' }} value="IN">India</option>
                                                <option {{ ($team->country == 'ID' ) ? 'selected' : '' }} value="ID">Indonesia</option>
                                                <option {{ ($team->country == 'IR' ) ? 'selected' : '' }} value="IR">Iran, Islamic Republic of</option>
                                                <option {{ ($team->country == 'IQ' ) ? 'selected' : '' }} value="IQ">Iraq</option>
                                                <option {{ ($team->country == 'IE' ) ? 'selected' : '' }} value="IE">Ireland</option>
                                                <option {{ ($team->country == 'IM' ) ? 'selected' : '' }} value="IM">Isle of Man</option>
                                                <option {{ ($team->country == 'IL' ) ? 'selected' : '' }} value="IL">Israel</option>
                                                <option {{ ($team->country == 'IT' ) ? 'selected' : '' }} value="IT">Italy</option>
                                                <option {{ ($team->country == 'JM' ) ? 'selected' : '' }} value="JM">Jamaica</option>
                                                <option {{ ($team->country == 'JP' ) ? 'selected' : '' }} value="JP">Japan</option>
                                                <option {{ ($team->country == 'JE' ) ? 'selected' : '' }} value="JE">Jersey</option>
                                                <option {{ ($team->country == 'JO' ) ? 'selected' : '' }} value="JO">Jordan</option>
                                                <option {{ ($team->country == 'KZ' ) ? 'selected' : '' }} value="KZ">Kazakhstan</option>
                                                <option {{ ($team->country == 'KE' ) ? 'selected' : '' }} value="KE">Kenya</option>
                                                <option {{ ($team->country == 'KI' ) ? 'selected' : '' }} value="KI">Kiribati</option>
                                                <option {{ ($team->country == 'KP' ) ? 'selected' : '' }} value="KP">Korea, Democratic People's Republic of</option>
                                                <option {{ ($team->country == 'KR' ) ? 'selected' : '' }} value="KR">Korea, Republic of</option>
                                                <option {{ ($team->country == 'KW' ) ? 'selected' : '' }} value="KW">Kuwait</option>
                                                <option {{ ($team->country == 'KG' ) ? 'selected' : '' }} value="KG">Kyrgyzstan</option>
                                                <option {{ ($team->country == 'LA' ) ? 'selected' : '' }} value="LA">Lao People's Democratic Republic</option>
                                                <option {{ ($team->country == 'LV' ) ? 'selected' : '' }} value="LV">Latvia</option>
                                                <option {{ ($team->country == 'LB' ) ? 'selected' : '' }} value="LB">Lebanon</option>
                                                <option {{ ($team->country == 'LS' ) ? 'selected' : '' }} value="LS">Lesotho</option>
                                                <option {{ ($team->country == 'LR' ) ? 'selected' : '' }} value="LR">Liberia</option>
                                                <option {{ ($team->country == 'LY' ) ? 'selected' : '' }} value="LY">Libya</option>
                                                <option {{ ($team->country == 'LI' ) ? 'selected' : '' }} value="LI">Liechtenstein</option>
                                                <option {{ ($team->country == 'LT' ) ? 'selected' : '' }} value="LT">Lithuania</option>
                                                <option {{ ($team->country == 'LU' ) ? 'selected' : '' }} value="LU">Luxembourg</option>
                                                <option {{ ($team->country == 'MO' ) ? 'selected' : '' }} value="MO">Macao</option>
                                                <option {{ ($team->country == 'MK' ) ? 'selected' : '' }} value="MK">Macedonia, the former Yugoslav Republic of</option>
                                                <option {{ ($team->country == 'MG' ) ? 'selected' : '' }} value="MG">Madagascar</option>
                                                <option {{ ($team->country == 'MW' ) ? 'selected' : '' }} value="MW">Malawi</option>
                                                <option {{ ($team->country == 'MY' ) ? 'selected' : '' }} value="MY">Malaysia</option>
                                                <option {{ ($team->country == 'MV' ) ? 'selected' : '' }} value="MV">Maldives</option>
                                                <option {{ ($team->country == 'ML' ) ? 'selected' : '' }} value="ML">Mali</option>
                                                <option {{ ($team->country == 'MT' ) ? 'selected' : '' }} value="MT">Malta</option>
                                                <option {{ ($team->country == 'MH' ) ? 'selected' : '' }} value="MH">Marshall Islands</option>
                                                <option {{ ($team->country == 'MQ' ) ? 'selected' : '' }} value="MQ">Martinique</option>
                                                <option {{ ($team->country == 'MR' ) ? 'selected' : '' }} value="MR">Mauritania</option>
                                                <option {{ ($team->country == 'MU' ) ? 'selected' : '' }} value="MU">Mauritius</option>
                                                <option {{ ($team->country == 'YT' ) ? 'selected' : '' }} value="YT">Mayotte</option>
                                                <option {{ ($team->country == 'MX' ) ? 'selected' : '' }} value="MX">Mexico</option>
                                                <option {{ ($team->country == 'FM' ) ? 'selected' : '' }} value="FM">Micronesia, Federated States of</option>
                                                <option {{ ($team->country == 'MD' ) ? 'selected' : '' }} value="MD">Moldova, Republic of</option>
                                                <option {{ ($team->country == 'MC' ) ? 'selected' : '' }} value="MC">Monaco</option>
                                                <option {{ ($team->country == 'MN' ) ? 'selected' : '' }} value="MN">Mongolia</option>
                                                <option {{ ($team->country == 'ME' ) ? 'selected' : '' }} value="ME">Montenegro</option>
                                                <option {{ ($team->country == 'MS' ) ? 'selected' : '' }} value="MS">Montserrat</option>
                                                <option {{ ($team->country == 'MA' ) ? 'selected' : '' }} value="MA">Morocco</option>
                                                <option {{ ($team->country == 'MZ' ) ? 'selected' : '' }} value="MZ">Mozambique</option>
                                                <option {{ ($team->country == 'MM' ) ? 'selected' : '' }} value="MM">Myanmar</option>
                                                <option {{ ($team->country == 'NA' ) ? 'selected' : '' }} value="NA">Namibia</option>
                                                <option {{ ($team->country == 'NR' ) ? 'selected' : '' }} value="NR">Nauru</option>
                                                <option {{ ($team->country == 'NP' ) ? 'selected' : '' }} value="NP">Nepal</option>
                                                <option {{ ($team->country == 'NL' ) ? 'selected' : '' }} value="NL">Netherlands</option>
                                                <option {{ ($team->country == 'NC' ) ? 'selected' : '' }} value="NC">New Caledonia</option>
                                                <option {{ ($team->country == 'NZ' ) ? 'selected' : '' }} value="NZ">New Zealand</option>
                                                <option {{ ($team->country == 'NI' ) ? 'selected' : '' }} value="NI">Nicaragua</option>
                                                <option {{ ($team->country == 'NE' ) ? 'selected' : '' }} value="NE">Niger</option>
                                                <option {{ ($team->country == 'NG' ) ? 'selected' : '' }} value="NG">Nigeria</option>
                                                <option {{ ($team->country == 'NU' ) ? 'selected' : '' }} value="NU">Niue</option>
                                                <option {{ ($team->country == 'NF' ) ? 'selected' : '' }} value="NF">Norfolk Island</option>
                                                <option {{ ($team->country == 'MP' ) ? 'selected' : '' }} value="MP">Northern Mariana Islands</option>
                                                <option {{ ($team->country == 'NO' ) ? 'selected' : '' }} value="NO">Norway</option>
                                                <option {{ ($team->country == 'OM' ) ? 'selected' : '' }} value="OM">Oman</option>
                                                <option {{ ($team->country == 'PK' ) ? 'selected' : '' }} value="PK">Pakistan</option>
                                                <option {{ ($team->country == 'PW' ) ? 'selected' : '' }} value="PW">Palau</option>
                                                <option {{ ($team->country == 'PS' ) ? 'selected' : '' }} value="PS">Palestinian Territory, Occupied</option>
                                                <option {{ ($team->country == 'PA' ) ? 'selected' : '' }} value="PA">Panama</option>
                                                <option {{ ($team->country == 'PG' ) ? 'selected' : '' }} value="PG">Papua New Guinea</option>
                                                <option {{ ($team->country == 'PY' ) ? 'selected' : '' }} value="PY">Paraguay</option>
                                                <option {{ ($team->country == 'PE' ) ? 'selected' : '' }} value="PE">Peru</option>
                                                <option {{ ($team->country == 'PH' ) ? 'selected' : '' }} value="PH">Philippines</option>
                                                <option {{ ($team->country == 'PN' ) ? 'selected' : '' }} value="PN">Pitcairn</option>
                                                <option {{ ($team->country == 'PL' ) ? 'selected' : '' }} value="PL">Poland</option>
                                                <option {{ ($team->country == 'PT' ) ? 'selected' : '' }} value="PT">Portugal</option>
                                                <option {{ ($team->country == 'PR' ) ? 'selected' : '' }} value="PR">Puerto Rico</option>
                                                <option {{ ($team->country == 'QA' ) ? 'selected' : '' }} value="QA">Qatar</option>
                                                <option {{ ($team->country == 'RE' ) ? 'selected' : '' }} value="RE">Réunion</option>
                                                <option {{ ($team->country == 'RO' ) ? 'selected' : '' }} value="RO">Romania</option>
                                                <option {{ ($team->country == 'RU' ) ? 'selected' : '' }} value="RU">Russian Federation</option>
                                                <option {{ ($team->country == 'RW' ) ? 'selected' : '' }} value="RW">Rwanda</option>
                                                <option {{ ($team->country == 'BL' ) ? 'selected' : '' }} value="BL">Saint Barthélemy</option>
                                                <option {{ ($team->country == 'SH' ) ? 'selected' : '' }} value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                                <option {{ ($team->country == 'KN' ) ? 'selected' : '' }} value="KN">Saint Kitts and Nevis</option>
                                                <option {{ ($team->country == 'LC' ) ? 'selected' : '' }} value="LC">Saint Lucia</option>
                                                <option {{ ($team->country == 'MF' ) ? 'selected' : '' }} value="MF">Saint Martin (French part)</option>
                                                <option {{ ($team->country == 'PM' ) ? 'selected' : '' }} value="PM">Saint Pierre and Miquelon</option>
                                                <option {{ ($team->country == 'VC' ) ? 'selected' : '' }} value="VC">Saint Vincent and the Grenadines</option>
                                                <option {{ ($team->country == 'WS' ) ? 'selected' : '' }} value="WS">Samoa</option>
                                                <option {{ ($team->country == 'SM' ) ? 'selected' : '' }} value="SM">San Marino</option>
                                                <option {{ ($team->country == 'ST' ) ? 'selected' : '' }} value="ST">Sao Tome and Principe</option>
                                                <option {{ ($team->country == 'SA' ) ? 'selected' : '' }} value="SA">Saudi Arabia</option>
                                                <option {{ ($team->country == 'SN' ) ? 'selected' : '' }} value="SN">Senegal</option>
                                                <option {{ ($team->country == 'RS' ) ? 'selected' : '' }} value="RS">Serbia</option>
                                                <option {{ ($team->country == 'SC' ) ? 'selected' : '' }} value="SC">Seychelles</option>
                                                <option {{ ($team->country == 'SL' ) ? 'selected' : '' }} value="SL">Sierra Leone</option>
                                                <option {{ ($team->country == 'SG' ) ? 'selected' : '' }} value="SG">Singapore</option>
                                                <option {{ ($team->country == 'SX' ) ? 'selected' : '' }} value="SX">Sint Maarten (Dutch part)</option>
                                                <option {{ ($team->country == 'SK' ) ? 'selected' : '' }} value="SK">Slovakia</option>
                                                <option {{ ($team->country == 'SI' ) ? 'selected' : '' }} value="SI">Slovenia</option>
                                                <option {{ ($team->country == 'SB' ) ? 'selected' : '' }} value="SB">Solomon Islands</option>
                                                <option {{ ($team->country == 'SO' ) ? 'selected' : '' }} value="SO">Somalia</option>
                                                <option {{ ($team->country == 'ZA' ) ? 'selected' : '' }} value="ZA">South Africa</option>
                                                <option {{ ($team->country == 'GS' ) ? 'selected' : '' }} value="GS">South Georgia and the South Sandwich Islands</option>
                                                <option {{ ($team->country == 'SS' ) ? 'selected' : '' }} value="SS">South Sudan</option>
                                                <option {{ ($team->country == 'ES' ) ? 'selected' : '' }} value="ES">Spain</option>
                                                <option {{ ($team->country == 'LK' ) ? 'selected' : '' }} value="LK">Sri Lanka</option>
                                                <option {{ ($team->country == 'SD' ) ? 'selected' : '' }} value="SD">Sudan</option>
                                                <option {{ ($team->country == 'SR' ) ? 'selected' : '' }} value="SR">Suriname</option>
                                                <option {{ ($team->country == 'SJ' ) ? 'selected' : '' }} value="SJ">Svalbard and Jan Mayen</option>
                                                <option {{ ($team->country == 'SZ' ) ? 'selected' : '' }} value="SZ">Swaziland</option>
                                                <option {{ ($team->country == 'SE' ) ? 'selected' : '' }} value="SE">Sweden</option>
                                                <option {{ ($team->country == 'CH' ) ? 'selected' : '' }} value="CH">Switzerland</option>
                                                <option {{ ($team->country == 'SY' ) ? 'selected' : '' }} value="SY">Syrian Arab Republic</option>
                                                <option {{ ($team->country == 'TW' ) ? 'selected' : '' }} value="TW">Taiwan, Province of China</option>
                                                <option {{ ($team->country == 'TJ' ) ? 'selected' : '' }} value="TJ">Tajikistan</option>
                                                <option {{ ($team->country == 'TZ' ) ? 'selected' : '' }} value="TZ">Tanzania, United Republic of</option>
                                                <option {{ ($team->country == 'TH' ) ? 'selected' : '' }} value="TH">Thailand</option>
                                                <option {{ ($team->country == 'TL' ) ? 'selected' : '' }} value="TL">Timor-Leste</option>
                                                <option {{ ($team->country == 'TG' ) ? 'selected' : '' }} value="TG">Togo</option>
                                                <option {{ ($team->country == 'TK' ) ? 'selected' : '' }} value="TK">Tokelau</option>
                                                <option {{ ($team->country == 'TO' ) ? 'selected' : '' }} value="TO">Tonga</option>
                                                <option {{ ($team->country == 'TT' ) ? 'selected' : '' }} value="TT">Trinidad and Tobago</option>
                                                <option {{ ($team->country == 'TN' ) ? 'selected' : '' }} value="TN">Tunisia</option>
                                                <option {{ ($team->country == 'TR' ) ? 'selected' : '' }} value="TR">Turkey</option>
                                                <option {{ ($team->country == 'TM' ) ? 'selected' : '' }} value="TM">Turkmenistan</option>
                                                <option {{ ($team->country == 'TC' ) ? 'selected' : '' }} value="TC">Turks and Caicos Islands</option>
                                                <option {{ ($team->country == 'TV' ) ? 'selected' : '' }} value="TV">Tuvalu</option>
                                                <option {{ ($team->country == 'UG' ) ? 'selected' : '' }} value="UG">Uganda</option>
                                                <option {{ ($team->country == 'UA' ) ? 'selected' : '' }} value="UA">Ukraine</option>
                                                <option {{ ($team->country == 'AE' ) ? 'selected' : '' }} value="AE">United Arab Emirates</option>
                                                <option {{ ($team->country == 'GB' ) ? 'selected' : '' }} value="GB">United Kingdom</option>
                                                <option {{ ($team->country == 'US' ) ? 'selected' : '' }} value="US" selected>United States</option>
                                                <option {{ ($team->country == 'UM' ) ? 'selected' : '' }} value="UM">United States Minor Outlying Islands</option>
                                                <option {{ ($team->country == 'UY' ) ? 'selected' : '' }} value="UY">Uruguay</option>
                                                <option {{ ($team->country == 'UZ' ) ? 'selected' : '' }} value="UZ">Uzbekistan</option>
                                                <option {{ ($team->country == 'VU' ) ? 'selected' : '' }} value="VU">Vanuatu</option>
                                                <option {{ ($team->country == 'VE' ) ? 'selected' : '' }} value="VE">Venezuela, Bolivarian Republic of</option>
                                                <option {{ ($team->country == 'VN' ) ? 'selected' : '' }} value="VN">Viet Nam</option>
                                                <option {{ ($team->country == 'VG' ) ? 'selected' : '' }} value="VG">Virgin Islands, British</option>
                                                <option {{ ($team->country == 'VI' ) ? 'selected' : '' }} value="VI">Virgin Islands, U.S.</option>
                                                <option {{ ($team->country == 'WF' ) ? 'selected' : '' }} value="WF">Wallis and Futuna</option>
                                                <option {{ ($team->country == 'EH' ) ? 'selected' : '' }} value="EH">Western Sahara</option>
                                                <option {{ ($team->country == 'YE' ) ? 'selected' : '' }} value="YE">Yemen</option>
                                                <option {{ ($team->country == 'ZM' ) ? 'selected' : '' }} value="ZM">Zambia</option>
                                                <option {{ ($team->country == 'ZW' ) ? 'selected' : '' }} value="ZW">Zimbabwe</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <label for="name">
                                            <i class="fa fa-globe"></i> City
                                        </label>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            {!! Form::text('city', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-default btn-app"><i class="fa fa-save"></i>
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        {!! Form::close() !!}

                        <hr>

                    </div><!-- /.box -->


                </div><!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">
                    <!-- Horizontal Form -->
                </div>
            </div>   <!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection()
@section('javascript')
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js') !!}
    <script>
        $('.icon').iconpicker();

    </script>
@endsection
