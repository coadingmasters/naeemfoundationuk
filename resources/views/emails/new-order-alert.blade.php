@extends('emails.layout')

@php
    $audience = 'internal';
    $money = fn ($n) => $symbol.number_format((float) $n, 2);
    $d = $details;
    $label = 'font-size:12px; color:#6b7280; padding:8px 12px 8px 0; border-bottom:1px solid #f0ece6; width:36%; vertical-align:top;';
    $value = 'font-size:13px; color:#122d3c; font-weight:600; padding:8px 0; border-bottom:1px solid #f0ece6;';
@endphp

@section('title', 'New shop order received')
@section('preheader', 'New shop order of '.$money($subtotal).' from '.($d['name'] ?? 'a customer').' — '.$reference.'.')

@section('content')
    <tr>
        <td style="padding-top:28px;">
            <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#740a2e;">Website notification</div>
            <h1 style="margin:6px 0 0; font-size:22px; line-height:1.3; font-weight:700; color:#122d3c;">New shop order received</h1>
        </td>
    </tr>

    <tr>
        <td style="padding-top:18px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#faf7f2; border:1px solid #ece7e1; border-radius:8px;">
                <tr>
                    <td style="padding:18px 22px;">
                        <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#6b7280;">Order total — paid</div>
                        <div style="margin-top:4px; font-size:30px; font-weight:700; color:#740a2e;">{{ $money($subtotal) }} <span style="font-size:13px; font-weight:600; color:#6b7280;">{{ $currency }}</span></div>
                        <div style="margin-top:6px; font-size:12px; color:#6b7280;">
                            Order <strong style="color:#122d3c;">{{ $reference }}</strong> &middot; {{ now()->timezone('Europe/London')->format('j M Y, H:i') }} UK time
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:24px;">
            <div style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#122d3c; padding-bottom:8px; border-bottom:2px solid #740a2e;">Items</div>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                @foreach ($items as $item)
                    <tr>
                        <td style="padding:10px 0; border-bottom:1px solid #f0ece6; font-size:14px; color:#122d3c;">{{ $item['name'] }} <span style="color:#6b7280;">&times; {{ $item['qty'] }}</span></td>
                        <td style="padding:10px 0; border-bottom:1px solid #f0ece6; font-size:14px; text-align:right; color:#122d3c; white-space:nowrap;">{{ $money($item['line']) }}</td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:24px;">
            <div style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#122d3c; padding-bottom:8px; border-bottom:2px solid #740a2e;">Customer &amp; delivery</div>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr><td style="{{ $label }}">Name</td><td style="{{ $value }}">{{ $d['name'] ?? '—' }}</td></tr>
                <tr><td style="{{ $label }}">Email</td><td style="{{ $value }}"><a href="mailto:{{ $d['email'] ?? '' }}" style="color:#740a2e; text-decoration:none;">{{ $d['email'] ?? '—' }}</a></td></tr>
                <tr><td style="{{ $label }}">Phone</td><td style="{{ $value }}">{{ $d['phone'] ?? '—' }}</td></tr>
                <tr><td style="{{ $label }}">Deliver to</td><td style="{{ $value }}">{{ collect([$d['address'] ?? null, $d['postcode'] ?? null])->filter()->implode(', ') ?: '—' }}</td></tr>
                <tr><td style="{{ $label }}">PayPal transaction</td><td style="{{ $value }}">{{ $paymentId ?: '—' }}</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:26px;">
            <a href="{{ config('contact.website') }}/admin/orders" style="display:inline-block; background-color:#740a2e; color:#ffffff; text-decoration:none; font-weight:600; font-size:14px; padding:11px 22px; border-radius:6px;">View orders in admin</a>
            <p style="margin:14px 0 0; font-size:12px; line-height:1.6; color:#6b7280;">Reply to this email to contact the customer directly.</p>
        </td>
    </tr>
@endsection
