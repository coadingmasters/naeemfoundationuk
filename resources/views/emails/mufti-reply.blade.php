@extends('emails.layout')

@section('title', 'A response to your question')
@section('preheader', 'A scholar from '.config('app.name').' has responded to your question.')

@section('content')
    <tr>
        <td style="padding-top:30px;">
            <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#740a2e;">Ask a Mufti</div>
            <h1 style="margin:6px 0 0; font-size:22px; line-height:1.3; font-weight:700; color:#122d3c;">A response to your question</h1>
            <p style="margin:14px 0 0; font-size:14px; line-height:1.7; color:#374151;">
                Assalamu alaikum {{ $name }},
            </p>
            <p style="margin:10px 0 0; font-size:14px; line-height:1.7; color:#374151;">
                Thank you for reaching out to our scholars. Please find our response to your question below.
            </p>
        </td>
    </tr>

    <tr>
        <td style="padding-top:22px;">
            <div style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6b7280; margin-bottom:8px;">Your question</div>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-left:3px solid #d8cfc4; background-color:#faf7f2; border-radius:4px;">
                <tr><td style="padding:14px 16px; font-size:14px; line-height:1.7; color:#374151;">{!! nl2br(e($question)) !!}</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:22px;">
            <div style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#122d3c; margin-bottom:8px;">Our response</div>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-left:3px solid #740a2e; background-color:#ffffff; border-top:1px solid #ece7e1; border-right:1px solid #ece7e1; border-bottom:1px solid #ece7e1; border-radius:4px;">
                <tr><td style="padding:16px 18px; font-size:15px; line-height:1.75; color:#122d3c;">{!! nl2br(e($answer)) !!}</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding-top:22px;">
            <p style="margin:0; font-size:13px; line-height:1.7; color:#4b5563;">
                We pray this response is of benefit to you. For complex or personal matters we recommend consulting a
                scholar in person. Please do not share confidential personal details by email.
            </p>
            <p style="margin:20px 0 0; font-size:14px; line-height:1.6; color:#374151;">
                May Allah reward you,<br>
                <strong style="color:#122d3c;">The Ask a Mufti Team</strong><br>
                <span style="color:#6b7280;">{{ config('app.name') }}</span>
            </p>
        </td>
    </tr>
@endsection
