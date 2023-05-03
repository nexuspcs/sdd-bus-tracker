<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Australia/Sydney");
$apiEndpoint = 'https://api.transport.nsw.gov.au/v1/tp/';
$apiCall = 'departure_mon'; // Set the location and time parameters
$when = time(); // Now
$stop = "209926"; // Replace with the desired stop ID (testing stop id, is qvb, york st;; 200041) (headland rd slgs stop id is; 209926;;;;;;;  quirk st; 209927) (mona bline; 210323)
$params = array(
    'outputFormat' => 'rapidJSON',
    'coordOutputFormat' => 'EPSG:4326',
    'mode' => 'direct',
    'type_dm' => 'stop',
    'name_dm' => $stop,
    'depArrMacro' => 'dep',
    'itdDate' => date('Ymd', $when),
    'itdTime' => date('Hi', $when),
    'TfNSWDM' => 'true'
);
$url = $apiEndpoint . $apiCall . '?' . http_build_query($params);

// Create a stream for the first API request
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "Authorization: apikey zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL\r\n"
    ]
];

// Perform the first API request and build the JSON response data
$context = stream_context_create($opts);
$response = file_get_contents($url, false, $context);
$json = json_decode($response, true);
$stopEvents = $json['stopEvents'];

// Loop over returned stop events and display on the left of the screen
echo '<div style="float: left; width: 50%;">';
foreach ($stopEvents as $stopEvent) {
    // Extract the route information
    $transportation = $stopEvent['transportation'];
    $routeNumber = $transportation['number'];
    $destination = $transportation['destination']['name'];
    $location = $stopEvent['location'];

    // Check if the departure time is estimated, otherwise fallback to planned time
    if (isset($stopEvent['departureTimeEstimated'])) {
        $time = strtotime($stopEvent['departureTimeEstimated']);
    } else {
        $time = strtotime($stopEvent['departureTimePlanned']);
    }

    $countdown = $time - time();
    $minutes = round($countdown / 60);

    if ($minutes >= 60) {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        echo $hours . "h " . $remainingMinutes . "mins from " . $location['name'] . "<br />";
    } else {
        echo $minutes . "mins from " . $location['name'] . "<br />";
    }
    echo $routeNumber . " to " . $destination . "<br /><br />";
}
echo '</div>';

// Make a second API request
$params['name_dm'] = 'stop2'; // Replace 'stop2' with the desired stop ID for the second request
$url = $apiEndpoint . $apiCall . '?' . http_build_query($params);

// Create a new stream for the second API request
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "Authorization: apikey zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL\r\n"
    ]
];
// Perform the second API request and build the JSON response data
$context = stream_context_create($opts);
$response = file_get_contents($url, false, $context);
$json = json_decode($response, true);
$stopEvents = $json['stopEvents'];

// Loop over returned stop events and display on the right of the screen
echo '<div style="float: right; width: 50%;">';
foreach ($stopEvents as $stopEvent) {
    // Extract the route information
    $transportation = $stopEvent['transportation'];
    $routeNumber = $transportation['number'];
    $destination = $transportation['destination']['name'];
    $location = $stopEvent['location'];
    // Check if the departure time is estimated, otherwise fallback to planned time
    if (isset($stopEvent['departureTimeEstimated'])) {
        $time = strtotime($stopEvent['departureTimeEstimated']);
    } else {
        $time = strtotime($stopEvent['departureTimePlanned']);
    }

    $countdown = $time - time();
    $minutes = round($countdown / 60);

    if ($minutes >= 60) {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        echo $hours . "h " . $remainingMinutes . "mins from " . $location['name'] . "<br />";
    } else {
        echo $minutes . "mins from " . $location['name'] . "<br />";
    }
    echo $routeNumber . " to " . $destination . "<br /><br />";
}
echo '</div>';
