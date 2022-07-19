<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, height=device-height, initial-scale=1.0">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <style media="all" type="text/css">
        *, *:before, *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #F5F5F5;
            font-size: 15px;
            line-height: 20px;
            color: #191919;
            font-family: "Helvetica Neue", Helvetica, Arial;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            -webkit-tap-highlight-color: transparent;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            -moz-osx-font-smoothing: grayscale;
        }

        table {
            border-collapse: collapse;
        }

        img, a img {
            outline: none;
            border: none;
        }

        a {
            color: #FE5050;
            font-weight: 500;
            text-decoration: underline;
        }

        a:hover {
            color: #155DDB;
            text-decoration: underline;
        }

        a:visited {
            color: #FE5050;
        }

        .email_container {
            margin: auto;
        }

        @media screen and (max-width: 580px) {
            body {
                font-size: 13px !important;
                line-height: 18px !important;
            }

            .email_container {
                width: 100% !important;
                margin: auto !important;
            }

            .gutter_width {
                width: 20px !important;
            }

            .gutter_height {
                height: 20px !important;
            }

            .expand {
                width: 100% !important;
                height: auto !important;
            }
        }

        @media screen and (max-width: 400px) {
            .footer_menu_grid tr, .footer_menu_grid td {
                width: 100% !important;
                display: block !important;

            }

            .social_connect tr, .social_connect td {
                display: table-cell !important;
                width: auto !important;
            }

            .footer_menu {
                padding-left: 20px !important;
                margin-bottom: 10px !important;
            }

            .social_connect {
                margin-left: 20px !important;
            }

            .social_connect td {
                width: 20px !important;
            }

            .craydel_logo {
                width: 140px !important;
                height: auto !important;
            }
        }


    </style>
</head>

<body style="margin:0; padding:0; font-family:'Helvetica Neue', Helvetica, Arial; color:#191919; background:#F5F5F5;">
<table width="600" summary="Welcome to the Craydel family!" bgcolor="#FFFFFF" border="0" align="center" cellpadding="0"
       cellspacing="0" class="email_container">
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
                    <td><a href="https://craydel.com/"><img
                                src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/craydel.png" alt="craydel.com"
                                width="167" height="32" class="craydel_logo"
                                style="display: block; outline: none; border: none;"></a></td>
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
            <img src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/craydel_email_banner.jpg" alt="Welcome to Craydel"
                 width="600" height="237" class="expand" style="display: block; outline: none; border: none;">
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
                        @if(isset($greetings))
                            <h2 style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:20px; margin:0 0 20px; padding:0;">
                                {{ $greetings }}
                            </h2>
                        @endIf

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">
                            You have been invited by {{ $school_name }} to access the Craydel FutureShapers Platform!
                        </p>
                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">
                            We know how hard it can be to decide what course you want to study, compare options to
                            decide where you want to study it, and figure out how to go about the application and visa
                            processes.
                        </p>
                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">
                            But worry no more! Craydel is here to partner you in your higher education journey.
                        </p>
                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">
                            The Craydel FutureShapers Platform will give you end to end support to help you find the
                            perfect university to build your dream career.
                        </p>
                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">
                            To start your journey on this platform, take a Career Match Psychometric Assessment (CTA).
                            <br/>
                            This is a great tool to help students who are confused which course to pursue in university
                            or need confidence in their career decisions. The assessment will;
                        </p>
                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">
                        <ol style="margin:0; margin-left:20px;">
                            <li style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">Outline your top 3 Ideal Career Options</li>
                            <li style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">Match your Personality, Skills, Aptitudes and Interests to your Future Career</li>
                            <li style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">Help you understand your Career Motivators</li>
                            <li style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">Give you Recommendations on courses matched to your Ideal Careers.</li>
                        </ol>
                            <br/><br/>
                        </p>
                        @if(isset($verification_button_cta) && isset($verification_url))
                            <div style="margin:0 0 30px;">
                                <!--[if mso]>
                                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml"
                                             xmlns:w="urn:schemas-microsoft-com:office:word" href=""
                                             style="height:47px;v-text-anchor:middle;width:300px;" arcsize="5%"
                                             fillcolor="#18244F">
                                    <w:anchorlock/>
                                    <center
                                        style="color:#ffffff;font-family:'Helvetica Neue', Helvetica, Arial;line-height: 18px; font-weight: bold; font-size:14px;">
                                        Take Psychometric Assessment
                                    </center>
                                </v:roundrect>
                                <![endif]-->
                                <a href="{{ $verification_url }}" class="edm_cta"
                                   style="background:#18244F;border:0;border-radius:103px;color:#ffffff;display:inline-block;font-family:'Helvetica Neue', Helvetica, Arial;  font-weight: bold; font-size:14px;line-height:47px;text-align:center;text-decoration:none;width:300px;margin:0; -webkit-text-size-adjust:none;mso-hide:all;">
                                    Take Psychometric Assessment
                                </a>
                            </div>
                        @endif
                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">
                            After you complete this, you will also be able to access a wide range of Career Resources,
                            explore all course and university options for higher education and get personal guidance and
                            support from our expert admission counsellors throughout your journey with us!
                        </p>
                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">
                            Here's to owning your future!
                        </p>

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px; padding:0;">
                            For any query or clarification, please feel free to contact us and we will be glad to serve
                            you.
                        </p>

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0; padding:0;">
                            Regards, <br>The Craydel team.
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
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/about"
                                                                                 target="_blank"
                                                                                 style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:10px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">About
                                                Us</a></li>
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/contact-us"
                                                                                 target="_blank"
                                                                                 style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:10px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Contact
                                                Us</a></li>
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/terms-of-use"
                                                                                 target="_blank"
                                                                                 style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:0px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Terms
                                                of Use</a></li>
                                    </ul>
                                </td>
                                <td class="gutter_width" width="30" align="left" valign="top">&nbsp;</td>
                                <td class="footer_menu_col" valign="top" align="left">
                                    <ul style="list-style-type: none; margin:0; padding:0;" class="footer_menu">
                                        <li style="display: block; margin:0;"><a
                                                href="https://craydel.com/privacy-policy" target="_blank"
                                                style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:10px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Privacy
                                                Policy</a></li>
                                        <li style="display: block; margin:0;"><a
                                                href="https://craydel.com/cancellation-and-refund-policy"
                                                target="_blank"
                                                style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:10px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Cancellation
                                                &amp; Refund Policy</a></li>
                                        <li style="display: block; margin:0;"><a href="https://craydel.com/partnership"
                                                                                 target="_blank"
                                                                                 style="color:#BABDCA; text-decoration: none; display: block; margin-bottom:0px; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Become
                                                a Partner</a></li>
                                    </ul>
                                </td>
                                <td class="gutter_width" width="30" align="right" valign="top">&nbsp;</td>
                                <td class="footer_menu_col" valign="top" align="right">
                                    <table width="100%" border="0" class="social_connect">
                                        <tbody>
                                        <tr>
                                            <td align="left" valign="top"><a
                                                    href="https://www.facebook.com/Craydel-103615141854770"
                                                    target="_blank"><img
                                                        src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/facebook-logo.png"
                                                        style="display: block; outline: none; border: none;" alt=""></a>
                                            </td>
                                            <td width="5" align="left" valign="top">&nbsp;</td>
                                            <td align="left" valign="top"><a href="https://twitter.com/Craydel_"
                                                                             target="_blank"><img
                                                        src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/twitter.png"
                                                        style="display: block; outline: none; border: none;" alt=""></a>
                                            </td>
                                            <td width="5" align="left" valign="top">&nbsp;</td>
                                            <td align="left" valign="top"><a
                                                    href="https://www.youtube.com/channel/UCtqKevjdarKpdS0NP6u7l_w"
                                                    target="_blank"><img
                                                        src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/youtube.png"
                                                        style="display: block; outline: none; border: none;" alt=""></a>
                                            </td>
                                            <td width="5" align="left" valign="top">&nbsp;</td>
                                            <td align="left" valign="top"><a href="https://www.instagram.com/craydel_"
                                                                             target="_blank"><img
                                                        src="https://craydel.fra1.cdn.digitaloceanspaces.com/edm/instagram.png"
                                                        style="display: block; outline: none; border: none;" alt=""></a>
                                            </td>
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
        <td bgcolor="#1D2D64" height="60" align="center" valign="middle"
            style="color:#b9bcca; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Copyright
            &copy; {{ date('Y') }} Craydel. All Rights Reserved
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
<?php
