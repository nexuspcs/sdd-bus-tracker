<!DOCTYPE html>
<html>

<head>
    <title>SLGS Bus Tracker</title>

    <style>
        .bus-info {
            color: gold;
            font-weight: bold;
            display: none;
        }

        table {
            border-collapse: collapse;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .welcomeTitle {
            text-align: center;
        }

        .route-number {
            font-weight: bold;
            font-size: 1.2em;
            color: blue;
        }

        .currentDateTime {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>

</head>

<body>
    <h2 class="bus-info">test</h2>
    <div id="busData"></div>


    <h2 class="bus-info" id="nearestBusInfo"></h2>



    <script>
        function displayNearestBus(nearestBus) {
            if (nearestBus !== null) {
                const hours = nearestBus.timeInMins >= 60 ? Math.floor(nearestBus.timeInMins / 60) : 0;

                const remainingMinutes = nearestBus.timeInMins % 60;
                const nearestBusInfo = document.getElementById("nearestBusInfo");
                nearestBusInfo.innerText = `Nearest Bus: ${nearestBus.routeInfo} in ${hours > 0 ? hours + 'h ' : ''}${remainingMinutes}m`;

                nearestBusInfo.style.display = "block";
            }
        }
        const refreshDelay = 5000; // Refreshes and pulls new data from API every x milliseconds
        var countMulpt = 0;
        var refreshDelayCounterSECONDS = 0;

        function fetchData() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("busData").innerHTML = xhr.responseText;
                    const busData = document.getElementById("busData");
                    const busRows = busData.querySelectorAll("tr");
                    let nearestBus = null;

                    for (let row of busRows) {
                        const routeInfo = row.cells[0].innerText;
                        const timeInfo = row.cells[1].innerText;
                        const timeRegex = /^(\d+)h?\s?(\d+)?m?$/;
                        const timeMatch = timeInfo.match(timeRegex);

                        if (timeMatch !== null) {
                            let timeInMins = parseInt(timeMatch[1]) * 60;

                            if (timeMatch[2]) {
                                timeInMins += parseInt(timeMatch[2]);
                            }

                            if (nearestBus === null || timeInMins < nearestBus.timeInMins) {
                                nearestBus = {
                                    routeInfo,
                                    timeInMins,
                                };
                            }
                        }
                    }

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
    </script>

    </script>

    <?php

    if (isset($_GET['action']) && $_GET['action'] == 'fetchData') {
        date_default_timezone_set("Australia/Sydney"); // set timezone to Sydney time AEST
        echo '<h1 class="welcomeTitle">St Luke\'s Grammar (Dee Why) - Bus Tracker</h1><br>'; // the reason this HTML code is not above, is so that it refreshs with the website. 

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
                        $timeStr = $hours > 0 ? $hours . 'h ' . $remainingMinutes . 'm' : $remainingMinutes . 'm';


                        echo "<tr>";
                        echo "<td>" . "<span class='route-number'>" . $routeNumber . "</span>" . " to " . $destination . " (from " . $location['name'] . ")" . "</td>";
                        echo "<td>" . $timeStr . "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";

                    if ($showClass) {
                        echo '<h2 class="bus-info">Nearest Bus: ' . $nearestBus['routeNumber'] . ' to ' . $nearestBus['destination'] . ' (' . $nearestBus['location'] . ') in ' . round($nearestBus['countdown'] / 60) . ' min(s)</h2>';
                    }
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