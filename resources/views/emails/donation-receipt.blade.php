@extends('emails.layout')

@php
    $sym = $currencySymbol ?? '£';
    $money = fn ($n) => $sym.number_format((float) $n, 2);
    $freqWord = ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly'];
    $isRecurring = collect($items)->contains(fn ($i) => isset($freqWord[$i['frequency'] ?? '']));
    $label = 'font-size:12px; color:#6b7280; padding:7px 0;';
    $value = 'font-size:13px; color:#122d3c; font-weight:600; padding:7px 0; text-align:right;';
@endphp

@section('title', 'Your donation receipt')
@section('preheader', 'JazakAllahu khairan — we have received your donation of '.$money($total).'. Receipt '.$reference.'.')

@section('content')
    <tr>
        <td style="padding-top:30px;">
            <h1 style="margin:0; font-size:22px; line-height:1.3; font-weight:700; color:#122d3c;">Thank you for your donation</h1>
            <p style="margin:14px 0 0; font-size:14px; line-height:1.7; color:#374151;">
                Dear {{ $name ?: 'Supporter' }},
            </p>
            <p style="margin:10px 0 0; font-size:14px; line-height:1.7; color:#374151;">
                JazakAllahu khairan for your generous donation to {{ config('app.name') }}. Your gift has been received
                and will go directly towards helping families in need. Please keep this email as your receipt.
            </p>
        </td>
    </tr>

    {{-- Amount + receipt details --}}
    <tr>
        <td style="padding-top:24px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#faf7f2; border:1px solid #ece7e1; border-radius:8px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#6b7280;">Amount donated</div>
                        <div style="margin-top:4px; font-size:30px; font-weight:700; color:#740a2e;">{{ $money($total) }}</div>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:12px; border-top:1px solid #ece7e1;">
                            <tr><td style="{{ $label }}">Receipt number</td><td style="{{ $value }}">{{ $reference }}</td></tr>
                            <tr><td style="{{ $label }}">Date</td><td style="{{ $value }}">{{ now()->timezone('Europe/London')->format('j F Y') }}</td></tr>
                            <tr><td style="{{ $label }}">Payment method</td><td style="{{ $value }}">PayPal</td></tr>
                            <tr><td style="{{ $label }}">Type</td><td style="{{ $value }}">{{ $isRecurring ? 'Regular gift' : 'One-off gift' }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Breakdown --}}
    <tr>
        <td style="padding-top:26px;">
            <div style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#122d3c; padding-bottom:8px; border-bottom:2px solid #740a2e;">Donation summary</div>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                @foreach ($items as $item)
                    <tr>
                        <td style="padding:11px 0; border-bottom:1px solid #f0ece6; font-size:14px; color:#122d3c;">
                            {{ $item['cause'] }}
                            @if (($item['qty'] ?? 1) > 1)
                                <span style="color:#6b7280;">&times; {{ $item['qty'] }}</span>
                            @endif
                            @if (isset($freqWord[$item['frequency'] ?? '']))
                                <span style="display:inline-block; margin-left:4px; padding:1px 7px; border-radius:10px; background-color:#f6e8ec; color:#740a2e; font-size:11px; font-weight:600;">{{ $freqWord[$item['frequency']] }}</span>
                            @endif
                        </td>
                        <td style="padding:11px 0; border-bottom:1px solid #f0ece6; font-size:14px; text-align:right; color:#122d3c; white-space:nowrap;">
                            {{ $money(($item['amount'] ?? 0) * ($item['qty'] ?? 1)) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="padding:10px 0 0; font-size:13px; color:#6b7280;">Subtotal</td>
                    <td style="padding:10px 0 0; font-size:13px; text-align:right; color:#122d3c;">{{ $money($subtotal) }}</td>
                </tr>
                @if ((float) $fee > 0)
                    <tr>
                        <td style="padding:6px 0 0; font-size:13px; color:#6b7280;">Transaction fee (kindly covered by you)</td>
                        <td style="padding:6px 0 0; font-size:13px; text-align:right; color:#122d3c;">{{ $money($fee) }}</td>
                    </tr>
                @endif
                <tr>
                    <td style="padding:12px 0 0; font-size:15px; font-weight:700; color:#122d3c;">Total</td>
                    <td style="padding:12px 0 0; font-size:15px; font-weight:700; text-align:right; color:#740a2e;">{{ $money($total) }}</td>
                </tr>
            </table>
        </td>
    </tr>

    @if ($giftAid)
        <tr>
            <td style="padding-top:22px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-left:3px solid #740a2e; background-color:#f6e8ec; border-radius:4px;">
                    <tr>
                        <td style="padding:13px 16px; font-size:13px; line-height:1.6; color:#122d3c;">
                            <strong>Gift Aid declared.</strong> As a UK taxpayer, your donation can be worth 25% more to us at no extra
                            cost to you. Please let us know if your tax status changes.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    @if ($isRecurring)
        <tr>
            <td style="padding-top:16px;">
                <p style="margin:0; font-size:13px; line-height:1.6; color:#4b5563;">
                    <strong style="color:#122d3c;">About your regular gift:</strong> PayPal will collect it automatically on schedule
                    until you choose to stop. You can cancel at any time from your PayPal account or by contacting us.
                </p>
            </td>
        </tr>
    @endif

    {{-- Sign-off --}}
    <tr>
        <td style="padding-top:26px;">
            <p style="margin:0; font-size:14px; line-height:1.7; color:#374151;">
                If you have any questions about your donation, simply reply to this email or contact us at
                <a href="mailto:{{ config('contact.email') }}" style="color:#740a2e; font-weight:600; text-decoration:none;">{{ config('contact.email') }}</a>.
            </p>
            <p style="margin:20px 0 0; font-size:14px; line-height:1.6; color:#374151;">
                With gratitude,<br>
                <strong style="color:#122d3c;">The {{ config('app.name') }} Team</strong>
            </p>
        </td>
    </tr>
@endsection
