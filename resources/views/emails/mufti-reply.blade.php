@php
    $brand = '#740a2e';
    $navy = '#183b4f';
    $navyDark = '#122d3c';
    $cream = '#f4efe6';
    $muted = '#6b7280';
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>A response to your question</title>
</head>
<body style="margin:0; padding:0; background-color:{{ $cream }}; -webkit-font-smoothing:antialiased; font-family:'Segoe UI', Arial, Helvetica, sans-serif; color:{{ $navyDark }};">

    <div style="display:none; max-height:0; overflow:hidden; opacity:0;">
        A scholar from {{ config('app.name') }} has responded to your question.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:{{ $cream }};">
        <tr>
            <td align="center" style="padding:28px 12px;">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 6px 24px rgba(18,45,60,0.10);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:{{ $navy }}; padding:28px 32px; text-align:center;">
                            <div style="font-size:20px; font-weight:800; letter-spacing:0.5px; color:#ffffff;">{{ config('app.name') }}</div>
                            <div style="margin-top:4px; font-size:11px; text-transform:uppercase; letter-spacing:2px; color:#e9b9c6;">Ask a Mufti</div>
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="padding:32px 32px 8px;">
                            <p style="margin:0; font-size:16px; font-weight:700; color:{{ $navyDark }};">Assalamu alaikum {{ $name }},</p>
                            <p style="margin:10px 0 0; font-size:14px; line-height:1.6; color:{{ $muted }};">
                                Thank you for reaching out to our scholars. In response to the question you submitted, please find our reply below.
                            </p>
                        </td>
                    </tr>

                    <!-- Your question -->
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <p style="margin:0 0 8px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:{{ $brand }};">Your question</p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-left:3px solid {{ $brand }}; background-color:{{ $cream }}; border-radius:6px;">
                                <tr><td style="padding:14px 16px; font-size:14px; line-height:1.6; color:{{ $navyDark }};">{!! nl2br(e($question)) !!}</td></tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Our response -->
                    <tr>
                        <td style="padding:24px 32px 0;">
                            <p style="margin:0 0 8px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:{{ $navy }};">Our response</p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb; border-radius:8px;">
                                <tr><td style="padding:18px 18px; font-size:15px; line-height:1.7; color:{{ $navyDark }};">{!! nl2br(e($answer)) !!}</td></tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Note -->
                    <tr>
                        <td style="padding:22px 32px 0;">
                            <p style="margin:0; font-size:13px; line-height:1.6; color:{{ $muted }};">
                                We pray this response is of benefit to you. For complex or personal matters we recommend consulting a scholar in person. May Allah accept your efforts and reward you.
                            </p>
                        </td>
                    </tr>

                    <!-- Sign off -->
                    <tr>
                        <td style="padding:22px 32px 30px;">
                            <p style="margin:0; font-size:14px; font-weight:700; color:{{ $navyDark }};">The Ask a Mufti team</p>
                            <p style="margin:2px 0 0; font-size:13px; color:{{ $muted }};">{{ config('app.name') }}</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:{{ $navyDark }}; padding:20px 32px; text-align:center;">
                            <p style="margin:0; font-size:12px; color:rgba(255,255,255,0.7);">{{ config('app.name') }}</p>
                            <p style="margin:6px 0 0; font-size:11px; color:rgba(255,255,255,0.45);">This is a response to a question you submitted on our website. Please do not share personal or confidential details by email.</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
