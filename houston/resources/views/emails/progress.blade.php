@extends('emails.layouts.app')

@section('content')

    @component('emails.components.content_head')
        @slot('heading')
            You're almost finished!
        @endslot

        @slot('body')
            Two out of three steps are completed.
        @endslot

        @slot('image')
            <img src="{{ url('assets/images/macbook_iphone_coffee.png') }}" style="max-width:100%; display:block;">
        @endslot
    @endcomponent

    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff" >
        <tr>
            <td style="background-color:#ffffff;">
                <br>
                <table class="columns" cellspacing="0" cellpadding="0" width="49%" align="left">
                    <tr>

                        <!-- ############# STEP ONE ############### -->
                        <!-- To change number images to step one:
                            - Replace image below with this url: https://www.filepicker.io/api/file/acgdn9j9T16oHaZ8znhv
                            - Then replace step two with this url: https://www.filepicker.io/api/file/iqmbVoMtT7ukbPUoo9zH
                            - Finally replace step three with this url: https://www.filepicker.io/api/file/ni2yEbRCRJKzRm3cYGnn

                            Finished!
                         -->
                        <td style="padding-left: 60px; padding-top: 10px;">
                            <img src="https://www.filepicker.io/api/file/zNDJy10QemuMhAcirOwQ" alt="step one" width="60" height="62">
                        </td>


                        <td style="color:#f3a389; text-align:left; padding-top: 10px;">
                            Account Activation Complete
                        </td>
                    </tr>
                    <tr>

                        <!-- ############# STEP TWO ############### -->
                        <!-- To change number images to step two:
                            - Replace image below with this url: https://www.filepicker.io/api/file/23h1I8Ts2PNLx755Dsfg
                            - Then replace step one with this url: https://www.filepicker.io/api/file/zNDJy10QemuMhAcirOwQ
                            - Finally replace step three with this url: https://www.filepicker.io/api/file/ni2yEbRCRJKzRm3cYGnn

                            Finished!
                         -->
                        <td style="padding-left: 60px; padding-top: 10px;">
                            <img src="https://www.filepicker.io/api/file/23h1I8Ts2PNLx755Dsfg" alt="step two" width="60" height="65">
                        </td>
                        <td style="color:#f5774e; text-align:left; padding-top: 10px;">
                            Update Account <br> Info
                        </td>
                    </tr>
                    <tr>

                        <!-- ############# STEP THREE ############### -->
                        <!-- To change number images to step three:
                            - Replace image below with this url: https://www.filepicker.io/api/file/OombIcyT92WWTaHB4vlE
                            - Then replace step one with this url: https://www.filepicker.io/api/file/zNDJy10QemuMhAcirOwQ
                            - Finally replace step three with this url: https://www.filepicker.io/api/file/iqmbVoMtT7ukbPUoo9zH

                            Finished!
                         -->
                        <td style="padding-left: 60px; padding-top: 10px;">
                            <img src="https://www.filepicker.io/api/file/ni2yEbRCRJKzRm3cYGnn" alt="step three" width="60" height="60">
                        </td>
                        <td  style="color:#f3a389; text-align:left; padding-top: 10px;">
                            Account set up <br>complete!
                        </td>
                    </tr>
                </table>
                <table class="columns" cellspacing="0" cellpadding="0" width="49%" align="right">
                    <tr>
                        <td class="column-padding" style="text-align:left; vertical-align:top; padding-left: 20px; padding-right:30px;">
                            <br>
                            <span style="color:#3bcdb0; font-size:20px; font-weight:bold;">two last step and you're done!</span><br>
                            We need you to update your account information. If there is ever a problem with your account, this information will make it easier for you to log back in.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="margin:0 auto;" cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff">
        <tr>
            <td style="text-align:center; margin:0 auto;">
                <br>
                <div><!--[if mso]>
                    <v:rect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://" style="height:45px;v-text-anchor:middle;width:220px;" stroke="f" fillcolor="#f5774e">
                        <w:anchorlock/>
                        <center>
                    <![endif]-->
                    <a href="http://"
                       style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:220px;-webkit-text-size-adjust:none;">Update Account</a>
                    <!--[if mso]>
                    </center>
                    </v:rect>
                    <![endif]--></div>
                <br>
            </td>
        </tr>
    </table>

@endsection
