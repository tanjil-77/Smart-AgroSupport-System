<?php
// API Key - Get your free key from openweathermap.org
$API_KEY = '7efdbc47913ede40106e35f63774f909';

// Get coordinates from request
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 23.8103;
$lon = isset($_GET['lon']) ? floatval($_GET['lon']) : 90.4125;
$type = isset($_GET['type']) ? $_GET['type'] : 'current';

// Set header to JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // Use Open-Meteo API (Free, no key needed!)
    if ($type === 'forecast') {
        // Get 7-day forecast from Open-Meteo
        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&daily=temperature_2m_max,temperature_2m_min,precipitation_probability_max,weathercode&timezone=Asia/Dhaka&forecast_days=7";
    } else {
        // Get current weather from Open-Meteo
        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weathercode,windspeed_10m,winddirection_10m,pressure_msl&timezone=Asia/Dhaka";
    }
    
    // Use cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);
    
    if ($curl_error) {
        throw new Exception('Connection Error: ' . $curl_error);
    }
    
    if ($http_code !== 200) {
        throw new Exception('API Error: Status ' . $http_code);
    }
    
    if ($response === false) {
        throw new Exception('Failed to fetch weather data');
    }
    
    // Parse Open-Meteo data
    $data = json_decode($response, true);
    
    if ($data === null) {
        throw new Exception('Invalid response from weather service');
    }
    
    // Convert Open-Meteo format to OpenWeatherMap-like format
    if ($type === 'current') {
        $converted = [
            'name' => 'Selected Location',
            'main' => [
                'temp' => round($data['current']['temperature_2m']),
                'feels_like' => round($data['current']['apparent_temperature']),
                'humidity' => $data['current']['relative_humidity_2m'],
                'pressure' => round($data['current']['pressure_msl'])
            ],
            'weather' => [[
                'main' => getWeatherCondition($data['current']['weathercode']),
                'description' => getWeatherDescription($data['current']['weathercode'])
            ]],
            'wind' => [
                'speed' => $data['current']['windspeed_10m'] / 3.6, // Convert to m/s
                'deg' => $data['current']['winddirection_10m']
            ],
            'clouds' => [
                'all' => getCloudCover($data['current']['weathercode'])
            ],
            'visibility' => 10000
        ];
        echo json_encode($converted);
    } else {
        // Convert forecast format
        $forecastList = [];
        for ($i = 0; $i < 5; $i++) {
            $forecastList[] = [
                'dt' => time() + ($i * 86400),
                'dt_txt' => date('Y-m-d 12:00:00', time() + ($i * 86400)),
                'main' => [
                    'temp' => ($data['daily']['temperature_2m_max'][$i] + $data['daily']['temperature_2m_min'][$i]) / 2,
                    'temp_max' => $data['daily']['temperature_2m_max'][$i],
                    'temp_min' => $data['daily']['temperature_2m_min'][$i]
                ],
                'weather' => [[
                    'main' => getWeatherCondition($data['daily']['weathercode'][$i]),
                    'description' => getWeatherDescription($data['daily']['weathercode'][$i])
                ]],
                'pop' => $data['daily']['precipitation_probability_max'][$i] / 100
            ];
        }
        echo json_encode(['list' => $forecastList]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

// Helper function to convert WMO weather codes to conditions
function getWeatherCondition($code) {
    $conditions = [
        0 => 'Clear',
        1 => 'Clear', 2 => 'Clouds', 3 => 'Clouds',
        45 => 'Mist', 48 => 'Mist',
        51 => 'Drizzle', 53 => 'Drizzle', 55 => 'Drizzle',
        61 => 'Rain', 63 => 'Rain', 65 => 'Rain',
        71 => 'Snow', 73 => 'Snow', 75 => 'Snow',
        80 => 'Rain', 81 => 'Rain', 82 => 'Rain',
        95 => 'Thunderstorm', 96 => 'Thunderstorm', 99 => 'Thunderstorm'
    ];
    return $conditions[$code] ?? 'Clouds';
}

// Helper function for weather descriptions
function getWeatherDescription($code) {
    $descriptions = [
        0 => 'clear sky',
        1 => 'mainly clear', 2 => 'partly cloudy', 3 => 'overcast',
        45 => 'foggy', 48 => 'depositing rime fog',
        51 => 'light drizzle', 53 => 'moderate drizzle', 55 => 'dense drizzle',
        61 => 'slight rain', 63 => 'moderate rain', 65 => 'heavy rain',
        71 => 'slight snow', 73 => 'moderate snow', 75 => 'heavy snow',
        80 => 'slight rain showers', 81 => 'moderate rain showers', 82 => 'violent rain showers',
        95 => 'thunderstorm', 96 => 'thunderstorm with hail', 99 => 'thunderstorm with heavy hail'
    ];
    return $descriptions[$code] ?? 'cloudy';
}

// Helper function for cloud coverage
function getCloudCover($code) {
    if ($code == 0 || $code == 1) return 10;
    if ($code == 2) return 50;
    if ($code == 3) return 90;
    return 60;
}
?>
