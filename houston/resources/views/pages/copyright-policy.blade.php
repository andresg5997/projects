@extends('layouts.app', ['title' => 'Copyright Policy'])

@section('content')

    <!-- Main container -->
    <main>

        <!-- Copyright Policy -->
        <section class="dotted-white-bg">
            <div class="container">
                <header align="center">
                    <h2><strong><i>Copyright Policy</i></strong></h2>
                    <hr class="grad divider-mini opacity-7" style="margin-top: 0px;">
                    <h3 class="kurale">Keep reading this section to figure which rules to go by.</h3>
                </header>

                <div class="card">
                    <div class="card-block">
                        <br>
                        <h5 class="footer-title"><strong>(A)</strong> Notification of Infringement</h5>
                        <br>
                        <p>It is our policy to respond to clear notices of alleged copyright infringement that comply with the Digital Millennium Copyright Act. In addition, we will promptly terminate the accounts of those determined by us to be "repeat infringers" without notice. If you are a copyright owner or an agent thereof, and you believe that any content hosted on our web site ({{ ucfirst(request()->getHost()) }}) infringes your copyrights, then you may submit a notification pursuant to the Digital Millennium Copyright Act ("DMCA") by providing {{ ucfirst(request()->getHost()) }}'s Designated Copyright Agent with the following information in writing (please consult your legal counsel or See 17 U.S.C. Section 512(c)(3) to confirm these requirements):
                            <ul class="intext">
                                <li>A physical or electronic signature of a person authorized to act on behalf of the owner of an exclusive right that is allegedly infringed.</li>
                                <li>Identification of the copyrighted work claimed to have been infringed, or, if multiple copyrighted works on {{ ucfirst(request()->getHost()) }} are covered by a single notification, a representative list of such works at that website.</li>
                                <li>Identification of the material that is claimed to be infringing or to be the subject of infringing activity and that is to be removed or access to which is to be disabled, and information reasonably sufficient to permit {{ ucfirst(request()->getHost()) }} to locate the material. Providing URLs in the body of an email is the best way to help us locate content quickly.</li>
                                <li>Information reasonably sufficient to permit {{ ucfirst(request()->getHost()) }} to contact the complaining party, such as an address, telephone number, and, if available, an electronic mail address at which the complaining party may be contacted.</li>
                            </ul>
                        </p>
                        <br>
                        <h5 class="footer-title"><strong>(B)</strong> Counter-Notification</h5>
                        <br>
                        <p>If you elect to send us a counter notice, to be effective it must be a written communication that includes the following (please consult your legal counsel or See 17 U.S.C. Section 512(g)(3) to confirm these requirements):
                            <ul class="intext">
                                <li>A physical or electronic signature of a person authorized to act on behalf of the owner of an exclusive right that is allegedly infringed.</li>
                                <li>Identification of the copyrighted work claimed to have been infringed, or, if multiple copyrighted works on {{ ucfirst(request()->getHost()) }} are covered by a single notification, a representative list of such works at that website.</li>
                            </ul>
                        </p>
                        <br>
                        <h5 class="footer-title"><strong>(C)</strong> Designated Copyright Agent</h5>
                        <br>
                        <p>{{ ucfirst(request()->getHost()) }}'s Designated Copyright Agent to receive notifications and counter-notifications of claimed infringement can be reached as follows:
                            <br><br>
                            <a class="button-link" href="{{ route('page.footer.show', ['services', 'copyright-policy']) }}">You may submit a copyright notice here</a> at {{ config('dmca_email') }} <br><br>
                            or clarity, only DMCA notices should go to the {{ ucfirst(request()->getHost()) }} Designated Copyright Agent.<br>
                            Any other feedback, comments, requests for technical support or other communications should be directed to {{ ucfirst(request()->getHost()) }} customer service through the {{ ucfirst(request()->getHost()) }} Contact Center.<br>
                            You acknowledge that if you fail to comply with all of the requirements of this section, your DMCA notice may not be valid.
                        </p>
                    </div>
                </div>

            </div>
        </section>
        <!-- END Copyright Policy -->

    </main>
    <!-- END Main container -->

@endsection
