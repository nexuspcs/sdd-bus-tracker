const API_KEY = "m5uvuKOQtsngacQIQemt0LqzC8Xq7nVxECVp";
const BUS_ROUTE = "your_bus_route_number";
const busDataDiv = document.getElementById("bus-data");


fetch(`https://api.transport.nsw.gov.au/v1/gtfs/vehiclepos/buses?routeNumber=${BUS_ROUTE}`, {
	headers: {
		"Authorization": `apikey ${API_KEY}`
	}
})
.then(response => response.json())
.then(data => {
	console.log(data);
	// Display data on the website
});

data.entity.forEach(bus => {
	const busDiv = document.createElement("div");
	busDiv.innerText = `Bus ID: ${bus.vehicle.id}, Lat: ${bus.vehicle.position.latitude}, Lon: ${bus.vehicle.position.longitude}`;
	busDataDiv.appendChild(busDiv);
});
