@extends('layouts.app', ['title' => 'TOS'])

@section('content')

    <!-- Main container -->
    <main>

        <!-- TOS -->
        <section class="dotted-white-bg">
            <div class="container">
                <header align="center">
                    <h2><strong><i>Terms Of Service</i></strong></h2>
                    <hr class="grad divider-mini opacity-7" style="margin-top: 0px;">
                    <h3 class="kurale">Keep reading this section to figure out which rules to go by.</h3>
                </header>

                <div class="card">
                    <div class="card-block">
                        <h5 class="footer-title">Terms &amp; Conditions</h5>
                        <br>
                        <p><b>{{ config('website_title', null) }}</b> reserves the right to remove any files that compromise the security of the server, use excess bandwidth, or are otherwise malignant.
                            The following types of files may not be uploaded under any circumstances:
                            <ul class="intext">
                                <li>Files that infringe on the copyrights of any entity.</li>
                                <li>Files that are illegal and/or are in violation of any laws.</li>
                            </ul>
                        </p>
                        <br>
                        <h5 class="footer-title" class="other-title">Terms Of Usage</h5>
                        <br>
                        <p><b>{{ config('website_title', null) }}</b> assumes no liability for lost or corrupt links, files or misplaced file URLs. It is user's responsibility to keep track of this information.</p>
                        <br>
                        <h5 class="footer-title" class="other-title">Legal Policy</h5>
                        <br>
                        <p>These Terms of Service are subject to change without prior warning. By using <b>{{ config('website_title', null) }}</b>, user agrees not to involve <b>{{ config('website_title', null) }}</b> in any type of legal action.<br>
                            <b>{{ config('website_title', null) }}</b> directs full legal responsibility of the contents of the files that are uploaded to <b>{{ config('website_title', null) }}</b> to individual users, and will cooperate with copyright owners and law enforcement entities in the case that uploaded files are deemed to be in violation of these Terms of Service.<br><br>
                            All files are copyrighted to their respective owners. <b>{{ config('website_title', null) }}</b> is not responsible for the content of any uploaded files, nor is it in affiliation with any entities that may be represented in the uploaded files.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- END TOS -->

    </main>
    <!-- END Main container -->

@endsection
