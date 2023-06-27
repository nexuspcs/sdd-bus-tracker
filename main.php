<!DOCTYPE html>
<html lang="en-GB">

<head>
    <title>St Luke's Grammar School Bus Tracker</title>
    <meta charset="UTF-8">
    <meta name="description" content="St Luke's Grammar School live bus tracker, and live traffic cameras">
    <meta name="keywords" content="St Luke's Grammar School, SLGS, Bus tracker, live traffic cameras">
    <meta name="author" content="https://stlukes.nsw.edu.au">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.typekit.net/ths7ysh.css"> <!--External font-->
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"> <!-- Set scale -->
    <link rel="stylesheet" href="main.css" type="text/css" /> <!-- Links to the external CSS file for simplicity -->
</head>

<body>
    <h2 class="welcomeTitle" id="SLGStxt"></h2><a href="">
        <img class="welcomeTitleLogo" src="/images:resources/SLGSBTLogo.png" alt="SLGS Logo" id="SLGSimg"></a>
    <h2 class="bus-info" id="nearestBusInfo"></h2>
    <div id="busData"></div>
    <div id="loading">
        <div>SLGS Bus Tracker - Loading bus location data<br>
            <!-- <span class="spinner">&#128260;</span> -->
            <div class="loader"></div>
        </div>
    </div>

    <!-- Create the help button -->
    <button id="help-button">Help</button>

    <!-- Create the live traffic camera button -->
    <button id="live-traffic-cameras-button">Live Traffic Cameras</button>

    <!-- Create the help window -->
    <div id="help-window">
        <button id="close-button">&times;</button>
        <p>
            <a href="user_manual.pdf">User Manual</a> |
            <a href="faqs/">FAQs</a>
        </p>
        <p>Site operators:</p>
        <ul>
            <li>
                <p>James Coates <a href="mailto:jamesac2024@student.stlukes.nsw.edu.au">email</a></p>
            </li>
            <li>
                <p>Aleks Coric <a href="mailto:alexsandarc2024@student.stlukes.nsw.edu.au">email</a></p>
            </li>
        </ul>
        <br><br>
        <div class="creativeCOMMONS">
            <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">
                <img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/80x15.png" />
            </a><br />
            This work is licensed under a
            <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">
                Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License
            </a>.
        </div>
    </div>

    <!-- Create the live traffic camera window -->
    <div id="live-traffic-cameras-window">
        <button id="liveTrafficCamerasCloseButton">&times;</button>
    </div>




    <script>
        // The function displayNearestBus(nearestBus) displays the nearest bus to the user in the form of a message. This function is called in the function displayBuses() and takes the parameter nearestBus, which is the nearest bus to the user. The function starts by checking if the nearestBus is not null. 
        // If it is null, nothing will happen. If it is not null, the function will display the nearest bus information. The function starts by setting the variable nearestBusInfo to the element with the id "nearestBusInfo". The next line sets the variable SLGSimg to the element with the id "SLGSimg". 
        // The next line sets the variable helpButton to the element with the id "help-button". The next line sets the variable closeButton to the element with the id "close-button". 
        // The next line sets the variable correctedRouteInfo to the nearestBus.routeInfo, which is the route information of the nearest bus, and then uses the replace() function to replace the first 'n' character in the string. This is done because the route information contains a '\n' character, which displays a new line. 
        // The next line displays the nearest bus information in the form of a message. The next line sets the nearestBusInfo display to "block". The next line sets the SLGSimg display to "block". The next line sets the helpButton display to "block". The next line sets the closeButton display to "block".

        // BEGIN: JS code to display the nearest bus
        function displayNearestBus(nearestBus) {
            if (nearestBus !== null) {
                const nearestBusInfo = document.getElementById("nearestBusInfo");
                const SLGSimg = document.getElementById("SLGSimg");
                SLGSimg.style.display = "block";
                const helpButton = document.getElementById("help-button");
                const liveTrafficCamerasButton = document.getElementById("live-traffic-cameras-button");
                helpButton.style.display = "block";
                liveTrafficCamerasButton.style.display = "block";
                const closeButton = document.getElementById("close-button");
                closeButton.style.display = "block";

                const liveTrafficCamerasCloseButton = document.getElementById("liveTrafficCamerasCloseButton");
                liveTrafficCamerasCloseButton.style.display = "block";

                let correctedRouteInfo = nearestBus.routeInfo.replace('\n', '');
                let hours = Math.floor(nearestBus.timeInMins / 60);
                let minutes = nearestBus.timeInMins % 60;
                let timeStr = '';

                if (hours > 0) {
                    timeStr += hours + 'h ';
                }

                timeStr += minutes + 'm';

                nearestBusInfo.innerText = `Nearest Bus: \n ${correctedRouteInfo} in ${timeStr}`;
                nearestBusInfo.style.display = "block";
            }
            if (nearestBus === null) {
                const helpButton = document.getElementById("help-button");
                const liveTrafficCamerasButton = document.getElementById("live-traffic-cameras-button");
                const closeButton = document.getElementById("close-button");
                const liveTrafficCamerasCloseButton = document.getElementById("liveTrafficCamerasCloseButton");
                helpButton.style.display = "block";
                liveTrafficCamerasButton.style.display = "block";
                closeButton.style.display = "block";
                liveTrafficCamerasCloseButton.style.display = "block";
            }
        }

        const refreshDelay = 10000; // Refreshes and pulls new data from API every x milliseconds (using 10000ms, 10sec, as that is optimal for speedy requests, and to not be rate limited )
        var countMulpt = 0;
        var refreshDelayCounterSECONDS = 0;

        // a boolean flag, so that after page is opened it won't refresh again.
        var isFirstLoad = true;

        function fetchData() {
            var xhr = new XMLHttpRequest();

            // loading screen showing;
            if (isFirstLoad) {
                document.getElementById("loading").style.display = "block";
                // document.getElementsById("SLGStxt").style.display = "none"; // hide the welcome title, doesn't work, just always stop the page from loading.
            }

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) { // status 200 means a successful api response (http/text)

                    //hide the loading screen once the api return 200, meaning a successful request, hide the loading screen
                    document.getElementById("loading").style.display = "none";

                    // display the welcome title on page load, and at same time as api data is returned/loaded.
                    document.getElementById("SLGStxt").style.display = "block";


                    // set a boolean flag to false, so that it won't show the loading again
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
                            let timeInMins = parseInt(timeMatch[1]);

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
            <div class="bus-time">${timeStrHRMIN}</div>
        </div>`;


                        }
                    }
                    if (busCardsHTML === "") {
                        busCardsHTML = `<div class="bus-card">
                        <div class="route-number">No Data Available</div>
                        <div class="bus-destination">No buses are reporting locational data. Please use the 'Help' button on this page to contact a site operator for assistance.</div>
                    </div>`;
                        // Get the logo element and set its display property to "block"
                        const SLGSimg = document.getElementById("SLGSimg");
                        SLGSimg.style.display = "block";
                        console.warn("No bus locational data is available, check your internet connection, or try again later. Use the 'Help' button to contact a site operator for assistance.");

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
        // ----------------------------- // 

        // HELP BUTTON BEGIN:
        // Get the help button, help window, and close button elements
        var helpButton = document.getElementById("help-button");
        var body = document.getElementsByTagName("main-excluding-help-modal")[0];
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
            if (event.target == body) {
                helpWindow.style.display = "none";
            }
        });

        // HELP BUTTON END

        // LIVE TRAFFIC CAMERAS BEGIN
        // Get the live traffic cameras button, window, and close button elements
        var trafficCameraButton = document.getElementById("live-traffic-cameras-button");
        var trafficCameraWindow = document.getElementById("live-traffic-cameras-window");
        var liveTrafficCamerasCloseButton = document.getElementById("liveTrafficCamerasCloseButton");

        // Show the live traffic cameras window when the live traffic cameras button is clicked
        trafficCameraButton.addEventListener("click", function() {
            trafficCameraWindow.style.display = "block";
            // Load the PHP file in the live traffic cameras window using an iframe
            var iframe = document.createElement("iframe");
            iframe.src = "/livetraffic/cameras.php?search=Dee+Why";
            iframe.style.width = "100%";
            iframe.style.height = "100%";
            iframe.style.border = "none";
            trafficCameraWindow.appendChild(iframe);
        });

        // Hide the live traffic cameras window when the user clicks the close button
        liveTrafficCamerasCloseButton.addEventListener("click", function() {
            trafficCameraWindow.style.display = "none";
            // Remove the iframe when closing the window
            var iframe = trafficCameraWindow.querySelector("iframe");
            if (iframe) {
                iframe.remove();
            }
        });
        // LIVE TRAFFIC CAMERAS END
    </script>




    <!-- PHP CODE BELOW -->
    <?php
    ini_set('display_errors', 1); // print all errors to PHP console
    ini_set('display_startup_errors', 1);
    ini_set('max_execution_time', 120); //120 seconds = 2 minutes, so no PHP file does not time out

    error_reporting(E_ALL); // print all errors to PHP console (this will be on the server, not the client side)

    if (isset($_GET['action']) && $_GET['action'] == 'fetchData') {
        date_default_timezone_set("Australia/Sydney"); // set timezone to Sydney time AEST
        echo '<h1 class="welcomeTitle">St Luke\'s Grammar (Dee Why) - Bus Tracker</h1><br>'; // the reason this HTML code is not above, is so that it refreshes with the website. 

        // echo '<p class="currentDateTime">' . date('H:i, l, d/m/Y') . '</p>'; // outputs 24hr time
        echo '<p class="currentDateTime">' . date('g:i a, l, d/m/Y') . '</p>';  //outputs 12hr AM/PM time


        // echo '<h2 class="bus-info">Nearest Bus: ' . '<br>' . $nearestBus['routeNumber'] . ' to ' . $nearestBus['destination'] . ' (' . $nearestBus['location'] . ') in ' . round($nearestBus['countdown'] / 60) . ' min(s)</h2>';


        error_reporting(E_ALL);
        ini_set('display_errors', 1);


        $apiEndpoint = 'https://api.transport.nsw.gov.au/v1/tp/'; // First define the API endpoint, which is the base URL of the API. This is the same for all API calls.
        $apiCall = 'departure_mon';
        $when = time(); // Now
        $stopIds = array("209926", "209927", "209929"); // St Luke's Grammar School Dee Why, ALL STOPS
        // ~testing below: 
        //$stopIds = array("200041", "200042", "200043", "200044", "200045", "200046", "200047", "200048", "200049", "200050"); // overload API TEST TEMP
        //$stopIds = array("210323"); //mona vale TEMP
        //$stopIds = array("200041", "200005", "200042", "200043"); //city TEMP
        // ~testing above:
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



                    // This code iterates through all stop events in the array and displays the route number, destination, and departure time. If the departure time is estimated, it displays the estimated departure time; otherwise, it displays the planned departure time.

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






</body>

</html>