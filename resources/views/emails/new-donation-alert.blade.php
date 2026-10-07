@php
    $brand = '#740a2e';
    $navy = '#183b4f';
    $navyDark = '#122d3c';
    $cream = '#f4efe6';
    $muted = '#6b7280';
    $money = fn ($n) => $currencySymbol.number_format((float) $n, 2);
    $d = $details;
    $name = trim(($d['first_name'] ?? '').' '.($d['last_name'] ?? ''));
    $freqWord = fn ($f) => ['daily' => 'every day', 'weekly' => 'every week', 'monthly' => 'every month', 'yearly' => 'every year'][$f] ?? 'one-off';
    $row = fn ($label, $value) => '<tr><td style="padding:7px 0; border-bottom:1px solid #eef2f5; font-size:13px; color:'.$muted.'; width:38%;">'.e($label).'</td><td style="padding:7px 0; border-bottom:1px solid #eef2f5; font-size:14px; color:'.$navyDark.'; font-weight:600;">'.$value.'</td></tr>';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New donation</title>
</head>
<body style="margin:0; padding:0; background-color:{{ $cream }}; font-family:'Segoe UI', Arial, Helvetica, sans-serif; color:{{ $navyDark }};">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:{{ $cream }};">
        <tr>
            <td align="center" style="padding:28px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden;">
                    <tr>
                        <td style="background-color:{{ $navy }}; padding:22px 32px;">
                            <div style="font-size:12px; text-transform:uppercase; letter-spacing:2px; color:#e9b9c6;">{{ config('app.name') }} — website</div>
                            <div style="margin-top:4px; font-size:20px; font-weight:800; color:#ffffff;">New donation received</div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 32px 4px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:{{ $cream }}; border-radius:12px;">
                                <tr>
                                    <td style="padding:16px 22px;">
                                        <div style="font-size:12px; text-transform:uppercase; letter-spacing:1px; color:{{ $muted }};">Amount {{ $recurring ? '(recurring)' : '' }}</div>
                                        <div style="margin-top:2px; font-size:30px; font-weight:800; color:{{ $brand }};">{{ $money($total) }} <span style="font-size:14px; color:{{ $muted }};">{{ $currency }}</span></div>
                                        <div style="margin-top:2px; font-size:12px; color:{{ $muted }};">Reference <strong style="color:{{ $navyDark }};">{{ $reference }}</strong> · {{ now()->timezone('Europe/London')->format('j M Y, H:i') }} UK time</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <div style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:{{ $navy }}; border-bottom:2px solid {{ $brand }}; padding-bottom:8px;">Donation</div>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:4px;">
                                @foreach ($items as $item)
                                    <tr>
                                        <td style="padding:8px 0; border-bottom:1px solid #eef2f5; font-size:14px;">
                                            <strong>{{ $item['cause'] }}</strong>
                                            @if (($item['qty'] ?? 1) > 1) <span style="color:{{ $muted }};">&times; {{ $item['qty'] }}</span> @endif
                                            <span style="color:{{ $brand }}; font-size:12px;"> — {{ $freqWord($item['frequency'] ?? 'one-off') }}</span>
                                        </td>
                                        <td style="padding:8px 0; border-bottom:1px solid #eef2f5; font-size:14px; text-align:right; font-weight:600;">{{ $money(($item['amount'] ?? 0) * ($item['qty'] ?? 1)) }}</td>
                                    </tr>
                                @endforeach
                                @if ($fee > 0)
                                    <tr>
                                        <td style="padding:8px 0; border-bottom:1px solid #eef2f5; font-size:13px; color:{{ $muted }};">Fee covered by donor</td>
                                        <td style="padding:8px 0; border-bottom:1px solid #eef2f5; font-size:14px; text-align:right;">{{ $money($fee) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding:10px 0 0; font-size:15px; font-weight:800;">Total</td>
                                    <td style="padding:10px 0 0; font-size:15px; font-weight:800; text-align:right; color:{{ $brand }};">{{ $money($total) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <div style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:{{ $navy }}; border-bottom:2px solid {{ $brand }}; padding-bottom:8px;">Donor</div>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:4px;">
                                {!! $row('Name', e($name ?: '—')) !!}
                                {!! $row('Email', '<a href="mailto:'.e($d['email'] ?? '').'" style="color:'.$brand.';">'.e($d['email'] ?? '—').'</a>') !!}
                                {!! $row('Phone', e($d['phone'] ?? '—')) !!}
                                {!! $row('Address', e(collect([$d['billing_address'] ?? null, $d['city'] ?? null, $d['postcode'] ?? null])->filter()->implode(', ') ?: '—')) !!}
                                @if (! empty($d['organisation_name']))
                                    {!! $row('On behalf of', e($d['organisation_name'])) !!}
                                @endif
                                {!! $row('Gift Aid', ! empty($d['gift_aid']) ? 'Yes' : 'No') !!}
                                {!! $row($recurring ? 'PayPal subscription' : 'PayPal transaction', e($paymentId ?: '—')) !!}
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 32px 28px;">
                            <a href="{{ route('admin.donations.index', ['q' => $reference]) }}" style="display:inline-block; background-color:{{ $brand }}; color:#ffffff; text-decoration:none; font-weight:700; font-size:14px; padding:11px 20px; border-radius:8px;">View in admin</a>
                            <p style="margin:16px 0 0; font-size:12px; line-height:1.6; color:{{ $muted }};">
                                Automatic notification from the {{ config('app.name') }} website. Reply to this email to contact the donor directly.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
