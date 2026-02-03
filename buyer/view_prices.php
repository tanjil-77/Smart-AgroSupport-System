<?php
require_once '../config/config.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

// Get filter
$filter_crop = isset($_GET['crop']) ? sanitize($_GET['crop']) : '';
$filter_location = isset($_GET['location']) ? sanitize($_GET['location']) : '';

// Build query with proper GROUP BY to avoid duplicates
$query = "SELECT 
            cp.crop_id,
            cp.location,
            MAX(cp.price_min) as price_min,
            MAX(cp.price_max) as price_max,
            AVG(cp.price_avg) as price_avg,
            cp.unit,
            MAX(cp.date_recorded) as date,
            c.name_bn as crop_name,
            c.name_en,
            c.id as crop_id
          FROM crop_prices cp 
          JOIN crops c ON cp.crop_id = c.id 
          WHERE cp.date_recorded >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";

if ($filter_crop) {
    $query .= " AND c.id = '" . $filter_crop . "'";
}
if ($filter_location) {
    $query .= " AND cp.location = '" . $filter_location . "'";
}

$query .= " GROUP BY cp.crop_id, cp.location, c.name_bn, c.name_en, c.id, cp.unit";
$query .= " ORDER BY MAX(cp.date_recorded) DESC, cp.location, c.name_bn";

$prices = $conn->query($query);

// Debug - uncomment to see query
// echo "Query: " . $query . "<br>";
// echo "Results: " . ($prices ? $prices->num_rows : 0) . "<br>";

// Get all crops for filter
$all_crops = $conn->query("SELECT * FROM crops ORDER BY name_bn");

// Manual crop names mapping (fallback)
$crop_names_bn = [
    1 => 'ধান',
    2 => 'গম',
    3 => 'আলু',
    4 => 'পেঁয়াজ',
    5 => 'টমেটো',
    6 => 'পাট',
    7 => 'ভুট্টা',
    8 => 'মসুর ডাল',
    9 => 'সরিষা',
    10 => 'আখ',
    11 => 'রসুন',
    12 => 'বেগুন',
    13 => 'মরিচ',
    14 => 'শিম',
    15 => 'পেপে',
    16 => 'লাউ',
    17 => 'মিষ্টি কুমড়া'
];

$crop_names_en = [
    1 => 'Rice',
    2 => 'Wheat',
    3 => 'Potato',
    4 => 'Onion',
    5 => 'Tomato',
    6 => 'Jute',
    7 => 'Corn',
    8 => 'Lentil',
    9 => 'Mustard',
    10 => 'Sugarcane',
    11 => 'Garlic',
    12 => 'Eggplant',
    13 => 'Chili',
    14 => 'Bean',
    15 => 'Papaya',
    16 => 'Bottle Gourd',
    17 => 'Sweet Pumpkin'
];

// English to Bengali district mapping
$district_map = [
    'Dhaka'=>'ঢাকা', 'Gazipur'=>'গাজীপুর', 'Narayanganj'=>'নারায়ণগঞ্জ', 'Tangail'=>'টাঙ্গাইল', 'Kishoreganj'=>'কিশোরগঞ্জ', 'Manikganj'=>'মানিকগঞ্জ', 'Munshiganj'=>'মুন্সিগঞ্জ', 'Narsingdi'=>'নরসিংদী', 'Faridpur'=>'ফরিদপুর', 'Gopalganj'=>'গোপালগঞ্জ', 'Madaripur'=>'মাদারীপুর', 'Rajbari'=>'রাজবাড়ী', 'Shariatpur'=>'শরীয়তপুর',
    'Chittagong'=>'চট্টগ্রাম', 'Cox'=>'কক্সবাজার', 'Rangamati'=>'রাঙ্গামাটি', 'Bandarban'=>'বান্দরবান', 'Khagrachari'=>'খাগড়াছড়ি', 'Cumilla'=>'কুমিল্লা', 'Chandpur'=>'চাঁদপুর', 'Brahmanbaria'=>'ব্রাহ্মণবাড়িয়া', 'Noakhali'=>'নোয়াখালী', 'Feni'=>'ফেনী', 'Lakshmipur'=>'লক্ষ্মীপুর',
    'Sylhet'=>'সিলেট', 'Moulvibazar'=>'মৌলভীবাজার', 'Habiganj'=>'হবিগঞ্জ', 'Sunamganj'=>'সুনামগঞ্জ',
    'Rajshahi'=>'রাজশাহী', 'Natore'=>'নাটোর', 'Naogaon'=>'নওগাঁ', 'Chapainawabganj'=>'চাঁপাইনবাবগঞ্জ', 'Pabna'=>'পাবনা', 'Sirajganj'=>'সিরাজগঞ্জ', 'Bogura'=>'বগুড়া', 'Joypurhat'=>'জয়পুরহাট',
    'Khulna'=>'খুলনা', 'Bagerhat'=>'বাগেরহাট', 'Satkhira'=>'সাতক্ষীরা', 'Jashore'=>'যশোর', 'Jhenaidah'=>'ঝিনাইদহ', 'Magura'=>'মাগুরা', 'Narail'=>'নড়াইল', 'Kushtia'=>'কুষ্টিয়া', 'Chuadanga'=>'চুয়াডাঙ্গা', 'Meherpur'=>'মেহেরপুর',
    'Barishal'=>'বরিশাল', 'Patuakhali'=>'পটুয়াখালী', 'Bhola'=>'ভোলা', 'Barguna'=>'বরগুনা', 'Jhalokati'=>'ঝালকাঠি', 'Pirojpur'=>'পিরোজপুর',
    'Rangpur'=>'রংপুর', 'Dinajpur'=>'দিনাজপুর', 'Thakurgaon'=>'ঠাকুরগাঁও', 'Panchagarh'=>'পঞ্চগড়', 'Nilphamari'=>'নীলফামারী', 'Lalmonirhat'=>'লালমনিরহাট', 'Kurigram'=>'কুড়িগ্রাম', 'Gaibandha'=>'গাইবান্ধা',
    'Mymensingh'=>'ময়মনসিংহ', 'Jamalpur'=>'জামালপুর', 'Netrokona'=>'নেত্রকোনা', 'Sherpur'=>'শেরপুর'
];

// Get unique locations from database
$locations_query = $conn->query("SELECT DISTINCT location FROM crop_prices ORDER BY location");
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ফসলের দাম - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Background Image Styling */
        body {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.03) 0%, rgba(32, 201, 151, 0.03) 100%), url('../agrologo/fosolerdam.jpg');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(40, 167, 69, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(32, 201, 151, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .page-header {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.65) 0%, rgba(32, 201, 151, 0.65) 100%);
            color: white;
            padding: 2rem 0;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
            animation: fadeInUp 0.6s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .page-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 3s infinite;
        }

        .search-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            padding: 2rem;
            margin-bottom: 2rem;
            animation: slideIn 0.6s ease;
            border: 2px solid rgba(40, 167, 69, 0.5);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .search-card:hover {
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
            transform: translateY(-3px);
            transition: all 0.3s ease;
            border-color: #28a745;
            background: rgba(255, 255, 255, 0.12);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            transform: scale(1.02);
        }

        .price-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(40, 167, 69, 0.5);
            height: 100%;
            animation: fadeInUp 0.6s ease;
            animation-fill-mode: both;
            backdrop-filter: blur(5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .price-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 20px 50px rgba(40, 167, 69, 0.4);
            border-color: #28a745;
            background: rgba(255, 255, 255, 0.12);
        }

        .price-card-header {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.75) 0%, rgba(32, 201, 151, 0.75) 100%);
            color: white;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .price-card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: pulse 3s infinite;
        }

        .price-card-body {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.85);
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border-bottom: 1px dashed rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
            background: rgba(40, 167, 69, 0.15);
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .price-row:hover {
            background: rgba(40, 167, 69, 0.25);
            transform: translateX(5px);
        }

        .price-row:last-child {
            border-bottom: none;
        }

        .price-label {
            font-weight: 700;
            color: #ffffff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        .price-value {
            font-weight: 800;
            font-size: 1.1rem;
            color: #ffffff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        .avg-price {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.85) 0%, rgba(255, 152, 0, 0.85) 100%);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
            text-align: center;
            animation: pulse 2s infinite;
            backdrop-filter: blur(10px);
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .avg-price .price-value {
            font-size: 2rem;
            color: white;
            display: block;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.4);
            font-weight: 800;
        }

        .location-badge {
            background: rgba(220, 53, 69, 0.85);
            color: #ffffff;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 700;
            margin-bottom: 1rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(220, 53, 69, 0.6);
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        .btn-search {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 123, 255, 0.4);
        }

        .btn-reset {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(108, 117, 125, 0.4);
        }

        .no-data {
            text-align: center;
            padding: 4rem 2rem;
            animation: fadeInUp 0.6s ease;
        }

        .no-data i {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .update-badge {
            background: rgba(0, 123, 255, 0.85);
            color: #ffffff;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            display: inline-block;
            margin-top: 0.5rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(0, 123, 255, 0.6);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
            font-weight: 600;
        }

        /* Stagger animation for cards */
        .price-card:nth-child(1) { animation-delay: 0.1s; }
        .price-card:nth-child(2) { animation-delay: 0.2s; }
        .price-card:nth-child(3) { animation-delay: 0.3s; }
        .price-card:nth-child(4) { animation-delay: 0.4s; }
        .price-card:nth-child(5) { animation-delay: 0.5s; }
        .price-card:nth-child(6) { animation-delay: 0.6s; }

        .icon-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* Main container with glass effect */
        main.col-md-9 {
            background: rgba(255, 255, 255, 0);
            backdrop-filter: blur(3px);
            border-radius: 20px 0 0 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* Navbar styling */
        nav.navbar {
            background: rgba(40, 167, 69, 0.5) !important;
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        /* Sidebar styling */
        .col-md-3 {
            background: rgba(40, 167, 69, 0.3);
            backdrop-filter: blur(15px);
        }

        /* Alert styling */
        .alert {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            border: 2px solid rgba(40, 167, 69, 0.5);
        }

        /* No data section */
        .no-data {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
        }
    </style>
</head>
<body>
    <?php 
    $role = getUserRole();
    if ($role === 'farmer') {
        include 'includes/navbar.php'; 
    } elseif ($role === 'buyer') {
        include '../buyer/includes/navbar.php'; 
    }
    ?>

    <div class="container-fluid">
        <div class="row">
            <?php 
            if ($role === 'farmer') {
                include 'includes/sidebar.php'; 
            } elseif ($role === 'buyer') {
                include '../buyer/includes/sidebar.php'; 
            }
            ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Page Header -->
                <div class="page-header text-center">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-chart-line icon-float"></i> ফসলের বাজার দর
                    </h1>
                    <p class="lead mb-0">বিভিন্ন এলাকায় ফসলের সর্বশেষ মূল্য তথ্য দেখুন</p>
                </div>

                <!-- Search Filter Card -->
                <div class="search-card">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h4 class="text-success mb-0">
                                <i class="fas fa-search"></i> ফসল ও জেলা ভিত্তিক খুঁজুন
                            </h4>
                            <small class="text-muted">ফসল ও জেলা নির্বাচন করে বাজার দর দেখুন</small>
                        </div>
                    </div>
                    
                    <form method="GET" action="" class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">
                                <i class="fas fa-seedling text-success"></i> ফসল নির্বাচন করুন
                            </label>
                            <select name="crop" class="form-select" id="cropSelect">
                                <option value="">সব ফসল দেখুন</option>
                                <?php while($crop = $all_crops->fetch_assoc()): 
                                    $crop_name = isset($crop_names_bn[$crop['id']]) ? $crop_names_bn[$crop['id']] : $crop['name_bn'];
                                    $crop_name_en = isset($crop_names_en[$crop['id']]) ? $crop_names_en[$crop['id']] : $crop['name_en'];
                                ?>
                                <option value="<?php echo $crop['id']; ?>" <?php echo $filter_crop == $crop['id'] ? 'selected' : ''; ?>>
                                    <?php echo $crop_name; ?> (<?php echo $crop_name_en; ?>)
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt text-danger"></i> জেলা নির্বাচন করুন
                            </label>
                            <select name="location" class="form-select" id="locationSelect">
                                <option value="">সকল জেলা দেখুন</option>
                                <?php 
                                // Sort districts by Bengali name
                                $sorted_districts = $district_map;
                                asort($sorted_districts);
                                foreach($sorted_districts as $eng => $bn): 
                                ?>
                                    <option value="<?php echo $eng; ?>" <?php echo $filter_location == $eng ? 'selected' : ''; ?>>
                                        <?php echo $bn; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-search btn-primary flex-fill">
                                    <i class="fas fa-search"></i> খুঁজুন
                                </button>
                                <?php if($filter_crop || $filter_location): ?>
                                <a href="view_prices.php" class="btn btn-reset btn-secondary">
                                    <i class="fas fa-redo"></i> রিসেট
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Results Info -->
                <?php if($filter_crop || $filter_location): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-filter"></i> 
                    <strong>সার্চ ফলাফল:</strong> 
                    <?php if($filter_crop): ?>
                        <span class="badge bg-success">নির্বাচিত ফসল</span>
                    <?php endif; ?>
                    <?php if($filter_location): ?>
                        <span class="badge bg-danger"><?php echo isset($district_map[$filter_location]) ? $district_map[$filter_location] : $filter_location; ?></span>
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Price Cards -->
                <div class="row g-4">
                    <?php if ($prices && $prices->num_rows > 0): ?>
                        <?php 
                        // No need for manual grouping, query already groups by crop_id and location
                        while($price = $prices->fetch_assoc()): 
                            // Use fallback names if database shows ???
                            $display_name = $price['crop_name'];
                            if (isset($price['crop_id']) && isset($crop_names_bn[$price['crop_id']])) {
                                $display_name = $crop_names_bn[$price['crop_id']];
                            }
                            // Convert English location to Bengali
                            $location_bn = isset($district_map[$price['location']]) ? $district_map[$price['location']] : $price['location'];
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="price-card">
                                <div class="price-card-header">
                                    <h5 class="mb-2 fw-bold">
                                        <i class="fas fa-seedling"></i> <?php echo $display_name; ?>
                                    </h5>
                                    <div class="location-badge">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo $location_bn; ?>
                                    </div>
                                </div>
                                
                                <div class="price-card-body">
                                    <div class="price-row">
                                        <span class="price-label">
                                            <i class="fas fa-arrow-down text-success"></i> সর্বনিম্ন দাম
                                        </span>
                                        <span class="price-value">
                                            ৳<?php echo number_format($price['price_min'], 2); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="price-row">
                                        <span class="price-label">
                                            <i class="fas fa-arrow-up text-danger"></i> সর্বোচ্চ দাম
                                        </span>
                                        <span class="price-value">
                                            ৳<?php echo number_format($price['price_max'], 2); ?>
                                        </span>
                                    </div>

                                    <div class="avg-price">
                                        <div class="price-label text-white mb-2">
                                            <i class="fas fa-balance-scale"></i> গড় দাম
                                        </div>
                                        <span class="price-value">
                                            ৳<?php echo number_format($price['price_avg'], 2); ?>
                                        </span>
                                        <small class="d-block mt-2" style="opacity: 0.9;">
                                            প্রতি <?php echo $price['unit']; ?>
                                        </small>
                                    </div>

                                    <div class="update-badge mt-3">
                                        <i class="fas fa-calendar-check"></i> 
                                        আপডেট: <?php echo date('d M Y', strtotime($price['date'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="no-data">
                                <i class="fas fa-search-minus"></i>
                                <h3 class="text-muted mb-3">কোনো দাম পাওয়া যায়নি</h3>
                                <p class="text-muted mb-4">
                                    <?php if($filter_crop || $filter_location): ?>
                                        নির্বাচিত ফিল্টারে এই মুহূর্তে কোনো মূল্য তথ্য উপলব্ধ নেই।
                                    <?php else: ?>
                                        এই মুহূর্তে কোনো মূল্য তথ্য পাওয়া যাচ্ছে না।
                                    <?php endif; ?>
                                </p>
                                <a href="view_prices.php" class="btn btn-success">
                                    <i class="fas fa-redo"></i> পুনরায় চেষ্টা করুন
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Info Section -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="alert alert-info" style="border-radius: 15px; animation: fadeInUp 0.6s ease;">
                            <h5><i class="fas fa-info-circle"></i> তথ্য সূত্র</h5>
                            <p class="mb-0">
                                এই মূল্য তথ্য বিভিন্ন বাজার থেকে সংগৃহীত এবং প্রতিদিন আপডেট করা হয়। 
                                সঠিক দাম জানতে স্থানীয় বাজারের সাথেও যোগাযোগ করুন।
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    
    <script>
        // Add smooth scroll to top button
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                if (!document.querySelector('.scroll-to-top')) {
                    const scrollBtn = document.createElement('button');
                    scrollBtn.className = 'scroll-to-top btn btn-success rounded-circle';
                    scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
                    scrollBtn.style.cssText = 'position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; z-index: 1000; box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);';
                    scrollBtn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
                    document.body.appendChild(scrollBtn);
                }
            } else {
                const btn = document.querySelector('.scroll-to-top');
                if (btn) btn.remove();
            }
        });

        // Add loading animation
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> খুঁজছি...';
            btn.disabled = true;
        });

        // Add hover effect to price cards
        document.querySelectorAll('.price-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>
