<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        img {
            max-width: 200px;
            max-height: 150px;
        }
    </style>
</head>
<body>
    
    <form method="GET">
        <label for="search">Search Title or View: </label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <input type="submit" value="Search">
    </form>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>View</th>
                <th>Direction</th>
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
                if (stripos($title, $searchTerm) !== false || stripos($view, $searchTerm) !== false) {
                    echo "<tr>
                            <td>$title</td>
                            <td>$view</td>
                            <td>$direction</td>
                            <td><img src='$image' alt='$title'></td>
                        </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</body>
</html>
