<!DOCTYPE html>
<html>

<head>
    <title>SLGS Bus Tracker - MAIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #044c8c;
            color: white;
            padding: 0;
        }

        .welcomeTitle {
            text-align: center;
            background-color: #3b5998;
            color: white;
            padding: 20px;
            font-size: 24px;
            margin-bottom: 20px;
        }

       

        .bus-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 20px;
            color: black;
        }

        .bus-card {
            background-color: white;
            width: 250px;
            padding: 15px;
            margin: 10px;
            border-radius: 4px;
            text-align: center;
            box-shadow:  10px 10px rgba(0, 0, 0, 0.5);
        }

        .route-number {
            border-bottom: 3px solid #fad207;
            padding-bottom: 1px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 1.6em;
            color: #044c8c;
        }

        .bus-destination {
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .bus-time {
            font-weight: 900;
            font-size: 1.5em;
            color: #044c8c;
        }

        .gonative .bus-container {
            flex-direction: column;
            align-items: center;

        }

        .bus-info {
            text-align: center;
            font-weight: 00;
            color: white;
        }

        
        #loading {
            position: absolute;
            top: 50%;
            left: 50%;
            font-size: 50px;
            color: white;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }

        .spinner {
            font-size: 50px;
        }
    </style>

</head>

<body>
    <h2 class="bus-info" id="nearestBusInfo"></h2>
    <div id="busData"></div>
    <div id="loading">
        <div>SLGS Bus Tracker - Loading bus location data...<br>
            <span class="spinner">&#128256;</span>
        </div>
    </div>






    <script>
        function displayNearestBus(nearestBus) {
            if (nearestBus !== null) {

                const nearestBusInfo = document.getElementById("nearestBusInfo");
                nearestBusInfo.innerText = `Nearest Bus: ${nearestBus.routeInfo} in ${nearestBus.timeInMins}m`;
                nearestBusInfo.style.display = "block";
            }
        }

        const refreshDelay = 5000; // Refreshes and pulls new data from API every x milliseconds
        var countMulpt = 0;
        var refreshDelayCounterSECONDS = 0;


        // a boolean flag, so that after page is opened it wont refresh again.
        var isFirstLoad = true;



        function fetchData() {
            var xhr = new XMLHttpRequest();

            // loading screen showing;
            if (isFirstLoad) {
                document.getElementById("loading").style.display = "block";
            }

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {

                    //hide the loading screen once the api return 200, meaning a successful request, hide the loading screen
                    document.getElementById("loading").style.display = "none";

                    
                    // set the boolean flag to false, so that it wont show the loading again
                    isFirstLoad = false;

                    document.getElementById("busData").innerHTML = xhr.responseText;
                    const busData = document.getElementById("busData");
                    const busRows = busData.querySelectorAll("tr");
                    let nearestBus = null;

                    let busCardsHTML = "";

                    for (let row of busRows) {
                        const routeInfo = row.cells[0].innerText;
                        const timeInfo = row.cells[1].innerText;
                        const timeRegex = /^(\d+)h?\s?(\d+)?m?$/;
                        const timeMatch = timeInfo.match(timeRegex);

                        if (timeMatch !== null) {
                            let timeInMins = parseInt(timeMatch[1]) * 1;

                            if (timeMatch[2]) {
                                timeInMins += parseInt(timeMatch[2]);
                            }

                            if (nearestBus === null || timeInMins < nearestBus.timeInMins) {
                                nearestBus = {
                                    routeInfo,
                                    timeInMins,
                                };
                            }

                            const routeNumber = routeInfo.match(/\d+/)[0];
                            const destination = routeInfo.replace(/^\d+/, "").trim();
                            const hours = timeInMins >= 60 ? Math.floor(timeInMins / 60) : 0;
                            const remainingMinutes = timeInMins % 60;
                            const timeStrHRMIN = hours > 0 ? hours + 'h ' + remainingMinutes + 'm' : remainingMinutes + 'm';


                            busCardsHTML += `<div class="bus-card">
    <div class="route-number">${routeNumber}</div>
    <div class="bus-destination">${destination}</div>
    <div class="bus-time">${timeInMins}m</div>
</div>`;


                        }
                    }

                    busData.innerHTML = `<div class="bus-container">${busCardsHTML}</div>`;


                    displayNearestBus(nearestBus);
                }
            };
            xhr.open("GET", "<?php echo $_SERVER['PHP_SELF']; ?>?action=fetchData", true);
            xhr.send();
            refreshDelayCounter = refreshDelay * countMulpt;
            refreshDelayCounterSECONDS = refreshDelayCounter / 1000; // dividing by 1000, to convert from milliseconds to seconds 
            console.log("Updating data from API --> Pulling new data ~ ~ ~ ~          " + "Time since page reloaded (Command/Control + R): " + refreshDelayCounterSECONDS + " second(s)");
            countMulpt = countMulpt + 1;
        }
        fetchData(); // Fetch data on initial page load
        setInterval(fetchData, refreshDelay); // Refresh data every x seconds, according to value


        document.addEventListener("DOMContentLoaded", function() {
            if (navigator.userAgent.indexOf('gonative') !== -1) {
                document.body.classList.add('gonative-background');
                document.body.classList.add('gonative');
            }
        });
    </script>

    </script>

    <?php

    if (isset($_GET['action']) && $_GET['action'] == 'fetchData') {
        date_default_timezone_set("Australia/Sydney"); // set timezone to Sydney time AEST
        echo '<h1 class="welcomeTitle">St Luke\'s Grammar (Dee Why) - Bus Tracker</h1><br>'; // the reason this HTML code is not above, is so that it refreshs with the website. 

        // echo '<p class="currentDateTime">' . date('H:i, l, d/m/Y') . '</p>'; // outputs 24hr time
        echo '<p class="currentDateTime">' . date('g:i a, l, d/m/Y') . '</p>';  //outputs 12hr AM/PM time


        echo '<h2 class="bus-info">Nearest Bus: ' . $nearestBus['routeNumber'] . ' to ' . $nearestBus['destination'] . ' (' . $nearestBus['location'] . ') in ' . round($nearestBus['countdown'] / 60) . ' min(s)</h2>';


        error_reporting(E_ALL);
        ini_set('display_errors', 1);


        $apiEndpoint = 'https://api.transport.nsw.gov.au/v1/tp/'; // First define the API endpoint, which is the base URL of the API. This is the same for all API calls.
        $apiCall = 'departure_mon';
        $when = time(); // Now
        $stopIds = array("209926", "209927", "209929"); // Replace with the desired stop ID (testing stop id, is qvb, york st;; 200041) (headland rd slgs stop id is; 209926;;;;;;;  quirk st; 209927) (mona bline; 210323)
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
        if ($showClass) {
            echo '<h2 class="bus-info">Nearest Bus: ' . $nearestBus['routeNumber'] . ' to ' . $nearestBus['destination'] . ' (' . $nearestBus['location'] . ') in ' . round($nearestBus['countdown'] / 60) . ' min(s)</h2>';
        }
        foreach ($stopIds as $stop) {
            $params['name_dm'] = $stop;
            $params['itdDate'] = date('Ymd', $when);
            $params['itdTime'] = date('Hi', $when);
            $url = $apiEndpoint . $apiCall . '?' . http_build_query($params);

            $attempt = 0;
            $success = false;
            $nearestBus = null;

            while ($attempt < $retryAttempts && !$success) {
                $context = stream_context_create($opts);
                $response = file_get_contents($url, false, $context);
                $json = json_decode($response, true);

                if (isset($json['stopEvents'])) {
                    $stopEvents = $json['stopEvents'];
                    $success = true;

                    echo "<table>";
                    echo "<thead><tr><th>Route</th><th>Time (hours / mins)</th></tr></thead>";
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

                        $hours = floor($minutes / 60);
                        $remainingMinutes = $minutes % 60;
                        $timeStr = $minutes . 'm';




                        echo "<tr>";
                        echo "<td>" . "<span class='route-number'>" . $routeNumber . "</span>" . " to " . $destination . "</td>"; // echo "<td>" . "<span class='route-number'>" . $routeNumber . "</span>" . " to " . $destination . " (from " . $location['name'] . ")" . "</td>";

                        echo "<td>" . $timeStr . "</td>";
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
        }
        
        exit;
    }
    ?>

</body>

</html>