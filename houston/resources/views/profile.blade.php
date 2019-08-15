@extends('layouts.app', ['title' => $user->username])

@section('styles')
@endsection

@section('content')
<!-- Main container -->
<main>

    <!-- Profile head -->
    <div class="profile-head p-0">
        <div class="profile-bg">
            <div class="container">

                @include('components.flash_notification')

                <img src="{{ getAvatarUrl($user->id) }}" alt="avatar">
                <h3>
                    <a class="text-white" href="{{ route('user.profile.index',$user->username) }}">
                        <strong>
                            {{ strtoupper($user->username) }}
                        </strong>
                    </a>
                </h3>
                <hr class="bold-divider" style="width: 120px">
                <p class="text-white" style="font-size: 18px;">
                    <em>
                        {{ trans('profile.member_since') }}: <br> {{ $user->created_at->toFormattedDateString() }}
                    </em>
                </p>
            </div>
        </div>
        <div class="bottom-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-3" align="left">
                        <span class="profile-points">
                            <b>
                                <i class="fa fa-star text-success"></i> {{ strtolower(trans('profile.points')) }}: {{ $user->pointsSum() }}
                            </b>
                        </span>
                        <br>
                        <div class="light-grad"></div>
                        @if(Auth::id() == $user->id)
                             <span align="left" class="account-status">
                                <strong>{{ trans('profile.account_status') }}</strong>:<br>
                                {{ trans('profile.unlimited_premium_account') }}
                            </span>
                        @endif
                    </div>
                    @if(Auth::check())
                        <ul class="col-sm-3 col-md-3 action-buttons">
                        @if($owner)
                            <li><a class="btn btn-grad btn-sm" href="{{ route('settings.profile') }}">{{ trans('profile.edit_profile') }}</a></li>
                        @else
                            @if(Auth::user()->isFollow($user->id))
                            <li><button data-id="{{ $user->id }}" class="follow btn btn-default btn-sm" href="#">{{ trans('profile.unfollow') }}</button></li>
                            @else
                            <li><button data-id="{{ $user->id }}" class="follow btn btn-grad btn-sm" href="#">{{ trans('profile.follow') }}</button></li>
                            @endif
                        @endif
                        </ul>
                    @endif
                     <ul class="col-sm-12 col-md-6 tab-list">
                        <li class="{{ ($page == 'index') ? 'active' : '' }}">
                            <a href="{{ route('user.profile.index', $user->username) }}"><i>{{ trans('profile.media') }}</i>
                                <span>
                                    {{ $user->media->count() }}
                                </span>
                            </a></li>
                        <li class="{{ ($page == 'likes') ? 'active' : 'hidden-xs' }}">
                            <a href="{{ route('user.profile.likes', $user->username) }}"><i>{{ trans('profile.likes') }}</i>
                                <span>
                                    {{ \App\Media::whereLikedBy ($user->id)->count() }}
                                </span>
                            </a>
                        </li>
                        <li class="{{ ($page == 'followers') ? 'active' : '' }}" >
                            <a href="{{ route('user.profile.followers',$user->username) }}">
                                <i>{{ trans('profile.followers') }}</i><span id="followers_count">{{ $user->followers->count() }}
                                </span>
                            </a>
                        </li>
                        <li class="{{ ($page == 'following') ? 'active' : '' }}" >
                            <a href="{{ route('user.profile.following', $user->username) }}">
                                <i>{{ trans('profile.following') }}</i><span>{{ $user->following->count() }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Profile head -->
    @if($page == 'likes' || $page == 'index')
        @include('components.profile_media')
    @endif

    @if($page == 'followers' || $page == 'following')
        @include('components.profile_followers')
    @endif
</main>
<!-- END Main container -->
@endsection

@section('scripts')
    <script>
        $('.shot-preview a').on('click',function(){
            window.location = $(this).attr('href');
        });

    </script>
@endsection
