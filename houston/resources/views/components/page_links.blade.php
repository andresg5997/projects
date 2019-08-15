<!-- Page links -->
<div class="page-links">
    <div class="container d-flex justify-content-center">
        @if($view == 'home')
            <div class="">
                <ul class="link-list">
                    @if($menu == 'followFeeds')
                        <li class="text-center">{{ trans('page_links.follow_feed') }}: {{ trans('page_links.media_from_follows') }}</li>
                    @elseif($menu == 'search')
                        <li class="text-center">{{ trans('page_links.search_results_for') }}: <div class="label label-primary"> {{ $q }}</div></li><br>
                        <li class="text-center">{{ trans('page_links.results_count') }}: <div class="label label-primary"> {{ $media->where('private', 0)->count() }}</div></li>
                    @elseif($menu == 'tag')
                        <li class="text-center">{{ trans('page_links.tag') }}: {{ $tag }}</li>
                    @else
                    <li>
                        <a {!! ($menu == 'recent') ? 'class="active"' : '' !!} href="{{ route('home') }}">
                            <i class="fa fa-list"></i> {{ trans('page_links.recent') }}
                        </a>
                    </li>
                    <li>
                        <a {!! ($menu == 'popular') ? 'class="active"' : '' !!} href="{{ route('media.mostPopular') }}">
                            <i class="fa fa-star"></i> {{ trans('page_links.popular') }}
                        </a>
                    </li>
                    <li>
                        <a {!! ($menu == 'likes') ? 'class="active"' : '' !!} href="{{ route('media.mostLikes') }}">
                            <i class="fa fa-heart"></i> {{ trans('page_links.most_liked') }}
                        </a>
                    </li>
                    <li>
                        <a {!! ($menu == 'comments') ? 'class="active"' : '' !!} href="{{ route('media.mostComments') }}">
                            <i class="fa fa-comments"></i> {{ trans('page_links.most_commented') }}
                        </a>
                    </li>
                    <li>
                        <a {!! ($menu == 'random') ? 'class="active"' : '' !!} href="{{ route('media.random') }}">
                            <i class="fa fa-random" aria-hidden="true"></i> {{ trans('page_links.random') }}
                        </a>
                    </li>
                </ul>
                @endif
            </div>
            <div class="pull-right">
                <ul class="link-list">
                    <li>
                        <a class="grid {!! (session()->get('grids') == '<div class="col-xs-12 col-sm-6 col-md-4">') ? 'active' : (empty(session()->get('grids'))) ? 'active' : '' !!}" data-url="{{ route('media.changeGrid', 3) }}">
                            <i class="ti-layout-grid3-alt" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a class="grid {!! (session()->get('grids') == '<div class="col-xs-12 col-sm-6">') ? 'active' : '' !!}" data-url="{{ route('media.changeGrid', 2) }}">
                            <i class="ti-layout-grid2-alt" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </div>
        @elseif ($view == 'settings')
            <div class="pull-right">
                <ul class="link-list">
                    <li><a class="page-link {!! ($page == 'profile') ? 'active' : '' !!}" href="{{ route('settings.profile') }}"><i class="fa fa-user"></i> {{ strtoupper(trans('page_links.profile')) }}</a></li>
                    <li><a class="page-link {!! ($page == 'notifications') ? 'active' : '' !!}" href="{{ route('settings.notifications') }}"><i class="fa fa-bullhorn"></i> {{ strtoupper(trans('page_links.notifications')) }}</a></li>
                    <li><a class="page-link {!! ($page == 'password') ? 'active' : '' !!}" href="{{ route('settings.password.edit') }}"><i class="fa fa-lock"></i> {{ strtoupper(trans('page_links.security')) }}</a></li>
                    {{-- <li><a {!! ($page == 'affiliate') ? 'class="active"' : '' !!} href="{{ route('user.settings.affiliate') }}"><i class="fa fa-briefcase"></i> {{ trans('page_links.affiliate') }}</a></li> --}}
                </ul>
            </div>
        {{-- @elseif ($view == 'affiliate')
            <div class="pull-right">
                <ul class="link-list">
                    <li><a {!! ($page == 'statistics') ? 'class="active"' : '' !!} href="{{ route('affiliate.statistics') }}"><i class="fa fa-line-chart"></i> {{ trans('page_links.overview') }}</a></li>
                    <li><a {!! ($page == 'statistics.video') ? 'class="active"' : '' !!} href="{{ route('affiliate.statistics.video') }}"><i class="fa fa-tv"></i> {{ trans('page_links.video') }}</a></li>
                    <li><a {!! ($page == 'statistics.image') ? 'class="active"' : '' !!} href="{{ route('affiliate.statistics.image') }}"><i class="fa fa-image"></i> {{ trans('page_links.image') }}</a></li>
                    <li><a {!! ($page == 'statistics.audio') ? 'class="active"' : '' !!} href="{{ route('affiliate.statistics.audio') }}"><i class="fa fa-music"></i> {{ trans('page_links.audio') }}</a></li>
                    <li><a {!! ($page == 'payment') ? 'class="active"' : '' !!} href="{{ route('affiliate.payment') }}"><i class="fa fa-credit-card"></i> {{ trans('page_links.payment') }}</a></li>
                    <li><a {!! ($page == 'info') ? 'class="active"' : '' !!} href="{{ route('affiliate.info') }}"><i class="fa fa-info-circle"></i> {{ trans('page_links.info') }}</a></li>
                    <li><a {!! ($page == 'settings') ? 'class="active"' : '' !!} href="{{ route('affiliate.settings') }}"><i class="fa fa-cog"></i> {{ trans('page_links.settings') }}</a></li>
                </ul>
            </div> --}}
            @if($page == 'statistics.video' || $page == 'statistics.image' || $page == 'statistics.audio' || $page == 'statistics')
                <div class="pull-right">
                    <p class="small">{{ trans('page_links.server_time') }}: {{ \Carbon\Carbon::now() }} | {{ trans('page_links.updated_every') }} {{ $expires_at_interval }} {{ trans('page_links.minutes') }}</p>
                </div>
            @elseif($page == 'payment')
                <div class="pull-right">
                    <p class="small">{{ trans('page_links.server_time') }}: {{ \Carbon\Carbon::now() }}</p>
                </div>
            @endif
        @elseif ($view == 'category')
            <div class="pull-right">
                <ul class="link-list">
                    <li>
                        <a {!! ($menu == 'recent') ? 'class="active"' : '' !!} href="{{ route('category.show', $category->slug) }}">
                            <i class="fa fa-list"></i> {{ trans('page_links.recent') }}
                        </a>
                    </li>
                    <li>
                        <a {!! ($menu == 'popular') ? 'class="active"' : '' !!} href="{{ route('category.show.mostPopular', $category->slug) }}">
                            <i class="fa fa-star"></i> {{ trans('page_links.popular') }}
                        </a>
                    </li>
                    <li>
                        <a {!! ($menu == 'likes') ? 'class="active"' : '' !!} href="{{ route('category.show.mostLikes', $category->slug) }}">
                            <i class="fa fa-heart"></i> {{ trans('page_links.most_liked') }}
                        </a>
                    </li>
                    <li>
                        <a {!! ($menu == 'comments') ? 'class="active"' : '' !!} href="{{ route('category.show.mostComments', $category->slug) }}">
                            <i class="fa fa-comments"></i> {{ trans('page_links.most_commented') }}
                        </a>
                    </li>
                    <li>
                        <a {!! ($menu == 'random') ? 'class="active"' : '' !!} href="{{ route('category.show.random', $category->slug) }}">
                            <i class="fa fa-random" aria-hidden="true"></i> {{ trans('page_links.random') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="pull-right">
                <ul class="link-list">
                    <li>
                        <a class="grid {!! (session()->get('grids') == '<div class="col-xs-12 col-sm-6 col-md-4">') ? 'active' : (empty(session()->get('grids'))) ? 'active' : '' !!}" data-url="{{ route('media.changeGrid', 3) }}">
                            <i class="ti-layout-grid3-alt" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a class="grid {!! (session()->get('grids') == '<div class="col-xs-12 col-sm-6">') ? 'active' : '' !!}" data-url="{{ route('media.changeGrid', 2) }}">
                            <i class="ti-layout-grid2-alt" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</div>
<!-- END Page links -->
