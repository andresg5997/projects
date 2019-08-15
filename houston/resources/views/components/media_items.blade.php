@php
    $agent = new Jenssegers\Agent\Agent();

    $x = 1;
    $i = 1;
    $rand = round((rand(1, count($media))) / 2);
@endphp
<!-- Media list -->
<section class="no-border-bottom section-sm">

    <div class="container">
        <div id="content">
            @if (Auth::check() && ! Auth::user()->isVerified())
                @include('components.flash_notification')
            @endif

            @foreach ($media as $media_item)
                <!-- Media -->
                <div class="media_item equal-cols col{{ $i }}">
                    @if(session()->has('grids'))
                        {!!  session()->get('grids')  !!}
                    @else
                        <div class="col-xs-12 col-sm-6 col-md-4">
                    @endif
                        @if ($i == $rand && session()->get('retrieve') != 1 and count($media) > 8 and config('advertisements_active') and config('home_page_ad_slot'))
                            <div class="shot shot-small" style="max-height: 317px">
                        @else
                            <div class="shot shot-small">
                        @endif
                            <div id="col{{ $i }}" class="shot-preview changeSize">

                                @if ($i == $rand && session()->get('retrieve') != 1 and count($media) > 8 and config('advertisements_active') and config('home_page_ad_slot'))
                                    @php
                                        $x = $x - 1;
                                    @endphp
                                    <h6 style="margin:8px;">
                                        <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                        <span>Featured</span>
                                    </h6>
                                    @include ('components.responsive_ad_display_only')
                                @elseif ($media_item->type == 'picture')
                                    <h6 style="margin:10px;" id="nonad{{ $x }}">
                                        @if($media_item->isGif())
                                            <i class="fa fa-spinner" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-picture-o" aria-hidden="true"></i>
                                        @endif
                                        <a class="title-header" href="{{ url('m/'.$media_item->key) }}">{{ str_limit($media_item->title, 30) }}</a>
                                    </h6>
                                    <a href="{{ url('m/'.$media_item->key) }}">
                                        @if($media_item->isGif())
                                            <span class="play fa fa-spinner"></span>
                                        @endif
                                        <img class="center" src="{{ $media_item->previewImageUrl() }}" alt="">
                                    </a>
                                @elseif($media_item->type == 'video')
                                    <h6 style="margin:10px;" id="nonad{{ $x }}">
                                        <i class="fa fa-play-circle-o" aria-hidden="true"></i>
                                        <a class="title-header" href="{{ url('m/'.$media_item->key) }}">{{ str_limit($media_item->title, 30) }}</a>
                                        {{-- Will hover the title
                                        <a class="title" href="{{ route('media.show', $media_item->slug) }}">{{ $media_item->title }}</a>
                                        --}}
                                    </h6>
                                    <a href="{{ url('m/'.$media_item->key) }}">
                                        <span class="play fa fa-play"></span>
                                        <img class="center" src="{{ $media_item->previewImageUrl() }}" alt="">
                                    </a>
                                @elseif($media_item->type == 'audio')
                                    <h6 style="margin:10px;" id="nonad{{ $x }}">
                                        <i class="fa fa-file-audio-o" aria-hidden="true"></i>
                                        <a class="title-header" href="{{ url('m/'.$media_item->key) }}">{{ str_limit($media_item->title, 30) }}</a>
                                    </h6>
                                    <a href="{{ url('m/'.$media_item->key) }}">
                                        <span class="play fa fa-play"></span>
                                        <img class="center" src="{{ $media_item->previewImageUrl() }}" alt="">
                                    </a>
                                @endif
                            </div>
                            @if ($i == $rand && session()->get('retrieve') != 1 and count($media) > 8 and config('advertisements_active') and config('home_page_ad_slot'))
                                @php
                                    session()->put('retrieve', '1');
                                @endphp
                            @else
                                <div class="shot-info">
                                    <div class="pull-left">
                                        @if ($media_item->anonymous)
                                            <a><img src="{{ url('uploads/avatars/default-avatar.png') }}" alt="anonymous"></a>
                                            <h6><a>Anonymous</a></h6>
                                        @else
                                            <a href="{{ route('user.profile.index', ! empty($media_item->user->username) ? $media_item->user->username : 'Guest' ) }}"><img src="{{ getAvatarUrl(! empty($media_item->user->id) ? $media_item->user->id : 1) }}" alt="avatar"></a>
                                            <h6><a href="{{ route('user.profile.index', ! empty($media_item->user->username) ? $media_item->user->username : 'Guest' ) }}">{{ ! empty($media_item->user->username) ? $media_item->user->username : 'Guest' }}</a></h6>
                                        @endif
                                        <p>
                                            <time>{{ $media_item->created_at->diffForHumans() }}</time>
                                            in <a href="{{ route('category.show', $media_item->category->slug) }}">
                                                {{ $media_item->category->name }}
                                            </a>
                                        </p>
                                    </div>
                                    <div class="pull-right stats">
                                        <ul class="list-unstyled list-inline shot-stats">
                                            <li>
                                                <div class="likes">
                                                    <span data-id="{{ $media_item->id }}" class="like {{ ($media_item->liked() ? 'liked' : '') }}"></span>
                                                    <span class="likes_counter">{{ $media_item->readableCount('likes') }}</span>
                                                </div>
                                            </li>
                                            <li><i class="fa fa-comments"></i> <span>{{ $media_item->readableCount('comments') }}</span></li>
                                            <li><i class="fa fa-eye"></i> <span>{{ $media_item->readableCount('views') }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- END Grids -->
                </div>
                <!-- END Media -->
                @php
                    $i++;
                    $x++;
                @endphp
            @endforeach
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="text-center hidden">{!! $media->render() !!}</div>

    @if (count($media) == config('media_per_page'))
        <center>
            <a class="btn btn-primary btn-round load-more" onclick="$('#content').infinitescroll('retrieve');" style="margin-top: 15px;">Load more</a>
        </center>
    @endif
</section>
<!-- END Shots list -->