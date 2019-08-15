@extends('layouts.app', ['title'=> '403 Error'])

@section('styles')
@endsection

@section('content')

    <!-- Main container -->
    <main>

        <section class="no-border-bottom">
            <div class="container">
                <header class="section-header">
                    <span>Error</span>
                    <h2>a 403 one</h2>
                    <br>
                    <span class="alert alert-danger">Seems like you are not permitted to go here?</span>
                </header>

            </div>
        </section>

    </main>
    <!-- END Main container -->

@endsection

@section('scripts')
@endsection
