<?php
// Set the cURL request options
$curl_options = array(
    CURLOPT_URL => "https://api.transport.nsw.gov.au/v1/tp/departure_mon?outputFormat=rapidJSON&coordOutputFormat=EPSG%3A4326&mode=direct&type_dm=stop&name_dm=10111010&itdDate=20161001&itdTime=1200&departureMonitorMacro=true&TfNSWDM=true&version=10.2.1.42",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "Accept: application/json",
        "Authorization: apikey zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL"
    )
);

// Initialize cURL
$curl = curl_init();

// Set the cURL options
curl_setopt_array($curl, $curl_options);

// Execute the cURL request
$response = curl_exec($curl);

// Close cURL
curl_close($curl);

// Display the response on the web page
echo $response;
?>
