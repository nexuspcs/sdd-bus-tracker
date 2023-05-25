<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Bus Positions</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta name="description" content="Bus Positions">
	<meta name="author" content="ChatGPT">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAysQG08FsOhtF0XyWN2UfjrpzHCQmsO6A"></script>
	<style>
		#map {
			height: 400px;
			width: 100%;
		}
	</style>
</head>
<body>
	<h1>Bus Positions</h1>
	<div id="map"></div>
	<script>
		function initMap() {
			const map = new google.maps.Map(document.getElementById("map"), {
				zoom: 12,
				center: { lat: -33.86785, lng: 151.20732 }, // center of Sydney
			});

			const url = "https://api.transport.nsw.gov.au/v1/gtfs/vehiclepos/buses?debug=true";
			const headers = {
				"Accept": "application/json",
				"Authorization": "apikey m5uvuKOQtsngacQIQemt0LqzC8Xq7nVxECVp",
			};

			fetch(url, { headers })
				.then(response => response.json())
				.then(data => {
					const entities = data.entity.slice(0, 10); // first 10 buses
					entities.forEach(entity => {
						const { latitude, longitude } = entity.vehicle.position;
						new google.maps.Marker({
							position: { lat: latitude, lng: longitude },
							map,
							title: entity.id,
						});
					});
				})
				.catch(error => console.error(error));
		}
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAysQG08FsOhtF0XyWN2UfjrpzHCQmsO6A&callback=initMap"></script>
    <?php
    // Perform the curl request and display the map
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.transport.nsw.gov.au/v1/gtfs/vehiclepos/buses?debug=true",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "Accept: text/plain",
            "Authorization: apikey m5uvuKOQtsngacQIQemt0LqzC8Xq7nVxECVp"
        ),
    ));
    $response = curl_exec($curl);
    var_dump($response);
    curl_close($curl);

    $data = json_decode($response, true);
    ?>
</body>
</html>
