<!DOCTYPE html>
<html>
<head>
    <title>Bus Positions Map</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body onload="initMap()">
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

    <div id="map" style="height: 400px;">Map could not be loaded.</div>
    <script>
        function initMap() {
            alert("initMap")
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {lat: -33.865143, lng: 151.209900} // Sydney CBD
            });

            // Add a marker for each bus position
            var busPositions = <?php echo json_encode($data['entity']); ?>;
            for (var i = 0; i < busPositions.length; i++) {
                var bus = busPositions[i].vehicle;
                var marker = new google.maps.Marker({
                    position: {lat: bus.position.latitude, lng: bus.position.longitude},
                    map: map,
                    title: bus.trip.route_id + ' - ' + bus.trip.trip_headsign
                });
            }
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAysQG08FsOhtF0XyWN2UfjrpzHCQmsO6A&callback=initMap"></script>
</body>
</html>
