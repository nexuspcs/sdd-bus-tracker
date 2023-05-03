<?php
$stop_id = "200041"; // Replace with the desired stop ID (testing stop id, is qvb, york st;; 200041) (slgs stop id is; 209926)
$time = ""; // Replace with the desired time in HHMM format

// Set the cURL request options
$curl_options = array(
    CURLOPT_URL => "https://api.transport.nsw.gov.au/v1/tp/departure_mon?outputFormat=rapidJSON&coordOutputFormat=EPSG%3A4326&mode=direct&type_dm=stop&name_dm={$stop_id}&itdDate=" . date('Ymd') . "&departureMonitorMacro=true&excludedMeans=checkbox&exclMOT_1=true&exclMOT_4=true&exclMOT_7=true&exclMOT_9=true&exclMOT_11=true&TfNSWDM=true&version=10.2.1.42",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "Accept: application/json",
        "Authorization: apikey zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL" // Replace 'YOUR_API_KEY_HERE' with your actual API key
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
