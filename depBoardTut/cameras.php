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
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        img:hover {
            transform: scale(1.2);
        }
        
        th {
            background-color: #044c8c;
            text-align: center;
            color: white;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 5px;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }
        
        .modal-content {
            position: relative;
            margin: auto;
            padding: 20px;
            max-width: 80%;
            max-height: 80%;
        }
        
        .modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }
        
        /* Rest of the styles */
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
            width: 8px;
            right: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }
        
        td.view {
            max-width: 40%;
            word-wrap: break-word;
        }
        
        th.direction {
            padding-left: 12px;
            padding-right: 12px;
        }
    </style>
    <script>
        function openModal(imageUrl) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("modalImage");
            
            modal.style.display = "block";
            modalImg.src = imageUrl;
            
            var closeSpan = document.getElementById("closeSpan");
            closeSpan.onclick = function() {
                modal.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <?php
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    
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
    ?>

    <form method="GET">
        <label for="search">Search live traffic cameras</label>
        <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
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
            <?php foreach ($data['features'] as $feature) :
                $title = $feature['properties']['title'];
                $view = $feature['properties']['view'];
                $direction = $feature['properties']['direction'];
                $image = $feature['properties']['href'];

                // Search both Title and View for the given search term
                if (stripos($title, $searchTerm) !== false || stripos($view, $searchTerm) !== false) :
            ?>
            <tr>
                <td><?php echo htmlspecialchars($title); ?></td>
                <td class="view"><?php echo htmlspecialchars($view); ?></td>
                <td><?php echo htmlspecialchars($direction); ?></td>
                <td><img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>" onclick="openModal('<?php echo htmlspecialchars($image); ?>')"></td>
            </tr>
            <?php
                endif;
            endforeach;
            ?>
        </tbody>
    </table>
    
    <!-- Modal -->
    <div id="myModal" class="modal">
        <span id="closeSpan" class="modal-close">&times;</span>
        <img id="modalImage" class="modal-content">
    </div>
</body>
</html>
