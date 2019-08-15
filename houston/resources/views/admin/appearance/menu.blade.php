@extends('admin.index')
@section('style')
    {!! Html::style('assets/admin/assets/plugins/select2/select2.min.css') !!}
    {!! Html::style('assets/admin/assets/plugins/sweet-alert/sweetalert.css') !!}
    {!! Html::style('assets/admin/assets/plugins/jquery-ui/jquery.ui.theme.css') !!}



@endsection
@section('page-content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Appearance
                <small>Menus</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Menus</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="clearfix"></div>
                                <hr>
                                <div id="categories" class="col-md-4" style="border-right:1px solid #ddd;">
                                    <center><h4>Categories</h4></center>
                                    <ul class="sortable ui-widget">
                                        @foreach ($categories as $category)
                                            <li class="ui-state-default" data-id="{{ $category->id }}"
                                                data-order="{{ $category->order }}">
                                                {{ $category->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div id="forum_categories" class="col-md-4" style="border-right:1px solid #ddd;">
                                    <center><h4>Forum Categories</h4></center>
                                    <ul class="sortable ui-widget">
                                        @foreach ($forum_categories as $forum_category)
                                            <li class="ui-state-default" data-id="{{ $forum_category->id }}"
                                                data-order="{{ $forum_category->order }}">
                                                {{ $forum_category->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div id="pages" class="col-md-4" style="border-right:1px solid #ddd;">
                                    <center><h4>Pages</h4></center>
                                    <ul class="sortable ui-widget">
                                        @foreach ($pages as $page)
                                            <li class="ui-state-default" data-id="{{ $page->id }}"
                                                data-order="{{ $page->order }}">
                                                {{ $page->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="clearfix"></div>

                                @foreach($parents as $parent)
                                    @if ($parent->name != 'Social Media')
                                        <div id="footer-pages" class="col-md-4" style="border-right:1px solid #ddd;">
                                            <center><h4>Pages {{ $parent->name }}</h4></center>
                                            <ul class="sortable ui-widget">
                                                @foreach($footer_pages->where('parent', $parent->id) as $page)
                                                    <li class="ui-state-default" data-id="{{ $page->id }}"
                                                        data-order="{{ $page->order }}">
                                                        {{ $page->name }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div>
@endsection
@section('javascript')
    {!! Html::script('assets/admin/assets/plugins/select2/select2.full.min.js') !!}
    {!! Html::script('assets/admin/assets/plugins/sweet-alert/sweetalert.min.js') !!}
    <script>
        $(document).ready(function () {


            $('.sortable').sortable().bind('sortupdate', function (e, ui) {

                var type = $(this).parent().closest('div').attr('id');

                var order = $('#' + type + ' ul.sortable > li').map(function () {
                    return $(this).data("order");
                }).get()

                var id = $('#' + type + ' ul.sortable > li').map(function () {
                    return $(this).data("id");
                }).get();

                var _token = '{{ csrf_token() }}';

                var path = "{{ route('admin.appearance.menu.sort', '0') }}";
                var url = path.replace(0, type);


                $.ajax({
                    type: "PUT",
                    url: url,
                    dataType: "json",
                    data: {_token: _token, order: order, id: id},
                    success: function (order) {
                        console.log(order)
                    }
                });

            });

        });
    </script>
@endsection
