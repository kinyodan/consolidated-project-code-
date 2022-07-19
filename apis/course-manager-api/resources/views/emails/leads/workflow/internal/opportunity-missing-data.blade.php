<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Internal communication</title>
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
<table width="600" summary="Welcome to the Craydel family!" bgcolor="#FFFFFF" border="0" align="center" cellpadding="0" cellspacing="0" class="email_container">
    <!-- Visually Hidden Preheader Text : BEGIN -->
    <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">
        Internal communication
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
                            @if(!empty($user_first_name))
                                Hi {{ $user_first_name }}
                            @else
                                Hi
                            @endif
                        </h2>

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 15px; padding:0;">
                            You have moved an opportunity to <b>{{ $current_opportunity_stage }}</b>, however the following details are missing.
                        </p>

                        @if(!empty($opportunity_id))
                            <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 15px; padding:0;">
                                Opportunity Link: <a href="https://crm.zoho.com/crm/org747566265/tab/Potentials/{{ $opportunity_id }}" target="_blank">{{ $opportunity_name }}</a>
                            </p>
                        @endif

                        @if(isset($missing_information))
                            <ul style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 30px 20px; padding:0;">
                                @foreach($missing_information as $record)
                                    <li>{{ $record }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; line-height: 20px; margin:0 0 15px; padding:0;">
                                Unable to fetch the missing records.
                            </p>
                        @endif

                        <p style="color:#191919; font-family:'Helvetica Neue', Helvetica, Arial; font-size:15px; font-weight: bold; line-height: 20px; margin:0 0 15px; padding:0;">
                            Note: The opportunity has reverted to the previous stage
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
        <td bgcolor="#1D2D64" height="60" align="center" valign="middle" style="color:#b9bcca; font-family:'Helvetica Neue', Helvetica, Arial; font-size:13px;">Copyright &copy; {{ date('Y') }} Craydel. All Rights Reserved</td>
    </tr>
    </tbody>
</table>
</body>
</html>
