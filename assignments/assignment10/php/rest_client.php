<?php

//Explain the difference between a REST API client and a REST API server. What role does this code play in that relationship?

//Why is JSON commonly used for API responses? What are the benefits of using JSON over other data formats like XML?

//(duplicate question)Explain the difference between a REST API client and a REST API server. What role does this code play in that relationship?

//How should an application handle different types of API responses (success, error, empty data)? What considerations are 
//important for each scenario?

//What is cURL used for in web development? 

function getWeather()
{
    $acknowledgement = "";
    $output = "";

    $zipCode = isset($_POST['zip_code']) ? trim($_POST['zip_code']) : "";

    $url = "https://russet-v8.wccnet.edu/~sshaper/assignments/assignment10_rest/get_weather_json.php?zip_code=" . urlencode($zipCode);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        curl_close($ch);
        $acknowledgement = "<p>There was an error retrieving the records.</p>";
        return [$acknowledgement, $output];
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if ($data === null) {
        $acknowledgement = "<p>There was an error retrieving the records.</p>";
        return [$acknowledgement, $output];
    }

    if (isset($data['error'])) {
        $errorMsg = htmlspecialchars($data['error']);
        $acknowledgement = "<p>$errorMsg</p>";
        return [$acknowledgement, $output];
    }


    $city     = $data['searched_city'] ?? [];
    $cityName = htmlspecialchars($city['name'] ?? '');

    $temp     = $city['temperature'] ?? '';
    $humidity = htmlspecialchars($city['humidity'] ?? '');
    $forecast = $city['forecast'] ?? [];

    $higherTemps = $data['higher_temperatures'] ?? [];
    $lowerTemps  = $data['lower_temperatures'] ?? [];

    $output .= "<h2>$cityName</h2>";
    $output .= "<p><strong>Temperature:</strong> $temp</p>";
    $output .= "<p><strong>Humidity:</strong> $humidity</p>";

    $output .= "<p><strong>3-day forecast</strong></p>";
    $output .= "<ul>";
    foreach ($forecast as $dayInfo) {
        $day       = htmlspecialchars($dayInfo['day'] ?? '');
        $condition = htmlspecialchars($dayInfo['condition'] ?? '');
        $output .= "<li>$day: $condition</li>";
    }
    $output .= "</ul>";


    if (!empty($higherTemps)) {
        $output .= "<p><strong>Up to three cities where temperatures are higher than $cityName</strong></p>";

        $higherTemps = array_slice($higherTemps, 0, 3);

        $output .= '<table class="table table-striped">';
        $output .= '<thead><tr><th scope="col">City Name</th><th scope="col">Temperature</th></tr></thead>';
        $output .= '<tbody>';

        foreach ($higherTemps as $cityInfo) {
            $hName = htmlspecialchars($cityInfo['name'] ?? '');
            $hTemp = $cityInfo['temperature'] ?? '';
            $output .= "<tr><td>$hName</td><td>$hTemp</td></tr>";
        }

        $output .= '</tbody></table>';
    } else {
        $output .= "<p><strong>There are no cities with temperatures higher than $cityName.</strong></p>";
    }


    if (!empty($lowerTemps)) {

        $output .= "<p><strong>Up to three cities where temperatures are lower than $cityName</strong></p>";

        $lowerTemps = array_slice($lowerTemps, 0, 5);

        $output .= '<table class="table table-striped">';
        $output .= '<thead><tr><th scope="col">City Name</th><th scope="col">Temperature</th></tr></thead>';
        $output .= '<tbody>';

        foreach ($lowerTemps as $cityInfo) {
            $lName = htmlspecialchars($cityInfo['name'] ?? '');
            $lTemp = $cityInfo['temperature'] ?? '';
            $output .= "<tr><td>$lName</td><td>$lTemp</td></tr>";
        }

        $output .= '</tbody></table>';

    } else {

        $output .= "<p><strong>There are no cities with temperatures lower than $cityName.</strong></p>";

}


    return [$acknowledgement, $output];
}

