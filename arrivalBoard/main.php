<?php
$stop_id = "200041"; // Replace with the desired stop ID
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
if (isset($data['departures'])) {
    echo "<h2>Departures:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Line</th><th>Direction</th><th>Departure Time</th></tr>";
    foreach ($data['departures'] as $departure) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($departure['line']['name']) . "</td>";
        echo "<td>" . htmlspecialchars($departure['direction']['name']) . "</td>";
        echo "<td>" . htmlspecialchars($departure['dateTime']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No departures found.</p>";
}
?>
