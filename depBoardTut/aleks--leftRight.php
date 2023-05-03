<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
date_default_timezone_set("Australia/Sydney");
$apiEndpoint = 'https://api.transport.nsw.gov.au/v1/tp/';
$apiCall = 'departure_mon'; // Set the location and time parameters
$when = time(); // Now
$stopIds = array("209926", "209927"); // Replace with the desired stop IDs
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

// Create a stream
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "Authorization: apikey zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL\r\n"
    ]
];

foreach ($stopIds as $stop) {
    $params['name_dm'] = $stop;
    $params['itdDate'] = date('Ymd', $when);
    $params['itdTime'] = date('Hi', $when);
    $url = $apiEndpoint . $apiCall . '?' . http_build_query($params);

    // Perform the request and build the JSON response data
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    $json = json_decode($response, true);
    $stopEvents = $json['stopEvents'];

    // Loop over returned stop events
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
            echo $hours . "h " . $remainingMinutes . "mins from " . $location['name'] . "\n<br />";
        } else {
            echo $minutes . "mins from " . $location['name'] . "\n<br />";
        }
        echo $routeNumber . " to " . $destination . "\n\n<br /><br />";
    }
}