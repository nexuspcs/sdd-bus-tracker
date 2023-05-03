<!DOCTYPE html>
<!-- This code currently does the EXACT same as the following curl command:
curl -X GET --header 'Accept: text/plain' --header 'Authorization: apikey m5uvuKOQtsngacQIQemt0LqzC8Xq7nVxECVp' 'https://api.transport.nsw.gov.au/v1/gtfs/vehiclepos/buses?debug=true'\n

*however, it simply prints it to a webpage. to see it, use the following PHP server command in the same directory as this file:
php -S localhost:9000

*then using a browser, access the following URL:
http://localhost:9000/TESTpullraw.php
http://localhost:9000/TESTpullraw.php?debug=true
-->

<html>
<head>
	<meta charset="utf-8">
	<title>Bus Positions - TEST Pull Raw (no Plot)</title>
	<meta http-equiv="refresh" content="7"> <!-- refresh every 7 seconds -->
</head>
<body>
	<h1>Bus Positions</h1>
	<?php
		$url = "https://api.transport.nsw.gov.au/v1/gtfs/vehiclepos/buses?debug=true";
		$headers = array(
			"Accept: text/plain",
			"Authorization: apikey m5uvuKOQtsngacQIQemt0LqzC8Xq7nVxECVp"
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$output = curl_exec($ch);
		curl_close($ch);

		echo "<pre>" . htmlspecialchars($output) . "</pre>";

		
	?>
</body>
</html>
