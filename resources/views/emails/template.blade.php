<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
</head>

<body style="font-family:Helvetica,Arial,sans-serif;box-sizing: border-box;">

	<table style="margin:auto;padding:0;max-width:800px;border-collapse: collapse;">
		<tbody>

			<!-- Header -->

			<tr style="margin:0;padding:0;width:100%;">
				<td colspan="3" style="background:#E98300;margin:0;padding:20px 15px;width:50%;color:#fff;font-size:24px;font-weight:300;">
					<a style="float:left;color:#fff;text-decoration:none;" href="https://www.utdallas.edu/">UT Dallas</a>
				</td>
				<td colspan="3" style="background:#E98300;margin:0;padding:20px 15px;width:50%;color:#fff;font-size:24px;font-weight:300;">
					<a style="float:right;color:#fff;text-decoration:none;" href="https://research.utdallas.edu">Research</a>
				</td>
			</tr>
			<tr style="margin:0;padding:0;width:100%;">
				<td colspan="6" style="background:#008542;margin:0;height:5px;width:100%;"></td>
			</tr>

			<tr style="margin:0;padding:0;width:100%;">
				<td colspan="6" style="background:#69BE28;margin:0;height:15px;width:100%;"></td>
			</tr>
			

			<!-- Body -->

			<tr style="margin:0;padding:0;width:100%;">
				<td colspan="6" style="background:#fff;margin:0;padding:5%;width:100%;">

					<!-- Message -->

					@yield('message')
					
					<p>This is an automated message from the <a href="{{ url('/') }}" title="link">UT Dallas Profiles</a> web application.</p>

					<p>Sincerely,</p>

					<p>Office of Research</p>
				</td>
			</tr>

			<!-- Footer -->

			<tr style="color:#fff;background:#E98300;margin:0;padding:0;width:100%;width:100%;font-size:12px;">
				<td colspan="3" style="width:40%;text-align:center;padding:15px;">
					<a style="color:#fff;text-decoration:none;" href="https://www.utdallas.edu/">The University of Texas at Dallas</a><br>
					800 West Campbell Rd, Richardson, TX 75080 | (972) 883-2111
				</td>
				<td colspan="3" style="width:20%;text-align:center;padding:15px;">
					Questions or comments? Contact:<br>
					<a style="color:#fff;text-decoration:none;font-weight:bold;" href="mailto:oris@utdallas.edu">
						oris@utdallas.edu
					</a>
				</td>
			</tr>
		</tbody>
	</table>
</body>

</html>