@extends('layouts.app', ['title' => trans('team.view_team') . $team->name])
@php
use Illuminate\Support\Facades\Storage;
@endphp

{{-- page level styles --}}
@section('styles')
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/bootstrap-magnify/bootstrap-magnify.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/user_profile.css') }}" rel="stylesheet" type="text/css"/>
    {{-- <link href="{{ asset('assets/vendors/owl_carousel/css/owl.carousel.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('assets/vendors/owl_carousel/css/owl.theme.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('assets/vendors/owl_carousel/css/owl.transitions.css') }}" rel="stylesheet" type="text/css"> --}}
    <link href="{{ asset('assets/vendors/sweetalert/css/sweetalert2.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/dashboard.css') }}">
    <style>
        .lightsOut {
            position: absolute;
            top: 0px;
            left: 0px;
            background-color: #000;
            width: 0px;
            height: 0px;
            z-index: 10000;
        }
        #block {
            margin-top: 0px;
        }
        section {
            padding: 50px 32px;
        }
</style>
@stop

{{-- Page content --}}
@section('content')
<div id="dashboard" style="background: rgba(253,253,253,1)">
    <div class="dashboard-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    {{-- <img src="{{ asset('img/logo.png') }}" class="img-responsive"> --}}
                </div>
                <div class="col-sm-6 d-flex" style="margin-top: 80px">
                    <h1 class="dashboard-title text-white mt-4 align-self-end flex p-0" align="center">
                        {{ $team->name }}
                    </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" align="center">
                    <hr class="divider-mini white-bg b-2">
                </div>
            </div>
        </div>
    </div>
    <div class="grad"></div>
    {{-- "Slider" --}}
    {{-- <div class="row">
        <div class="col-md-10 col-md-offset-1" style="height:250px; display:flex; justify-content: center; align-items:center; overflow:hidden">
            <div style="position:absolute; z-index: 2">
                <h1 class="team-title">{{ $team->name }}</h1>
            </div>
            @if(count($sliderImages) > 2)
                <div class="col-md-6 sliderImage" style="background-image: url('{{ $sliderImages[0]['url']  }}')">
                </div>
                <div class="col-md-2 sliderImage" style="background-image: url('{{ $sliderImages[1]['url']  }}')">
                </div>
                <div class="col-md-4 sliderImage" style="background-image: url('{{ $sliderImages[2]['url']  }}')">
                </div>
            @endif
        </div>
    </div> --}}

{{--     <div class="row" style="position:relative; top: -60px">
        <div class="col-md-offset-1 col-md-2">
            <div class="form-group">
                <div class="text-center">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="thumbnail">
                            <img src="{{ Storage::url($team->avatar) }}" />
                        </div>
                        @if($team->user->id == Auth::id())
                            <div class="row">
                                {!! Form::open(['method' => 'POST', 'route' => ['teams.uploadPicture', $team->id], 'files' => true]) !!}
                                    {!! Form::file('avatar', ['class' => 'filestyle', 'data-classButton' => 'btn btn-primary', 'data-input' => 'false', 'data-classIcon' => 'icon-plus', 'data-buttonText' => trans('team.upload_avatar'), 'onchange' => 'this.form.submit()']) !!}
                                {!! Form::close() !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <section class="content">
        <div class="container-fluid">
            <h1>Dashboard</h1>
            <div  class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-12" align="center">
                                    <button class="btn btn-grad" data-toggle="modal" data-target="#postModal"><i class="fa fa-edit"></i> {{ trans('team.post_text') }}</button>
                                </div>
                            </div>
                            <br>
                            <ul class="nav nav-tabs ul-edit responsive">
                                <li class="active">
                                    <a href="#tab-activity" data-toggle="tab">
                                        <i class="livicon" data-name="list" data-size="16" data-c="#01BC8C" data-hc="#01BC8C" data-loop="true"></i> {{ trans('team.feed') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab-upcoming" data-toggle="tab"><i class="livicon" data-name="user-flag" data-size="16" data-c="#01BC8C" data-hc="#01BC8C" data-loop="true"></i> {{ trans('team.upcoming_events') }}</a>
                                </li>
                                <li>
                                    <a href="#tab-media" data-toggle="tab"><i class="livicon" data-name="user-flag" data-size="16" data-c="#01BC8C" data-hc="#01BC8C" data-loop="true"></i> {{ trans('team.media') }}</a>
                                </li>
                                @if($team->isOwner(Auth::id()))
                                    <li>
                                        <a href="#tab-members" data-toggle="tab"><i class="livicon" data-name="user-flag" data-size="16" data-c="#01BC8C" data-hc="#01BC8C" data-loop="true"></i> {{ trans('team.members') }}</a>
                                    </li>
                                @endif
                            </ul>
                            <div class="tab-content">
                                <div id="tab-activity" class="tab-pane fade in active">
                                    <div class="activity">
                                        <div class="imgs-profile" v-for="post in posts" v-if="posts">
                                            <img :src="post.avatar_url" class="pull-left media-object img-circle">
                                            <div v-if="post.user.id == user_id" class="pull-right">
                                                <a href="javascript:;" @click="editPost(post)"><i class="fa fa-edit"></i></a>
                                                <a href="javascript:;" @click="deletePost(post.id)"><i class="fa fa-remove"></i></a>
                                            </div>
                                            <div class="media-body">
                                                <strong>
                                                    <a :href="post.profile_url" :title="fullName(post.user)">@{{ fullName(post.user) }}</a>
                                                </strong>
                                                <span v-if="post.media.length > 0">
                                                    {{ trans('team.uploaded') }}
                                                    <a :href="post.medias[0].link" v-if="post.media_count > 1"> @{{ post.media_count }} photos</a>
                                                    <a :href="post.medias[0].link" v-else="post.media_count > 1"> a photo</a>
                                                    <br>
                                                </span>
                                                <span v-else="post.media">{{ trans('team.posted_message') }}</span>
                                                <div class="text-muted">
                                                    @{{ post.created_at | moment }}
                                                </div>
                                                <h4 style="letter-spacing: 0"><a href="javascript:;" style="color:#337ab7" data-toggle="modal" data-target="#postModal" @click="updateViewPost(post)">@{{ post.subject }}</a></h4>
                                                <p v-html="post.parsedContent"></p>
                                                <span v-if="post.media">
                                                    <div class="row">
                                                        <div class="thumbnail col-md-4" v-for="item in post.medias">
                                                            <a :href="item.link"><img :src="item.url" class="img-responsive"></a>
                                                        </div>
                                                    </div>
                                                </span>
                                                {{-- End of pictures --}}
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <a href="javascript:;" id="postLike" data-type="post">
                                                            <i :data-id="post.id" class="fa fa-heart likeColor" :class="{ likeActive: post.liked }" style="height:20px"></i> <span v-text="post.likesTotal"></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-push-2 col-md-2">
                                                        <a href="javascript:;" @click="comment(post.id)"><i class="fa fa-comments commentColor"></i> <span v-text="post.commentsTotal"></span></a><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <ul id="comments-list" class="comments-list" v-if="post.comments.length > 0" style="list-style-type: none;">
                                                        <li v-for="comment in post.comments">
                                                            <div class="comment-main-level">
                                                                 <!-- Avatar -->
                                                                <img :src="comment.avatar_url" class="comment-object pull-left" alt="">
                                                                <!-- Contenedor del Comentario -->
                                                                <div class="comment-box">
                                                                    <div class="comment-head">
                                                                        <h6 class="comment-name" :class="{ byauthor: comment.user.id == post.user.id }"><a :href="comment.profile_url">@{{ fullName(comment.user) }}</a></h6>
                                                                        <span>@{{ comment.created_at | moment }}</span>
                                                                        <i  v-if="comment.user.id == user_id" class="fa fa-remove" @click="deleteComment(comment.id)"></i>
                                                                        <i v-if="comment.user.id == user_id"  class="fa fa-edit" @click="editComment(comment.id, comment.body)"></i>
                                                                    </div>
                                                                    <div class="comment-content">
                                                                        @{{ comment.body }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 v-if="posts.length === 0">{{ trans('team.posts_empty') }}</h4>
                                    </div>
                                </div>
                                <div id="tab-upcoming" class="tab-pane fade in">
                                    @foreach($events as $event)
                                        <div class="activity">
                                            <div class="imgs-profile">
                                                @if(strlen($team->avatar) > 0)
                                                    <img src="{{ (\App::environment('production')) ? Storage::disk('s3')->temporaryUrl($team->avatar, \Carbon\Carbon::now()->addMinutes(5)) : Storage::url($team->avatar) }}" alt="" class="pull-left media-object img-circle">
                                                @else
                                                    <img src="{{ (\App::environment('production')) ? Storage::disk('s3')->temporaryUrl($team->sport->logo, \Carbon\Carbon::now()->addMinutes(5)) : Storage::url($team->sport->logo) }}" alt="" class="pull-left media-object img-circle">
                                                @endif
                                                <div class="media-body">
                                                    {{ trans('team.on') }} <strong>{{ date('d-M-Y', strtotime($event->date)) }}</strong>
                                                    {{ trans('team.we_are_going_to_play_at') }}
                                                    <strong><a target="_blank" href="{{ $event->location_url }}">{{ $event->location_name }}</a></strong>.
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="tab-media" class="tab-pane fade in">
                                    <div class="activity">
                                        <div class="imgs-profile" v-for="post in mediaPosts" v-if="mediaPosts">
                                            <img :src="post.avatar_url" class="pull-left media-object img-circle">
                                            <div v-if="post.user.id == user_id" class="pull-right">
                                                <a href="javascript:;" @click="editPost(post)"><i class="fa fa-edit"></i></a>
                                                <a href="javascript:;" @click="deletePost(post.id)"><i class="fa fa-remove"></i></a>
                                            </div>
                                            <div class="media-body">
                                                <strong>
                                                    <a :href="post.profile_url" :title="fullName(post.user)">@{{ fullName(post.user) }}</a>
                                                </strong>
                                                <span v-if="post.media.length > 0">
                                                    {{ trans('team.uploaded') }}
                                                    <a :href="post.medias[0].link" v-if="post.media_count > 1"> @{{ post.media_count }} photos</a>
                                                    <a :href="post.medias[0].link" v-else="post.media_count > 1"> a photo</a>
                                                    <br>
                                                </span>
                                                <span v-else="post.media">{{ trans('team.posted_message') }}</span>
                                                <div class="text-muted">
                                                    @{{ post.created_at | moment }}
                                                </div>
                                                <h4 style="letter-spacing: 0"><a href="javascript:;" style="color:#337ab7" data-toggle="modal" data-target="#postModal" @click="updateViewPost(post)">@{{ post.subject }}</a></h4>
                                                <p v-html="post.parsedContent"></p>
                                                <span v-if="post.media">
                                                    <div class="row">
                                                        <div class="thumbnail col-md-4" v-for="item in post.medias">
                                                            <a :href="item.link"><img :src="item.url" class="img-responsive"></a>
                                                        </div>
                                                    </div>
                                                </span>
                                                {{-- End of pictures --}}
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <a href="javascript:;" id="postLike" data-type="post">
                                                            <i :data-id="post.id" class="fa fa-heart likeColor" :class="{ likeActive: post.liked }" style="height:20px"></i> <span v-text="post.likesTotal"></span>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-push-2 col-md-2">
                                                        <a href="javascript:;" @click="comment(post.id)"><i class="fa fa-comments commentColor"></i> <span v-text="post.commentsTotal"></span></a><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <ul id="comments-list" class="comments-list" v-if="post.comments.length > 0" style="list-style-type: none;">
                                                        <li v-for="comment in post.comments">
                                                            <div class="comment-main-level">
                                                                 <!-- Avatar -->
                                                                <img :src="comment.avatar_url" class="comment-object pull-left" alt="">
                                                                <!-- Contenedor del Comentario -->
                                                                <div class="comment-box">
                                                                    <div class="comment-head">
                                                                        <h6 class="comment-name" :class="{ byauthor: comment.user.id == post.user.id }"><a :href="comment.profile_url">@{{ fullName(comment.user) }}</a></h6>
                                                                        <span>@{{ comment.created_at | moment }}</span>
                                                                        <i  v-if="comment.user.id == user_id" class="fa fa-remove" @click="deleteComment(comment.id)"></i>
                                                                        <i v-if="comment.user.id == user_id"  class="fa fa-edit" @click="editComment(comment.id, comment.body)"></i>
                                                                    </div>
                                                                    <div class="comment-content">
                                                                        @{{ comment.body }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 v-if="posts.length === 0">{{ trans('team.posts_empty') }}</h4>
                                    </div>
                                </div>
                                @if($team->isOwner(Auth::id()))
                                    <div id="tab-members" class="tab-pane fade in">
                                        <div class="activity">
                                            @if(count($members) > 0)
                                                @foreach($members as $member)
                                                    <div class="imgs-profile">
                                                        <img src="{{ getAvatarUrl($member->id) }}" alt="" class="pull-left media-object img-circle">
                                                        <div class="media-body">
                                                            <a href="{{ route('user.profile.index', $member->username) }}"><strong>{{ $member->name }}</strong></a>
                                                            {{ trans('team.registered_at') }}
                                                            <strong>{{ date('d-M-Y', strtotime($member->created_at)) }}</strong>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="imgs-profile">
                                                    <h4>{{ trans('team.no_members') }}</h4>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <th colspan="4" class="grad"><h2 class="m-0">{{ trans('team.scoreboard') }}</h2></th>
                                </thead>

                                <template v-if="events.length > 0">
                                    <thead>
                                        <th colspan="4"><b>{{ trans('team.season') }}</b>: @{{ season }}</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="event in events">
                                            <td>@{{ event.date | eMoment }}</td>
                                            <td>vs</td>
                                            <td v-text="event.enemy_team"></td>
                                            <td v-if="event.status === 'loaded'">
                                                <b v-if="event.goals_1 > event.goals_2" style="color: green">W</b>
                                                <b v-else-if="event.goals_1 === event.goals_2">Tie</b>
                                                <b v-else style="color: red">L</b>
                                                @{{ event.goals_1 }} - @{{ event.goals_2 }}
                                            </td>
                                            <td v-else>
                                                <a v-if="event.status === 'update'" href="#!" class="editable" data-type="event" :data-pk="event.id" data-placement="left" data-url="/updateEvent" data-title="Update event">{{ trans('team.update') }}</a>
                                                <span v-else>{{ trans('team.upcoming') }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </template>

                                <template v-else>
                                    <tbody>
                                        <tr>
                                            <td colspan="4">{{ trans('team.no_events') }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </table>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="users">
                                    <thead>
                                        <th colspan="2" class="grad"><h2 class="m-0">{{ trans('team.team_info') }}</h2></th>
                                    </thead>
                                    <tr>
                                        <td><b>{{ trans('team.name') }}</b></td>
                                        <td>
                                        <a href="#">{{ $team->name }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ trans('team.number_of_events') }}</b></td>
                                        <td>{{ count($team->events) }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>City</b></td>
                                        <td>{{ $team->city }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ trans('team.total_players') }}</b></td>
                                        <td>{{ count($team->players) }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ trans('team.founded') }}</b></td>
                                        <td>{{ date('m/d/Y', strtotime($team->founded_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{ trans('team.invite_people') }}</b></td>
                                        <td><button type="button" data-toggle="modal" data-target="#inviteModal" class="btn btn-success btn-sm">{{ trans('team.invite') }}</button></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <th colspan="2" class="grad">
                                            <h2 class="m-0">{{ trans('team.key_contacts') }}</h2>
                                        </th>
                                    </thead>
                                    @foreach($team->coaches as $coach)
                                        <tr>
                                            <th colspan="2">
                                                <h4>
                                                    {{ $coach->title }}
                                                    @if($team->isOwner(Auth::id()))
                                                        <a href="#!" @click="questionRemoveCoach({{$coach->id}})" class="text-danger pull-right" title="Remove coach"><i class="fa fa-remove"></i></a>
                                                        <a href="#!" data-toggle="modal" data-target="#editCoachModal" @click="openEditCoach({{$coach->id}})" class="text-primary pull-right" title="Edit coach"><i class="fa fa-edit"></i></a>
                                                    @endif
                                                </h4>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td><b>{{ trans('team.coach_name') }}</b></td>
                                            <td>
                                                <span>{{ $coach->name }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>{{ trans('team.coach_email') }}</b></td>
                                            <td><a href="mailto:{{ $coach->email }}">{{ $coach->email }}</a></td>
                                        </tr>
                                        @if($coach->phone)
                                            <tr>
                                                <td><b>{{ trans('team.coach_phone') }}</b></td>
                                                <td>{{ $coach->phone }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    @if($team->isOwner(Auth::id()))
                                        <tr>
                                            <td align="center" colspan="2">
                                                <a href="#!" class="dark-text" data-toggle="modal" data-target="#coachModal">
                                                    <i class="fa fa-plus"></i> {{ trans('team.add_coach') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <th colspan="2" class="grad">
                                        <h2 class="m-0">{{ trans('team.archives') }}</h2>
                                    </th>
                                    @if(count($team->archives) > 0)
                                    @foreach($archives as $archive)
                                        @php
                                            switch($archive->extension){
                                                case 'doc':
                                                    $icon = 'fas fa-file-word text-primary';
                                                break;

                                                case 'docx':
                                                    $icon = 'fas fa-file-word text-primary';
                                                break;

                                                case 'pdf':
                                                    $icon = 'fas fa-file-pdf text-danger';
                                                break;

                                                case 'xls':
                                                    $icon = 'fas fa-file-excel text-success';
                                                break;

                                                case 'xlsx':
                                                    $icon = 'fas fa-file-excel text-success';
                                                break;

                                                case 'png':
                                                    $icon = 'fas fa-image text-info';
                                                break;

                                                case 'jpeg':
                                                    $icon = 'fas fa-image text-info';
                                                break;

                                                case 'jpg':
                                                    $icon = 'fas fa-image text-info';
                                                break;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                @if($archive->user->id == \Auth::id() || Auth::user()->type == 'admin')
                                                    <span class="close" @click="deleteArchive({{$archive->id}})"><i class="fa fa-times"></i></span>
                                                @endif
                                                <i class="{{ $icon }}" style="margin-right: 5px"></i>
                                                <a href="{{ Storage::url($archive->path) }}" download="{{ $archive->name }}">{{ $archive->name }}</a><br>
                                                <div class="text-muted">{{ date('d-M-Y', strtotime($archive->created_at)) }}</div>
                                                <strong>Uploaded by:</strong> <a href="{{ route('user.profile.index', $archive->user->username) }}">{{ $archive->user->name }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td align="right">{{ $archives->render() }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="2">
                                            <p>{{ trans('team.no_archives') }}</p>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            @if($team->isOwner(Auth::id()))
                            {!! Form::open(['method' => 'POST', 'route' => ['teams.uploadArchives', $team->id], 'files' => 'true']) !!}
                                 <div class="form-group" align="center">
                                    {!! Form::file('archive', ['class' => 'filestyle', 'data-classButton' => 'btn btn-primary', 'data-input' => 'false', 'data-classIcon' => 'icon-plus', 'data-buttonText' => trans('team.button_upload_archive'), 'onchange' => 'this.form.submit()']) !!}
                                </div>
                            {!! Form::close() !!}
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <b>{{ trans('team.allowed_formats') }}</b>:
                                            doc, docx, pdf, xls, png, jpeg, jpg
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Modal for creating/editing coach --}}
    @if($team->isOwner(Auth::id()))
        <div class="modal" id="editCoachModal" tabindex="1" role="dialog" aria-labelledby="mostModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">{{ trans('team.edit_coach') }}</h4>
                    </div>
                    <form method="POST" @submit.prevent="updateCoach">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">{{ trans('team.coach_title') }}</label>
                                        <input id="title" type="text" v-model="editCoach.title" class="form-control">
                                        <p class="text-danger" v-if="errors.title">@{{ errors.title[0] }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ trans('team.coach_name') }}</label>
                                        <input id="name" type="text" v-model="editCoach.name" class="form-control">
                                        <p class="text-danger" v-if="errors.name">@{{ errors.name[0] }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email">{{ trans('team.coach_email') }}</label>
                                        <input id="email" type="text" v-model="editCoach.email" class="form-control">
                                        <p class="text-danger" v-if="errors.email">@{{ errors.email[0] }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="phone">{{ trans('team.coach_phone') }}</label>
                                        <input id="phone" type="text" v-model="editCoach.phone" placeholder="(999) 999-9999" v-mask="'(###) ###-####'" class="form-control">
                                        <p class="text-danger" v-if="errors.phone">@{{ errors.phone[0] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a data-dismiss="modal" class="btn btn-danger">{{ trans('player.cancel') }}</a>
                            <button type="submit" class="btn btn-success">{{ trans('team.update_coach') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" id="coachModal" tabindex="4" role="dialog" aria-labelledby="mostModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">{{ trans('team.add_coach') }}</h4>
                    </div>
                    <form method="POST" @submit.prevent="submitCoach">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">{{ trans('team.coach_title') }}</label>
                                        <input id="title" type="text" v-model="coach.title" class="form-control">
                                        <p class="text-danger" v-if="errors.title">@{{ errors.title[0] }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">{{ trans('team.coach_name') }}</label>
                                        <input id="name" type="text" v-model="coach.name" class="form-control">
                                        <p class="text-danger" v-if="errors.name">@{{ errors.name[0] }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email">{{ trans('team.coach_email') }}</label>
                                        <input id="email" type="text" v-model="coach.email" class="form-control">
                                        <p class="text-danger" v-if="errors.email">@{{ errors.email[0] }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="phone">{{ trans('team.coach_phone') }}</label>
                                        <input id="phone" type="text" v-model="coach.phone" placeholder="(999) 999-9999" v-mask="'(###) ###-####'" class="form-control">
                                        <p class="text-danger" v-if="errors.phone">@{{ errors.phone[0] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a data-dismiss="modal" class="btn btn-danger">{{ trans('player.cancel') }}</a>
                            <button type="submit" class="btn btn-success">{{ trans('team.add_coach') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <!-- Invite Modal -->
    <div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="inviteModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Send an invitation to join your team dashboard!</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" @submit.prevent="sendInvitationForm">
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input id="name" type="text" placeholder="Name" v-model="invite.name" class="form-control">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input id="email" type="text" placeholder="Email" v-model="invite.email" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
        </div>
    </div>
    </div>

    <!-- Modal for Creating post -->
        <div class="modal" id="postModal" tabindex="-1" role="dialog" aria-labelledby="mostModalLabel" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" @click="closeModal"><span aria-hidden="true">&times;</span></button>
                    <div class="pull-right" style="margin-right: 10px" v-if="postModalTitle == viewTitle">
                        <a class="btn btn-danger" @click="deletePost(viewPost.id)"><i class="fa fa-remove"></i> {{ trans('team.delete_post') }}</a>
                        <button class="btn btn-info" @click="editPost(viewPost)"><i class="fa fa-edit"></i> {{ trans('team.edit_post') }}</button>
                    </div>
                    <h4 class="modal-title" id="myModalLabel">@{{ postModalTitle }}</h4>
                </div>
                <div class="modal-body">
                      <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#post" aria-controls="post" role="tab" data-toggle="tab">{{ trans('team.post_tab') }}</a></li>
                    <li role="presentation"><a href="#media" aria-controls="media" role="tab" data-toggle="tab">{{ trans('team.media_tab') }}</a></li>
                  </ul>
                    <form action="{{ route('posts.store') }}" id="newPost" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="post">
                        <br>
                        <strong>{{ trans('team.posted_by') }}</strong>: {{ \Auth::user()->fullName() }}<br><br>
                        <template v-if="postModalTitle != viewTitle">
                            <div class="form-group">
                                <input name="subject" type="text" placeholder="{{ trans('team.post_subject') }}" v-model="postFields.subject" @keydown.enter.prevent required class="form-control">
                            </div>
                            <div class="form-group">
                                <textarea name="content" id="" cols="30" rows="10" class="form-control" placeholder="{{ trans('team.post_body') }}" required="" v-model="postFields.content"></textarea>
                            </div>
                        </template>
                        <template v-else>
                            <h4 style="letter-spacing: 0">{{ trans('team.subject') }}: @{{ viewPost.subject }}</h4>
                            <h4 style="letter-spacing: 0; margin-top: 10px">{{ trans('team.content') }}:</h4>
                            <p v-html="viewPost.content"></p>
                        </template>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="media">
                        <br>
                        <strong>{{ trans('team.posted_by') }}</strong>: {{ \Auth::user()->fullName() }}<br><br>
                        <div class="form-group" v-show="postModalTitle == createTitle">
                            <small>
                                <b>{{ trans('team.unaccepted_formats') }}: MKV, OGM, MPEG4</b>.
                            </small>
                            <input @change="filesChanged" type="file" data-buttonText="{{ trans('team.post_images') }}" multiple class="filestyle" name="files[]">
                        </div>
                        <div v-if="postFields.content != '' && postFields.medias">
                            <div class="row">
                                <div class="col-md-4" v-for="(item, index) in postFields.medias">
                                    <div class="thumbnail">
                                        <p><img :src="item.url" class="img-responsive"></p>
                                        <div class="caption">
                                            <p align="center"><a href="#" @click="deleteMedia(item.slug, index)" class="btn btn-danger btn-sm">remove</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="postModalTitle === viewTitle && viewPost.medias">
                            <div class="row">
                                <div class="col-md-4" v-for="(item, index) in viewPost.medias">
                                    <div class="thumbnail">
                                        <p><img :src="item.url" class="img-responsive"></p>
                                        <div class="caption">
                                            <p align="center"><a href="#" @click="deleteMedia(item.slug, index)" class="btn btn-danger btn-sm">remove</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" @click="closeModal">Close</button>
                    <template v-if="postModalTitle != viewTitle">
                        <button v-if="postModalTitle == editTitle" type="button" class="btn btn-primary" @click="updatePost">{{ trans('team.update_text') }}</button>
                        <button v-if="postModalTitle == createTitle" type="button" class="btn btn-primary" @click="submitMessage">{{ trans('team.post_text') }}</button>
                    </template>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

{{-- page level scripts --}}
@section('scripts')

    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/jquery-mockjax/js/jquery.mockjax.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/x-editable/js/bootstrap-editable.js') }}"  type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-magnify/bootstrap-magnify.js') }}" ></script>
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}" ></script>
    <script src="{{ asset('assets/js/holder.js') }}"  type="text/javascript"></script>
    {{-- <script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script> --}}
    <script src="{{ asset('assets/vendors/sweetalert/js/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/swal-forms/swal-forms.js') }}"></script>
{{--     <script src="{{ asset('assets/vendors/owl_carousel/js/owl.carousel.min.js') }}" type="text/javascript"></script>
 --}}    <script src="{{ asset('assets/vendors/x-editable/js/bootstrap-editable.js') }}" ></script>
    <script src="{{ asset('assets/vendors/x-editable/js/event.js') }}" ></script>

    <script>
    const storage = {
            test: 'test',
            editTitle: '{{ trans('team.post_edit_title') }}',
            createTitle: '{{ trans('team.post_title') }}',
            viewTitle: '{{ trans('team.view_post') }}',
            postModalTitle: '{{ trans('team.post_title') }}',
            user_id: '{{ Auth::id() }}',
            team_id: '{{ $team->id }}',
            team: {!! $team->toJson() !!}
        }
    </script>
    <script src="{{ mix('js/dashboard.js') }}"></script>
    <script>
        $(document).on('click', '#postLike', function(){

            if(auth === false){
                window.location = "{{ url('login') }}";
            }

            var likes = this;
            console.log($(likes).data('type'));

            if($(likes).data('type') === 'post'){

                $.ajax({
                    url: "{{ route('posts.like') }}",
                    method: "PUT",
                    type: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $(likes).find('.fa-heart').data('id')
                    },
                    success: function(data) {
                        app.updatePosts();
                        // $(likes).find('.like').toggleClass("liked");
                    }
                });
            }

        });
    </script>
@stop
