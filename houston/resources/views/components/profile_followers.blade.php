
<!-- User list -->
<section class="no-border-bottom section-sm dotted-gray-bg">
    <div class="container">

        <div class="row equal-blocks">
            @foreach($users as $user)
            <!-- User widget -->
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="card user-widget">
                    <div class="card-block text-center" style="height: 277px;">
                        <a href="{{ route('user.profile.index', $user->username) }}"><img src="{{ getAvatarUrl($user->id) }}" alt="avatar"></a>
                        <h5><a href="{{ route('user.profile.index', $user->username) }}">{{ $user->username }}</a></h5>
                        <p class="lead"><i class="fa fa-star"></i> Points: {{ $user->pointsSum() }}</p>
                        <small>Member Since: {{ $user->created_at->toFormattedDateString() }}</small>

                            @if(Auth::check() && Auth::user()->id != $user->id)
                                @if(!Auth::user()->isFollow($user->id))
                                    <br>
                                    <a data-id="{{ $user->id }}" class="follow btn btn-success btn-sm" href="#">Follow</a>
                                    <br>
                                @else
                                    <br>
                                    <a data-id="{{ $user->id }}" class="follow btn btn-default btn-sm" href="#">UnFollow</a>
                                    <br>
                                @endif
                            @endif
                    </div>

                    <div class="card-footer">
                        <ul class="user-stats">
                            <li><a href="#"><i>Media</i><span>{{ $user->media->count() }}</span></a></li>
                            <li><a href="#"><i>Followers</i><span>{{ $user->followers->count() }}</span></a></li>
                            <li><a href="#"><i>Following</i><span>{{ $user->following->count() }}</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- END User widget -->
            @endforeach
        </div>

        <nav class="text-center">
            {{ $users->render() }}
        </nav>

    </div>
</section>
<!-- END User list -->
