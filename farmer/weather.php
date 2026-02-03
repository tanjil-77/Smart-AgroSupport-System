<?php
require_once '../config/config.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

// Get all weather advisories
$advisories = $conn->query("SELECT * FROM weather_advisory WHERE is_active=1 ORDER BY priority DESC, id DESC");

// All Bangladesh districts (জেলা)
$locations = [
    // Division: Dhaka
    ['name' => 'ঢাকা', 'lat' => 23.8103, 'lon' => 90.4125],
    ['name' => 'গাজীপুর', 'lat' => 23.9999, 'lon' => 90.4203],
    ['name' => 'নারায়ণগঞ্জ', 'lat' => 23.6238, 'lon' => 90.4996],
    ['name' => 'টাঙ্গাইল', 'lat' => 24.2513, 'lon' => 89.9167],
    ['name' => 'ফরিদপুর', 'lat' => 23.6070, 'lon' => 89.8429],
    ['name' => 'মানিকগঞ্জ', 'lat' => 23.8617, 'lon' => 90.0003],
    ['name' => 'মুন্সিগঞ্জ', 'lat' => 23.5422, 'lon' => 90.5305],
    ['name' => 'কিশোরগঞ্জ', 'lat' => 24.4449, 'lon' => 90.7766],
    ['name' => 'নরসিংদী', 'lat' => 23.9322, 'lon' => 90.7151],
    ['name' => 'মাদারীপুর', 'lat' => 23.1641, 'lon' => 90.1897],
    ['name' => 'রাজবাড়ী', 'lat' => 23.7574, 'lon' => 89.6444],
    ['name' => 'শরীয়তপুর', 'lat' => 23.2423, 'lon' => 90.4348],
    ['name' => 'গোপালগঞ্জ', 'lat' => 23.0050, 'lon' => 89.8266],
    
    // Division: Chittagong
    ['name' => 'চট্টগ্রাম', 'lat' => 22.3569, 'lon' => 91.7832],
    ['name' => 'কক্সবাজার', 'lat' => 21.4272, 'lon' => 92.0058],
    ['name' => 'রাঙ্গামাটি', 'lat' => 22.6533, 'lon' => 92.1753],
    ['name' => 'বান্দরবান', 'lat' => 22.1953, 'lon' => 92.2184],
    ['name' => 'খাগড়াছড়ি', 'lat' => 23.1193, 'lon' => 91.9847],
    ['name' => 'ফেনী', 'lat' => 23.0159, 'lon' => 91.3976],
    ['name' => 'কুমিল্লা', 'lat' => 23.4607, 'lon' => 91.1809],
    ['name' => 'ব্রাহ্মণবাড়িয়া', 'lat' => 23.9571, 'lon' => 91.1115],
    ['name' => 'চাঁদপুর', 'lat' => 23.2332, 'lon' => 90.6712],
    ['name' => 'লক্ষ্মীপুর', 'lat' => 22.9447, 'lon' => 90.8412],
    ['name' => 'নোয়াখালী', 'lat' => 22.8696, 'lon' => 91.0995],
    
    // Division: Rajshahi
    ['name' => 'রাজশাহী', 'lat' => 24.3745, 'lon' => 88.6042],
    ['name' => 'নাটোর', 'lat' => 24.4206, 'lon' => 89.0000],
    ['name' => 'নওগাঁ', 'lat' => 24.8133, 'lon' => 88.9211],
    ['name' => 'চাঁপাইনবাবগঞ্জ', 'lat' => 24.5965, 'lon' => 88.2775],
    ['name' => 'পাবনা', 'lat' => 24.0064, 'lon' => 89.2372],
    ['name' => 'সিরাজগঞ্জ', 'lat' => 24.4533, 'lon' => 89.7006],
    ['name' => 'বগুড়া', 'lat' => 24.8465, 'lon' => 89.3770],
    ['name' => 'জয়পুরহাট', 'lat' => 25.0968, 'lon' => 89.0227],
    
    // Division: Khulna
    ['name' => 'খুলনা', 'lat' => 22.8456, 'lon' => 89.5403],
    ['name' => 'যশোর', 'lat' => 23.1667, 'lon' => 89.2167],
    ['name' => 'সাতক্ষীরা', 'lat' => 22.7185, 'lon' => 89.0705],
    ['name' => 'বাগেরহাট', 'lat' => 22.6602, 'lon' => 89.7895],
    ['name' => 'ঝিনাইদহ', 'lat' => 23.5450, 'lon' => 89.5403],
    ['name' => 'মাগুরা', 'lat' => 23.4855, 'lon' => 89.4198],
    ['name' => 'নড়াইল', 'lat' => 23.1725, 'lon' => 89.5125],
    ['name' => 'কুষ্টিয়া', 'lat' => 23.9012, 'lon' => 89.1199],
    ['name' => 'চুয়াডাঙ্গা', 'lat' => 23.6401, 'lon' => 88.8410],
    ['name' => 'মেহেরপুর', 'lat' => 23.7622, 'lon' => 88.6318],
    
    // Division: Barisal
    ['name' => 'বরিশাল', 'lat' => 22.7010, 'lon' => 90.3535],
    ['name' => 'পটুয়াখালী', 'lat' => 22.3596, 'lon' => 90.3298],
    ['name' => 'ভোলা', 'lat' => 22.6859, 'lon' => 90.6482],
    ['name' => 'পিরোজপুর', 'lat' => 22.5841, 'lon' => 89.9720],
    ['name' => 'বরগুনা', 'lat' => 22.1590, 'lon' => 90.1119],
    ['name' => 'ঝালকাঠি', 'lat' => 22.6406, 'lon' => 90.1987],
    
    // Division: Sylhet
    ['name' => 'সিলেট', 'lat' => 24.8949, 'lon' => 91.8687],
    ['name' => 'মৌলভীবাজার', 'lat' => 24.4829, 'lon' => 91.7315],
    ['name' => 'হবিগঞ্জ', 'lat' => 24.3745, 'lon' => 91.4160],
    ['name' => 'সুনামগঞ্জ', 'lat' => 25.0658, 'lon' => 91.3950],
    
    // Division: Rangpur
    ['name' => 'রংপুর', 'lat' => 25.7439, 'lon' => 89.2752],
    ['name' => 'দিনাজপুর', 'lat' => 25.6217, 'lon' => 88.6354],
    ['name' => 'লালমনিরহাট', 'lat' => 25.9923, 'lon' => 89.2847],
    ['name' => 'নীলফামারী', 'lat' => 25.9317, 'lon' => 88.8560],
    ['name' => 'কুড়িগ্রাম', 'lat' => 25.8073, 'lon' => 89.6360],
    ['name' => 'ঠাকুরগাঁও', 'lat' => 26.0336, 'lon' => 88.4616],
    ['name' => 'পঞ্চগড়', 'lat' => 26.3411, 'lon' => 88.5541],
    ['name' => 'গাইবান্ধা', 'lat' => 25.3286, 'lon' => 89.5430],
    
    // Division: Mymensingh
    ['name' => 'ময়মনসিংহ', 'lat' => 24.7471, 'lon' => 90.4203],
    ['name' => 'জামালপুর', 'lat' => 24.9375, 'lon' => 89.9377],
    ['name' => 'নেত্রকোনা', 'lat' => 24.8803, 'lon' => 90.7282],
    ['name' => 'শেরপুর', 'lat' => 25.0204, 'lon' => 90.0152],
];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>আবহাওয়া পরামর্শ - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Background with Image */
        body {
            background: 
                linear-gradient(-45deg, 
                    rgba(74, 144, 226, 0.15), 
                    rgba(142, 158, 171, 0.15), 
                    rgba(52, 152, 219, 0.15), 
                    rgba(149, 165, 166, 0.15), 
                    rgba(52, 73, 94, 0.15), 
                    rgba(44, 62, 80, 0.15)
                ),
                url('../agrologo/weather.jpg') center center / cover fixed;
            background-size: cover;
            min-height: 100vh;
            position: relative;
        }

        /* Page Title */
        .page-title {
            animation: slideInDown 0.6s ease-out;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.5);
            color: #fff !important;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.5) 0%, rgba(52, 73, 94, 0.5) 100%);
            padding: 20px 30px;
            border-radius: 15px;
            display: inline-block;
            font-weight: bold;
            backdrop-filter: blur(25px);
        }

        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cards */
        .card {
            animation: fadeInUp 0.8s ease-out;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(40px) saturate(180%);
            box-shadow: 0 10px 40px rgba(52, 152, 219, 0.1);
            position: relative;
            z-index: 1;
        }

        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(52, 152, 219, 0.3);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-body {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(35px);
            padding: 25px !important;
        }

        /* Location Selector */
        .location-selector {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(30px);
            margin-bottom: 30px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .location-selector select {
            border: 3px solid #3498db;
            border-radius: 15px;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: 800;
            background: rgba(255, 255, 255, 0.65);
            color: #000;
            transition: all 0.3s ease;
        }

        .location-selector select:focus {
            border-color: #2ecc71;
            box-shadow: 0 0 25px rgba(46, 204, 113, 0.6);
            transform: scale(1.03);
        }

        /* Weather Cards */
        .weather-current {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.4) 0%, rgba(41, 128, 185, 0.4) 100%);
            border-radius: 25px;
            padding: 30px;
            backdrop-filter: blur(30px);
            text-align: center;
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        .temp-display {
            font-size: 72px;
            font-weight: 900;
            color: #000;
            text-shadow: 3px 3px 15px rgba(255, 255, 255, 1), 0 0 30px rgba(255, 255, 255, 0.8);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .weather-icon {
            font-size: 80px;
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Forecast Cards */
        .forecast-card {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(25px);
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid rgba(52, 152, 219, 0.3);
        }

        .forecast-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.35);
            border-color: #3498db;
            box-shadow: 0 15px 40px rgba(52, 152, 219, 0.4);
        }

        .forecast-icon {
            font-size: 48px;
            margin: 10px 0;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Advisory Cards */
        .advisory-card {
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.35) 0%, rgba(39, 174, 96, 0.35) 100%);
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(30px);
            margin-bottom: 20px;
            border-left: 5px solid #27ae60;
            animation: slideInRight 0.8s ease-out;
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .advisory-card.warning {
            background: linear-gradient(135deg, rgba(241, 196, 15, 0.35) 0%, rgba(243, 156, 18, 0.35) 100%);
            border-left-color: #f39c12;
        }

        .advisory-card.danger {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.35) 0%, rgba(192, 57, 43, 0.35) 100%);
            border-left-color: #c0392b;
        }

        /* Weather Stats */
        .weather-stat {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(25px);
            text-align: center;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .weather-stat:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.05);
        }

        .weather-stat i {
            font-size: 36px;
            color: #3498db;
            animation: pulse 2s infinite;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 900;
            color: #000;
            text-shadow: 2px 2px 8px rgba(255, 255, 255, 1);
        }

        .stat-label {
            font-size: 14px;
            font-weight: 700;
            color: #555;
            text-shadow: 1px 1px 4px rgba(255, 255, 255, 0.9);
        }

        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 5px solid rgba(52, 152, 219, 0.3);
            border-radius: 50%;
            border-top-color: #3498db;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Button */
        .btn-weather {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }

        .btn-weather:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(52, 152, 219, 0.6);
        }

        /* Text Styles */
        h5, h6, .card-title {
            color: #000 !important;
            font-weight: 900 !important;
            text-shadow: 2px 2px 8px rgba(255, 255, 255, 1), 0 0 15px rgba(255, 255, 255, 0.8) !important;
        }

        p, .small {
            color: #000 !important;
            font-weight: 700 !important;
            text-shadow: 1px 1px 5px rgba(255, 255, 255, 1), 0 0 10px rgba(255, 255, 255, 0.8) !important;
        }

        /* Main Content */
        main {
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1 class="h2 mb-4 page-title"><i class="fas fa-cloud-sun-rain"></i> আবহাওয়া ভিত্তিক ফসল পরামর্শ</h1>

                <!-- Location Selector -->
                <div class="location-selector">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <label class="form-label" style="color: #000; font-weight: 900; font-size: 20px; text-shadow: 2px 2px 8px rgba(255, 255, 255, 1);">
                                <i class="fas fa-map-marker-alt" style="color: #e74c3c;"></i> জেলা নির্বাচন করুন
                            </label>
                            <select id="locationSelect" class="form-select">
                                <option value="">জেলা বেছে নিন</option>
                                <?php foreach($locations as $location): ?>
                                <option value="<?php echo $location['lat'].','.$location['lon']; ?>">
                                    <?php echo $location['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center py-5" style="display: none;">
                    <div class="loading-spinner"></div>
                    <p style="color: #000; font-weight: 700; margin-top: 20px; text-shadow: 1px 1px 5px rgba(255, 255, 255, 1);">
                        আবহাওয়া তথ্য লোড হচ্ছে...
                    </p>
                </div>

                <!-- Weather Data Container -->
                <div id="weatherContainer" style="display: none;">
                    <!-- Current Weather -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="weather-current">
                                <h4 style="color: #000; font-weight: 900; text-shadow: 2px 2px 8px rgba(255, 255, 255, 1);">
                                    <i class="fas fa-location-dot"></i> <span id="currentLocation">ঢাকা</span>
                                </h4>
                                <div class="weather-icon" id="currentIcon">
                                    <i class="fas fa-sun" style="color: #f39c12;"></i>
                                </div>
                                <div class="temp-display" id="currentTemp">--°C</div>
                                <h5 id="weatherCondition" style="color: #000; font-weight: 900;">--</h5>
                                <p id="weatherDescription" style="color: #555; font-weight: 700;">--</p>
                            </div>
                        </div>

                        <!-- Weather Stats -->
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="weather-stat">
                                        <i class="fas fa-temperature-high"></i>
                                        <div class="stat-value" id="feelsLike">--°C</div>
                                        <div class="stat-label">অনুভূত তাপমাত্রা</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="weather-stat">
                                        <i class="fas fa-droplet" style="color: #3498db;"></i>
                                        <div class="stat-value" id="humidity">--%</div>
                                        <div class="stat-label">আর্দ্রতা</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="weather-stat">
                                        <i class="fas fa-wind" style="color: #95a5a6;"></i>
                                        <div class="stat-value" id="windSpeed">-- km/h</div>
                                        <div class="stat-label">বাতাসের গতি</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="weather-stat">
                                        <i class="fas fa-cloud-rain" style="color: #3498db;"></i>
                                        <div class="stat-value" id="rainChance">--%</div>
                                        <div class="stat-label">বৃষ্টির সম্ভাবনা</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="weather-stat">
                                        <i class="fas fa-eye" style="color: #95a5a6;"></i>
                                        <div class="stat-value" id="visibility">-- km</div>
                                        <div class="stat-label">দৃশ্যমানতা</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="weather-stat">
                                        <i class="fas fa-gauge" style="color: #e67e22;"></i>
                                        <div class="stat-value" id="pressure">-- mb</div>
                                        <div class="stat-label">বায়ুচাপ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5-Day Forecast -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 style="color: #000; font-weight: 900; margin-bottom: 20px; text-shadow: 2px 2px 8px rgba(255, 255, 255, 1);">
                                <i class="fas fa-calendar-days"></i> আগামী ৫ দিনের পূর্বাভাস
                            </h4>
                            <div class="row g-3" id="forecastContainer">
                                <!-- Forecast cards will be inserted here -->
                            </div>
                        </div>
                    </div>

                    <!-- Weather-Based Advisories -->
                    <div class="card">
                        <div class="card-body">
                            <h4 style="color: #000; font-weight: 900; margin-bottom: 20px; text-shadow: 2px 2px 8px rgba(255, 255, 255, 1);">
                                <i class="fas fa-lightbulb"></i> আবহাওয়া ভিত্তিক পরামর্শ
                            </h4>
                            <div id="advisoryContainer">
                                <!-- Advisories will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Default Tips (shown when no location selected) -->
                <div id="defaultTips" class="card">
                    <div class="card-body">
                        <h5 style="color: #000; font-weight: 900; text-shadow: 2px 2px 8px rgba(255, 255, 255, 1);">
                            <i class="fas fa-info-circle"></i> আবহাওয়া সংক্রান্ত গুরুত্বপূর্ণ টিপস
                        </h5>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-cloud-sun-rain me-3" style="color: #3498db; font-size: 32px;"></i>
                                    <div>
                                        <strong style="color: #000; font-weight: 900;">বৃষ্টির আগে:</strong>
                                        <p class="mb-0 small">ফসল কাটার কাজ দ্রুত শেষ করুন এবং ফসল সংরক্ষণ করুন</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-sun me-3" style="color: #f39c12; font-size: 32px;"></i>
                                    <div>
                                        <strong style="color: #000; font-weight: 900;">গরমের সময়:</strong>
                                        <p class="mb-0 small">সকাল বা বিকালে সেচ দিন। দুপুরে সেচ এড়িয়ে চলুন</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-snowflake me-3" style="color: #3498db; font-size: 32px;"></i>
                                    <div>
                                        <strong style="color: #000; font-weight: 900;">শীতকালে:</strong>
                                        <p class="mb-0 small">তুষার ও ঠান্ডা থেকে ফসল রক্ষা করুন</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Real API mode - always fetching live data
        const DEMO_MODE = false; // Set to true only for demo/testing
        const API_KEY = '7efdbc47913ede40106e35f63774f909'; // Get free key from openweathermap.org

        let currentLat = 23.8103; // Default: Dhaka
        let currentLon = 90.4125;
        let selectedLocationName = 'ঢাকা';

        // Weather condition translations
        const weatherTranslations = {
            'Clear': 'পরিষ্কার আকাশ',
            'Clouds': 'মেঘলা',
            'Rain': 'বৃষ্টি',
            'Drizzle': 'গুঁড়ি গুঁড়ি বৃষ্টি',
            'Thunderstorm': 'ঝড় ও বজ্রপাত',
            'Snow': 'তুষারপাত',
            'Mist': 'কুয়াশা',
            'Smoke': 'ধোঁয়াশা',
            'Haze': 'ঝাপসা',
            'Dust': 'ধুলো',
            'Fog': 'ঘন কুয়াশা',
            'Sand': 'বালুঝড়',
            'Ash': 'ছাই',
            'Squall': 'ঝঞ্ঝা',
            'Tornado': 'ঘূর্ণিঝড়'
        };

        // Day names in Bengali
        const bengaliDays = ['রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার', 'শনিবার'];

        // Location selector change
        document.getElementById('locationSelect').addEventListener('change', function() {
            if (this.value) {
                const [lat, lon] = this.value.split(',');
                currentLat = parseFloat(lat);
                currentLon = parseFloat(lon);
                selectedLocationName = this.options[this.selectedIndex].text;
                fetchWeatherData();
            }
        });

        // Get current location
        document.getElementById('getCurrentLocation').addEventListener('click', function() {
            selectedLocationName = 'আপনার বর্তমান অবস্থান';
            fetchWeatherData();
        });

        // Refresh weather
        document.getElementById('refreshWeather').addEventListener('click', fetchWeatherData);

        // Auto-load demo data on page load
        window.addEventListener('load', function() {
            setTimeout(fetchWeatherData, 500);
        });

        // Fetch weather data from server-side API
        async function fetchWeatherData() {
            showLoading();
            
            try {
                // Fetch current weather from server
                console.log('Fetching weather for:', currentLat, currentLon);
                const currentResponse = await fetch(
                    `get_weather.php?lat=${currentLat}&lon=${currentLon}&type=current`
                );
                
                console.log('Current response status:', currentResponse.status);
                
                if (!currentResponse.ok) {
                    const errorText = await currentResponse.text();
                    console.error('Response error:', errorText);
                    throw new Error('Server error: ' + currentResponse.status);
                }
                
                const currentData = await currentResponse.json();
                console.log('Current data:', currentData);
                
                // Check for API errors
                if (currentData.error) {
                    throw new Error(currentData.message || 'API Error');
                }

                // Fetch 5-day forecast from server
                const forecastResponse = await fetch(
                    `get_weather.php?lat=${currentLat}&lon=${currentLon}&type=forecast`
                );
                
                if (!forecastResponse.ok) {
                    throw new Error('Failed to fetch forecast');
                }
                
                const forecastData = await forecastResponse.json();
                
                // Check for API errors
                if (forecastData.error) {
                    throw new Error(forecastData.message || 'API Error');
                }

                // Display real data from API
                displayCurrentWeather(currentData);
                displayForecast(forecastData);
                generateAdvisories(currentData, forecastData);

                hideLoading();
                showWeatherContainer();
            } catch (error) {
                console.error('Error fetching weather:', error);
                hideLoading();
                alert('❌ আবহাওয়া তথ্য লোড করতে সমস্যা হয়েছে:\n\n' + error.message + '\n\n✓ ইন্টারনেট সংযোগ চেক করুন\n✓ API সার্ভার সচল কিনা চেক করুন');
            }
        }

        // Display current weather
        function displayCurrentWeather(data) {
            const temp = Math.round(data.main.temp);
            const feelsLike = Math.round(data.main.feels_like);
            const humidity = data.main.humidity;
            const windSpeed = Math.round(data.wind.speed * 3.6); // Convert m/s to km/h
            const visibility = (data.visibility / 1000).toFixed(1);
            const pressure = data.main.pressure;
            const condition = data.weather[0].main;
            const description = data.weather[0].description;
            
            document.getElementById('currentLocation').textContent = data.name || 'নির্বাচিত এলাকা';
            document.getElementById('currentTemp').textContent = temp + '°C';
            document.getElementById('weatherCondition').textContent = weatherTranslations[condition] || condition;
            document.getElementById('weatherDescription').textContent = description;
            document.getElementById('feelsLike').textContent = feelsLike + '°C';
            document.getElementById('humidity').textContent = humidity + '%';
            document.getElementById('windSpeed').textContent = windSpeed + ' km/h';
            document.getElementById('visibility').textContent = visibility + ' km';
            document.getElementById('pressure').textContent = pressure + ' mb';

            // Calculate rain chance from clouds
            const rainChance = data.clouds?.all || 0;
            document.getElementById('rainChance').textContent = rainChance + '%';

            // Set weather icon
            setWeatherIcon(condition, document.getElementById('currentIcon'));
        }

        // Set weather icon
        function setWeatherIcon(condition, element) {
            const icons = {
                'Clear': '<i class="fas fa-sun" style="color: #f39c12;"></i>',
                'Clouds': '<i class="fas fa-cloud" style="color: #95a5a6;"></i>',
                'Rain': '<i class="fas fa-cloud-rain" style="color: #3498db;"></i>',
                'Drizzle': '<i class="fas fa-cloud-drizzle" style="color: #3498db;"></i>',
                'Thunderstorm': '<i class="fas fa-cloud-bolt" style="color: #e74c3c;"></i>',
                'Snow': '<i class="fas fa-snowflake" style="color: #3498db;"></i>',
                'Mist': '<i class="fas fa-smog" style="color: #95a5a6;"></i>',
                'Fog': '<i class="fas fa-smog" style="color: #7f8c8d;"></i>'
            };
            element.innerHTML = icons[condition] || icons['Clear'];
        }

        // Display forecast
        function displayForecast(data) {
            const forecastContainer = document.getElementById('forecastContainer');
            forecastContainer.innerHTML = '';

            // Get one forecast per day (at noon)
            const dailyForecasts = data.list.filter(item => 
                item.dt_txt.includes('12:00:00')
            ).slice(0, 5);

            dailyForecasts.forEach(forecast => {
                const date = new Date(forecast.dt * 1000);
                const dayName = bengaliDays[date.getDay()];
                const temp = Math.round(forecast.main.temp);
                const condition = forecast.weather[0].main;
                const rainProb = Math.round((forecast.pop || 0) * 100);

                const card = document.createElement('div');
                card.className = 'col';
                card.innerHTML = `
                    <div class="forecast-card">
                        <h6 style="color: #000; font-weight: 900; text-shadow: 2px 2px 8px rgba(255, 255, 255, 1);">
                            ${dayName}
                        </h6>
                        <div class="forecast-icon">
                            ${getWeatherIconHTML(condition)}
                        </div>
                        <div style="font-size: 24px; font-weight: 900; color: #000; text-shadow: 2px 2px 8px rgba(255, 255, 255, 1);">
                            ${temp}°C
                        </div>
                        <div style="font-size: 14px; font-weight: 700; color: #555; text-shadow: 1px 1px 4px rgba(255, 255, 255, 0.9);">
                            ${weatherTranslations[condition] || condition}
                        </div>
                        <div style="font-size: 12px; color: #3498db; font-weight: 700; margin-top: 5px;">
                            <i class="fas fa-droplet"></i> ${rainProb}%
                        </div>
                    </div>
                `;
                forecastContainer.appendChild(card);
            });
        }

        // Get weather icon HTML
        function getWeatherIconHTML(condition) {
            const icons = {
                'Clear': '<i class="fas fa-sun" style="color: #f39c12;"></i>',
                'Clouds': '<i class="fas fa-cloud" style="color: #95a5a6;"></i>',
                'Rain': '<i class="fas fa-cloud-rain" style="color: #3498db;"></i>',
                'Drizzle': '<i class="fas fa-cloud-drizzle" style="color: #3498db;"></i>',
                'Thunderstorm': '<i class="fas fa-cloud-bolt" style="color: #e74c3c;"></i>',
                'Snow': '<i class="fas fa-snowflake" style="color: #3498db;"></i>',
                'Mist': '<i class="fas fa-smog" style="color: #95a5a6;"></i>'
            };
            return icons[condition] || icons['Clear'];
        }

        // Generate comprehensive weather-based farming advisories
        function generateAdvisories(current, forecast) {
            const advisories = [];
            const temp = current.main.temp;
            const condition = current.weather[0].main;
            const humidity = current.main.humidity;
            const windSpeed = current.wind.speed * 3.6;
            const rainChance = current.clouds?.all || 0;

            // Debug: Log weather conditions
            console.log('🌦️ আবহাওয়া তথ্য:', {
                'তাপমাত্রা': temp + '°C',
                'অবস্থা': condition,
                'আর্দ্রতা': humidity + '%',
                'বাতাস': windSpeed.toFixed(1) + ' km/h',
                'মেঘ': rainChance + '%'
            });

            // Check for rain in next 2 days
            const upcomingRain = forecast.list.slice(0, 16).some(item => 
                item.weather[0].main === 'Rain' || item.weather[0].main === 'Thunderstorm'
            );

            // Check for continuous rain (flood risk)
            const heavyRainDays = forecast.list.slice(0, 24).filter(item => 
                item.weather[0].main === 'Rain' || item.weather[0].main === 'Thunderstorm'
            ).length;

            console.log('🔍 বিশ্লেষণ:', {
                'আগামী বৃষ্টি': upcomingRain ? 'হ্যাঁ' : 'না',
                'ভারী বৃষ্টির দিন': heavyRainDays
            });

            // 🌧️ RAIN & THUNDERSTORM - Heavy Rain Advisory
            if (condition === 'Thunderstorm' || heavyRainDays > 5) {
                advisories.push({
                    type: 'danger',
                    icon: 'cloud-bolt',
                    title: '⛈️ ঝড় ও বজ্রপাত সতর্কতা',
                    advice: '🔴 জরুরি পদক্ষেপ: খোলা মাঠে কাজ বন্ধ করুন। নিরাপদ আশ্রয়ে থাকুন। বিদ্যুৎ চালিত যন্ত্রপাতি বন্ধ রাখুন।',
                    action: '✅ করণীয়: পাকা ফসল দ্রুত ঘরে তুলুন। গাছের নিচে আশ্রয় নেবেন না। পশুপাখি নিরাপদ স্থানে রাখুন। জমির পানি নিষ্কাশনের ব্যবস্থা করুন।'
                });
            }
            // 🌧️ Regular Rain Advisory
            else if (upcomingRain || condition === 'Rain') {
                advisories.push({
                    type: 'danger',
                    icon: 'cloud-showers-heavy',
                    title: '☔ বৃষ্টি হবে - প্রস্তুতি নিন',
                    advice: '🌾 ফসল রক্ষা: পাকা ধান/গম থাকলে দ্রুত কেটে ঘরে তুলুন। বীজতলা উঁচু করুন। সার ও কীটনাশক স্প্রে এখন করবেন না।',
                    action: '✅ করণীয়: সেচ বন্ধ রাখুন। নিচু জমিতে নালা কেটে পানি বের করার ব্যবস্থা করুন। ফসলের গোড়ায় মাটি তুলে দিন যাতে পানি না জমে। সংরক্ষিত বীজ ও সার শুকনো জায়গায় রাখুন।'
                });
            }
            // 🌧️ Post-Rain / High Humidity - Disease Risk
            else if (humidity > 85 && condition === 'Clouds') {
                advisories.push({
                    type: 'warning',
                    icon: 'droplet',
                    title: '💧 বৃষ্টির পর - রোগ দমন জরুরি',
                    advice: '⚠️ সতর্কতা: আর্দ্র আবহাওয়ায় ছত্রাক রোগ, পাতা পচা, ব্লাস্ট রোগ দ্রুত ছড়ায়। ফসলের পাতায় হলুদ দাগ বা পচন দেখলে দ্রুত ব্যবস্থা নিন।',
                    action: '✅ করণীয়: অনুমোদিত ছত্রাকনাশক (ব্যাভিস্টিন/টিল্ট) স্প্রে করুন। আক্রান্ত পাতা কেটে পুড়িয়ে ফেলুন। জমিতে বাতাস চলাচলের জন্য ঘন চারা পাতলা করুন। জমিতে জমে থাকা পানি সরান।'
                });
            }

            // ☀️ SUNNY & DRY - Perfect Weather Advisory
            if (condition === 'Clear' && temp >= 20 && temp <= 32 && humidity < 70) {
                advisories.push({
                    type: 'success',
                    icon: 'sun',
                    title: '☀️ রোদ ও শুষ্ক আবহাওয়া - সেরা সময়!',
                    advice: '🌟 আদর্শ কৃষিকাজ: এখন ফসল কাটা, মাড়াই, বীজ শুকানো, জমি চাষ, চারা রোপণের সবচেয়ে ভালো সময়। রোদে ধান/গম শুকিয়ে সংরক্ষণ করুন।',
                    action: '✅ করণীয়: সকালে জমি চাষ করুন। বীজতলা তৈরি করুন। সবজি ও মসলা চারা রোপণ করুন। সংরক্ষিত ফসল রোদে শুকান (আর্দ্রতা ১২-১৪%)। আগাছা পরিষ্কার করুন। জৈব সার মিশিয়ে মাটি তৈরি করুন। পাইপ দিয়ে হালকা সেচ দিন।'
                });
            }
            // ☀️ Hot & Dry Weather - Drought Risk
            else if (temp > 35 && condition === 'Clear') {
                advisories.push({
                    type: 'warning',
                    icon: 'temperature-high',
                    title: '🔥 খরা - অতিরিক্ত গরম সতর্কতা',
                    advice: '⚠️ খরার প্রভাব: মাটি শুকিয়ে ফাটল ধরবে। ফসলের পাতা মরে যাবে। ফুল ও ফল ঝরে যেতে পারে। ফলন কমে যাবে।',
                    action: '✅ করণীয়: সকাল ৬-৮টা বা বিকাল ৪-৬টায় সেচ দিন (দুপুরে নয়)। ফসলের গোড়ায় খড়/শুকনো ঘাস বিছিয়ে মালচিং করুন। ড্রিপ/পাইপ সেচ ব্যবহার করুন। খোলা মাটিতে পানি জমিয়ে রাখবেন না। সূর্যমুখী, ভুট্টা, শাকসবজিতে বেশি পানি দিন। দিনে ২-৩ বার হালকা স্প্রে করুন।'
                });
            }

            // ☁️ CLOUDY WEATHER - Good for Planting
            if (condition === 'Clouds' && !upcomingRain && temp >= 20 && temp <= 32) {
                advisories.push({
                    type: 'success',
                    icon: 'cloud-sun',
                    title: '☁️ মেঘলা আবহাওয়া - চারা রোপণের সময়',
                    advice: '🌱 চারা রোপণ সুবিধা: মেঘলা থাকায় রোদের তাপ কম। চারা শুকাবে না। শিকড় দ্রুত গজাবে। সফলতার হার বেশি।',
                    action: '✅ করণীয়: ধান, টমেটো, বেগুন, মরিচ, ফুলকপির চারা রোপণ করুন। কলম তৈরি ও গ্রাফটিং করুন। কীটনাশক ও ছত্রাকনাশক স্প্রে করুন (বৃষ্টি না হলে)। জৈব সার ও কম্পোস্ট মাটিতে মেশান। আগাছা পরিষ্কার করুন।'
                });
            }

            // 🌪️ STRONG WIND - Storm/Cyclone Advisory
            if (windSpeed > 40) {
                advisories.push({
                    type: 'danger',
                    icon: 'wind',
                    title: '🌪️ ঘূর্ণিঝড় সতর্কতা - প্রবল বাতাস',
                    advice: '🔴 জরুরি: ঘর থেকে বের হবেন না। টিনের চাল শক্ত করে বেঁধে রাখুন। বৈদ্যুতিক লাইন থেকে দূরে থাকুন।',
                    action: '✅ করণীয়: কলা, পেঁপে, নারিকেল গাছে খুঁটি দিয়ে বাঁধুন। গাছের ডালপালা কেটে হালকা করুন। পলিথিন শেড শক্ত করে বাঁধুন। ঘরের জানালা দরজা বন্ধ করুন। পশু শেডে নিরাপদ রাখুন। হাঁস-মুরগি ঘরে তুলুন। বাঁশ/কাঠের খুঁটি দিয়ে ঘর সাপোর্ট দিন।'
                });
            }
            // 💨 Moderate Wind - Beneficial
            else if (windSpeed >= 15 && windSpeed <= 30 && condition !== 'Rain') {
                advisories.push({
                    type: 'success',
                    icon: 'wind',
                    title: '💨 হালকা বাতাস - ফসলের জন্য ভাল',
                    advice: '🌾 উপকারিতা: পরাগায়ন ভাল হয়। রোগ পোকা কম হয়। ফসল শক্ত ও সুস্থ থাকে।',
                    action: '✅ করণীয়: ভুট্টা, সূর্যমুখী, সরিষা ফসলের পরাগায়নের সময়। কীটনাশক স্প্রে করুন (তরল দ্রুত শুকিয়ে যাবে)। লতানো সবজিতে মাচা তৈরি করুন।'
                });
            }

            // ❄️ COLD WEATHER - Winter Advisory
            if (temp < 12) {
                advisories.push({
                    type: 'warning',
                    icon: 'temperature-low',
                    title: '❄️ শীত - ঠান্ডা থেকে ফসল রক্ষা',
                    advice: '⚠️ ঠান্ডার প্রভাব: আলু, টমেটোতে নাবী ধসা রোগ। ফুলকপি পচে যাওয়া। ধানে শীষ বের না হওয়া।',
                    action: '✅ করণীয়: সকালে হালকা সেচ দিন (রাতে নয়)। চারা গাছে পলিথিন ঢাকা দিন রাতে। ধোঁয়া দিয়ে তাপমাত্রা বাড়ান। ঠান্ডা সহনশীল জাতের ফসল (শাক, মটর, সরিষা) বপন করুন। গাছের গোড়ায় মাটি/খড় জড়িয়ে দিন।'
                });
            }

            // 🌊 FLOOD RISK - Continuous Heavy Rain
            if (heavyRainDays > 8) {
                advisories.push({
                    type: 'danger',
                    icon: 'water',
                    title: '🌊 বন্যার সম্ভাবনা - জরুরি প্রস্তুতি',
                    advice: '🔴 বন্যা সতর্কতা: নদীর পানি বৃদ্ধি পাচ্ছে। নিচু এলাকা প্লাবিত হতে পারে। ফসল ক্ষতিগ্রস্ত হবে।',
                    action: '✅ করণীয়: পাকা ফসল দ্রুত কেটে উঁচু স্থানে সংরক্ষণ করুন। বীজ, সার, কীটনাশক নিরাপদ জায়গায় তুলুন। পশুখাদ্য মজুদ করুন। ভাসমান সবজি চাষ শুরু করুন। মাছ চাষের জাল মজবুত করুন। জরুরি যোগাযোগ নম্বর সংরক্ষণ করুন। উঁচু শুকনো জায়গায় আশ্রয় নিন।'
                });
            }

            // 🌡️ MODERATE & GOOD Weather - General Tips
            if (temp >= 22 && temp <= 30 && humidity >= 50 && humidity <= 75 && condition !== 'Rain') {
                advisories.push({
                    type: 'success',
                    icon: 'seedling',
                    title: '🌡️ আদর্শ আবহাওয়া - সব কাজের সময়',
                    advice: '✨ পারফেক্ট কন্ডিশন: না গরম না ঠান্ডা। না বেশি শুকনো না বেশি ভেজা। সব ধরনের কৃষিকাজের জন্য সেরা সময়!',
                    action: '✅ করণীয়: জমি চাষ ও বীজ বপন করুন। সার প্রয়োগ করুন (ইউরিয়া, টিএসপি, এমওপি)। আগাছা দমন করুন। রোগ পোকার আক্রমণ পরীক্ষা করুন। প্রয়োজনে কীটনাশক দিন। সেচ ব্যবস্থা পরীক্ষা করুন। পানির পাম্প ঠিক রাখুন। নতুন ফসলের পরিকল্পনা করুন। কৃষি কর্মকর্তার পরামর্শ নিন।'
                });
            }

            // 🐛 Pest Control Advisory based on weather
            if (temp > 28 && humidity > 70 && condition !== 'Rain') {
                advisories.push({
                    type: 'warning',
                    icon: 'bug',
                    title: '🐛 পোকামাকড় বৃদ্ধির সম্ভাবনা',
                    advice: '⚠️ গরম ও আর্দ্র আবহাওয়ায় পোকার আক্রমণ বাড়ে। ফসলে মাজরা, পাতা মোড়ানো, জাব পোকা দেখা দিতে পারে।',
                    action: '✅ করণীয়: নিয়মিত ক্ষেত পরিদর্শন করুন। হাত দিয়ে পোকা ধরে মারুন। হলুদ/নীল আঠালো ফাঁদ ব্যবহার করুন। জৈব কীটনাশক (নিমতেল) স্প্রে করুন। প্রয়োজনে রাসায়নিক কীটনাশক ব্যবহার করুন। উপকারী পোকা (লেডিবার্ড) রক্ষা করুন।'
                });
            }

            displayAdvisories(advisories);
        }

        // Display advisories
        function displayAdvisories(advisories) {
            const container = document.getElementById('advisoryContainer');
            
            if (advisories.length === 0) {
                container.innerHTML = `
                    <div class="advisory-card">
                        <h5 style="color: #000; font-weight: 900;">
                            <i class="fas fa-check-circle" style="color: #27ae60;"></i> সাধারণ আবহাওয়া
                        </h5>
                        <p style="color: #000; font-weight: 700;">
                            বর্তমান আবহাওয়া স্বাভাবিক। নিয়মিত কৃষিকাজ চালিয়ে যান।
                        </p>
                    </div>
                `;
                return;
            }

            container.innerHTML = advisories.map(adv => `
                <div class="advisory-card ${adv.type}">
                    <h5 style="color: #000; font-weight: 900; margin-bottom: 15px;">
                        <i class="fas fa-${adv.icon}"></i> ${adv.title}
                    </h5>
                    <div style="margin-bottom: 10px;">
                        <strong style="color: #000; font-weight: 900;">পরামর্শ:</strong>
                        <p style="color: #000; font-weight: 700; margin: 5px 0;">${adv.advice}</p>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <strong style="color: #000; font-weight: 900;">করণীয়:</strong>
                        <p style="color: #000; font-weight: 700; margin: 5px 0;">${adv.action}</p>
                    </div>
                    ${adv.timing ? `
                    <div style="background: rgba(255, 255, 255, 0.3); padding: 10px; border-radius: 10px; margin-top: 10px;">
                        <p style="color: #000; font-weight: 800; margin: 0;">${adv.timing}</p>
                    </div>
                    ` : ''}
                </div>
            `).join('');
        }

        // Show/hide loading
        function showLoading() {
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('weatherContainer').style.display = 'none';
            document.getElementById('defaultTips').style.display = 'none';
        }

        function hideLoading() {
            document.getElementById('loadingState').style.display = 'none';
        }

        function showWeatherContainer() {
            document.getElementById('weatherContainer').style.display = 'block';
            document.getElementById('defaultTips').style.display = 'none';
        }
    </script>
</body>
</html>
