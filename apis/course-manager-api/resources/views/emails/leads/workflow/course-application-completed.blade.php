<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>The Craydel family!</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, height=device-height, initial-scale=1.0">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <style media="all" type="text/css">
        *, *:before, *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            margin:0; padding:0;
        }

        body{
            margin:0;
            padding:0;
            background:#F5F5F5;
            font-size:15px;
            line-height:20px;
            color:#191919;
            font-family:"Helvetica Neue", Helvetica, Arial;
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            -webkit-tap-highlight-color: transparent;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            -moz-osx-font-smoothing: grayscale;
        }
        table {
            border-collapse: collapse;
        }

        img, a img{
            outline:none;
            border:none;
        }

        a{
            color:#FE5050;
            font-weight: 500;
            text-decoration: underline;
        }

        a:hover{
            color:#155DDB;
            text-decoration:underline;
        }

        a:visited{
            color:#FE5050;
        }

        .email_container{
            margin: auto;
        }

        @media screen and (max-width: 580px) {
            body{
                font-size:13px !important;
                line-height:18px !important;
            }

            .email_container {
                width: 100% !important;
                margin: auto !important;
            }

            .gutter_width{
                width:20px !important;
            }

            .gutter_height{
                height:20px !important;
            }
            .expand{
                width: 100% !important;
                height: auto !important;
            }
        }

        @media screen and (max-width: 400px) {
            .footer_menu_grid tr, .footer_menu_grid td{
                width:100% !important;
                display: block !important;

            }

            .social_connect tr, .social_connect td{
                display: table-cell !important;
                width: auto !important;
            }
            .footer_menu {
                padding-left: 20px !important;
                margin-bottom:10px !important;
            }

            .social_connect{
                margin-left: 20px !important;
            }
            .social_connect td{
                width:20px !important;
            }
            .craydel_logo{
                width: 140px !important;
                height: auto !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; font-family:'Helvetica Neue', Helvetica, Arial; color:#191919; background:#F5F5F5;">
<table width="600" summary="The Craydel family!" bgcolor="#FFFFFF" border="0" align="center" cellpadding="0" cellspacing="0" class="email_container">
    <!-- Visually Hidden Preheader Text : BEGIN -->
    <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">
        Hope you've had a great start to the day!
    </div>
    <!-- Visually Hidden Preheader Text : END -->
    <tbody>
    <tr>
        <td height="40" align="left" class="gutter_height" valign="top">&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0">
                <tbody>
                <tr>
                    <td class="gutter_width" width="40" align="left" valign="top">&nbsp;</td>
                    <td><a href="https://craydel.com/"><img src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/craydel.png" alt="craydel.com" width="167" height="32" class="craydel_logo" style="display: block; outline: none; border: none;"></a></td>
                    <td class="gutter_width" width="40" align="right" valign="top">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td height="40" align="left" class="gutter_height" valign="top">&nbsp;</td>
    </tr>
    <tr>
        <td>
            <img src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/craydel_banner.jpg" alt="Welcome to Craydel" width="600" height="237" class="expand" style="display: block; outline: none; border: none;">
        </td>
    </tr>
    <tr>
        <td height="40" align="left" class="gutter_height" valign="top">&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0">
                <tbody>
                <tr>
                    <td class="gutter_width" width="40" align="left" valign="top">&nbsp;</td>
                    <td align="left" valign="top">
                        <h2 style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:20px; margin:0 0 20px; padding:0;">
                            Hi {{ $student_first_name }}
                        </h2>

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 15px; padding:0;">
                            Hope you've had a great start to the day! Well, let's make it better with this great news!
                        </p>

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 15px; padding:0;">
                            Your application to <b>{{ $institution_name }}</b> for <b>{{ $course_name }}</b> has been submitted!
                        </p>

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 15px; padding:0;">
                            Once your application is verified by the <b>{{ $institution_name }}'s</b> admissions team, you will get an offer letter. It usually takes <b>2 - 6 weeks</b> to get it
                        </p>

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 15px; padding:0;">
                            We will reach out to you as soon as it is generated.
                        </p>

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 15px; padding:0;">
                            I am available on <b>{!! $counsellor_phone_number !!}</b> between 9 AM and 6 PM from Monday to Friday and 9 AM to 2 PM on Saturday should you need any help.
                        </p>

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 15px; padding:0;">
                            Here's to Owning your Future!<br/><br/>
                            Yours Sincerely,<br/>
                            Team Craydel<br/>
                            <a href="https://www.craydel.com">https://www.craydel.com</a><br/>
                        </p>
                    </td>
                    <td class="gutter_width" width="40" align="right" valign="top">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td height="40" align="left" class="gutter_height" valign="top">&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" bgcolor="#18244F">
                <tbody>
                <tr>
                    <td height="30" align="left" valign="top" class="gutter_height">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" border="0" class="footer_menu_grid">
                            <tbody>
                            <tr>
                                <td class="gutter_width" width="40" align="left" valign="top">&nbsp;</td>
                                <td class="footer_menu_col" valign="top" align="left">
                                    <ul style="list-style-type: none; margin:0; padding:0;" class="footer_menu">
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/about" target="_blank" style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:10px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">About Us</a></li>
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/contact-us" target="_blank" style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:10px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Contact Us</a></li>
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/terms-of-use" target="_blank" style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:0px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Terms of Use</a></li>
                                    </ul>
                                </td>
                                <td class="gutter_width" width="30" align="left" valign="top">&nbsp;</td>
                                <td class="footer_menu_col" valign="top" align="left">
                                    <ul style="list-style-type: none; margin:0; padding:0;" class="footer_menu">
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/privacy-policy" target="_blank" style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:10px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Privacy Policy</a></li>
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/cancellation-and-refund-policy" target="_blank" style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:10px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Cancellation &amp; Refund Policy</a></li>
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/partnership" target="_blank" style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:0px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Become a Partner</a></li>
                                    </ul>
                                </td>
                                <td class="gutter_width" width="30" align="right" valign="top">&nbsp;</td>
                                <td class="footer_menu_col" valign="top" align="right">
                                    <table width="100%" border="0" class="social_connect">
                                        <tbody>
                                        <tr>
                                            <td align="left" valign="top"><a href="https://www.facebook.com/Craydel-103615141854770" target="_blank"><img src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/facebook-logo.png" style="display: block; outline: none; border: none;" alt=""></a></td>
                                            <td width="5" align="left" valign="top">&nbsp;</td>
                                            <td align="left" valign="top"><a href="https://twitter.com/Craydel_" target="_blank"><img src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/twitter.png" style="display: block; outline: none; border: none;" alt=""></a></td>
                                            <td width="5" align="left" valign="top">&nbsp;</td>
                                            <td align="left" valign="top"><a href="https://www.youtube.com/channel/UCtqKevjdarKpdS0NP6u7l_w" target="_blank"><img src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/youtube.png" style="display: block; outline: none; border: none;" alt=""></a></td>
                                            <td width="5" align="left" valign="top">&nbsp;</td>
                                            <td align="left" valign="top"><a href="https://www.instagram.com/craydel_" target="_blank"><img src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/instagram.png" style="display: block; outline: none; border: none;" alt=""></a></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </td>
                                <td class="gutter_width" width="30" align="right" valign="top">&nbsp;</td>
                            </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>

                <tr>
                    <td height="30" align="left" valign="top" class="gutter_height">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#1D2D64" height="60" align="center" valign="middle" style="color:#b9bcca; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Copyright &copy; {{ date('Y') }} Craydel. All Rights Reserved</td>
    </tr>
    </tbody>
</table>
</body>
</html>
