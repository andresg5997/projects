@extends('layouts.app', ['title' => $page->title])

@section('content')

<!-- Main container -->
<main>

    {!! $page->content !!}

</main>
@endsection
