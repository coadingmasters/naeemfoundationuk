@php
    $brand = '#740a2e';
    $navy = '#183b4f';
    $navyDark = '#122d3c';
    $cream = '#f4efe6';
    $muted = '#6b7280';
    $money = fn ($n) => ($symbol ?? '£') . number_format((float) $n, 2);
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Your order confirmation</title>
</head>
<body style="margin:0; padding:0; background-color:{{ $cream }}; -webkit-font-smoothing:antialiased; font-family:'Segoe UI', Arial, Helvetica, sans-serif; color:{{ $navyDark }};">

    <!-- Preheader (hidden) -->
    <div style="display:none; max-height:0; overflow:hidden; opacity:0;">
        Thank you for your order of {{ $money($subtotal) }}. Reference {{ $reference }}.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:{{ $cream }};">
        <tr>
            <td align="center" style="padding:28px 12px;">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 6px 24px rgba(18,45,60,0.10);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:{{ $navy }}; padding:28px 32px; text-align:center;">
                            <div style="font-size:20px; font-weight:800; letter-spacing:0.5px; color:#ffffff;">{{ config('app.name') }}</div>
                            <div style="margin-top:4px; font-size:11px; text-transform:uppercase; letter-spacing:2px; color:#e9b9c6;">Building Hopes &amp; Futures</div>
                        </td>
                    </tr>

                    <!-- Success band -->
                    <tr>
                        <td style="padding:32px 32px 8px; text-align:center;">
                            <table role="presentation" cellpadding="0" cellspacing="0" align="center">
                                <tr>
                                    <td style="width:56px; height:56px; background-color:{{ $brand }}; border-radius:9999px; text-align:center; vertical-align:middle; color:#ffffff; font-size:28px; line-height:56px;">&#10003;</td>
                                </tr>
                            </table>
                            <h1 style="margin:18px 0 6px; font-size:24px; color:{{ $navyDark }};">Thank you for your order</h1>
                            <p style="margin:0; font-size:15px; color:{{ $muted }};">
                                {{ $name ?: 'Valued customer' }} — your order has been received and every purchase supports our work.
                            </p>
                        </td>
                    </tr>

                    <!-- Reference -->
                    <tr>
                        <td style="padding:22px 32px 4px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:{{ $cream }}; border-radius:12px;">
                                <tr>
                                    <td style="padding:18px 22px; text-align:center;">
                                        <div style="font-size:12px; text-transform:uppercase; letter-spacing:1px; color:{{ $muted }};">Order total</div>
                                        <div style="margin-top:4px; font-size:32px; font-weight:800; color:{{ $brand }};">{{ $money($subtotal) }}</div>
                                        <div style="margin-top:4px; font-size:12px; color:{{ $muted }};">Reference: <strong style="color:{{ $navyDark }};">{{ $reference }}</strong></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Items -->
                    <tr>
                        <td style="padding:22px 32px 6px;">
                            <div style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:{{ $navy }}; border-bottom:2px solid {{ $brand }}; padding-bottom:8px;">Order summary</div>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:6px;">
                                @foreach ($items as $item)
                                    <tr>
                                        <td style="padding:10px 0; border-bottom:1px solid #eef2f5; font-size:14px; color:{{ $navyDark }};">
                                            <strong>{{ $item['name'] }}</strong>
                                            @if (($item['qty'] ?? 1) > 1)
                                                <span style="color:{{ $muted }};">&times; {{ $item['qty'] }}</span>
                                            @endif
                                        </td>
                                        <td style="padding:10px 0; border-bottom:1px solid #eef2f5; font-size:14px; text-align:right; font-weight:600; color:{{ $navyDark }};">
                                            {{ $money($item['line'] ?? (($item['price'] ?? 0) * ($item['qty'] ?? 1))) }}
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td style="padding:12px 0 0; font-size:16px; font-weight:800; color:{{ $navyDark }};">Total</td>
                                    <td style="padding:12px 0 0; font-size:16px; font-weight:800; text-align:right; color:{{ $brand }};">{{ $money($subtotal) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @if ($address)
                        <tr>
                            <td style="padding:18px 32px 0;">
                                <div style="font-size:12px; text-transform:uppercase; letter-spacing:0.5px; color:{{ $muted }};">Delivery address</div>
                                <p style="margin:4px 0 0; font-size:14px; line-height:1.5; color:{{ $navyDark }};">{!! nl2br(e($address)) !!}</p>
                            </td>
                        </tr>
                    @endif

                    <!-- Message -->
                    <tr>
                        <td style="padding:24px 32px 8px;">
                            <p style="margin:0 0 12px; font-size:14px; line-height:1.65; color:{{ $navyDark }};">
                                Our team will be in touch shortly to confirm your total (including delivery) and arrange secure
                                payment. Please keep this email for your records.
                            </p>
                            <p style="margin:0; font-size:14px; line-height:1.65; color:{{ $navyDark }};">
                                Any questions? Reply to this email or contact us at
                                <a href="mailto:Contact@naeemfoundation.co.uk" style="color:{{ $brand }}; font-weight:600;">Contact@naeemfoundation.co.uk</a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 32px 30px;">
                            <hr style="border:none; border-top:1px solid #eef2f5; margin:0 0 16px;">
                            <p style="margin:0; font-size:12px; line-height:1.6; color:{{ $muted }};">
                                {{ config('app.name') }} — a registered charity in the UK (No. 1199466).<br>
                                2 Falcon Gate, Shire Park, Welwyn Garden City, AL7 1TW, United Kingdom.<br>
                                +44 20 7078 8118
                            </p>
                            <p style="margin:12px 0 0; font-size:11px; color:{{ $muted }};">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>
