@extends('layouts.app', ['title' => 'Manage Teams', 'noHeader' => true])

@section('styles')
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
    <link href="{{ asset('assets/vendors/sweetalert/css/sweetalert2.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="banner-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12" align="center">
                <ul class="noheader-menu">
                    @if(Auth::user()->type == 'admin')
                        <li class="dark-text"><a href="{{ route('admin.dashboard') }}" class="text-white"><i class="fa fa-lock"></i> Admin Panel</a></li>
                    @endif
                    <li class="dark-text"><a href="{{ route('user.profile.index', Auth::user()->username) }}" class="text-white"><i class="fa fa-user"></i> Profile</a></li>
                    <li class="dark-text"><a class="text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="glyphicon glyphicon-log-out"></i> Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                    </li>

                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <img src="{{ asset('img/logo.png') }}" class="img-responsive">
            </div>
            <div class="col-sm-6" style="margin-top: 40px">
                <h2 class="text-white index-header" align="center">
                    {{ trans('team.index_header') }}
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" align="center">
                <button class="btn btn-white" data-toggle="modal" data-target="#createTeam"><i class="fa fa-edit"></i> {{ trans('team.create') }}</button>
            </div>
        </div>
    </div>
</div>
<main class="dotted-gray-bg">
    <div id="app" class="container">
        <div class="py-2" align="center">
            <h2><strong>{{ trans('team.your_teams') }}</strong></h2>
            <hr class="grad divider-mini m-0 opacity-7">
        </div>
        @if(count($teams))
            @foreach($teams as $team)
                <div class="col-md-4">
                    <div class="team-card">
                        <div class="team-header">
                            <h5 class="m-0">{{ ($team->isOwner(Auth::id())) ? trans('team.owner') : trans('team.member') }}</h5>
                            @if($team->isOwner(Auth::id()))
                                <span class="team-delete fas fa-times" @click="deleteTeam({{$team->id}})"></span>
                            @endif
                            @if(Auth::user()->type === 'admin' || $team->isOwner(Auth::id()))
                                <a href="#!" data-toggle="modal" data-target="#editTeam" @click="edit({{ $team->id }})"><i class="fa fa-edit"></i> {{ trans('team.edit') }}</a>
                            @endif
                            <hr class="light-divider">
                        </div>
                        <div class="team-body">
                            <div class="team-img" align="center">
                                <a href="{{ route('teams.show', $team->id) }}">
                                    @if($team->avatar)
                                        @if(\App::environment('production'))
                                            <img src="{{ Storage::disk('s3')->temporaryUrl($team->avatar, \Carbon\Carbon::now()->addMinutes(5)) }}" height="120px">
                                        @else
                                            <img src="{{ Storage::url($team->avatar) }}" height="120px">
                                        @endif
                                    @else
                                        @if(\App::environment('production'))
                                            <img src="{{ Storage::disk('s3')->temporaryUrl($team->sport->logo, \Carbon\Carbon::now()->addMinutes(5)) }}" alt="{{ $team->sport->name }} Team" class="img-responsive">
                                        @else
                                            <img src="{{ Storage::url($team->sport->logo) }}" alt="{{ $team->sport->name }} Team" class="img-responsive">
                                        @endif
                                    @endif
                                </a>
                            </div>
                            <br>
                            <div class="grad-inverse"></div>
                            <br>
                            <table style="width:100%">
                                <tr>
                                    {{-- @if(count($team->avatar) > 0)
                                        <td class="team-sport">
                                            @if(\App::environment('production'))
                                                <img height="80px" src="{{ Storage::disk('s3')->temporaryUrl($team->sport->logo, \Carbon\Carbon::now()->addMinutes(5)) }}" alt="{{ $team->sport->name }}">
                                            @else
                                                <img height="80px" src="{{ Storage::url($team->sport->logo) }}" alt="{{ $team->sport->name }}">
                                            @endif
                                        </td>
                                    @endif --}}
                                    <th class="team-name">
                                        <a href="{{ route('teams.show', $team->id) }}">{{ $team->name }}</a>
                                    </th>
                                </tr>
                            </table>
                            <table style="width:100%">
                                <tbody>
                                    <tr>
                                        <th><i class="fa fa-user"></i> {{ trans('team.team_owner') }}:</th>
                                        <td align="center">
                                            <a href="{{ route('user.profile.index', $team->owner->username)}}">
                                                {{ $team->owner->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <th><i class="fa fa-calendar"></i> {{ trans('team.next_event') }}:</th>
                                        <td align="center">No upcoming event.</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa fa-user"></i> {{ trans('breadcrumb.assignments') }}:</th>
                                        <td align="center">{{ $team->user->name }}</td>
                                    </tr> --}}
                                    @if($team->founded_at)
                                        <tr>
                                            <th><i class="fa fa-gift"></i> {{ trans('team.founded_at') }}:</th>
                                            <td align="center">{{ date('d - M - Y', strtotime($team->founded_at)) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <h3>{{ trans('team.no_teams') }}</h3>
        @endif

        <div class="modal" id="createTeam" tabindex="-1" role="dialog" aria-labelledby="mostModalLabel" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" @click="closeModal()"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">{{ trans('team.create') }}</h4>
                    </div>
                    <form method="POST" enctype="multipart/form-data" @submit.prevent="onSubmit">
                        <div class="modal-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#details" aria-controls="details" role="tab" data-toggle="tab">
                                        {{trans('team.team_details')}}
                                    </a>
                                </li>
                                {{-- <li role="presentation">
                                    <a href="#coaches" aria-controls="coaches" role="tab" data-toggle="tab">
                                        {{ trans('team.coaches') }}
                                    </a>
                                </li> --}}
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="details">
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="avatar">{{ trans('team.picture') }}
                                                <input type="file" name="avatar" accept="image/*" @change="imageChanged($event)">
                                            </label>
                                            <p class="text-danger" v-if="errors.avatar">@{{ errors.avatar[0] }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <img id="output" class="img-responsive">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('name', trans('team.name')) !!}
                                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Your team\'s name', 'v-model' => 'form.name']) !!}
                                            <small class="text-muted">{{ trans('team.can_be_changed') }}</small>
                                            <p class="text-danger" v-if="errors.name">@{{ errors.name[0] }}</p>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('founded_at', trans('team.created_date')) !!}
                                            {!! Form::date('founded_at', null, ['class' => 'form-control', 'v-model' => 'form.founded_at']) !!}
                                            <p class="text-danger" v-if="errors.founded_at">@{{ errors.founded_at[0] }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('country', trans('team.country')) !!}
                                            <select class="form-control" v-model="form.country" name="country">
                                                <option value="AF">Afghanistan</option>
                                                <option value="AX">Åland Islands</option>
                                                <option value="AL">Albania</option>
                                                <option value="DZ">Algeria</option>
                                                <option value="AS"> Samoa</option>
                                                <option value="AD">Andorra</option>
                                                <option value="AO">Angola</option>
                                                <option value="AI">Anguilla</option>
                                                <option value="AQ">Antarctica</option>
                                                <option value="AG">Antigua and Barbuda</option>
                                                <option value="AR">Argentina</option>
                                                <option value="AM">Armenia</option>
                                                <option value="AW">Aruba</option>
                                                <option value="AU">Australia</option>
                                                <option value="AT">Austria</option>
                                                <option value="AZ">Azerbaijan</option>
                                                <option value="BS">Bahamas</option>
                                                <option value="BH">Bahrain</option>
                                                <option value="BD">Bangladesh</option>
                                                <option value="BB">Barbados</option>
                                                <option value="BY">Belarus</option>
                                                <option value="BE">Belgium</option>
                                                <option value="BZ">Belize</option>
                                                <option value="BJ">Benin</option>
                                                <option value="BM">Bermuda</option>
                                                <option value="BT">Bhutan</option>
                                                <option value="BO">Bolivia, Plurinational State of</option>
                                                <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                                <option value="BA">Bosnia and Herzegovina</option>
                                                <option value="BW">Botswana</option>
                                                <option value="BV">Bouvet Island</option>
                                                <option value="BR">Brazil</option>
                                                <option value="IO">British Indian Ocean Territory</option>
                                                <option value="BN">Brunei Darussalam</option>
                                                <option value="BG">Bulgaria</option>
                                                <option value="BF">Burkina Faso</option>
                                                <option value="BI">Burundi</option>
                                                <option value="KH">Cambodia</option>
                                                <option value="CM">Cameroon</option>
                                                <option value="CA">Canada</option>
                                                <option value="CV">Cape Verde</option>
                                                <option value="KY">Cayman Islands</option>
                                                <option value="CF">Central African Republic</option>
                                                <option value="TD">Chad</option>
                                                <option value="CL">Chile</option>
                                                <option value="CN">China</option>
                                                <option value="CX">Christmas Island</option>
                                                <option value="CC">Cocos (Keeling) Islands</option>
                                                <option value="CO">Colombia</option>
                                                <option value="KM">Comoros</option>
                                                <option value="CG">Congo</option>
                                                <option value="CD">Congo, the Democratic Republic of the</option>
                                                <option value="CK">Cook Islands</option>
                                                <option value="CR">Costa Rica</option>
                                                <option value="CI">Côte d'Ivoire</option>
                                                <option value="HR">Croatia</option>
                                                <option value="CU">Cuba</option>
                                                <option value="CW">Curaçao</option>
                                                <option value="CY">Cyprus</option>
                                                <option value="CZ">Czech Republic</option>
                                                <option value="DK">Denmark</option>
                                                <option value="DJ">Djibouti</option>
                                                <option value="DM">Dominica</option>
                                                <option value="DO">Dominican Republic</option>
                                                <option value="EC">Ecuador</option>
                                                <option value="EG">Egypt</option>
                                                <option value="SV">El Salvador</option>
                                                <option value="GQ">Equatorial Guinea</option>
                                                <option value="ER">Eritrea</option>
                                                <option value="EE">Estonia</option>
                                                <option value="ET">Ethiopia</option>
                                                <option value="FK">Falkland Islands (Malvinas)</option>
                                                <option value="FO">Faroe Islands</option>
                                                <option value="FJ">Fiji</option>
                                                <option value="FI">Finland</option>
                                                <option value="FR">France</option>
                                                <option value="GF">French Guiana</option>
                                                <option value="PF">French Polynesia</option>
                                                <option value="TF">French Southern Territories</option>
                                                <option value="GA">Gabon</option>
                                                <option value="GM">Gambia</option>
                                                <option value="GE">Georgia</option>
                                                <option value="DE">Germany</option>
                                                <option value="GH">Ghana</option>
                                                <option value="GI">Gibraltar</option>
                                                <option value="GR">Greece</option>
                                                <option value="GL">Greenland</option>
                                                <option value="GD">Grenada</option>
                                                <option value="GP">Guadeloupe</option>
                                                <option value="GU">Guam</option>
                                                <option value="GT">Guatemala</option>
                                                <option value="GG">Guernsey</option>
                                                <option value="GN">Guinea</option>
                                                <option value="GW">Guinea-Bissau</option>
                                                <option value="GY">Guyana</option>
                                                <option value="HT">Haiti</option>
                                                <option value="HM">Heard Island and McDonald Islands</option>
                                                <option value="VA">Holy See (Vatican City State)</option>
                                                <option value="HN">Honduras</option>
                                                <option value="HK">Hong Kong</option>
                                                <option value="HU">Hungary</option>
                                                <option value="IS">Iceland</option>
                                                <option value="IN">India</option>
                                                <option value="ID">Indonesia</option>
                                                <option value="IR">Iran, Islamic Republic of</option>
                                                <option value="IQ">Iraq</option>
                                                <option value="IE">Ireland</option>
                                                <option value="IM">Isle of Man</option>
                                                <option value="IL">Israel</option>
                                                <option value="IT">Italy</option>
                                                <option value="JM">Jamaica</option>
                                                <option value="JP">Japan</option>
                                                <option value="JE">Jersey</option>
                                                <option value="JO">Jordan</option>
                                                <option value="KZ">Kazakhstan</option>
                                                <option value="KE">Kenya</option>
                                                <option value="KI">Kiribati</option>
                                                <option value="KP">Korea, Democratic People's Republic of</option>
                                                <option value="KR">Korea, Republic of</option>
                                                <option value="KW">Kuwait</option>
                                                <option value="KG">Kyrgyzstan</option>
                                                <option value="LA">Lao People's Democratic Republic</option>
                                                <option value="LV">Latvia</option>
                                                <option value="LB">Lebanon</option>
                                                <option value="LS">Lesotho</option>
                                                <option value="LR">Liberia</option>
                                                <option value="LY">Libya</option>
                                                <option value="LI">Liechtenstein</option>
                                                <option value="LT">Lithuania</option>
                                                <option value="LU">Luxembourg</option>
                                                <option value="MO">Macao</option>
                                                <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                                <option value="MG">Madagascar</option>
                                                <option value="MW">Malawi</option>
                                                <option value="MY">Malaysia</option>
                                                <option value="MV">Maldives</option>
                                                <option value="ML">Mali</option>
                                                <option value="MT">Malta</option>
                                                <option value="MH">Marshall Islands</option>
                                                <option value="MQ">Martinique</option>
                                                <option value="MR">Mauritania</option>
                                                <option value="MU">Mauritius</option>
                                                <option value="YT">Mayotte</option>
                                                <option value="MX">Mexico</option>
                                                <option value="FM">Micronesia, Federated States of</option>
                                                <option value="MD">Moldova, Republic of</option>
                                                <option value="MC">Monaco</option>
                                                <option value="MN">Mongolia</option>
                                                <option value="ME">Montenegro</option>
                                                <option value="MS">Montserrat</option>
                                                <option value="MA">Morocco</option>
                                                <option value="MZ">Mozambique</option>
                                                <option value="MM">Myanmar</option>
                                                <option value="NA">Namibia</option>
                                                <option value="NR">Nauru</option>
                                                <option value="NP">Nepal</option>
                                                <option value="NL">Netherlands</option>
                                                <option value="NC">New Caledonia</option>
                                                <option value="NZ">New Zealand</option>
                                                <option value="NI">Nicaragua</option>
                                                <option value="NE">Niger</option>
                                                <option value="NG">Nigeria</option>
                                                <option value="NU">Niue</option>
                                                <option value="NF">Norfolk Island</option>
                                                <option value="MP">Northern Mariana Islands</option>
                                                <option value="NO">Norway</option>
                                                <option value="OM">Oman</option>
                                                <option value="PK">Pakistan</option>
                                                <option value="PW">Palau</option>
                                                <option value="PS">Palestinian Territory, Occupied</option>
                                                <option value="PA">Panama</option>
                                                <option value="PG">Papua New Guinea</option>
                                                <option value="PY">Paraguay</option>
                                                <option value="PE">Peru</option>
                                                <option value="PH">Philippines</option>
                                                <option value="PN">Pitcairn</option>
                                                <option value="PL">Poland</option>
                                                <option value="PT">Portugal</option>
                                                <option value="PR">Puerto Rico</option>
                                                <option value="QA">Qatar</option>
                                                <option value="RE">Réunion</option>
                                                <option value="RO">Romania</option>
                                                <option value="RU">Russian Federation</option>
                                                <option value="RW">Rwanda</option>
                                                <option value="BL">Saint Barthélemy</option>
                                                <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                                <option value="KN">Saint Kitts and Nevis</option>
                                                <option value="LC">Saint Lucia</option>
                                                <option value="MF">Saint Martin (French part)</option>
                                                <option value="PM">Saint Pierre and Miquelon</option>
                                                <option value="VC">Saint Vincent and the Grenadines</option>
                                                <option value="WS">Samoa</option>
                                                <option value="SM">San Marino</option>
                                                <option value="ST">Sao Tome and Principe</option>
                                                <option value="SA">Saudi Arabia</option>
                                                <option value="SN">Senegal</option>
                                                <option value="RS">Serbia</option>
                                                <option value="SC">Seychelles</option>
                                                <option value="SL">Sierra Leone</option>
                                                <option value="SG">Singapore</option>
                                                <option value="SX">Sint Maarten (Dutch part)</option>
                                                <option value="SK">Slovakia</option>
                                                <option value="SI">Slovenia</option>
                                                <option value="SB">Solomon Islands</option>
                                                <option value="SO">Somalia</option>
                                                <option value="ZA">South Africa</option>
                                                <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                <option value="SS">South Sudan</option>
                                                <option value="ES">Spain</option>
                                                <option value="LK">Sri Lanka</option>
                                                <option value="SD">Sudan</option>
                                                <option value="SR">Suriname</option>
                                                <option value="SJ">Svalbard and Jan Mayen</option>
                                                <option value="SZ">Swaziland</option>
                                                <option value="SE">Sweden</option>
                                                <option value="CH">Switzerland</option>
                                                <option value="SY">Syrian Arab Republic</option>
                                                <option value="TW">Taiwan, Province of China</option>
                                                <option value="TJ">Tajikistan</option>
                                                <option value="TZ">Tanzania, United Republic of</option>
                                                <option value="TH">Thailand</option>
                                                <option value="TL">Timor-Leste</option>
                                                <option value="TG">Togo</option>
                                                <option value="TK">Tokelau</option>
                                                <option value="TO">Tonga</option>
                                                <option value="TT">Trinidad and Tobago</option>
                                                <option value="TN">Tunisia</option>
                                                <option value="TR">Turkey</option>
                                                <option value="TM">Turkmenistan</option>
                                                <option value="TC">Turks and Caicos Islands</option>
                                                <option value="TV">Tuvalu</option>
                                                <option value="UG">Uganda</option>
                                                <option value="UA">Ukraine</option>
                                                <option value="AE">United Arab Emirates</option>
                                                <option value="GB">United Kingdom</option>
                                                <option value="US" selected>United States</option>
                                                <option value="UM">United States Minor Outlying Islands</option>
                                                <option value="UY">Uruguay</option>
                                                <option value="UZ">Uzbekistan</option>
                                                <option value="VU">Vanuatu</option>
                                                <option value="VE">Venezuela, Bolivarian Republic of</option>
                                                <option value="VN">Viet Nam</option>
                                                <option value="VG">Virgin Islands, British</option>
                                                <option value="VI">Virgin Islands, U.S.</option>
                                                <option value="WF">Wallis and Futuna</option>
                                                <option value="EH">Western Sahara</option>
                                                <option value="YE">Yemen</option>
                                                <option value="ZM">Zambia</option>
                                                <option value="ZW">Zimbabwe</option>
                                            </select>
                                            <span class="text-danger" v-if="errors.country">@{{ errors.country[0] }}</span>
                                        </div>
                                        <div class="col-md-6 form-group" v-if="form.country != 'US'">
                                            {!! Form::label('city', trans('team.city')) !!}
                                            {!! Form::text('city', null, ['class' => 'form-control', 'v-model' => 'form.city']) !!}
                                            <span class="text-danger" v-if="errors.city">@{{ errors.city[0] }}</span>
                                        </div>
                                        <div class="col-md-6 form-group" v-else>
                                            {!! Form::label('zip', trans('team.zip')) !!}
                                            {!! Form::text('zip', null, ['class' => 'form-control', 'v-model' => 'form.zip']) !!}
                                            <span class="text-danger" v-if="errors.zip">@{{ errors.zip[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('sport_id', trans('team.sport_select')) !!}
                                            <select v-model="form.sport_id" name="sport_id" class="form-control">
                                                @foreach($sports as $sport)
                                                    <option value="{{$sport->id}}">{{ $sport->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">{{ trans('team.this_too') }}</small>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('timezone', trans('team.timezone')) !!}
                                            <select name="timezone" class="form-control" v-model="form.timezone">
                                                <option value="America/Adak">Adak</option>
                                                <option value="America/Anchorage">Anchorage</option>
                                                <option value="America/Anguilla">Anguilla</option>
                                                <option value="America/Antigua">Antigua</option>
                                                <option value="America/Araguaina">Araguaina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Aruba">Aruba</option>
                                                <option value="America/Asuncion">Asuncion</option>
                                                <option value="America/Atikokan">Atikokan</option>
                                                <option value="America/Atka">Atka</option>
                                                <option value="America/Bahia">Bahia</option>
                                                <option value="America/Barbados">Barbados</option>
                                                <option value="America/Belem">Belem</option>
                                                <option value="America/Belize">Belize</option>
                                                <option value="America/Blanc">Blanc</option>
                                                <option value="America/Boa_Vista">Boa_Vista</option>
                                                <option value="America/Bogota">Bogota</option>
                                                <option value="America/Boise">Boise</option>
                                                <option value="America/Buenos_Aires">Buenos_Aires</option>
                                                <option value="America/Cambridge_Bay">Cambridge_Bay</option>
                                                <option value="America/Campo_Grande">Campo_Grande</option>
                                                <option value="America/Cancun">Cancun</option>
                                                <option value="America/Caracas">Caracas</option>
                                                <option value="America/Catamarca">Catamarca</option>
                                                <option value="America/Cayenne">Cayenne</option>
                                                <option value="America/Cayman">Cayman</option>
                                                <option value="America/Chicago">Chicago</option>
                                                <option value="America/Chihuahua">Chihuahua</option>
                                                <option value="America/Coral_Harbour">Coral_Harbour</option>
                                                <option value="America/Cordoba">Cordoba</option>
                                                <option value="America/Costa_Rica">Costa_Rica</option>
                                                <option value="America/Cuiaba">Cuiaba</option>
                                                <option value="America/Curacao">Curacao</option>
                                                <option value="America/Danmarkshavn">Danmarkshavn</option>
                                                <option value="America/Dawson">Dawson</option>
                                                <option value="America/Dawson_Creek">Dawson_Creek</option>
                                                <option value="America/Denver">Denver</option>
                                                <option value="America/Detroit">Detroit</option>
                                                <option value="America/Dominica">Dominica</option>
                                                <option value="America/Edmonton">Edmonton</option>
                                                <option value="America/Eirunepe">Eirunepe</option>
                                                <option value="America/El_Salvador">El_Salvador</option>
                                                <option value="America/Ensenada">Ensenada</option>
                                                <option value="America/Fort_Wayne">Fort_Wayne</option>
                                                <option value="America/Fortaleza">Fortaleza</option>
                                                <option value="America/Glace_Bay">Glace_Bay</option>
                                                <option value="America/Godthab">Godthab</option>
                                                <option value="America/Goose_Bay">Goose_Bay</option>
                                                <option value="America/Grand_Turk">Grand_Turk</option>
                                                <option value="America/Grenada">Grenada</option>
                                                <option value="America/Guadeloupe">Guadeloupe</option>
                                                <option value="America/Guatemala">Guatemala</option>
                                                <option value="America/Guayaquil">Guayaquil</option>
                                                <option value="America/Guyana">Guyana</option>
                                                <option value="America/Halifax">Halifax</option>
                                                <option value="America/Havana">Havana</option>
                                                <option value="America/Hermosillo">Hermosillo</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indianapolis">Indianapolis</option>
                                                <option value="America/Inuvik">Inuvik</option>
                                                <option value="America/Iqaluit">Iqaluit</option>
                                                <option value="America/Jamaica">Jamaica</option>
                                                <option value="America/Jujuy">Jujuy</option>
                                                <option value="America/Juneau">Juneau</option>
                                                <option value="America/Kentucky">Kentucky</option>
                                                <option value="America/Kentucky">Kentucky</option>
                                                <option value="America/Knox_IN">Knox_IN</option>
                                                <option value="America/La_Paz">La_Paz</option>
                                                <option value="America/Lima">Lima</option>
                                                <option value="America/Los_Angeles">Los_Angeles</option>
                                                <option value="America/Louisville">Louisville</option>
                                                <option value="America/Maceio">Maceio</option>
                                                <option value="America/Managua">Managua</option>
                                                <option value="America/Manaus">Manaus</option>
                                                <option value="America/Marigot">Marigot</option>
                                                <option value="America/Martinique">Martinique</option>
                                                <option value="America/Matamoros">Matamoros</option>
                                                <option value="America/Mazatlan">Mazatlan</option>
                                                <option value="America/Mendoza">Mendoza</option>
                                                <option value="America/Menominee">Menominee</option>
                                                <option value="America/Merida">Merida</option>
                                                <option value="America/Mexico_City">Mexico_City</option>
                                                <option value="America/Miquelon">Miquelon</option>
                                                <option value="America/Moncton">Moncton</option>
                                                <option value="America/Monterrey">Monterrey</option>
                                                <option value="America/Montevideo">Montevideo</option>
                                                <option value="America/Montreal">Montreal</option>
                                                <option value="America/Montserrat">Montserrat</option>
                                                <option value="America/Nassau">Nassau</option>
                                                <option value="America/New_York">New_York</option>
                                                <option value="America/Nipigon">Nipigon</option>
                                                <option value="America/Nome">Nome</option>
                                                <option value="America/Noronha">Noronha</option>
                                                <option value="America/North_Dakota">North_Dakota</option>
                                                <option value="America/North_Dakota">North_Dakota</option>
                                                <option value="America/Ojinaga">Ojinaga</option>
                                                <option value="America/Panama">Panama</option>
                                                <option value="America/Pangnirtung">Pangnirtung</option>
                                                <option value="America/Paramaribo">Paramaribo</option>
                                                <option value="America/Phoenix">Phoenix</option>
                                                <option value="America/Port">Port</option>
                                                <option value="America/Port_of_Spain">Port_of_Spain</option>
                                                <option value="America/Porto_Acre">Porto_Acre</option>
                                                <option value="America/Porto_Velho">Porto_Velho</option>
                                                <option value="America/Puerto_Rico">Puerto_Rico</option>
                                                <option value="America/Rainy_River">Rainy_River</option>
                                                <option value="America/Rankin_Inlet">Rankin_Inlet</option>
                                                <option value="America/Recife">Recife</option>
                                                <option value="America/Regina">Regina</option>
                                                <option value="America/Resolute">Resolute</option>
                                                <option value="America/Rio_Branco">Rio_Branco</option>
                                                <option value="America/Rosario">Rosario</option>
                                                <option value="America/Santa_Isabel">Santa_Isabel</option>
                                                <option value="America/Santarem">Santarem</option>
                                                <option value="America/Santiago">Santiago</option>
                                                <option value="America/Santo_Domingo">Santo_Domingo</option>
                                                <option value="America/Sao_Paulo">Sao_Paulo</option>
                                                <option value="America/Scoresbysund">Scoresbysund</option>
                                                <option value="America/Shiprock">Shiprock</option>
                                                <option value="America/St_Barthelemy">St_Barthelemy</option>
                                                <option value="America/St_Johns">St_Johns</option>
                                                <option value="America/St_Kitts">St_Kitts</option>
                                                <option value="America/St_Lucia">St_Lucia</option>
                                                <option value="America/St_Thomas">St_Thomas</option>
                                                <option value="America/St_Vincent">St_Vincent</option>
                                                <option value="America/Swift_Current">Swift_Current</option>
                                                <option value="America/Tegucigalpa">Tegucigalpa</option>
                                                <option value="America/Thule">Thule</option>
                                                <option value="America/Thunder_Bay">Thunder_Bay</option>
                                                <option value="America/Tijuana">Tijuana</option>
                                                <option value="America/Toronto">Toronto</option>
                                                <option value="America/Tortola">Tortola</option>
                                                <option value="America/Vancouver">Vancouver</option>
                                                <option value="America/Virgin">Virgin</option>
                                                <option value="America/Whitehorse">Whitehorse</option>
                                                <option value="America/Winnipeg">Winnipeg</option>
                                                <option value="America/Yakutat">Yakutat</option>
                                                <option value="America/Yellowknife">Yellowknife</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <a @click="closeModal()" data-dismiss="modal" class="btn btn-danger">{{ trans('player.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ trans('team.create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" id="editTeam" role="dialog" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" class="close" @click="closeModal()"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">{{ trans('team.edit') }} @{{ editForm.name }}</h4>
                    </div>
                    <form method="PUT" enctype="multipart/form-data" @submit.prevent="onUpdate">
                        <div class="modal-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#editDetails" aria-controls="details" role="tab" data-toggle="tab">
                                        {{trans('team.team_details')}}
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="editDetails">
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="avatar">{{ trans('team.picture') }}
                                                <input type="file" name="avatar" accept="image/*" @change="editImageChanged($event)">
                                            </label>
                                            <p class="text-danger" v-if="editErrors.avatar">{{ trans('team.picture_required') }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <img src="" id="output2">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('name', trans('team.name')) !!}
                                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Your team\'s name', 'v-model' => 'editForm.name']) !!}
                                            <small class="text-muted">{{ trans('team.can_be_changed') }}</small>
                                            <p class="text-danger" v-if="editErrors.name">@{{ editErrors.name[0] }}</p>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('founded_at', trans('team.created_date')) !!}
                                            {!! Form::date('founded_at', null, ['class' => 'form-control', 'v-model' => 'editForm.founded_at']) !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('country', trans('team.country')) !!}
                                            <select class="form-control" v-model="editForm.country" name="country">
                                                <option value="AF">Afghanistan</option>
                                                <option value="AX">Åland Islands</option>
                                                <option value="AL">Albania</option>
                                                <option value="DZ">Algeria</option>
                                                <option value="AS"> Samoa</option>
                                                <option value="AD">Andorra</option>
                                                <option value="AO">Angola</option>
                                                <option value="AI">Anguilla</option>
                                                <option value="AQ">Antarctica</option>
                                                <option value="AG">Antigua and Barbuda</option>
                                                <option value="AR">Argentina</option>
                                                <option value="AM">Armenia</option>
                                                <option value="AW">Aruba</option>
                                                <option value="AU">Australia</option>
                                                <option value="AT">Austria</option>
                                                <option value="AZ">Azerbaijan</option>
                                                <option value="BS">Bahamas</option>
                                                <option value="BH">Bahrain</option>
                                                <option value="BD">Bangladesh</option>
                                                <option value="BB">Barbados</option>
                                                <option value="BY">Belarus</option>
                                                <option value="BE">Belgium</option>
                                                <option value="BZ">Belize</option>
                                                <option value="BJ">Benin</option>
                                                <option value="BM">Bermuda</option>
                                                <option value="BT">Bhutan</option>
                                                <option value="BO">Bolivia, Plurinational State of</option>
                                                <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                                <option value="BA">Bosnia and Herzegovina</option>
                                                <option value="BW">Botswana</option>
                                                <option value="BV">Bouvet Island</option>
                                                <option value="BR">Brazil</option>
                                                <option value="IO">British Indian Ocean Territory</option>
                                                <option value="BN">Brunei Darussalam</option>
                                                <option value="BG">Bulgaria</option>
                                                <option value="BF">Burkina Faso</option>
                                                <option value="BI">Burundi</option>
                                                <option value="KH">Cambodia</option>
                                                <option value="CM">Cameroon</option>
                                                <option value="CA">Canada</option>
                                                <option value="CV">Cape Verde</option>
                                                <option value="KY">Cayman Islands</option>
                                                <option value="CF">Central African Republic</option>
                                                <option value="TD">Chad</option>
                                                <option value="CL">Chile</option>
                                                <option value="CN">China</option>
                                                <option value="CX">Christmas Island</option>
                                                <option value="CC">Cocos (Keeling) Islands</option>
                                                <option value="CO">Colombia</option>
                                                <option value="KM">Comoros</option>
                                                <option value="CG">Congo</option>
                                                <option value="CD">Congo, the Democratic Republic of the</option>
                                                <option value="CK">Cook Islands</option>
                                                <option value="CR">Costa Rica</option>
                                                <option value="CI">Côte d'Ivoire</option>
                                                <option value="HR">Croatia</option>
                                                <option value="CU">Cuba</option>
                                                <option value="CW">Curaçao</option>
                                                <option value="CY">Cyprus</option>
                                                <option value="CZ">Czech Republic</option>
                                                <option value="DK">Denmark</option>
                                                <option value="DJ">Djibouti</option>
                                                <option value="DM">Dominica</option>
                                                <option value="DO">Dominican Republic</option>
                                                <option value="EC">Ecuador</option>
                                                <option value="EG">Egypt</option>
                                                <option value="SV">El Salvador</option>
                                                <option value="GQ">Equatorial Guinea</option>
                                                <option value="ER">Eritrea</option>
                                                <option value="EE">Estonia</option>
                                                <option value="ET">Ethiopia</option>
                                                <option value="FK">Falkland Islands (Malvinas)</option>
                                                <option value="FO">Faroe Islands</option>
                                                <option value="FJ">Fiji</option>
                                                <option value="FI">Finland</option>
                                                <option value="FR">France</option>
                                                <option value="GF">French Guiana</option>
                                                <option value="PF">French Polynesia</option>
                                                <option value="TF">French Southern Territories</option>
                                                <option value="GA">Gabon</option>
                                                <option value="GM">Gambia</option>
                                                <option value="GE">Georgia</option>
                                                <option value="DE">Germany</option>
                                                <option value="GH">Ghana</option>
                                                <option value="GI">Gibraltar</option>
                                                <option value="GR">Greece</option>
                                                <option value="GL">Greenland</option>
                                                <option value="GD">Grenada</option>
                                                <option value="GP">Guadeloupe</option>
                                                <option value="GU">Guam</option>
                                                <option value="GT">Guatemala</option>
                                                <option value="GG">Guernsey</option>
                                                <option value="GN">Guinea</option>
                                                <option value="GW">Guinea-Bissau</option>
                                                <option value="GY">Guyana</option>
                                                <option value="HT">Haiti</option>
                                                <option value="HM">Heard Island and McDonald Islands</option>
                                                <option value="VA">Holy See (Vatican City State)</option>
                                                <option value="HN">Honduras</option>
                                                <option value="HK">Hong Kong</option>
                                                <option value="HU">Hungary</option>
                                                <option value="IS">Iceland</option>
                                                <option value="IN">India</option>
                                                <option value="ID">Indonesia</option>
                                                <option value="IR">Iran, Islamic Republic of</option>
                                                <option value="IQ">Iraq</option>
                                                <option value="IE">Ireland</option>
                                                <option value="IM">Isle of Man</option>
                                                <option value="IL">Israel</option>
                                                <option value="IT">Italy</option>
                                                <option value="JM">Jamaica</option>
                                                <option value="JP">Japan</option>
                                                <option value="JE">Jersey</option>
                                                <option value="JO">Jordan</option>
                                                <option value="KZ">Kazakhstan</option>
                                                <option value="KE">Kenya</option>
                                                <option value="KI">Kiribati</option>
                                                <option value="KP">Korea, Democratic People's Republic of</option>
                                                <option value="KR">Korea, Republic of</option>
                                                <option value="KW">Kuwait</option>
                                                <option value="KG">Kyrgyzstan</option>
                                                <option value="LA">Lao People's Democratic Republic</option>
                                                <option value="LV">Latvia</option>
                                                <option value="LB">Lebanon</option>
                                                <option value="LS">Lesotho</option>
                                                <option value="LR">Liberia</option>
                                                <option value="LY">Libya</option>
                                                <option value="LI">Liechtenstein</option>
                                                <option value="LT">Lithuania</option>
                                                <option value="LU">Luxembourg</option>
                                                <option value="MO">Macao</option>
                                                <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                                <option value="MG">Madagascar</option>
                                                <option value="MW">Malawi</option>
                                                <option value="MY">Malaysia</option>
                                                <option value="MV">Maldives</option>
                                                <option value="ML">Mali</option>
                                                <option value="MT">Malta</option>
                                                <option value="MH">Marshall Islands</option>
                                                <option value="MQ">Martinique</option>
                                                <option value="MR">Mauritania</option>
                                                <option value="MU">Mauritius</option>
                                                <option value="YT">Mayotte</option>
                                                <option value="MX">Mexico</option>
                                                <option value="FM">Micronesia, Federated States of</option>
                                                <option value="MD">Moldova, Republic of</option>
                                                <option value="MC">Monaco</option>
                                                <option value="MN">Mongolia</option>
                                                <option value="ME">Montenegro</option>
                                                <option value="MS">Montserrat</option>
                                                <option value="MA">Morocco</option>
                                                <option value="MZ">Mozambique</option>
                                                <option value="MM">Myanmar</option>
                                                <option value="NA">Namibia</option>
                                                <option value="NR">Nauru</option>
                                                <option value="NP">Nepal</option>
                                                <option value="NL">Netherlands</option>
                                                <option value="NC">New Caledonia</option>
                                                <option value="NZ">New Zealand</option>
                                                <option value="NI">Nicaragua</option>
                                                <option value="NE">Niger</option>
                                                <option value="NG">Nigeria</option>
                                                <option value="NU">Niue</option>
                                                <option value="NF">Norfolk Island</option>
                                                <option value="MP">Northern Mariana Islands</option>
                                                <option value="NO">Norway</option>
                                                <option value="OM">Oman</option>
                                                <option value="PK">Pakistan</option>
                                                <option value="PW">Palau</option>
                                                <option value="PS">Palestinian Territory, Occupied</option>
                                                <option value="PA">Panama</option>
                                                <option value="PG">Papua New Guinea</option>
                                                <option value="PY">Paraguay</option>
                                                <option value="PE">Peru</option>
                                                <option value="PH">Philippines</option>
                                                <option value="PN">Pitcairn</option>
                                                <option value="PL">Poland</option>
                                                <option value="PT">Portugal</option>
                                                <option value="PR">Puerto Rico</option>
                                                <option value="QA">Qatar</option>
                                                <option value="RE">Réunion</option>
                                                <option value="RO">Romania</option>
                                                <option value="RU">Russian Federation</option>
                                                <option value="RW">Rwanda</option>
                                                <option value="BL">Saint Barthélemy</option>
                                                <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                                <option value="KN">Saint Kitts and Nevis</option>
                                                <option value="LC">Saint Lucia</option>
                                                <option value="MF">Saint Martin (French part)</option>
                                                <option value="PM">Saint Pierre and Miquelon</option>
                                                <option value="VC">Saint Vincent and the Grenadines</option>
                                                <option value="WS">Samoa</option>
                                                <option value="SM">San Marino</option>
                                                <option value="ST">Sao Tome and Principe</option>
                                                <option value="SA">Saudi Arabia</option>
                                                <option value="SN">Senegal</option>
                                                <option value="RS">Serbia</option>
                                                <option value="SC">Seychelles</option>
                                                <option value="SL">Sierra Leone</option>
                                                <option value="SG">Singapore</option>
                                                <option value="SX">Sint Maarten (Dutch part)</option>
                                                <option value="SK">Slovakia</option>
                                                <option value="SI">Slovenia</option>
                                                <option value="SB">Solomon Islands</option>
                                                <option value="SO">Somalia</option>
                                                <option value="ZA">South Africa</option>
                                                <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                <option value="SS">South Sudan</option>
                                                <option value="ES">Spain</option>
                                                <option value="LK">Sri Lanka</option>
                                                <option value="SD">Sudan</option>
                                                <option value="SR">Suriname</option>
                                                <option value="SJ">Svalbard and Jan Mayen</option>
                                                <option value="SZ">Swaziland</option>
                                                <option value="SE">Sweden</option>
                                                <option value="CH">Switzerland</option>
                                                <option value="SY">Syrian Arab Republic</option>
                                                <option value="TW">Taiwan, Province of China</option>
                                                <option value="TJ">Tajikistan</option>
                                                <option value="TZ">Tanzania, United Republic of</option>
                                                <option value="TH">Thailand</option>
                                                <option value="TL">Timor-Leste</option>
                                                <option value="TG">Togo</option>
                                                <option value="TK">Tokelau</option>
                                                <option value="TO">Tonga</option>
                                                <option value="TT">Trinidad and Tobago</option>
                                                <option value="TN">Tunisia</option>
                                                <option value="TR">Turkey</option>
                                                <option value="TM">Turkmenistan</option>
                                                <option value="TC">Turks and Caicos Islands</option>
                                                <option value="TV">Tuvalu</option>
                                                <option value="UG">Uganda</option>
                                                <option value="UA">Ukraine</option>
                                                <option value="AE">United Arab Emirates</option>
                                                <option value="GB">United Kingdom</option>
                                                <option value="US" selected>United States</option>
                                                <option value="UM">United States Minor Outlying Islands</option>
                                                <option value="UY">Uruguay</option>
                                                <option value="UZ">Uzbekistan</option>
                                                <option value="VU">Vanuatu</option>
                                                <option value="VE">Venezuela, Bolivarian Republic of</option>
                                                <option value="VN">Viet Nam</option>
                                                <option value="VG">Virgin Islands, British</option>
                                                <option value="VI">Virgin Islands, U.S.</option>
                                                <option value="WF">Wallis and Futuna</option>
                                                <option value="EH">Western Sahara</option>
                                                <option value="YE">Yemen</option>
                                                <option value="ZM">Zambia</option>
                                                <option value="ZW">Zimbabwe</option>
                                            </select>
                                            <span class="text-danger" v-if="editErrors.country">@{{ editErrors.country[0] }}</span>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('city', trans('team.city')) !!}
                                            {!! Form::text('city', null, ['class' => 'form-control', 'v-model' => 'editForm.city']) !!}
                                            <span class="text-danger" v-if="editErrors.city">@{{ errors.city[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('sport_id', trans('team.sport_select')) !!}
                                            <select v-model="editForm.sport_id" name="sport_id" class="form-control">
                                                @foreach($sports as $sport)
                                                    <option value="{{$sport->id}}">{{ $sport->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">{{ trans('team.this_too') }}</small>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            {!! Form::label('timezone', trans('team.timezone')) !!}
                                            <select name="timezone" class="form-control" v-model="editForm.timezone">
                                                <option value="America/Adak">Adak</option>
                                                <option value="America/Anchorage">Anchorage</option>
                                                <option value="America/Anguilla">Anguilla</option>
                                                <option value="America/Antigua">Antigua</option>
                                                <option value="America/Araguaina">Araguaina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Argentina">Argentina</option>
                                                <option value="America/Aruba">Aruba</option>
                                                <option value="America/Asuncion">Asuncion</option>
                                                <option value="America/Atikokan">Atikokan</option>
                                                <option value="America/Atka">Atka</option>
                                                <option value="America/Bahia">Bahia</option>
                                                <option value="America/Barbados">Barbados</option>
                                                <option value="America/Belem">Belem</option>
                                                <option value="America/Belize">Belize</option>
                                                <option value="America/Blanc">Blanc</option>
                                                <option value="America/Boa_Vista">Boa_Vista</option>
                                                <option value="America/Bogota">Bogota</option>
                                                <option value="America/Boise">Boise</option>
                                                <option value="America/Buenos_Aires">Buenos_Aires</option>
                                                <option value="America/Cambridge_Bay">Cambridge_Bay</option>
                                                <option value="America/Campo_Grande">Campo_Grande</option>
                                                <option value="America/Cancun">Cancun</option>
                                                <option value="America/Caracas">Caracas</option>
                                                <option value="America/Catamarca">Catamarca</option>
                                                <option value="America/Cayenne">Cayenne</option>
                                                <option value="America/Cayman">Cayman</option>
                                                <option value="America/Chicago">Chicago</option>
                                                <option value="America/Chihuahua">Chihuahua</option>
                                                <option value="America/Coral_Harbour">Coral_Harbour</option>
                                                <option value="America/Cordoba">Cordoba</option>
                                                <option value="America/Costa_Rica">Costa_Rica</option>
                                                <option value="America/Cuiaba">Cuiaba</option>
                                                <option value="America/Curacao">Curacao</option>
                                                <option value="America/Danmarkshavn">Danmarkshavn</option>
                                                <option value="America/Dawson">Dawson</option>
                                                <option value="America/Dawson_Creek">Dawson_Creek</option>
                                                <option value="America/Denver">Denver</option>
                                                <option value="America/Detroit">Detroit</option>
                                                <option value="America/Dominica">Dominica</option>
                                                <option value="America/Edmonton">Edmonton</option>
                                                <option value="America/Eirunepe">Eirunepe</option>
                                                <option value="America/El_Salvador">El_Salvador</option>
                                                <option value="America/Ensenada">Ensenada</option>
                                                <option value="America/Fort_Wayne">Fort_Wayne</option>
                                                <option value="America/Fortaleza">Fortaleza</option>
                                                <option value="America/Glace_Bay">Glace_Bay</option>
                                                <option value="America/Godthab">Godthab</option>
                                                <option value="America/Goose_Bay">Goose_Bay</option>
                                                <option value="America/Grand_Turk">Grand_Turk</option>
                                                <option value="America/Grenada">Grenada</option>
                                                <option value="America/Guadeloupe">Guadeloupe</option>
                                                <option value="America/Guatemala">Guatemala</option>
                                                <option value="America/Guayaquil">Guayaquil</option>
                                                <option value="America/Guyana">Guyana</option>
                                                <option value="America/Halifax">Halifax</option>
                                                <option value="America/Havana">Havana</option>
                                                <option value="America/Hermosillo">Hermosillo</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indiana">Indiana</option>
                                                <option value="America/Indianapolis">Indianapolis</option>
                                                <option value="America/Inuvik">Inuvik</option>
                                                <option value="America/Iqaluit">Iqaluit</option>
                                                <option value="America/Jamaica">Jamaica</option>
                                                <option value="America/Jujuy">Jujuy</option>
                                                <option value="America/Juneau">Juneau</option>
                                                <option value="America/Kentucky">Kentucky</option>
                                                <option value="America/Kentucky">Kentucky</option>
                                                <option value="America/Knox_IN">Knox_IN</option>
                                                <option value="America/La_Paz">La_Paz</option>
                                                <option value="America/Lima">Lima</option>
                                                <option value="America/Los_Angeles">Los_Angeles</option>
                                                <option value="America/Louisville">Louisville</option>
                                                <option value="America/Maceio">Maceio</option>
                                                <option value="America/Managua">Managua</option>
                                                <option value="America/Manaus">Manaus</option>
                                                <option value="America/Marigot">Marigot</option>
                                                <option value="America/Martinique">Martinique</option>
                                                <option value="America/Matamoros">Matamoros</option>
                                                <option value="America/Mazatlan">Mazatlan</option>
                                                <option value="America/Mendoza">Mendoza</option>
                                                <option value="America/Menominee">Menominee</option>
                                                <option value="America/Merida">Merida</option>
                                                <option value="America/Mexico_City">Mexico_City</option>
                                                <option value="America/Miquelon">Miquelon</option>
                                                <option value="America/Moncton">Moncton</option>
                                                <option value="America/Monterrey">Monterrey</option>
                                                <option value="America/Montevideo">Montevideo</option>
                                                <option value="America/Montreal">Montreal</option>
                                                <option value="America/Montserrat">Montserrat</option>
                                                <option value="America/Nassau">Nassau</option>
                                                <option value="America/New_York">New_York</option>
                                                <option value="America/Nipigon">Nipigon</option>
                                                <option value="America/Nome">Nome</option>
                                                <option value="America/Noronha">Noronha</option>
                                                <option value="America/North_Dakota">North_Dakota</option>
                                                <option value="America/North_Dakota">North_Dakota</option>
                                                <option value="America/Ojinaga">Ojinaga</option>
                                                <option value="America/Panama">Panama</option>
                                                <option value="America/Pangnirtung">Pangnirtung</option>
                                                <option value="America/Paramaribo">Paramaribo</option>
                                                <option value="America/Phoenix">Phoenix</option>
                                                <option value="America/Port">Port</option>
                                                <option value="America/Port_of_Spain">Port_of_Spain</option>
                                                <option value="America/Porto_Acre">Porto_Acre</option>
                                                <option value="America/Porto_Velho">Porto_Velho</option>
                                                <option value="America/Puerto_Rico">Puerto_Rico</option>
                                                <option value="America/Rainy_River">Rainy_River</option>
                                                <option value="America/Rankin_Inlet">Rankin_Inlet</option>
                                                <option value="America/Recife">Recife</option>
                                                <option value="America/Regina">Regina</option>
                                                <option value="America/Resolute">Resolute</option>
                                                <option value="America/Rio_Branco">Rio_Branco</option>
                                                <option value="America/Rosario">Rosario</option>
                                                <option value="America/Santa_Isabel">Santa_Isabel</option>
                                                <option value="America/Santarem">Santarem</option>
                                                <option value="America/Santiago">Santiago</option>
                                                <option value="America/Santo_Domingo">Santo_Domingo</option>
                                                <option value="America/Sao_Paulo">Sao_Paulo</option>
                                                <option value="America/Scoresbysund">Scoresbysund</option>
                                                <option value="America/Shiprock">Shiprock</option>
                                                <option value="America/St_Barthelemy">St_Barthelemy</option>
                                                <option value="America/St_Johns">St_Johns</option>
                                                <option value="America/St_Kitts">St_Kitts</option>
                                                <option value="America/St_Lucia">St_Lucia</option>
                                                <option value="America/St_Thomas">St_Thomas</option>
                                                <option value="America/St_Vincent">St_Vincent</option>
                                                <option value="America/Swift_Current">Swift_Current</option>
                                                <option value="America/Tegucigalpa">Tegucigalpa</option>
                                                <option value="America/Thule">Thule</option>
                                                <option value="America/Thunder_Bay">Thunder_Bay</option>
                                                <option value="America/Tijuana">Tijuana</option>
                                                <option value="America/Toronto">Toronto</option>
                                                <option value="America/Tortola">Tortola</option>
                                                <option value="America/Vancouver">Vancouver</option>
                                                <option value="America/Virgin">Virgin</option>
                                                <option value="America/Whitehorse">Whitehorse</option>
                                                <option value="America/Winnipeg">Winnipeg</option>
                                                <option value="America/Yakutat">Yakutat</option>
                                                <option value="America/Yellowknife">Yellowknife</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <a @click="closeModal()" data-dismiss="modal" class="btn btn-danger">{{ trans('player.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ trans('team.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- END Main container -->
@endsection

@section('scripts')
    <script src="{{ asset('assets/vendors/sweetalert/js/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyAATw7zkkc8lZjaBjtsv-7zUYJR1CRv0_w&libraries=places"></script>
    <script>
        const storage = {
            csrf_token: '{{ csrf_token() }}',
            auth_id: {{ Auth::id() }},
            today: '{{ date('Y-m-d', strtotime('today')) }}'
        }
    </script>
    <script src="{{ mix('js/index.js') }}" type="text/javascript"></script>
@endsection
