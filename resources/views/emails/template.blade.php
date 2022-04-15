<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
</head>

<!-- Using colors from the settings -->
@php
		$bg_primary = $settings['primary_color'] ?? '#008542';
		$bg_secondary = $settings['secondary_color'] ?? '#008542';
		$bg_tertiary = $settings['tertiary_color'] ?? '#008542';
@endphp

<body style="font-family:Helvetica,Arial,sans-serif;box-sizing: border-box;">

	<table style="margin:auto;padding:0;max-width:800px;border-collapse: collapse;">
		<tbody>

			<!-- Header -->
			<tr style="margin:0;padding:0;width:100%;background:{{ $bg_primary }};">
				<td colspan="6" style="text-align:center;margin:0;padding:20px 15px;width:100%;color:#fff;">
					<a style="color:#fff;text-decoration:none;" title="{{ $settings['site_title'] ?? 'UT Dallas Profiles' }}" href="{{ url('/') }}">
						<img style="height:70px;" class="profiles-logo" src="{{ $settings['logo'] ?? asset('img/UTDmono_rev.svg') }}" alt="Logo">
						<h4>{{ $settings['site_title'] ?? 'UT Dallas Profiles' }}</h4>
					</a>
				</td>
			</tr>

			<!-- Body -->

			<tr style="margin:0;padding:0;width:100%;">
				<td colspan="6" style="background:#fff;margin:0;padding:5%;width:100%;">

					<!-- Message -->

					@yield('message')
					
					<p>This is an automated message from the <a href="{{ url('/') }}" title="{{ $settings['site_title'] ?? 'UT Dallas Profiles' }}">{{ $settings['site_title'] ?? 'UT Dallas Profiles' }}</a> web application.</p>

					<p>Sincerely,</p>

					<p>Office of Research and Innovation</p>
				</td>
			</tr>

			<!-- Footer -->

			<tr style="color:#fff;margin:0;padding:0;width:100%;font-size:12px;background:{{ $bg_primary }};">
				<td colspan="6" style="width:100%;text-align:center;padding-top:12px">
				</td>
			</tr>
			<tr style="color:#fff;margin:0;padding:0;width:100%;font-size:16px;background:#C1C1BC;">
				<td colspan="6" style="width:100%;text-align:center;padding:40px 40px;">
					@if(isset($settings['footer']))
						{!! $settings['footer'] !!}
					@else
						Questions?<br><a href="/faq">Check our FAQ</a> or <a href="mailto:email@example.com?subject=Profiles">contact us.</a><br><br>
						<a href="https://example.com">Example Link</a><br><br>
						<a href="https://example.com">Example Institution</a><br>
					@endif
				</td>
			</tr>
		</tbody>
	</table>
</body>

</html>