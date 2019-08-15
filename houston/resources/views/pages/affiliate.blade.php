@extends('layouts.app', ['title' => 'Affiliate Program'])

@section('styles')
    <link href="{{ url('assets/css/affiliate-tiers.css') }}" rel="stylesheet">
@endsection

@section('content')

    <!-- Main container -->
    <main>

        <!-- Affiliate section -->
        <section class="no-border-bottom section-sm">

            <header class="section-header">
                <span>Make money</span>
                <h2>Our Affiliate Program</h2>
                <p>It is as easy as 1, 2, 3</p>
            </header>

            @include('components.affiliate_tiers')

        </section>
        <!-- END affiliate section -->

    </main>
    <!-- END Main container -->

@endsection