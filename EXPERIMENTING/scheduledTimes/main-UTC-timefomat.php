<?php


// TIMES ARE IN ISO 8601 FORMAT -- google it
// Enter your Transport for NSW API key here
$API_KEY = "zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL";

// Enter the bus route and stop ID you want to display
$BUS_STOP_ID = "209926";

// Define a function to retrieve the next bus arrival times
function get_next_bus_arrivals($API_KEY, $BUS_STOP_ID) {
    $url = "https://api.transport.nsw.gov.au/v1/tp/departure_mon?outputFormat=rapidJSON&mode=direct&serviceLineNoticeFilter=3&coordOutputFormat=EPSG%3A4326&departureMonitorMacro=true&name_dm={$BUS_STOP_ID}&itdTimeHour=12&itdTimeMinute=00&type_dm=any&nameLine=";
    $headers = ["Authorization: apikey {$API_KEY}"];
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => implode("\r\n", $headers),
            "ignore_errors" => true,
        ]
    ];
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    $data = json_decode($response, true);
    return $data['stopEvents'];
}

function update_bus_arrivals_label($API_KEY, $BUS_STOP_ID) {
    $bus_arrivals = get_next_bus_arrivals($API_KEY, $BUS_STOP_ID);
    $bus_arrivals_text = "";
    foreach ($bus_arrivals as $event) {
        if (isset($event['departureTimePlanned'])) {
            $departureTimePlanned = $event['departureTimePlanned'];
        } else {
            $departureTimePlanned = "unknown";
        }
        if (isset($event['transportation']['disassembledName'])) {
            $name = $event['transportation']['disassembledName'];
        } else {
            $name = "unknown";
        }
        $bus_arrivals_text .= "{$name}: {$departureTimePlanned}<br>";
    }
    return $bus_arrivals_text;
// }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Next buses at stop <?php echo $BUS_STOP_ID; ?></title>
    <script>
        function refreshBusArrivals() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("bus_arrivals_label").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "bus_arrivals.php", true);
            xhttp.send();
            setTimeout(refreshBusArrivals, 10000); // Update the bus arrival times every 10 seconds
        }
    </script>
</head>
<body onload="refreshBusArrivals();">
    <h1>Next buses at stop <?php echo $BUS_STOP_ID; ?></h1>
    <div id="bus_arrivals_label">Fetching bus arrival times...</div>
</body>
</html>
