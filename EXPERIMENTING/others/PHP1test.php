<?php
// Enter your Transport for NSW API key here
$API_KEY = "zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL";

// Enter the bus route and stop ID you want to display
$BUS_ROUTE = "199";
$BUS_STOP_ID = "209913";

// Define a function to retrieve the next bus arrival times
function get_next_bus_arrivals() {
    global $API_KEY, $BUS_ROUTE, $BUS_STOP_ID;
    $url = "https://api.transport.nsw.gov.au/v1/tp/departure_mon?outputFormat=rapidJSON&mode=direct&serviceLineNoticeFilter=3&coordOutputFormat=EPSG%3A4326&departureMonitorMacro=true&name_dm=$BUS_STOP_ID&itdDateYear=2023&itdDateMonth=05&itdDateDay=02&itdTimeHour=12&itdTimeMinute=00&type_dm=any&nameLine=$BUS_ROUTE";
    $headers = ["Authorization: apikey $API_KEY"];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['stopEvents'];
}

// Create a window to display the bus arrival times
echo "<html><body><h2>Next buses for route $BUS_ROUTE at stop $BUS_STOP_ID</h2>";

// Create a label to display the bus arrival times
echo "<p id='bus-arrivals-label'>Fetching bus arrival times...</p>";

// Define a function to update the bus arrival times label
function update_bus_arrivals_label() {
    $bus_arrivals = get_next_bus_arrivals();
    $bus_arrivals_text = "";
    foreach ($bus_arrivals as $event) {
        if (array_key_exists('displayTime', $event)) {
            $display_time = $event['displayTime'];
        } else {
            $display_time = "unknown";
        }
        if (array_key_exists('transportation', $event) && array_key_exists('disassembledName', $event['transportation'])) {
            $name = $event['transportation']['disassembledName'];
        } else {
            $name = "unknown";
        }
        $bus_arrivals_text .= "$name: $display_time<br>";
    }
    echo "<script>document.getElementById('bus-arrivals-label').innerHTML='$bus_arrivals_text';</script>";
    sleep(10);  // Wait for 10 seconds before updating the bus arrival times
    update_bus_arrivals_label();  // Call this function again to update the bus arrival times
}

// Start updating the bus arrival times label
update_bus_arrivals_label();

// End the HTML page
echo "</body></html>";
?>