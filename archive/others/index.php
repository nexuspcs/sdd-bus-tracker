<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
date_default_timezone_set("Australia/Sydney");
$apiEndpoint = 'https://api.transport.nsw.gov.au/v1/tp/';
$apiCall = 'departure_mon'; // Set the location and time parameters
$when = time(); // Now
$stop = '209926'; // Domestic Airport Station // Build the request parameters
$params = array( 'outputFormat' => 'rapidJSON', 'coordOutputFormat' => 'EPSG:4326', 'mode' => 'direct', 'type_dm' => 'stop', 'name_dm' => $stop, 'depArrMacro' => 'dep', 'itdDate' => date('Ymd', $when), 'itdTime' => date('Hi', $when), 'TfNSWDM' => 'true' );
$url = $apiEndpoint . $apiCall . '?' . http_build_query($params);

echo "test before stream/request" . PHP_EOL;
// Create a stream
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "Authorization: apikey m5uvuKOQtsngacQIQemt0LqzC8Xq7nVxECVp"
    ]
];

// Perform the request and build the JSON response data
$context = stream_context_create($opts);
$response = file_get_contents($url, false, $context);
$json = json_decode($response, true);

if (isset($json['stopEvents'])) {
    $stopEvents = $json['stopEvents'];
    // Loop over returned stop events
    foreach ($stopEvents as $stopEvent) {
        // Extract the route information
        $transportation = $stopEvent['transportation'];
        $routeNumber = $transportation['number'];
        $destination = $transportation['destination']['name']; // In the case of a train, the location includes platform information
        $location = $stopEvent['location']; // Determine how many minutes until departure
        $time = strtotime($stopEvent['departureTimePlanned']);
        $countdown = $time - time();
        $minutes = round($countdown / 60); // Output the stop event with a countdown timer
        echo $minutes . "m from " . $location['name'] . "\n<br />";
        echo $routeNumber . " to " . $destination . "\n\n<br /><br />";
    }
} else {
    // Handle the case where stopEvents is missing
    echo "stopEvents is missing in JSON response";
}

echo "test (last line of code...)" . PHP_EOL;
