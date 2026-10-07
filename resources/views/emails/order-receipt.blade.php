@extends('emails.layout')

@php
    $money = fn ($n) => ($symbol ?? '£').number_format((float) $n, 2);
    $label = 'font-size:12px; color:#6b7280; padding:7px 0;';
    $value = 'font-size:13px; color:#122d3c; font-weight:600; padding:7px 0; text-align:right;';
@endphp

@section('title', 'Your order confirmation')
@section('preheader', 'Thank you for your order of '.$money($subtotal).'. Order '.$reference.'.')

@section('content')
    <tr>
        <td style="padding-top:30px;">
            <h1 style="margin:0; font-size:22px; line-height:1.3; font-weight:700; color:#122d3c;">Thank you for your order</h1>
            <p style="margin:14px 0 0; font-size:14px; line-height:1.7; color:#374151;">
                Dear {{ $name ?: 'Customer' }},
            </p>
            <p style="margin:10px 0 0; font-size:14px; line-height:1.7; color:#374151;">
                Your payment has been received and your order is confirmed. Every purchase from our shop supports the
                work of {{ config('app.name') }}. Please keep this email for your records.
            </p>
        </td>
    </tr>

    <tr>
        <td style="padding-top:24px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#faf7f2; border:1px solid #ece7e1; border-radius:8px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#6b7280;">Order total</div>
                        <div style="margin-top:4px; font-size:30px; font-weight:700; color:#740a2e;">{{ $money($subtotal) }}</div>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:12px; border-top:1px solid #ece7e1;">
                            <tr><td style="{{ $label }}">Order number</td><td style="{{ $value }}">{{ $reference }}</td></tr>
                            <tr><td style="{{ $label }}">Date</td><td style="{{ $value }}">{{ now()->timezone('Europe/London')->format('j F Y') }}</td></tr>
                            <tr><td style="{{ $label }}">Payment</td><td style="{{ $value }}">Paid via PayPal</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:26px;">
            <div style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#122d3c; padding-bottom:8px; border-bottom:2px solid #740a2e;">Order summary</div>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                @foreach ($items as $item)
                    <tr>
                        <td style="padding:11px 0; border-bottom:1px solid #f0ece6; font-size:14px; color:#122d3c;">
                            {{ $item['name'] }}
                            @if (($item['qty'] ?? 1) > 1)
                                <span style="color:#6b7280;">&times; {{ $item['qty'] }}</span>
                            @endif
                        </td>
                        <td style="padding:11px 0; border-bottom:1px solid #f0ece6; font-size:14px; text-align:right; color:#122d3c; white-space:nowrap;">
                            {{ $money($item['line'] ?? (($item['price'] ?? 0) * ($item['qty'] ?? 1))) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="padding:12px 0 0; font-size:15px; font-weight:700; color:#122d3c;">Total paid</td>
                    <td style="padding:12px 0 0; font-size:15px; font-weight:700; text-align:right; color:#740a2e;">{{ $money($subtotal) }}</td>
                </tr>
            </table>
        </td>
    </tr>

    @if ($address)
        <tr>
            <td style="padding-top:22px;">
                <div style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#122d3c;">Delivery address</div>
                <p style="margin:6px 0 0; font-size:14px; line-height:1.6; color:#374151;">{!! nl2br(e($address)) !!}</p>
            </td>
        </tr>
    @endif

    <tr>
        <td style="padding-top:26px;">
            <p style="margin:0; font-size:14px; line-height:1.7; color:#374151;">
                Our team will be in touch to arrange delivery. If you have any questions, simply reply to this email or contact us at
                <a href="mailto:{{ config('contact.email') }}" style="color:#740a2e; font-weight:600; text-decoration:none;">{{ config('contact.email') }}</a>.
            </p>
            <p style="margin:20px 0 0; font-size:14px; line-height:1.6; color:#374151;">
                With thanks,<br>
                <strong style="color:#122d3c;">The {{ config('app.name') }} Team</strong>
            </p>
        </td>
    </tr>
@endsection
