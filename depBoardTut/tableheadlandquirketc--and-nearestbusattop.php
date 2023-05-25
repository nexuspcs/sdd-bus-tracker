<!DOCTYPE html>
<html>

<head>
    <title>SLGS Bus Tracker - MAIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <style>
        /* CSS Styles */

        /* Styles for the body */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #044c8c;
            color: white;
            padding: 0;
        }

        /* Styles for the welcome title NOT USED AS LOGO IS NOW THERE INSTEAD*/
        /* .welcomeTitle {
            text-align: center;
            background-color: white;
            color: #044c8c;
            align-items: center;
            padding: 20px;
            margin-left: 20%;
            margin-right: 20%;
            font-size: 24px;
            display: none;
            margin-bottom: 20px;
        } */

        /* Styles for the bus container */
        .bus-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 20px;
            color: black;
        }

        /* Styles for the bus card */
        .bus-card {
            background-color: white;
            width: 250px;
            padding: 15px;
            margin: 10px;
            border-radius: 4px;
            text-align: center;
            box-shadow: 10px 10px rgba(0, 0, 0, 0.5);
        }

        /* Styles for the route number */
        .route-number {
            border-bottom: 3px solid #fad207;
            padding-bottom: 1px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 1.6em;
            color: #044c8c;
        }

        /* Styles for the bus destination */
        .bus-destination {
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        /* Styles for the bus time */
        .bus-time {
            font-weight: 900;
            font-size: 1.5em;
            color: #044c8c;
        }

        /* Styles for mobile app */
        .gonative .bus-container {
            flex-direction: column;
            align-items: center;
        }

        /* Styles for bus info */
        .bus-info {
            text-align: center;
            font-weight: 00;
            color: white;
        }

        /* Styles for loading indicator */
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

        /* Styles for spinner */
        .spinner {
            font-size: 50px;
        }

        .welcomeTitleLogo {
            max-width: 20%;
            /* make the logo responsive */
            display: none;
            /* hide the logo on page load, and only show it when the api data is returned/loaded. */
            border-bottom: white solid 1px;
            box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.5);
            padding-top: 15px;
            padding-bottom: 15px;
            border-radius: 10px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 40px;
            padding-right: 40px;
            margin-bottom: 0px;
            background-color: white;
        }
    </style>

</head>

<body>
    <h2 class="welcomeTitle" id="SLGStxt"></h2>
    <img class="welcomeTitleLogo" src="SLGSBTLogo.png" alt="SLGS Logo" id="SLGSimg">


    <h2 class="bus-info" id="nearestBusInfo"></h2>
    <div id="busData"></div>
    <div id="loading">
        <div>SLGS Bus Tracker - Loading bus location data...<br>
            <span class="spinner">&#128260;</span>
        </div>
    </div>






    <script>
        function displayNearestBus(nearestBus) {
            if (nearestBus !== null) {
                const nearestBusInfo = document.getElementById("nearestBusInfo");
                //const SLGStxt = document.getElementById("SLGStxt"); // commented this line out, as the TEXT is being replaced by the logo
                const SLGSimg = document.getElementById("SLGSimg");
                //SLGStxt.innerText = `St Luke's Grammar School Bus Tracker`; // commented this line out, as the TEXT is being replaced by the logo
                SLGSimg.style.display = "block";
                let correctedRouteInfo = nearestBus.routeInfo.replace('\n', ''); // This will replace the first 'n' character in the string
                nearestBusInfo.innerText = `Nearest Bus: \n ${correctedRouteInfo} in ${nearestBus.timeInMins}m`;
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
                // document.getElementsById("SLGStxt").style.display = "none"; // hide the welcome title, doesnt work, just always stop the page from loading. 
            }

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) { // status 200 means a successful api response (http/text)

                    //hide the loading screen once the api return 200, meaning a successful request, hide the loading screen
                    document.getElementById("loading").style.display = "none";

                    // display the welcome title on page load, and at same time as api data is returned/loaded.
                    document.getElementById("SLGStxt").style.display = "block";


                    // set a boolean flag to false, so that it wont show the loading again
                    isFirstLoad = false;

                    document.getElementById("busData").innerHTML = xhr.responseText;
                    const busData = document.getElementById("busData");
                    const busRows = busData.querySelectorAll("tr");
                    let nearestBus = null;

                    let busCardsHTML = ""; // initialise an empty variable to store the html for the bus cards

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

                            // Generate HTML for bus cards
                            busCardsHTML += `<div class="bus-card">
    <div class="route-number">${routeNumber}</div>
    <div class="bus-destination">${destination}</div>
    <div class="bus-time">${timeInMins}m</div>
</div>`;


                        }
                    }
                    if (busCardsHTML === "") {
                        busCardsHTML = `<div class="bus-card">
                        <div class="route-number">No Data Available</div>
                        <div class="bus-destination">No buses are reporting locational data</div>
                    </div>`;
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


        // this below function, and event listener, will look for any user agent with 'gonative' in the user agent string
        // our app, was converted using webkit, and GoNative, which 'tags' a user agent of 'gonative'.
        // if the useragent 'gonative', or other variations is seen, the css class will be implemented, hence resulting in appropriate changes as per the gonative css. 
        // Apply specific styles for the mobile app

        document.addEventListener("DOMContentLoaded", function() {
            if (navigator.userAgent.indexOf('gonative') !== -1) {

                document.body.classList.add('gonative-background');
                document.body.classList.add('gonative');

            }



        });
    </script>

    </script>




    <?php
    ini_set('display_errors', 1); // print all errors to PHP console
    ini_set('display_startup_errors', 1);
    ini_set('max_execution_time', 120); //120 seconds = 2 minutes, so no PHP file does not timeout

    error_reporting(E_ALL);

    if (isset($_GET['action']) && $_GET['action'] == 'fetchData') {
        date_default_timezone_set("Australia/Sydney"); // set timezone to Sydney time AEST
        echo '<h1 class="welcomeTitle">St Luke\'s Grammar (Dee Why) - Bus Tracker</h1><br>'; // the reason this HTML code is not above, is so that it refreshs with the website. 

        // echo '<p class="currentDateTime">' . date('H:i, l, d/m/Y') . '</p>'; // outputs 24hr time
        echo '<p class="currentDateTime">' . date('g:i a, l, d/m/Y') . '</p>';  //outputs 12hr AM/PM time


        echo '<h2 class="bus-info">Nearest Bus: ' . '<br>' . $nearestBus['routeNumber'] . ' to ' . $nearestBus['destination'] . ' (' . $nearestBus['location'] . ') in ' . round($nearestBus['countdown'] / 60) . ' min(s)</h2>';


        error_reporting(E_ALL);
        ini_set('display_errors', 1);


        $apiEndpoint = 'https://api.transport.nsw.gov.au/v1/tp/'; // First define the API endpoint, which is the base URL of the API. This is the same for all API calls.
        $apiCall = 'departure_mon';
        $when = time(); // Now
        //$stopIds = array("210323"); //mona vale TEMP
        $stopIds = array("209926", "209927", "209929"); // Replace with the desired stop ID (testing stop id, is qvb, york st;; 200041) (headland rd slgs stop id is; 209926;;;;;;;  quirk st; 209927, 209929) (mona bline; 210323)
        $stop = "";
        $retryAttempts = 3; // Next define the number of retry attempts for the API call. This is the number of times that the code will try to get data from the API before returning a failure.
        $retryDelay = 0; // A delay (in seconds) for how long the request will 'hang' while waiting for data back from the API

        // large array to parse into the API url.
        // Array of parameters for the API call

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
            echo '<h2 class="bus-info">Nearest Bus: ' . '<br>' . $nearestBus['routeNumber'] . ' to ' . $nearestBus['destination'] . ' (' . $nearestBus['location'] . ') in ' . round($nearestBus['countdown'] / 60) . ' min(s)</h2>';
        }
        foreach ($stopIds as $stop) { // Loop through each stop ID
            $params['name_dm'] = $stop;
            $params['itdDate'] = date('Ymd', $when);
            $params['itdTime'] = date('Hi', $when);
            $url = $apiEndpoint . $apiCall . '?' . http_build_query($params);

            $attempt = 0;
            $success = false;
            $nearestBus = null;

            while ($attempt < $retryAttempts && !$success) { // Retry API call until success or maximum attempts reached
                $context = stream_context_create($opts);
                $response = file_get_contents($url, false, $context);
                $json = json_decode($response, true);

                if (isset($json['stopEvents'])) {
                    $stopEvents = $json['stopEvents'];
                    $success = true;

                    // Display bus data in a table
                    echo "<table>";
                    echo "<thead><tr><th>Route</th><th>Time (hours / mins)</th></tr></thead>";
                    echo "<tbody>";

                    foreach ($stopEvents as $stopEvent) { // Iterate through stop events

                        $transportation = $stopEvent['transportation'];
                        $routeNumber = preg_replace("/n/", "", $transportation['number'], 1);
                        $destination = preg_replace("/n/", "", $transportation['destination']['name'], 0);
                        $location = $stopEvent['location'];

                        if (isset($stopEvent['departureTimeEstimated'])) { // Determine the estimated or planned departure time
                            $time = strtotime($stopEvent['departureTimeEstimated']);
                        } else {
                            $time = strtotime($stopEvent['departureTimePlanned']);
                        }

                        $countdown = $time - time();
                        $minutes = round($countdown / 60);

                        $hours = floor($minutes / 60);
                        $remainingMinutes = $minutes % 60;
                        $timeStr = $minutes . 'm';



                        // Display route and time information in table rows
                        echo "<tr>";
                        echo "<td>" . "<span class='route-number'>" . $routeNumber . "</span>" . " to " . $destination . "</td>"; // echo "<td>" . "<span class='route-number'>" . $routeNumber . "</span>" . " to " . $destination . " (from " . $location['name'] . ")" . "</td>";

                        echo "<td>" . $timeStr . "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                } else { // within this else statement, the php code will try until it exceeds the pre-defined values of $retryAttempts and $retryDelay, to avoid the API from timing out, or being rate limited.
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

    <style>
        /* Style the help button */
        #help-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Style the help window */
        #help-window {
            display: none;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    color: black;
    border: 1px solid black;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    z-index: 1;
    width: 90%;
    height: 90%;
    color: black;
        }

         /* Style the close button */
         #close-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: transparent;
            border: none;
            font-size: 20px;
            color: black;
            cursor: pointer;
        }
    </style>
    <!-- Create a help button -->
    <button id="help-button">Help</button>

    <!-- Create a help window -->
    <div id="help-window">
        <button id="close-button">&times;</button>
        <p>This is the help text.</p>
    </div>



    <script>
        // Get the help button, help window, and close button elements
        var helpButton = document.getElementById("help-button");
        var helpWindow = document.getElementById("help-window");
        var closeButton = document.getElementById("close-button");

        // Show the help window when the help button is clicked
        helpButton.addEventListener("click", function() {
            helpWindow.style.display = "block";
        });

        // Hide the help window when the user clicks the close button
        closeButton.addEventListener("click", function() {
            helpWindow.style.display = "none";
        });

        // Hide the help window when the user clicks outside of it
        window.addEventListener("click", function(event) {
            if (event.target == helpWindow) {
                helpWindow.style.display = "none";
            }
        });
    </script>









</body>

</html>