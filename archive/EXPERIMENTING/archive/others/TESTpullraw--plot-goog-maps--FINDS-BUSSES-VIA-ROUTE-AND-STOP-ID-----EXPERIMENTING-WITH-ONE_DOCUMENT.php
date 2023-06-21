<?php
// Enter your Transport for NSW API key here
$API_KEY = "zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL";

// Enter the bus route and stop ID you want to display
$BUS_ROUTE = "199";
$BUS_STOP_ID = "209913";

function get_next_bus_arrivals_func() {
    global $API_KEY, $BUS_ROUTE, $BUS_STOP_ID;
    $url = "https://api.transport.nsw.gov.au/v1/tp/departure_mon?outputFormat=rapidJSON&mode=direct&serviceLineNoticeFilter=3&coordOutputFormat=EPSG%3A4326&departureMonitorMacro=true&name_dm=$BUS_STOP_ID&itdDateYear=2023&itdDateMonth=05&itdDateDay=02&itdTimeHour=12&itdTimeMinute=00&type_dm=any&nameLine=$BUS_ROUTE";
    $headers = array(
        "Authorization: apikey $API_KEY"
    );
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['stopEvents'];
}

// Call the get_next_bus_arrivals_func function and return the result as JSON
$busArrivals = get_next_bus_arrivals_func();
$busArrivalsJson = json_encode($busArrivals);
?>

<html>
<head>
    <title>Next buses for route <?php echo $BUS_ROUTE; ?> at stop <?php echo $BUS_STOP_ID; ?></title>
    <script>
        function updateBusArrivalsLabel() {
            var busArrivalsLabel = document.getElementById('bus-arrivals-label');
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    var busArrivals = JSON.parse(this.responseText);
                    var busArrivalsText = '';
                    for (var i = 0; i < busArrivals.length; i++) {
                        var event = busArrivals[i];
                        var displayTime = event['displayTime'] || 'unknown';
                        busArrivalsText += displayTime + '\n\n';
                    }
                    busArrivalsLabel.innerText = busArrivalsText.trim();
                }
            };
            xmlhttp.open('GET', 'get_bus_arrivals.php', true);
            xmlhttp.send();
        }

        // Call the updateBusArrivalsLabel function initially
        updateBusArrivalsLabel();

        // Set interval to call the updateBusArrivalsLabel function every 10 seconds
        setInterval(updateBusArrivalsLabel, 10000);
    </script>
</head>
<body>
    <label id="bus-arrivals-label"><?php echo "Fetching bus arrival times..."; ?></label>

    <?php
    // Include the necessary code from the original script
    function get_next_bus_arrivals_func() {
        global $API_KEY, $BUS_ROUTE, $BUS_STOP_ID;
        $url = "https://api.transport.nsw.gov.au/v1/tp/departure_mon?outputFormat=rapidJSON&mode=direct&serviceLineNoticeFilter=3&coordOutputFormat=EPSG%3A4326&departureMonitorMacro=true&name_dm=$BUS_STOP_ID&itdDateYear=2023&it$itdDateMonth=05&itdDateDay=02&itdTimeHour=12&itdTimeMinute=00&type_dm=any&nameLine=$BUS_ROUTE";
        $headers = array(
            "Authorization: apikey $API_KEY"
        );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
        return $data['stopEvents'];
    }
    // Call the get_next_bus_arrivals_func function and return the result as JSON
    $busArrivals = get_next_bus_arrivals_func();
    $busArrivalsJson = json_encode($busArrivals);
    ?>

    <html>
    <head>
        <title>Next buses for route <?php echo $BUS_ROUTE; ?> at stop <?php echo $BUS_STOP_ID; ?></title>
        <script>
            function updateBusArrivalsLabel() {
                var busArrivalsLabel = document.getElementById('bus-arrivals-label');
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        var busArrivals = JSON.parse(this.responseText);
                        var busArrivalsText = '';
                        for (var i = 0; i < busArrivals.length; i++) {
                            var event = busArrivals[i];
                            var displayTime = event['displayTime'] || 'unknown';
                            busArrivalsText += displayTime + '\n\n';
                        }
                        busArrivalsLabel.innerText = busArrivalsText.trim();
                    }
                };
                xmlhttp.open('GET', 'get_bus_arrivals.php', true);
                xmlhttp.send();
            }

            // Call the updateBusArrivalsLabel function initially
            updateBusArrivalsLabel();

            // Set interval to call the updateBusArrivalsLabel function every 10 seconds
            setInterval(updateBusArrivalsLabel, 10000);
        </script>
    </head>
    <body>
        <label id="bus-arrivals-label"><?php echo "Fetching bus arrival times..."; ?></label>

        <script>
            // Pass the busArrivalsJson variable to JavaScript
            var busArrivalsJson = <?php echo $busArrivalsJson; ?>;
        </script>
    </body>
    </html>