<?php

// Enter your Transport for NSW API key here
$API_KEY = "zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL";

// Enter the bus route and stop ID you want to display
$BUS_ROUTE = "199";
$BUS_STOP_ID = "209913";

// Define a function to retrieve the next bus arrival times
function get_next_bus_arrivals() {
    global $API_KEY, $BUS_ROUTE, $BUS_STOP_ID;
    
    $url = "https://api.transport.nsw.gov.au/v1/tp/departure_mon?outputFormat=rapidJSON&mode=direct&serviceLineNoticeFilter=3&coordOutputFormat=EPSG%3A4326&departureMonitorMacro=true&name_dm=".$BUS_STOP_ID."&itdDateYear=2023&itdDateMonth=05&itdDateDay=02&itdTimeHour=12&itdTimeMinute=00&type_dm=any&nameLine=".$BUS_ROUTE;
    $headers = array("Authorization: apikey " . $API_KEY);
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($curl);
    curl_close($curl);
    
    $data = json_decode($response, true);
    return $data['stopEvents'];
}

// Create a window to display the bus arrival times
echo "<html><body>";
echo "<h1>Next buses for route ".$BUS_ROUTE." at stop ".$BUS_STOP_ID."</h1>";

// Create a label to display the bus arrival times
echo "<p id='bus_arrivals_label'>Fetching bus arrival times...</p>";

// Define a function to update the bus arrival times label
echo "<script>";
echo "function updateBusArrivalsLabel() {";
echo "    var busArrivals = " . json_encode(get_next_bus_arrivals()) . ";";
echo "    var busArrivalsText = '';";
echo "    for (var i = 0; i < busArrivals.length; i++) {";
echo "        var event = busArrivals[i];";
echo "        var displayTime = event['displayTime'] || 'unknown';";
echo "        var name = event['transportation']['disassembledName'] || 'unknown';";
echo "        busArrivalsText += name + ': ' + displayTime + '<br>';";
echo "    }";
echo "    document.getElementById('bus_arrivals_label').innerHTML = busArrivalsText;";
echo "    setTimeout(updateBusArrivalsLabel, 10000);"; // Update the bus arrival times every 10 seconds
echo "}";
echo "updateBusArrivalsLabel();";
echo "</script>";
echo "</body></html>";

?>
