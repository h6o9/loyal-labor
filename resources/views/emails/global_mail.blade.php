@php
    $appName = loyalBrandName();
    $logoUrl = loyalEmailLogoUrl();
    $primary = '#FE7701';
    $primaryLight = '#fff4eb';
    $emailTitle = loyalSanitizeBrandText($mail_subject);
    $emailTitle = preg_replace('/^' . preg_quote($appName, '/') . '\s*[-–]\s*/iu', '', $emailTitle);
    $emailTitle = trim($emailTitle) !== '' ? trim($emailTitle) : loyalSanitizeBrandText($mail_subject);
    $year = date('Y');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ loyalSanitizeBrandText($mail_subject) }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f2f5;font-family:Arial,Helvetica,sans-serif;-webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f0f2f5;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td align="center" style="background-color:{{ $primary }};padding:36px 32px 28px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="background-color:#ffffff;border-radius:10px;padding:14px 20px;">
                                        <img src="{{ $logoUrl }}" alt="{{ $appName }}" width="180" style="display:block;max-width:180px;height:auto;border:0;">
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:20px 0 6px;font-size:22px;font-weight:700;color:#ffffff;line-height:1.3;">{{ $emailTitle }}</p>
                            <p style="margin:0;font-size:14px;color:#ffe4cc;line-height:1.5;">{{ $appName }}</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px 36px 28px;background-color:#ffffff;">
                            <div style="font-size:15px;line-height:1.7;color:#444444;">
                                {!! clean(loyalSanitizeBrandText($mail_message)) !!}
                            </div>

                            @if (!empty($link))
                                @foreach ($link as $key => $url)
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:28px auto 0;">
                                        <tr>
                                            <td align="center" style="border-radius:6px;background-color:{{ $primary }};">
                                                <a href="{{ $url }}" target="_blank" style="display:inline-block;padding:14px 32px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:6px;background-color:{{ $primary }};">{{ $key }}</a>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                            @endif
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="padding:24px 32px;background-color:{{ $primaryLight }};border-top:1px solid #fde0c8;">
                            <p style="margin:0 0 6px;font-size:15px;font-weight:700;color:{{ $primary }};">{{ $appName }}</p>
                            <p style="margin:0;font-size:12px;color:#999999;">&copy; {{ $year }} {{ $appName }}. {{ __('All rights reserved.') }}</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
