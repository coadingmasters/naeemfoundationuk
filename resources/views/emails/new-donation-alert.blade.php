@extends('emails.layout')

@php
    $audience = 'internal';
    $money = fn ($n) => $currencySymbol.number_format((float) $n, 2);
    $d = $details;
    $name = trim(($d['first_name'] ?? '').' '.($d['last_name'] ?? ''));
    $freqWord = ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly'];
    $label = 'font-size:12px; color:#6b7280; padding:8px 12px 8px 0; border-bottom:1px solid #f0ece6; width:36%; vertical-align:top;';
    $value = 'font-size:13px; color:#122d3c; font-weight:600; padding:8px 0; border-bottom:1px solid #f0ece6;';
@endphp

@section('title', 'New donation received')
@section('preheader', 'New '.($recurring ? 'regular' : 'one-off').' donation of '.$money($total).' from '.($name ?: 'a donor').' — '.$reference.'.')

@section('content')
    <tr>
        <td style="padding-top:28px;">
            <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#740a2e;">Website notification</div>
            <h1 style="margin:6px 0 0; font-size:22px; line-height:1.3; font-weight:700; color:#122d3c;">New donation received</h1>
        </td>
    </tr>

    <tr>
        <td style="padding-top:18px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#faf7f2; border:1px solid #ece7e1; border-radius:8px;">
                <tr>
                    <td style="padding:18px 22px;">
                        <div style="font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#6b7280;">{{ $recurring ? 'Regular gift — first payment' : 'One-off gift' }}</div>
                        <div style="margin-top:4px; font-size:30px; font-weight:700; color:#740a2e;">{{ $money($total) }} <span style="font-size:13px; font-weight:600; color:#6b7280;">{{ $currency }}</span></div>
                        <div style="margin-top:6px; font-size:12px; color:#6b7280;">
                            Ref <strong style="color:#122d3c;">{{ $reference }}</strong> &middot; {{ now()->timezone('Europe/London')->format('j M Y, H:i') }} UK time
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:24px;">
            <div style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#122d3c; padding-bottom:8px; border-bottom:2px solid #740a2e;">Donation</div>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                @foreach ($items as $item)
                    <tr>
                        <td style="padding:10px 0; border-bottom:1px solid #f0ece6; font-size:14px; color:#122d3c;">
                            {{ $item['cause'] }}
                            @if (($item['qty'] ?? 1) > 1)
                                <span style="color:#6b7280;">&times; {{ $item['qty'] }}</span>
                            @endif
                            <span style="display:inline-block; margin-left:4px; padding:1px 7px; border-radius:10px; background-color:#f6e8ec; color:#740a2e; font-size:11px; font-weight:600;">{{ $freqWord[$item['frequency'] ?? ''] ?? 'One-off' }}</span>
                        </td>
                        <td style="padding:10px 0; border-bottom:1px solid #f0ece6; font-size:14px; text-align:right; color:#122d3c; white-space:nowrap;">{{ $money(($item['amount'] ?? 0) * ($item['qty'] ?? 1)) }}</td>
                    </tr>
                @endforeach
                @if ((float) $fee > 0)
                    <tr>
                        <td style="padding:8px 0 0; font-size:13px; color:#6b7280;">Fee covered by donor</td>
                        <td style="padding:8px 0 0; font-size:13px; text-align:right; color:#122d3c;">{{ $money($fee) }}</td>
                    </tr>
                @endif
                <tr>
                    <td style="padding:10px 0 0; font-size:15px; font-weight:700; color:#122d3c;">Total</td>
                    <td style="padding:10px 0 0; font-size:15px; font-weight:700; text-align:right; color:#740a2e;">{{ $money($total) }}</td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:24px;">
            <div style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#122d3c; padding-bottom:8px; border-bottom:2px solid #740a2e;">Donor</div>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr><td style="{{ $label }}">Name</td><td style="{{ $value }}">{{ $name ?: '—' }}</td></tr>
                <tr><td style="{{ $label }}">Email</td><td style="{{ $value }}"><a href="mailto:{{ $d['email'] ?? '' }}" style="color:#740a2e; text-decoration:none;">{{ $d['email'] ?? '—' }}</a></td></tr>
                <tr><td style="{{ $label }}">Phone</td><td style="{{ $value }}">{{ $d['phone'] ?? '—' }}</td></tr>
                <tr><td style="{{ $label }}">Address</td><td style="{{ $value }}">{{ collect([$d['billing_address'] ?? null, $d['city'] ?? null, $d['postcode'] ?? null])->filter()->implode(', ') ?: '—' }}</td></tr>
                @if (! empty($d['organisation_name']))
                    <tr><td style="{{ $label }}">On behalf of</td><td style="{{ $value }}">{{ $d['organisation_name'] }}</td></tr>
                @endif
                <tr><td style="{{ $label }}">Gift Aid</td><td style="{{ $value }}">{{ ! empty($d['gift_aid']) ? 'Yes — declared' : 'No' }}</td></tr>
                <tr><td style="{{ $label }}">{{ $recurring ? 'PayPal subscription' : 'PayPal transaction' }}</td><td style="{{ $value }}">{{ $paymentId ?: '—' }}</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:26px;">
            <a href="{{ config('contact.website') }}/admin/donations?q={{ urlencode($reference) }}" style="display:inline-block; background-color:#740a2e; color:#ffffff; text-decoration:none; font-weight:600; font-size:14px; padding:11px 22px; border-radius:6px;">View in admin</a>
            <p style="margin:14px 0 0; font-size:12px; line-height:1.6; color:#6b7280;">Reply to this email to contact the donor directly.</p>
        </td>
    </tr>
@endsection
