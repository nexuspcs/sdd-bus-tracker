<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            
         
        }
        img {
            max-width: 400px;
            max-height: 400px;
            padding: 5px;
        }
        th {
            background-color: #044c8c;
            text-align: center;
            color: white;
            padding-top: 10px;
            padding-bottom: 10px;
          
            padding-left: 5px;
        }
        form {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 7px;
        font-size: 25px;
        font-weight: bold;
    }

    input[type="text"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 300px;
        max-width: 100%;
    }

    input[type="submit"] {
        padding: 8px 20px;
        background-color: #044c8c;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
    }

    input[type="submit"]:hover {
        background-color: #033e76;
    }


    ::-webkit-scrollbar {
        width: 8px; /* Set the width of the scrollbar */
        right: 10px; /* Move the scrollbar further to the right */
    }

    ::-webkit-scrollbar-track {
        background-color: #f1f1f1; /* Set the background color of the scrollbar track */
    }

    ::-webkit-scrollbar-thumb {
        background-color: #888; /* Set the color of the scrollbar thumb */
        border-radius: 4px; /* Round the corners of the scrollbar thumb */
    }

    ::-webkit-scrollbar-thumb:hover {
        background-color: #555; /* Set the color of the scrollbar thumb on hover */
    }

    td.view {
        max-width: 40%; /* Adjust the maximum width as needed */
    word-wrap: break-word;
    }
    th.direction {
        padding-left: 12px;
        padding-right: 12px;
    }
    </style>
</head>
<body>
    
    <form method="GET">
        <label for="search">Search live traffic cameras</label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <input type="submit" value="Search">
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
                if (stripos($title, $searchTerm) !== false || stripos($view, $searchTerm) !== false) {
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
</body>
</html>
