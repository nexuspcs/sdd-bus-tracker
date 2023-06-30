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
            $url = 'https://api.transport.nsw.gov.au/v1/live/cameras';
            $headers = array(
                'Accept: application/json',
                'Authorization: apikey 3kmgfklveanp939zhNzrpZ4yxJ3obVPkuBjx'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';




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
                }
            }
            ?>
        </tbody>
    </table>

    <script>
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }
    </script>

</body>

</html>