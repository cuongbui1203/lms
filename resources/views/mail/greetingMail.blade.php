<!DOCTYPE html PUBLIC "-W3CDTD XHTML 1.0 Transitional EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr">

<head>
    <title>{{ $title }}</title>
    <style>
        a {
            outline: none;
        }
    </style> <!--[if gte mso 9]>
<xml>
<o:OfficeDocumentSettings>
<o:AllowPNG/>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
<![endif]-->
    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important
        }

        body,
        table,
        td,
        div,
        span,
        a,
        li,
        img {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            box-sizing: border-box
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important
        }

        #outlook a {
            padding: 0
        }

        .yshortcuts a {
            border-bottom: none !important
        }

        img {
            display: inline-block;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            line-height: 12px;
            color: #919293
        }

        .footer img {
            font-size: 8px
        }

        table {
            border-spacing: 0
        }

        table td {
            border-collapse: collapse
        }

        table a {
            text-decoration: none
        }

        table p a.texthover:hover {
            text-decoration: underline !important
        }

        .button-wrap.inline {
            display: inline-block;
            padding: 0 5px
        }

        .cta-button {
            transition: all .2s ease
        }

        .cta-button:hover {
            color: white !important;
            background-color: black !important
        }

        table.main {
            margin: 0 auto;
            width: 100%;
            min-width: 300px;
            max-width: 900px;
            color: white;
            background-color: black
        }

        .header .headerCredit {
            color: rgba(255, 255, 255, 0.7) !important
        }

        .header .headerCredit a:hover {
            color: #00e59b !important
        }

        .footer a {
            text-decoration: underline !important;
            color: inherit;
            text-decoration: none;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit
        }

        .footer a:hover {
            color: #00e59b !important
        }

        .footer p {
            margin: 0 auto;
            max-width: 550px;
            padding: 0px 0px 20px;
            box-sizing: border-box
        }

        @media only screen and (max-width: 700px) {
            .headerLetterhead .logo {
                width: 100px !important;
                height: 25px !important
            }

            h1.mobileStyle {
                font-size: 26px !important;
                line-height: 36px !important
            }

            p {
                font-size: 16px !important;
                line-height: 24px !important
            }

            .block-notice p {
                font-size: 12px !important;
                line-height: 18px !important
            }

            .mobile-center {
                text-align: center !important
            }

            .maincontent .button-wrap {
                text-align: center
            }

            .maincontent .button-wrap .cta-button {
                width: 90%;
                max-width: 280px
            }

            .button-wrap.inline {
                display: inline-block;
                padding: 0 2px
            }

            .cta-button {
                margin: 0 auto
            }

            .headerWrap {
                height: 120px !important
            }

            .header {
                background-size: 200% !important
            }

            .header td,
            .header td.headerSecond,
            .header td.headerThird {
                display: block !important;
                width: 100% !important;
                float: left !important
            }

            .header td.headerFirst {
                padding: 30px 30px 0px 30px !important
            }

            .header .headerButton {
                padding: 0 0px 20px 30px !important
            }

            .header .headerCredit {
                padding: 0 10px 5px 25px !important;
                text-align: left !important
            }

            .header .headline {
                font-size: 22px !important;
                line-height: 28px !important
            }

            .maincontent {
                padding: 0 20px 30px 20px !important;
                min-height: 380px
            }

            .maincontent td {
                display: block !important;
                width: 100% !important
            }

            .maincontent table.art-blocks td {
                padding: 0px 0px 0px 0px !important
            }

            .maincontent .art-block {
                min-height: 160px !important
            }

            .maincontent .artist-block {
                width: 48% !important
            }

            .maincontent .artist-block .username {
                font-size: 16px !important
            }

            .maincontent .border-top {
                margin: 0 !important
            }

            .maincontent div.term-block {
                margin: 0 auto;
                max-width: 332px;
                padding: 0px 30px 30px 30px !important
            }

            .maincontent div.term-block img {
                max-width: 100px !important
            }

            .footer {
                padding: 20px 4px 20px 4px !important
            }

            .footer p {
                font-size: 12px !important;
                line-height: 20px !important;
                padding: 0px 0px 12px 0px !important
            }

            .footer a:hover {
                color: #00e59b !important
            }
        }

        @supports (display: flex) {
            .art-block {
                display: flex !important;
                line-height: 1 !important
            }
        }

        .footer span {
            color: #66737C !important
        }

        @media only screen and (max-width: 700px) {
            .header {
                background-size: cover !important
            }

            .header td.headerLetterhead {
                display: table-cell !important;
                float: none !important;
                height: 140px !important
            }

            .maincontent {
                padding-top: 0px !important
            }

            .maincontent h1 {
                font-size: 24px !important;
                line-height: 32px !important;
                padding-bottom: 20px !important
            }
        }
    </style>
</head>

<body
    style="background-color:#e1e2e3;margin:0;padding:0;min-width:100%;width:100%;height:100%;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;line-height:0;font-size:0;">
    <table class="main"
        style="table-layout:fixed;border-spacing:0;border-collapse:collapse;width:100%;max-width:680px;min-width:320px;margin:0 auto;background-color:black;"
        border="0" cellpadding="0" cellspacing="0" width="680">
        <tr>
            <td class="headerLetterhead"
                style="padding-bottom:0px;padding-top:0px;width:100%;max-width:680px;height:auto;background-image:url(https://www.da-files.com/emailmarketing/2020/global/bg-letterhead.png?9147116777);background-color:#ffffff;background-repeat:no-repeat!important;background-position:center top;background-size:cover;border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;"
                align="left" valign="middle">

                <table style="table-layout:auto;border-spacing:0;width:100%;margin:0 auto;" border="0"
                    cellpadding="0" cellspacing="0" width="680">
                    <tr>
                        <td class="headerWrap"
                            style="height:200px;width:100%;text-align:center;padding:20px 5% 20px 5%;background-color:transparent;"
                            align="center" valign="middle">
                            <a href='#homePage' style="display:block; ">
                                <img class="logo"
                                    src="https://cdn.discordapp.com/attachments/1008257463465820194/1239879053725401150/image.png?ex=664486da&is=6643355a&hm=98290ad75da4274d1824ab8f4f52d1d819b08a03220991efbd127e5b835e91bc&"
                                    style="margin:0 auto 0 auto;width:135px;height:34px;display:block;" width="135"
                                    height="34" alt="Logo" />

                            </a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td style="padding:0px 0px 0px 0px;background-color:#ffffff;color:#000000;" align="left" valign="top">
                <div class="maincontent" style="padding:10px 7% 55px 7%;">
                    <table style="table-layout:fixed;border-spacing:0;width:100%;margin:0 auto;" border="0"
                        cellpadding="0" cellspacing="0" width="680">
                        <tr>
                            <td style="padding:0 0 0 0;background-color:#ffffff;color:#000000;text-align:center;"
                                colspan="2">
                                <h1
                                    style="font-family:Arial, Helvetica, sans-serif;font-size:30px;line-height:40px;font-weight:bold;margin:0px 0px 0px 0px;padding:0px 0px 30px 0px;text-align:center;">
                                    Chào mừng đến với {{ env('APP_NAME') }}!</h1>
                                <p
                                    style="font-family:Arial, Helvetica, sans-serif;font-size:18px;line-height:30px;font-weight:bold;color:#000000 !important;margin:0px 0px 0px 0px;padding:0px 0px 16px 0px;text-align:center;">
                                    Hi {{ $name }},</p>
                                <p
                                    style="font-family:Arial, Helvetica, sans-serif;font-size:18px;line-height:30px;color:#121314;margin:0px auto 0px auto;padding:0px 0px 0 0px;text-align:center;max-width:500px !important;">
                                    Cảm ơn bạn đã đăng ký nhận email từ chúng tôi. Chúng tôi rất vui mừng chào đón bạn
                                    đến với cộng đồng {{ env('APP_NAME') }}!</p>


                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="footer" style="padding:30px 30px 30px 30px;text-align:center;color:#67737b;">
                    <div class="wrap" style="margin:0 auto 0 auto;max-width:340px !important;">
                        <div class="footer-mkt" style="color:#ffffff;">
                            <p
                                style="font-family:Arial, Helvetica, sans-serif;font-size:12px;line-height:20px;letter-spacing:-.5px;">
                                Questions? Check out our <a href="#help"
                                    style="color:#ffffff;text-decoration:underline;font-size:12px;line-height:20px;">Help
                                    Center</a>&nbsp;to learn more.</p>
                        </div>
                        <p style="font-family:Arial, Helvetica, sans-serif;font-size:12px!important;line-height:20px;">
                            <span
                                style="padding-bottom:0px;font-weight:normal;font-size:12px;color:#66737C !important;text-decoration:none;">{{ env('COMPANY_ADDRESS', 'Hn') }}<br /><br /><a
                                    href="https://www.deviantart.com?quix=3Dfooter"
                                    style="color:#67737b;text-decoration:underline;font-size:12px;line-height:20px;">
                                    {{ '' }}</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#policy"
                                    style="color:#66737C;text-decoration:underline;">Privacy Policy</a></span>
                        </p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <img src="https://sg.deviantart.com/wf/open?upn=3Du001.nYjxqYgeb9lkA3377NQy8ZlXZ44yxXgH1HhXa9SY5b-2BxrLUKieBU-2FvdoMvMDpRivIDyT3yDrDQGG5jPu8-2BwMP-2BrkBI8THV0wy3lorVnpZ9u3NXMnR61wBK-2BG88Bb-2FmdAYAcoeSFEeJYKn5VajitHX5iE2927-2B9F3Nn3roJict5aVRxBSpV8syiSBUIsqdTswcrRMc-2F2Gjr8r2mJ4FWkd2yQsfh0qZpFwDe7KcyUKACIZVANZftr3eq5EfK-2FSxrWwj8MYnnEsg98uL8HD-2Fm3EmuNtRZs-2BeaDfAua5e9-2BvUFbZIzZ7iXg0l1-2BD8UQLXcwxWXMdq7vURv9WkAQMBgLrXw-3D-3D"
        alt="" width="1" height="1" border="0"
        style="height:1px !important;width:1px !important;border-width:0 !important;margin-top:0 !important;margin-bottom:0 !important;margin-right:0 !important;margin-left:0 !important;padding-top:0 !important;padding-bottom:0 !important;padding-right:0 !important;padding-left:0 !important;" />
</body>

</html>
