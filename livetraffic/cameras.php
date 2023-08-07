<!DOCTYPE html>
<html>

<head>
    <title>Live Traffic Cameras | SLGS Bus Tracker</title>
    <link rel="stylesheet" href="https://use.typekit.net/ths7ysh.css"> <!--External font-->
    <link rel="stylesheet" href="styles.css"> <!--External CSS for the cameras-->
</head>

<body>

    <!-- The loading div -->
    <div id="loading">
        <div class="loader"></div>
    </div>

    <form method="GET" onsubmit="showLoading()">
        <label for="search">Search live traffic cameras</label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <input type="submit" value="Search">
        <button class="clearSearch" type="button" onclick="clearSearch()">Clear Search</button>

        </select>
    </form>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>View</th>
                <th class="direction">Direction</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $url = 'https://api.transport.nsw.gov.au/v1/live/cameras'; // URL to the API const
            $headers = array( // Headers to send to the API
                'Accept: application/json', // Tell the API we want JSON
                'Authorization: apikey 3kmgfklveanp939zhNzrpZ4yxJ3obVPkuBjx' // Our API key
            );

            // Initialise cURL
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            // Check if the search term is set, if not set it to an empty string
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
            $foundResults = false; // Flag variable to track if any matching results were found


            // Loop through each feature in the data
            foreach ($data['features'] as $feature) {
                $title = $feature['properties']['title'];
                $view = $feature['properties']['view'];
                $direction = $feature['properties']['direction'];
                $image = $feature['properties']['href'];

                // Search both Title and View for the given search term
                if ((stripos($title, $searchTerm) !== false || stripos($view, $searchTerm) !== false)) {
                    echo "<tr>
                            <td>$title</td>
                            <td class='view'>$view</td>
                            <td>$direction</td>
                            <td><img src='$image' alt='$title'></td>
                        </tr>";
                    $foundResults = true; // Set the flag to true since matching result(s) were found
                }
            }
            // Check if no results were found and display a message
            if (!$foundResults) {
                echo "<tr>
                        <td colspan='4' class='no-results'>No results found</td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function showLoading() { // Function to show the loading div
            document.getElementById('loading').style.display = 'block';
        }

        function clearSearch() {
            var searchInput = document.getElementById('search');
            searchInput.value = ' '; // Set the search box value to one empty space
            document.forms[0].submit(); // Submit the form programmatically
            showLoading(); // Show the loading div
        }
    </script>

</body>

</html>