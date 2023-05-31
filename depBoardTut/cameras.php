<!DOCTYPE html>
<html>
<head>
    <title>API Data</title>
</head>
<body>
    <h1>API Data</h1>
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

            foreach ($data['features'] as $feature) {
                $title = $feature['properties']['title'];
                $view = $feature['properties']['view'];
                $direction = $feature['properties']['direction'];
                $image = $feature['properties']['href'];

                echo "<tr>
                        <td>$title</td>
                        <td>$view</td>
                        <td>$direction</td>
                        <td><img src='$image' alt='$title' style='width: 200px;'></td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
