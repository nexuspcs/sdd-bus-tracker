<!DOCTYPE html>
<html>
<head>
	<title>Bus Locations</title>
	<!-- Load Leaflet from CDN -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1.3.1/leaflet.css" />
	<script src="https://cdn.jsdelivr.net/leaflet/1.3.1/leaflet.js"></script>
</head>
<body>
	<!-- Create a map container with a specified height -->
	<div id="mapid" style="height: 500px;"></div>

	<script>
		// Create a Leaflet map centered at Sydney with zoom level 12
		var mymap = L.map('mapid').setView([-33.865, 151.209], 12);

		// Load the OpenStreetMap tiles from the CDN
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
		}).addTo(mymap);

		// Perform the curl request and display the buses on the map
		var xhr = new XMLHttpRequest();
		xhr.open("GET", "https://api.transport.nsw.gov.au/v1/gtfs/vehiclepos/buses?debug=true");
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Authorization", "apikey m5uvuKOQtsngacQIQemt0LqzC8Xq7nVxECVp");
		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4 && xhr.status === 200) {
				var data = JSON.parse(xhr.responseText);
				// Get the latitude and longitude for the first 10 buses
				var buses = data.entity.slice(0, 10).map(function(entity) {
					return [entity.vehicle.position.latitude, entity.vehicle.position.longitude];
				});
				// Add the bus markers to the map
				for (var i = 0; i < buses.length; i++) {
					L.marker(buses[i]).addTo(mymap);
				}
			}
		};
		xhr.send();
	</script>
</body>
</html>
