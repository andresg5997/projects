<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \DB::table('pages')->insert([
            // create footer headings
            [
                'id'         => 1,
                'slug'       => 'clooud-media',
                'name'       => 'Clooud Media',
                'order'      => 1,
                'title'      => 0,
                'content'    => 0,
                'parent'     => 0,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 2,
                'slug'       => 'policies',
                'name'       => 'Policies',
                'order'      => 2,
                'title'      => 0,
                'content'    => 0,
                'parent'     => 0,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 3,
                'slug'       => 'services',
                'name'       => 'Services',
                'order'      => 3,
                'title'      => 0,
                'content'    => 0,
                'parent'     => 0,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 4,
                'slug'       => 'social-media',
                'name'       => 'Social Media',
                'order'      => 4,
                'title'      => 0,
                'content'    => 0,
                'parent'     => 0,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            // fill with footer with sample pages

            [
                'id'         => 5,
                'slug'       => 'news',
                'name'       => 'News',
                'order'      => 1,
                'title'      => 'News',
                'content'    => '<section class="no-border-bottom section-sm">

    <header class="section-header">
        <span>Hello There</span>
        <h2>We got news.</h2>
        <p>Read below for what\'s happening.</p>
    </header>

    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card" style="padding: 0px 25px">
                    <div class="card-header">
                        <h6>by chris</h6>
                    </div>
                    <div class="card-block">
                        <p>Great news! We just implemented many new features and improvements.</p>
                        <ul><li>unregistered users can now upload media</li>
                            <li><strong>Remote Uploads!</strong></li>
                            <li>Openload Uploads</li>
                            <li>password protected files</li>
                            <li>various other improvements</li>
                        </ul>
                        <p>That\'s the list of what has been implemented.</p>
                        <p> </p>
                        <p>What are "remote uploads"?</p>
                        <p>Let\'s say you have your files stored somewhere online already. Instead of uploading your file through our system, you can pass through your link (the URL where the file is stored) and we will automatically download the file for you and add it to your account. </p>
                        <p>We also now enabled Openload uploads. If you have files stored in Openload, just copy and paste those links under the "Openload" tab and these will be added to your account as well. Please note that during certain hours a day, Openload may experience high bandwith usage and not allow any API downloads. In that case, if you know how to access the direct URL to the file, you can still download it via the "Simple" remote upload.</p>
                        <hr>
                        <div>
                            <span class="badge">Posted 2017-03-23 22:00 PST</span>
                            <div class="pull-right">
                                <span class="label label-success">News</span>
                                <span class="label label-primary">Launch</span>
                                <!--<span class="label label-default">blog</span>
                                <span class="label label-info">personal</span>
                                <span class="label label-warning">Warning</span>
                                <span class="label label-danger">Danger</span>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card" style="padding: 0px 25px">
                    <div class="card-header">
                        <h6>by chris</h6>
                    </div>
                    <div class="card-block">

                        <h1>Revolution has begun!</h1>
                        <p>We are proud and excited to launch Clooud.tv. After months of hard work we finally reached the point of where we would like to share our project with the public.</p>
                        <p>As daily users of media sharing sites ourselves, we got so annoyed by the abundance and ridiculous amount of advertisements that are visualized on many media sharing sites. We do understand that in order to run a successful business money has to be involved, but we found most commonly used advertising techniques absolutely horrible. Our idea is to provide a friendly, safe and innovative user experience which is not cluttered in ads. Hence, we understand the three priorities: Privacy, effectiveness, and no annoyance through advertising, such as popups. Those are our priorities and this is where we separate us drastically from any competition.</p>
                        <hr>
                        <div>
                            <span class="badge">Posted 2017-03-14 16:00 PST</span>
                            <div class="pull-right">
                                <span class="label label-success">News</span>
                                <span class="label label-primary">Launch</span>
                                <!--<span class="label label-default">blog</span>
                                <span class="label label-info">personal</span>
                                <span class="label label-warning">Warning</span>
                                <span class="label label-danger">Danger</span>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>',
                'parent'     => 1,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 6,
                'slug'       => 'about-us',
                'name'       => 'About Us',
                'order'      => 2,
                'title'      => 'About Us',
                'content'    => '<!-- Team -->
        <section class="no-border-bottom">
            <div class="container">
                <header class="section-header">
                    <span>Who we are</span>
                    <h2>Our team</h2>
                    <p>Currently, we\'re three geeks and will grow up soon</p>
                </header>

                <div class="row equal-blocks">

                    <!-- User widget -->
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="card user-widget">
                            <div class="card-block text-center">
                                <a href="https://clooud.tv/user/Chris"><img src="https://clooud.tv/uploads/avatars/chris.jpg" alt="Chris"></a>
                                <h5><a href="#">Chris Breuer</a></h5>
                                <p class="lead">Founder</p>
                                <br>
                                <p class="text-justify">Got annoyed by all the annoying websites throwing 20 popups each, so he figured he can do better.</p>
                            </div>

                            <div class="card-footer">
                                <ul class="social-icons">
                                    <li><a class="twitter" target="_blank" href="https://twitter.com/ChrisBreuer1904"><i class="fa fa-twitter"></i></a></li>
                                    <li><a class="linkedin" target="_blank" href="https://www.linkedin.com/in/chris-breuer-33231765/"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a class="facebook" target="_blank" href="https://www.facebook.com/chrisbreuer93"><i class="fa fa-facebook"></i></a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <!-- END User widget -->

                    <!-- User widget -->
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="card user-widget">
                            <div class="card-block text-center">
                                <a href="https://clooud.tv/user/Bent"><img src="https://clooud.tv/uploads/avatars/bent.jpg" alt="Bent"></a>
                                <h5><a href="#">Bent Jenson Jr.</a></h5>
                                <p class="lead">Graphics</p>
                                <br>
                                <p class="text-justify">The reason this website is looking as it does is because of this guy!</p>
                            </div>

                            <div class="card-footer">
                                <ul class="social-icons">
                                    <li><a class="linkedin" target="_blank" href="https://www.linkedin.com/in/bent-jenson-457a5b87/"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a class="facebook" target="_blank" href="https://www.facebook.com/bent.j.jenson"><i class="fa fa-facebook"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END User widget -->

                    <!-- User widget -->
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="card user-widget">
                            <div class="card-block text-center">
                                <a href="https://clooud.tv/user/ahill41"><img src="https://clooud.tv/uploads/avatars/avery.jpg" alt="Avery"></a>
                                <h5><a href="#">Avery Hill</a></h5>
                                <p class="lead">Marketing</p>
                                <br>
                                <p class="text-justify">In charge of all the marketing related concerns and social media stuff!</p>
                            </div>

                            <div class="card-footer">
                                <ul class="social-icons">
                                    <li><a class="linkedin" target="_blank" href="https://www.linkedin.com/in/avery-hill-4599b4104/"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a class="facebook" target="_blank" href="https://www.facebook.com/ave.hill"><i class="fa fa-facebook"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END User widget -->

                </div>

            </div>
        </section>
        <!-- END Team -->',
                'parent'     => 1,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 7,
                'slug'       => 'faq',
                'name'       => 'FAQ',
                'order'      => 3,
                'title'      => 'FAQ',
                'content'    => '<section class="no-border-bottom section-sm">

            <div class="container">

                <header class="section-header">
                    <span>Got Questions?</span>
                    <h2>We got answers.</h2>
                    <p>Keep reading this section to figure out your concerns.</p>
                </header>

                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="card" style="padding: 0px 25px">
                            <div class="card-header">
                                <h6>General FAQ</h6>
                            </div>
                            <div class="card-block">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse1" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">Why another media sharing site?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>As daily users of media sharing sites ourselves, we got so annoyed by the abundance and ridiculous amount of advertisements that are visualized on many media sharing sites.</p>
                                                <p>We do understand that in order to run a successful business money has to be involved, but we found most commonly used advertising techniques absolutely horrific. Our idea is to provide a friendly, safe and innovative user experience which is not cluttered in ads. Hence, we understand the three priorities: Privacy, effectiveness, and no annoyance through advertising, such as popups.</p>
                                                <p>Those are our priorities and this is where we separate us drastically from any competition. A media playing and sharing service built for speed, simplicity, and security.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse2" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How does this website work?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Very easy.</p>
                                                <p>We differentiate ourselves from our competition by the ease and friendliness of use. You can simply sign yourself up for a free account, or not, and upload as many files as you like.</p>
                                                <p>These files can be played and shared with your family and friends and you can gain points and money while doing it!</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse5" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">What are points?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse5" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Points is our competitive measurement system. You gain different amount of points for uploading media, commenting on media, etc. You can compete with and other users to reach a higher score! It is fun.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse7" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How long are my files stored?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse7" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>They generally will never get deleted. What we do, though, in order to keep up servers clean and up and running at full speed, we will notify users who have files that have not been visited by anyone for the past 60 days.</p>
                                                <p>In case you would like to keep the file store, just follow the instructions in your email to keep it up.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse8" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">Does Clooud throttle download speeds?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse8" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>No, we do not. We do prioritize premium users to get higher download speeds, though.</p>
                                                <p>What that means is that in case some of our servers are reaching some of their limits during certain parts of the day, we will send the request from specific premium member groups to servers which have more resources available at given time.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="card" style="padding: 0px 25px">
                            <div class="card-header">
                                <h6>Money FAQ</h6>
                            </div>
                            <div class="card-block">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How can I make money?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>First off, make sure you enroll in our <a href="{{ route(\'settings.affiliate\') }}">Affiliate Program</a>.</p>
                                                <p>Once you are checked the box and saved the settings, you will gain access to the <a href="{{ route(\'affiliate.info\') }}">Affiliate tab</a> with all of your needed statistics and information.</p>
                                                <p>The moment visitors watch your uploaded videos, gaze at your images and listen to your audio files - that is what you earn money for.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse4" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How can I get paid the money I earned?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Currently, we offer PayPal and Payza as our payout processors. If you wish to see another option, <a href="{{ route(\'contact.show\') }}">contact us</a> and we will discuss it.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse9" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">How does the referral system work?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse9" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Within your affiliate account you have your referral link. You need to send this one to your family or friends and if anyone signs up after having visited your link, you will receive a referral.</p>
                                                <p>Once you have referrals start uploading media you will start earning 30% of the total revenue they make.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 data-toggle="collapse" data-parent="#accordion" href="#collapse6" class="panel-title expand">
                                                <div class="right-arrow pull-right">+</div>
                                                <a href="#">Do you have more affiliate related questions?</a>
                                            </h4>
                                        </div>
                                        <div id="collapse6" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Please, log in and look at the <a href="{{ route(\'affiliate.info\') }}">affiliate information tab</a>.</p>
                                                <p>Many other questions are answered there and if anything is left unanswered, feel free to <a href="{{ route(\'contact.show\') }}">contact us</a>.</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </section>

    <script>
        $(function() {
            $(".expand").on( "click", function() {
                // $(this).next().slideToggle(200);
                $expand = $(this).find(">:first-child");

                if($expand.text() == "+") {
                    $expand.text("-");
                } else {
                    $expand.text("+");
                }
            });
        });
    </script>',
                'parent'     => 1,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 8,
                'slug'       => 'contact-us',
                'name'       => 'Contact Us',
                'order'      => 4,
                'title'      => 'Contact Us',
                'content'    => 0,
                'parent'     => 1,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 9,
                'slug'       => 'tos',
                'name'       => 'Terms of Service',
                'order'      => 1,
                'title'      => 'Terms of Service',
                'content'    => '<!-- TOS -->
        <section class="bg-white">
            <div class="container">
                <header class="section-header">
                    <span>Rules</span>
                    <h2>Terms Of Service</h2>
                    <p>Keep reading this section to figure which rules to go by.</p>
                </header>

                <h5>Terms & Conditions</h5>
                <p>Clooud.tv reserves the right to remove any files that compromise the security of the server, use excess bandwidth, or are otherwise malignant.
                    The following types of files may not be uploaded under any circumstances:
                    <ul class="intext">
                        <li>Files that infringe on the copyrights of any entity.</li>
                        <li>Files that are illegal and/or are in violation of any laws.</li>
                    </ul>
                </p>
                <h5 class="other-title">Terms Of Usage</h5>
                <p>Clooud.tv assumes no liability for lost or corrupt links, files or misplaced file URLs. It is user\'s responsibility to keep track of this information.</p>
                <h5 class="other-title">Legal Policy</h5>
                <p>These Terms of Service are subject to change without prior warning. By using Clooud.tv, user agrees not to involve Clooud.tv in any type of legal action.<br>
                    Clooud.tv directs full legal responsibility of the contents of the files that are uploaded to Clooud.tv to individual users, and will cooperate with copyright owners and law enforcement entities in the case that uploaded files are deemed to be in violation of these Terms of Service.<br><br>
                    All files are copyrighted to their respective owners. Clooud.tv is not responsible for the content of any uploaded files, nor is it in affiliation with any entities that may be represented in the uploaded files.</p>
            </div>
        </section>
        <!-- END TOS -->',
                'parent'     => 2,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 10,
                'slug'       => 'privacy-policy',
                'name'       => 'Privacy Policy',
                'order'      => 2,
                'title'      => 'Privacy Policy',
                'content'    => '<section class="no-border-bottom section-sm">

            <div class="container">

                <header class="section-header">
                    <span>We keep you safe.</span>
                    <h2>Privacy Policy</h2>
                    <p>Your privacy is our number one concern. Keep reading this section to figure out your concerns.</p>
                </header>

                <h5>Privacy Policy</h5>
                <p>This privacy policy has been compiled to better serve those who are concerned with how their \'Personally Identifiable Information\' (PII) is being used online. PII, as described in US privacy law and information security, is information that can be used on its own or with other information to identify, contact, or locate a single person, or to identify an individual in context. Please read our privacy policy carefully to get a clear understanding of how we collect, use, protect or otherwise handle your Personally Identifiable Information in accordance with our website.</p>

                <h5>What personal information do we collect from the people that visit our blog, website or app?</h5>

                <p>When ordering or registering on our site, as appropriate, you may be asked to enter your or other details to help you with your experience.</p>

                <h5>When do we collect information?</h5>

                <p>We collect information from you when you register on our site, subscribe to a newsletter, fill out a form or enter information on our site.</p>

                <h5>How do we use your information?</h5>

                <p>We may use the information we collect from you when you register, make a purchase, sign up for our newsletter, respond to a survey or marketing communication, surf the website, or use certain other site features in the following ways:</p>
                <ul>
                    <li>To personalize your experience and to allow us to deliver the type of content and product offerings in which you are most interested.</li>
                    <li>To improve our website in order to better serve you.</li>
                </ul>

                <h5>How do we protect your information?</h5>

                <p>Our website is scanned on a regular basis for security holes and known vulnerabilities in order to make your visit to our site as safe as possible.</p>

                <p>We use regular Malware Scanning.</p>

                <p>Your personal information is contained behind secured networks and is only accessible by a limited number of persons who have special access rights to such systems, and are required to keep the information confidential. In addition, all sensitive/credit information you supply is encrypted via Secure Socket Layer (SSL) technology.</p>

                <p>We implement a variety of security measures when a user places an order enters, submits, or accesses their information to maintain the safety of your personal information.</p>

                <p>All transactions are processed through a gateway provider and are not stored or processed on our servers.</p>

                <h5>Do we use \'cookies\'?</h5>

                <p>Yes. Cookies are small files that a site or its service provider transfers to your computer\'s hard drive through your Web browser (if you allow) that enables the site\'s or service provider\'s systems to recognize your browser and capture and remember certain information. For instance, we use cookies to help us remember and process the items in your shopping cart. They are also used to help us understand your preferences based on previous or current site activity, which enables us to provide you with improved services. We also use cookies to help us compile aggregate data about site traffic and site interaction so that we can offer better site experiences and tools in the future.</p>

                <p>We use cookies to:</p>
                <ul>
                    <li>Compile aggregate data about site traffic and site interactions in order to offer better site experiences and tools in the future. We may also use trusted third-party services that track this information on our behalf.</li>
                </ul>

                <p>You can choose to have your computer warn you each time a cookie is being sent, or you can choose to turn off all cookies. You do this through your browser settings. Since browser is a little different, look at your browser\'s Help Menu to learn the correct way to modify your cookies.</p>

                <p>If you turn cookies off, some features will be disabled. It won\'t affect the user\'s experience that make your site experience more efficient and may not function properly.</p>

                <p>However, you will still be able to place orders.</p>


                <h5>Third-party disclosure</h5>

                <p>We do not sell, trade, or otherwise transfer to outside parties your Personally Identifiable Information.</p>

                <h5>Third-party links</h5>

                <p>Occasionally, at our discretion, we may include or offer third-party products or services on our website. These third-party sites have separate and independent privacy policies. We therefore have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.</p>

                <h5>Google</h5>

                <p>Google\'s advertising requirements can be summed up by Google\'s Advertising Principles. They are put in place to provide a positive experience for users. https://support.google.com/adwordspolicy/answer/1316548?hl=en</p>

                <p>We use Google AdSense Advertising on our website.</p>

                <p>Google, as a third-party vendor, uses cookies to serve ads on our site. Google\'s use of the DART cookie enables it to serve ads to our users based on previous visits to our site and other sites on the Internet. Users may opt-out of the use of the DART cookie by visiting the Google Ad and Content Network privacy policy.</p>

                <p>We have implemented the following:</p>
                <ul>
                    <li>Google Display Network Impression Reporting</li>
                </ul>

                <p>We, along with third-party vendors such as Google use first-party cookies (such as the Google Analytics cookies) and third-party cookies (such as the DoubleClick cookie) or other third-party identifiers together to compile data regarding user interactions with ad impressions and other ad service functions as they relate to our website.</p>

                <p>Opting out:</p>
                <p>Users can set preferences for how Google advertises to you using the Google Ad Settings page. Alternatively, you can opt out by visiting the Network Advertising Initiative Opt Out page or by using the Google Analytics Opt Out Browser add on.</p>

                <h5>California Online Privacy Protection Act</h5>

                <p>CalOPPA is the first state law in the nation to require commercial websites and online services to post a privacy policy. The law\'s reach stretches well beyond California to require any person or company in the United States (and conceivably the world) that operates websites collecting Personally Identifiable Information from California consumers to post a conspicuous privacy policy on its website stating exactly the information being collected and those individuals or companies with whom it is being shared. - See more at: http://consumercal.org/california-online-privacy-protection-act-caloppa/#sthash.0FdRbT51.dpuf</p>

                <p>According to CalOPPA, we agree to the following:</p>
                <p>Users can visit our site anonymously.</p>
                <p>Once this privacy policy is created, we will add a link to it on our home page or as a minimum, on the first significant page after entering our website.</p>
                <p>Our Privacy Policy link includes the word \'Privacy\' and can easily be found on the page specified above.</p>

                <p>You will be notified of any Privacy Policy changes:</p>
                <ul>
                    <li>On our Privacy Policy Page</li>
                </ul>
                <p>Can change your personal information:</p>
                <ul>
                    <li>By logging in to your account</li>
                </ul>

                <h5>How does our site handle Do Not Track signals?</h5>
                <p>We honor Do Not Track signals and Do Not Track, plant cookies, or use advertising when a Do Not Track (DNT) browser mechanism is in place.</p>

                <h5>Does our site allow third-party behavioral tracking?</h5>
                <p>It\'s also important to note that we do not allow third-party behavioral tracking</p>

                <h5>COPPA (Children Online Privacy Protection Act)</h5>

                <p>When it comes to the collection of personal information from children under the age of 13 years old, the Children\'s Online Privacy Protection Act (COPPA) puts parents in control. The Federal Trade Commission, United States\' consumer protection agency, enforces the COPPA Rule, which spells out what operators of websites and online services must do to protect children\'s privacy and safety online.</p>

                <p>We do not specifically market to children under the age of 13 years old.</p>

                <h5>Fair Information Practices</h5>

                <p>The Fair Information Practices Principles form the backbone of privacy law in the United States and the concepts they include have played a significant role in the development of data protection laws around the globe. Understanding the Fair Information Practice Principles and how they should be implemented is critical to comply with the various privacy laws that protect personal information.</p>

                <p>In order to be in line with Fair Information Practices we will take the following responsive action, should a data breach occur:</p>
                <p>We will notify you via email</p>
                <ul>
                    <li>Within 1 business day</li>
                </ul>

                <p>We also agree to the Individual Redress Principle which requires that individuals have the right to legally pursue enforceable rights against data collectors and processors who fail to adhere to the law. This principle requires not only that individuals have enforceable rights against data users, but also that individuals have recourse to courts or government agencies to investigate and/or prosecute non-compliance by data processors.</p>

                <h5>CAN SPAM Act</h5>

                <p>The CAN-SPAM Act is a law that sets the rules for commercial email, establishes requirements for commercial messages, gives recipients the right to have emails stopped from being sent to them, and spells out tough penalties for violations.</p>

                <p>We collect your email address in order to:</p>
                <ul>
                    <li>Send information, respond to inquiries, and/or other requests or questions</li>
                </ul>

                <p>To be in accordance with CANSPAM, we agree to the following:</p>
                <ul>
                    <li>Not use false or misleading subjects or email addresses.</li>
                    <li>Identify the message as an advertisement in some reasonable way.</li>
                    <li>Include the physical address of our business or site headquarters.</li>
                    <li>Monitor third-party email marketing services for compliance, if one is used.</li>
                    <li>Honor opt-out/unsubscribe requests quickly.</li>
                    <li>Allow users to unsubscribe by using the link at the bottom of each email.</li>
                </ul>

                <p>If at any time you would like to unsubscribe from receiving future emails, you can email us at</p>
                <ul>
                    <li>Follow the instructions at the bottom of each email.</li>
                </ul>
                <p>and we will promptly remove you from ALL correspondence.</p>


                <h5>Contacting Us</h5>

                <p>If there are any questions regarding this privacy policy, you may contact us using the information below.</p>

                <p>clooud.tv/contact</p>
                <p>support@clooud.tv</p>

                <p>Last Edited on 2017-01-24</p>

            </div>

        </section>',
                'parent'     => 2,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 11,
                'slug'       => 'copyright-policy',
                'name'       => 'Copyright Policy',
                'order'      => 3,
                'title'      => 'Copyright Policy',
                'content'    => 0,
                'parent'     => 2,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 12,
                'slug'       => 'report-abuse',
                'name'       => 'Report Abuse',
                'order'      => 4,
                'title'      => 'Report Abuse',
                'content'    => 0,
                'parent'     => 2,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 13,
                'slug'       => 'subscriptions',
                'name'       => 'Subscriptions',
                'order'      => 1,
                'title'      => 'Subscriptions',
                'content'    => 0,
                'parent'     => 3,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

            [
                'id'         => 14,
                'slug'       => 'affiliate',
                'name'       => 'Affiliate',
                'order'      => 2,
                'title'      => 'Affiliate',
                'content'    => 0,
                'parent'     => 3,
                'icon'       => 0,
                'footer'     => 1,
                'created_at' => Carbon::now()
            ],

        ]);
    }
}
