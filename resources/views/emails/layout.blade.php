{{--
    Shared shell for every outgoing email. Child views set:
      @section('title')      <title> / accessibility label
      @section('preheader')  inbox preview line (hidden in the body)
      @section('content')    the email body rows (<tr>…</tr>)
    and may pass $audience = 'internal' for staff-only notifications.
--}}
@php
    $c = config('contact');
    $tel = fn ($n) => 'tel:'.preg_replace('/[^+0-9]/', '', $n);
    $internal = ($audience ?? null) === 'internal';
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="color-scheme" content="light">
    <title>@yield('title')</title>
</head>
<body style="margin:0; padding:0; background-color:#f2f0ec; -webkit-font-smoothing:antialiased; font-family:'Segoe UI', Roboto, Arial, Helvetica, sans-serif; color:#1f2a33;">

    <div style="display:none; max-height:0; overflow:hidden; opacity:0; mso-hide:all;">@yield('preheader')</div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f2f0ec;">
        <tr>
            <td align="center" style="padding:32px 12px;">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:100%; max-width:600px;">

                    {{-- Letterhead --}}
                    <tr>
                        <td style="background-color:#ffffff; border-top:4px solid #740a2e; border-radius:10px 10px 0 0; padding:24px 36px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:middle; width:60px;">
                                        <img src="{{ $c['website'] }}/images/logo.png" width="52" height="52" alt="{{ config('app.name') }}" style="display:block; width:52px; height:52px; border:0;">
                                    </td>
                                    <td style="vertical-align:middle; padding-left:12px;">
                                        <div style="font-size:18px; font-weight:700; color:#122d3c; letter-spacing:0.2px;">{{ config('app.name') }}</div>
                                        <div style="margin-top:2px; font-size:11px; letter-spacing:1.5px; text-transform:uppercase; color:#8a6b74;">Building Hopes &amp; Futures</div>
                                    </td>
                                    <td align="right" style="vertical-align:middle; font-size:11px; line-height:1.5; color:#6b7280;">
                                        Registered Charity<br>
                                        <strong style="color:#122d3c;">No. {{ $c['charity_no'] }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#ffffff; padding:0 36px;">
                            <div style="border-top:1px solid #ece7e1; font-size:0; line-height:0;">&nbsp;</div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="background-color:#ffffff; padding:0 36px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                @yield('content')
                            </table>
                        </td>
                    </tr>

                    {{-- Contact footer --}}
                    <tr>
                        <td style="background-color:#122d3c; border-radius:0 0 10px 10px; padding:26px 36px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:top; font-size:12px; line-height:1.7; color:#c9d3da; padding-bottom:14px;">
                                        <div style="font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#ffffff; margin-bottom:6px;">Contact us</div>
                                        Donation line: <a href="{{ $tel($c['phone']) }}" style="color:#ffffff; text-decoration:none;">{{ $c['phone'] }}</a><br>
                                        Mobile / WhatsApp: <a href="{{ $tel($c['mobile']) }}" style="color:#ffffff; text-decoration:none;">{{ $c['mobile'] }}</a><br>
                                        Email: <a href="mailto:{{ $c['email'] }}" style="color:#ffffff; text-decoration:none;">{{ $c['email'] }}</a><br>
                                        Web: <a href="{{ $c['website'] }}" style="color:#ffffff; text-decoration:none;">{{ preg_replace('#^https?://#', '', $c['website']) }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top; font-size:12px; line-height:1.7; color:#c9d3da; padding-bottom:14px;">
                                        <div style="font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#ffffff; margin-bottom:6px;">Registered office</div>
                                        {!! implode('<br>', array_map('e', $c['address'])) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:1px solid rgba(255,255,255,0.12); padding-top:14px; font-size:11px; line-height:1.6; color:#8fa1ad;">
                                        @if ($internal)
                                            Internal notification from the {{ config('app.name') }} website — not sent to the donor.
                                        @else
                                            {{ config('app.name') }} is a registered charity in the UK (No. {{ $c['charity_no'] }}).
                                            You are receiving this email because of an action you took on our website.
                                        @endif
                                        <br>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>
</html>
