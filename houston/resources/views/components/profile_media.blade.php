<!-- User Media -->
<hr class="grad" style="margin: 0">
<section class="no-border-bottom section-sm dotted-gray-bg">
    <div class="container">

        <div class="row">
            @foreach ($data as $media)
                <!-- Shot -->
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="shot shot-minimal">
                        <div class="shot-preview">
                            <a class="img" href="{{ route('media.show', $media->slug) }}">
                                <img class="center" src="{{ $media->previewImageUrl() }}" alt="">
                            </a>

                            <a class="text-overlay" href="{{ route('media.show', $media->slug) }}">
                                <span class="heading">{{ $media->title }}</span>
                                <span class="desc">{{ $media->body }}</span>
                                <time>{{ $media->created_at->diffForHumans() }}</time>
                            </a>
                        </div>

                        <div class="shot-detail">
                            <div class="shot-info">
                                <p><a href="{{ route('category.show', $media->category->slug) }}">{{ $media->category->name }}</a></p>
                            </div>

                            <ul class="shot-stats">
                                <li><i class="fa fa-eye"></i><span>{{ $media->views }}</span></li>
                                <li><i class="fa fa-comments-o"></i><span>{{ $media->comments->count() }}</span></li>
                                <li><i class="fa fa-heart-o"></i><span>{{ $media->likeCount }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- END Shot -->
            @endforeach
        </div>

        <!-- Pagination -->
        <nav class="text-center">
            {{ $data->render() }}
        </nav>
        <!-- END Pagination -->

    </div>
</section>

<!-- END User Media -->
