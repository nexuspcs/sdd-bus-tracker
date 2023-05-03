<!DOCTYPE html>
<html>

<head>
    <title>SLGS Bus Tracker</title>
    <style>
        .bus-info {
            color: red;
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
    </style>
</head>
<h1 class=welcomeTitle>St Luke's Grammar (Dee Why) - Bus Tracker</h1><br>

<body>

    <h2 class="bus-info">test</h2>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    date_default_timezone_set("Australia/Sydney");

    $apiEndpoint = 'https://api.transport.nsw.gov.au/v1/tp/';
    $apiCall = 'departure_mon';
    $when = time();
    $stopIds = array("209926", "2000133", "209927");
    $stop = "";
    $retryAttempts = 3;
    $retryDelay = 0;

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
                echo "<thead><tr><th>Route</th><th>Time (mins hrs)</th></tr></thead>";
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
                    echo "<tr>";

                    echo "<td>" . "<span class='route-number'>" . $routeNumber . "</span>" . " to " . $destination . " (from " . $location['name'] . ")" . "</td>";


                    if ($minutes >= 60) {
                        $hours = floor($minutes / 60);
                        $remainingMinutes = $minutes % 60;
                        echo "<td>" . $hours . "h " . $remainingMinutes . "mins</td>";
                    } else {
                        echo "<td>" . $minutes . "mins</td>";
                    }

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
            echo "Failed to retrieve data for stop ID: " . $stop . "\n<br/>";
        }
    }

    if ($showClass) {
        echo "<script>document.querySelector('.bus-info').style.display = 'block';</script>";
    }
    ?>
</body>

</html>