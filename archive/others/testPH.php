<?php

// Enter your Transport for NSW API key here
$API_KEY = "zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL";

// Enter the bus route and stop ID you want to display
$BUS_ROUTE = "199";
$BUS_STOP_ID = "209913";

// Define a function to retrieve the next bus arrival times
function get_next_bus_arrivals() {
    global $API_KEY, $BUS_ROUTE, $BUS_STOP_ID;
    $url = "https://api.transport.nsw.gov.au/v1/tp/departure_mon?outputFormat=rapidJSON&mode=direct&serviceLineNoticeFilter=3&coordOutputFormat=EPSG%3A4326&departureMonitorMacro=true&name_dm={$BUS_STOP_ID}&itdDateYear=2023&itdDateMonth=05&itdDateDay=02&itdTimeHour=12&itdTimeMinute=00&type_dm=any&nameLine={$BUS_ROUTE}";
    $headers = ["Authorization: apikey {$API_KEY}"];
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => implode("\r\n", $headers),
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $data = json_decode($response, true);
    return $data['stopEvents'];
}

// Function to display the bus arrival times as HTML
function display_bus_arrivals() {
    $bus_arrivals = get_next_bus_arrivals();
    $bus_arrivals_text = "";
    foreach ($bus_arrivals as $event) {
        $display_time = isset($event['displayTime']) ? $event['displayTime'] : "unknown";
        $name = isset($event['transportation']['disassembledName']) ? $event['transportation']['disassembledName'] : "unknown";
        $bus_arrivals_text .= "{$name}: {$display_time}<br>";
    }
    return $bus_arrivals_text;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Next buses for route <?= $BUS_ROUTE ?> at stop <?= $BUS_STOP_ID ?></title>
    <script>
        function refreshBusArrivals() {
            location.reload();
        }
        setTimeout(refreshBusArrivals, 10000); // Update the bus arrival times every 10 seconds
    </script>
</head>
<body>
    <h1>Next buses for route <?= $BUS_ROUTE ?> at stop <?= $BUS_STOP_ID ?></h1>
    <div><?= display_bus_arrivals() ?></div>
</body>
</html>
