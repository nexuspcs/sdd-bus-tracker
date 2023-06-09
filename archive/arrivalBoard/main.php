<?php


//enable error reporting for debug
ini_set('display_errors', 1);
error_reporting(E_ALL);
// end

$stop_id = "2000435"; // Replace with the desired stop ID (testing stop id, is qvb, york st;; 200041) (slgs stop id is; 209926) (mona bline; 210323)
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

// Decode the JSON response
$data = json_decode($response, true);

// Display the formatted data
if (isset($data['stopEvents'])) {
    echo "<h2>Departures:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Line</th><th>Direction</th><th>Departure Time</th></tr>";
    foreach ($data['stopEvents'] as $event) {
        $line = isset($event['transportation']['number']) ? htmlspecialchars($event['transportation']['number']) : "";
        $direction = isset($event['transportation']['description']) ? htmlspecialchars($event['transportation']['description']) : "";
        $destination = isset($event['destination']['name']) ? htmlspecialchars($event['destination']['name']) : "";
        $departureDateTime = isset($event['departure']['dateTime']) ? new DateTime($event['departure']['dateTime']) : new DateTime('now');
    
        echo "<tr>";
        echo "<td>" . $line . "</td>";
        echo "<td>" . $direction . "</td>";
        echo "<td>" . $destination . "</td>";
        $departure_time = $departureDateTime !== null ? $departureDateTime->format('Y-m-d H:i:s') : "";
        echo "<td>" . $departure_time . "</td>";
        echo "</tr>";
    }
    
    
    
    
    
    echo "</table>";
} else {
    echo "<p>No departures found.</p>";
}
?>
