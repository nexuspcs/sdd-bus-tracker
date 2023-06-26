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

const refreshDelay = 5000; // Refreshes and pulls new data from API every x milliseconds (using 5000ms, 5sec, as that is optimal for speedy requests, and to not be rate limited )
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

    xhr.onreadystatechange = function () {
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

document.addEventListener("DOMContentLoaded", function () {
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
helpButton.addEventListener("click", function () {
    helpWindow.style.display = "block";
});

// Hide the help window when the user clicks the close button
closeButton.addEventListener("click", function () {
    helpWindow.style.display = "none";
});

// Hide the help window when the user clicks outside of it
window.addEventListener("click", function (event) {
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
trafficCameraButton.addEventListener("click", function () {
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
liveTrafficCamerasCloseButton.addEventListener("click", function () {
    trafficCameraWindow.style.display = "none";
    // Remove the iframe when closing the window
    var iframe = trafficCameraWindow.querySelector("iframe");
    if (iframe) {
        iframe.remove();
    }
});
        // LIVE TRAFFIC CAMERAS END