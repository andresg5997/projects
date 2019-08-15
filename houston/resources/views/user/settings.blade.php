@extends('layouts.app', ['title' => 'Profile Settings'])

@section('styles')
@endsection

@section('content')
    <!-- Main container -->
    <main>

        @include('components.page_links', ['view' => 'settings'])

        <section class="no-border-bottom section-sm dotted-white-bg">
            <div class="container">

                @include('components.flash_notification')

                <div class="row">
                    <div class="col-xs-12 {{ ($page != 'profile') ? 'col-md-offset-3' : ''  }} col-sm-6 settings-card-container">
                        <div class="card settings-card">
                            <div class="card-header settings-card-header">
                                <h6>{{ $page }} {{ trans('settings.settings') }}</h6>
                            </div>

                            <div class="card-block">
                                @if($page == 'profile')
                                    <form class="form-horizontal" method="post" action="{{ route('settings.profile.update') }}">
                                        {{ method_field('patch') }}
                                        {{ csrf_field() }}
                                        <div class="form-group {{($errors->has('username') ? 'has-error' : '')}}">
                                            <label for="username" class="col-sm-2 control-label">{{ trans('settings.username') }}</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="username" id="username" value="{{ $user->username }}">
                                                @if ($errors->has('username'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('username') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group {{($errors->has('email') ? 'has-error' : '')}}">
                                        <label for="email" class="col-sm-2 control-label">{{ trans('settings.email') }}</label>
                                            <div class="col-sm-10">
                                                <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}">
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-group" align="center">
                                                    <button class="btn btn-grad btn-sm" type="submit">{{ trans('settings.save_changes') }}</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                @elseif($page == 'password')
                                     <form class="form-horizontal" method="post" action="{{ route('settings.password.update') }}">
                                        {{ method_field('patch') }}
                                        {{ csrf_field() }}
                                        <div class="form-group {{($errors->has('password') ? 'has-error' : '')}}">
                                            <label for="password" class="col-sm-2 control-label">{{ trans('settings.password') }}</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="password" id="username" value="">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group {{($errors->has('password_confirmation') ? 'has-error' : '')}}">
                                            <label for="password_confirmation" class="col-sm-2 control-label">{{ trans('settings.password_repeat') }}</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" value="">
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-group" align="center">
                                                    <button class="btn btn-grad btn-sm" type="submit">{{ trans('settings.password_update') }}</button>
                                                </div>
                                            </div>
                                        </div>

                                     </form>
                                @elseif($page == 'affiliate')
                                    <form class="form-horizontal" method="post" action="{{ route('user.settings.affiliate.update') }}">
                                        {{ method_field('patch') }}
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <span style="margin-right: 20px">{{ trans('settings.affiliate_status') }}:</span>
                                                    <input name="affiliate" class="js-switch" {{ ($status) ? "checked" : "" }} type="checkbox">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <button class="btn btn-grad btn-sm" type="submit" onclick="cacheStats()">{{ trans('settings.save_changes') }}</button>
                                            </div>
                                        </div>

                                    </form>
                                @elseif($page == 'notifications')
                                    <form class="form-horizontal" method="post" action="{{ route('settings.notifications.update') }}" style="margin: 0 20%;">
                                        {{ method_field('patch') }}
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="comments" class="js-switch" {{ ($user->notification_comments == 1) ? "checked" : "" }} type="checkbox">
                                                    {{ trans('settings.someone_commented') }}
                                                </label>
                                            </div>

                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="following" class="js-switch" {{ ($user->notification_following == 1) ? "checked" : "" }} type="checkbox">
                                                    {{ trans('settings.someone_started_following') }}
                                                </label>
                                            </div>

                                            <div class="checkbox checkbox-switch">
                                                <label>
                                                    <input name="followers" class="js-switch" {{ ($user->notification_followers_add_media == 1) ? "checked" : "" }} type="checkbox">
                                                    {{ trans('settings.new_media_from_followers') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <div class="form-group" align="center">
                                                    <button class="btn btn-grad btn-sm" type="submit">{{ trans('settings.save_changes') }}</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($page == 'profile')
                        <div class="col-xs-12 col-sm-6 settings-card-container">
                            <div class="card settings-card">
                                <div class="card-header settings-card-header">
                                    <h6>{{ trans('settings.avatar_cover') }}</h6>
                                </div>

                                <div class="card-block">

                                    @include('errors.list')

                                    <form method="post" action="{{ route('settings.avatar.update') }}"  enctype="multipart/form-data" >
                                        {{ csrf_field() }}
                                        {{ method_field('patch') }}
                                        <div class="row">
                                            <div class="col-sm-12" align="center">
                                                <label for="avatar">{{ trans('settings.avatar') }}
                                                    <input id="avatar" type="file" name="avatar" data-default-file="{{ getAvatarUrl($user->id) }}" data-max-height="400" data-max-width="400">
                                                </label>
                                                <label for="cover">{{ trans('settings.cover') }}
                                                    <input id="cover" type="file" name="cover" data-default-file="{{ getAvatarUrl($user->id) }}" data-max-height="400" data-max-width="400">
                                                </label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group" align="center">
                                            <button class="btn btn-grad btn-sm" type="submit">{{ trans('settings.update') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </section>

    </main>
    <!-- END Main container -->

@endsection

@section('scripts')
    <script src="{{ url('assets/js/switchery.min.js') }}"></script>
    <script>
        function cacheStats() {
            $.ajax({
                url: "{{ route('affiliate.statistics.video') }}",
                method: "GET",
                type: 'json',
                data: {
                    _token: "{{ csrf_token() }}"
                }
            });
            $.ajax({
                url: "{{ route('affiliate.statistics.audio') }}",
                method: "GET",
                type: 'json',
                data: {
                    _token: "{{ csrf_token() }}"
                }
            });
            $.ajax({
                url: "{{ route('affiliate.statistics.image') }}",
                method: "GET",
                type: 'json',
                data: {
                    _token: "{{ csrf_token() }}"
                }
            });
            $.ajax({
                url: "{{ route('affiliate.statistics') }}",
                method: "GET",
                type: 'json',
                data: {
                    _token: "{{ csrf_token() }}"
                }
            });
        }
    </script>
@endsection
