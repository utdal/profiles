<!DOCTYPE html>
<html lang="en-US">

@php
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
    </style>
</head>

<body style="font-family:Helvetica,Arial,sans-serif;box-sizing: border-box;">

    <table style="margin:auto;padding:0;max-width:800px;border-collapse: collapse;">
        <tbody>

            <!-- Header -->
            <tr style="margin:0;padding:0;width:100%;background:{{ $bg_primary }};">
                <td colspan="6" style="text-align:center;margin:0;padding:20px 15px;width:100%;color:#fff;">
                    <a style="display:flex; align-items: center; justify-content: center; color:#fff;text-decoration:none;" title="{{ $settings['site_title'] ?? 'Profiles' }}" href="{{ url('/') }}">
                        @if(isset($settings['logo']))
                        <img style="height:70px; margin-right:1rem;" class="profiles-logo" src="{{ $settings['logo'] }}" alt="Logo">
                        @endif
                        <span style="font-size:1.25rem; white-space:nowrap;">{{ $settings['site_title'] ?? 'Profiles' }}</span>
                    </a>
                </td>
            </tr>

            <!-- Body -->

            <tr style="margin:0;padding:0;width:100%;">
                <td colspan="6" style="background:#fff;margin:0;padding:5%;width:100%;">

                    <!-- Message -->

                    @yield('message')
                    
                    <hr style="margin-top: 4em;">

                    <p>This is an automated message from the <a href="{{ url('/') }}" title="{{ $settings['site_title'] ?? 'Profiles' }}">{{ $settings['site_title'] ?? 'Profiles' }}</a> website.</p>

                </td>
            </tr>

            <!-- Footer -->

            <tr style="color:#fff;margin:0;padding:0;width:100%;font-size:12px;background:{{ $bg_tertiary }};">
                <td colspan="6" style="width:100%;text-align:center;padding-top:12px">
                </td>
            </tr>
            <tr style="color:#fff;margin:0;padding:0;width:100%;font-size:16px;background:#919191;background-image:url('{{asset('/img/60-lines.png')}}');" class="footer">
                <td colspan="6" style="width:100%;text-align:center;padding:40px 40px;">
                    @if(isset($settings['footer']))
                        {!! $settings['footer'] !!}
                    @else
                        Questions? <a href="{{ url('/faq') }}">Check our FAQ</a> or <a href="mailto:{{ config('mail.from.address') }}?subject=Profiles">contact us.</a>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</body>

<style>
    tr.footer td{
        > ul {
            list-style: none;

            > li {
                display: inline;
            }
        }
    }
</style>

</html>