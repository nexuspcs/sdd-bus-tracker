<!DOCTYPE html>
<html>

<head>
    <title>SLGS Bus Tracker</title>
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <h2 class="bus-info">test</h2>
    <div id="busData">test</div>

    <script>
        const refreshDelay = 5000 // refreshes and pulls new data from api every x milliseconds (MIN value; 5000, as OpenData API is rate-limited to min every 5 seconds).  
        var countMulpt = 0
        var refreshDelayCounterSECONDS = 0

        function fetchData() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("busData").innerHTML = xhr.responseText;
                }
            };
            xhr.open("GET", "<?php echo $_SERVER['PHP_SELF']; ?>?action=fetchData", true);
            xhr.send();
            refreshDelayCounter = refreshDelay * countMulpt
            refreshDelayCounterSECONDS = refreshDelayCounter / 1000 // dividing by 1000, to convert from milliseconds to seconds 
            console.log("Updating data from API --> Pulling new data ~ ~ ~ ~          " + "Time since page reloaded (Command/Control + R): " + refreshDelayCounterSECONDS + " second(s)")
            countMulpt = countMulpt + 1
        }

        fetchData(); // Fetch data on initial page load
        setInterval(fetchData, refreshDelay); // Refresh data every x seconds, according to value 
    </script>







    <?php

    if (isset($_GET['action']) && $_GET['action'] == 'fetchData') {
        date_default_timezone_set("Australia/Sydney"); // set timezone to Sydney time AEST
        echo '<h1 class="welcomeTitle">St Luke\'s Grammar (Dee Why) - Bus Tracker</h1><br>'; // the reason this HTML code is not above, is so that it refreshs with the website. 
        // echo '<iframe class="clock-time" src="https://free.timeanddate.com/clock/i8u027l2/n240/szw210/szh210/hoc000/hbw2/cf100/hnc004c8b/fiv0/fan2/fas20/facfff/fdi60/mqc000/mqs3/mql25/mqw6/mqd96/mhc000/mhs3/mhl20/mhw6/mhd96/mmc000/mms3/mml10/mmw2/mmd96/hhl55/hhw16/hhr9/hml80/hmw16/hmr9/hscfff/hss3/hsl90/hsw6/hsr3" frameborder="0" width="210" height="210"></iframe>';

        // echo '<p class="currentDateTime">' . date('H:i, l, d/m/Y') . '</p>'; // outputs 24hr time
        echo '<p class="currentDateTime">' . date('g:i a, l, d/m/Y') . '</p>';  //outputs 12hr AM/PM time



        error_reporting(E_ALL);
        ini_set('display_errors', 1);


        $apiEndpoint = 'https://api.transport.nsw.gov.au/v1/tp/'; // First define the API endpoint, which is the base URL of the API. This is the same for all API calls.
        $apiCall = 'departure_mon';
        $when = time(); // Now
        $stopIds = array("209926", "209927"); // Replace with the desired stop ID (testing stop id, is qvb, york st;; 200041) (headland rd slgs stop id is; 209926;;;;;;;  quirk st; 209927) (mona bline; 210323)
        $stop = "";
        $retryAttempts = 3; // Next define the number of retry attempts for the API call. This is the number of times that the code will try to get data from the API before returning a failure.  
        $retryDelay = 0; // A delay (in seconds) for how long the request will 'hang' while waiting for data back from the API

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

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Authorization: apikey zZBkkDXyybkIuLAPPW81EuzExQvJuWJ0breL\r\n"
            ]
        ];

        $showClass = false;

        foreach ($stopIds as $stop) {
            $params['name_dm'] = $stop;
            $params['itdDate'] = date('Ymd', $when);
            $params['itdTime'] = date('Hi', $when);
            $url = $apiEndpoint . $apiCall . '?' . http_build_query($params);

            $attempt = 0;
            $success = false;
            while ($attempt < $retryAttempts && !$success) {
                $context = stream_context_create($opts);
                $response = file_get_contents($url, false, $context);
                $json = json_decode($response, true);

                if (isset($json['stopEvents'])) {
                    $stopEvents = $json['stopEvents'];
                    $success = true;

                    echo "<table>";
                    echo "<thead><tr><th>Route</th><th>Time (hours / mins)</th><th>Arrival Time</th></tr></thead>";
                    echo "<tbody>";

                    foreach ($stopEvents as $stopEvent) {
                        $transportation = $stopEvent['transportation'];
                        $routeNumber = $transportation['number'];
                        $destination = $transportation['destination']['name'];
                        $location = $stopEvent['location'];
                    
                        if (isset($stopEvent['departureTimeEstimated'])) {
                            $time = strtotime($stopEvent['departureTimeEstimated']);
                        } else {
                            $time = strtotime($stopEvent['departureTimePlanned']);
                        }
                    
                        $countdown = $time - time();
                        $minutes = round($countdown / 60);
                    
                        $arrivalTime = date('g:i a', $time); // get the arrival time
                    
                        echo "<tr>";
                        echo "<td>" . "<span class='route-number'>" . $routeNumber . "</span>" . " to " . $destination . " (from " . $location['name'] . ")" . "</td>";
                    
                        if ($minutes >= 60) {
                            $hours = floor($minutes / 60);
                            $remainingMinutes = $minutes % 60;
                            echo "<td>" . $hours . "h " . $remainingMinutes . "mins</td>";
                        } else {
                            echo "<td>" . $minutes . "mins</td>";
                        }
                    
                        echo "<td>" . $arrivalTime . "</td>"; // display the arrival time
                    
                        echo "</tr>";
                    }
                    

                    echo "</tbody>";
                    echo "</table>";
                } else {
                    $attempt++;
                    if ($attempt < $retryAttempts) {
                        sleep($retryDelay);
                    }
                }
            }

            if (!$success) {
                echo "Failed to retrieve data for Stop ID: " . $stop . "\n<br/>";
            }
        }

        if ($showClass) {
            echo "<script>document.querySelector('.bus-info').style.display = 'block';</script>";
        }

        exit(); // Prevent the rest of the HTML from being output in the AJAX response
    }
    ?>
</body>

</html>