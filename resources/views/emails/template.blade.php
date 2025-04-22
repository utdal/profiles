<!DOCTYPE html>
<html lang="en-US">

@php
    use Stevebauman\Purify\Facades\Purify;

    $bg_primary = $settings['primary_color'] ?? '#154734';
    $bg_secondary = $settings['secondary_color'] ?? '#C95100';
    $bg_tertiary = $settings['tertiary_color'] ?? '#34827A';
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        <!--[if (gte mso 9)|(IE)]><!-->
        body { font-family: 'Roboto', 'Segoe UI', Helvetica, Arial, sans-serif !important; }
        <!--<![endif]-->
        a[href], a[href]:visited { color: {{ $bg_primary }}; }
        .footer a[href], .footer a[href]:visited { color: #fff; }
        td.footer-cell a[href] { color: #fff !important; font-size: 16px; text-decoration: underline; }
    </style>
</head>

<body style="font-family:Helvetica,Arial,sans-serif;box-sizing: border-box;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center">
                <table width="800" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; margin:0 auto; border-collapse:collapse;">

                    <!-- Header -->
                    <tr style="height: 70px !important;">
                        <td align="center" style="background-color: {{ $bg_primary }}; padding: 20px 15px;">
                            <a href="{{ url('/') }}" style="text-decoration: none;">
                                <table cellpadding="0" cellspacing="0" border="0" align="center" role="presentation">
                                    <tr>
                                        @if(isset($settings['logo']))
                                        <td style="padding-right: 10px;">
                                            <img src="{{ asset('img/monogram-solid-rgb-full.png') }}" alt="UT Dallas logo" width="70" height="70">
                                        </td>
                                        @endif
                                        <td style="color: #ffffff; font-size: 20px; white-space: nowrap; font-family: Helvetica, Arial, sans-serif;">
                                            {{ $settings['site_title'] ?? 'Profiles' }}
                                        </td>
                                    </tr>
                                </table>
                            </a>
                        </td>
                    </tr>
    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333333; font-size: 16px; line-height: 1.6;">
                            <!-- Message -->
                            @yield('message')
                            <hr style="margin-top: 4em;">
                            <p>This is an automated message from the <a href="{{ url('/') }}" title="{{ $settings['site_title'] ?? 'Profiles' }}">{{ $settings['site_title'] ?? 'Profiles' }}</a> website.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr style="background-color: {{ $bg_tertiary }};">
                        <td colspan="6" style="text-align:center;padding-top:12px"></td>
                    </tr>
                    <tr style="background-color: #919191; padding: 40px; color: #ffffff; font-size: 14px; text-align: center;">
                        <td colspan="6" style="text-align:center;padding:40px 40px;">

                            @if(isset($settings['footer']))
                                @php
                                    $footer = Purify::config('trix_email')->clean($settings['footer']);
                                    $footer = str_replace('<a ', '<a style="color:#ffffff; text-decoration:underline;" ', $footer);
                                @endphp
                                {!! $footer !!}
                            @else
                                Questions? <a href="{{ url('/faq') }}">Check our FAQ</a> or <a href="mailto:{{ config('mail.from.address') }}?subject=Profiles">contact us.</a>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>