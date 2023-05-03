<!DOCTYPE html>
<html>
<head>
<title>SLGS Bus Tracker</title>
</head>
<body>

<style>
        .bus-info {
            color: red;
            font-weight: bold;
            display: none;
        }
    </style>

<h2 class="bus-info">test</h2>

</body>



</html>







<?php
error_reporting(E_ALL); ini_set('display_errors', 1); // tells php to show all errors - good for debugging
date_default_timezone_set("Australia/Sydney"); // default in PHP is UTC, which is not useful for a bus tracking application in Sydney...

//

$apiEndpoint = 'https://api.transport.nsw.gov.au/v1/tp/';
$apiCall = 'departure_mon'; // Set the location and time parameters
$when = time(); // Now
$stopIds = array("209926", "2000133", "209927"); // Replace with the desired stop IDs (209926 = Headland RD, 209927 = Quirk ST). (2000133 = Lang Park, York St, SYD CBD, using for testing purposes)
$stop = ""; // Initialize the variable with an empty string
$retryAttempts = 3;
$retryDelay = 0; // Delay in seconds if the API does not return data, after the time in seconds, it will cancel request.

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

// Define a variable to keep track of whether to show the class
$showClass = false;

foreach ($stopIds as $stop) {
    $params['name_dm'] = $stop;
    $params['itdDate'] = date('Ymd', $when);
    $params['itdTime'] = date('Hi', $when);
    $url = $apiEndpoint . $apiCall . '?' . http_build_query($params);

    $attempt = 0;
    $success = false;
    while ($attempt < $retryAttempts && !$success) {
        // Perform the request and build the JSON response data
        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);
        $json = json_decode($response, true);

        if (isset($json['stopEvents'])) {
            $stopEvents = $json['stopEvents'];
            $success = true;

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

                // Print header "bus below" just before another 'stop' is printed
                if(isset($lastStop) && $lastStop != $stop) {
                    $showClass = true; // Set the variable to true to show the class after the bus information
                }
                $lastStop = $stop;

                if ($minutes >= 60) {
                    $hours = floor($minutes / 60);
                    $remainingMinutes = $minutes % 60;
                    echo $hours . "h " . $remainingMinutes . "mins from " . $location['name'] . "\n<br />";
                } else {
                    echo $minutes . "mins from " . $location['name'] . "\n<br />";
                }
                echo $routeNumber . " to " . $destination . "\n\n<br /><br />";

            }
        } else {
            $attempt++;
            if ($attempt < $retryAttempts) {
                sleep($retryDelay);
            }
        }
    }
    
    if (!$success) {
        echo "Failed to retrieve data for stop ID: " . $stop . "\n<br/>";
    }

    
}

// Show the class after the bus information
if ($showClass) {
    echo "<script>document.querySelector('.bus-info').style.display = 'block';</script>";
}

