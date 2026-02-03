<?php
require_once '../config/config.php';

// Check if user is logged in and is farmer
if (!isLoggedIn() || getUserRole() !== 'farmer') {
    redirect('../auth/login.php');
}

$farmer_id = $_SESSION['user_id'];

// Get farmer statistics
$stats = [];
$stats['total_posts'] = $conn->query("SELECT COUNT(*) as count FROM crop_posts WHERE farmer_id=$farmer_id")->fetch_assoc()['count'];
$stats['active_posts'] = $conn->query("SELECT COUNT(*) as count FROM crop_posts WHERE farmer_id=$farmer_id AND status='active'")->fetch_assoc()['count'];
$stats['sold_posts'] = $conn->query("SELECT COUNT(*) as count FROM crop_posts WHERE farmer_id=$farmer_id AND status='sold'")->fetch_assoc()['count'];

// Get recent posts
$recent_posts = $conn->query("SELECT cp.*, c.name_bn as crop_name 
                               FROM crop_posts cp 
                               JOIN crops c ON cp.crop_id = c.id 
                               WHERE cp.farmer_id = $farmer_id 
                               ORDER BY cp.created_at DESC LIMIT 5");

// Get today's crop prices
$today_prices = $conn->query("SELECT cp.*, c.name_bn as crop_name 
                              FROM crop_prices cp 
                              JOIN crops c ON cp.crop_id = c.id 
                              WHERE cp.date_recorded = CURDATE() 
                              LIMIT 5");

// Get weather advisory
$weather_advisory = $conn->query("SELECT * FROM weather_advisory WHERE is_active=1 ORDER BY priority DESC LIMIT 3");

// Check if weather_advisory table is empty and insert sample data if needed
if (!$weather_advisory || $weather_advisory->num_rows == 0) {
    // Insert sample weather advisory
    $conn->query("INSERT INTO weather_advisory (weather_condition, advisory_bn, priority, is_active) VALUES 
        ('ভারী বৃষ্টি', 'আগামী ৩ দিন ভারী বৃষ্টির সম্ভাবনা রয়েছে। ফসল সংরক্ষণের ব্যবস্থা নিন এবং নিচু এলাকার ফসল তুলে রাখুন।', 'high', 1),
        ('খরা', 'আগামী সপ্তাহে বৃষ্টির সম্ভাবনা কম। সেচের ব্যবস্থা করুন এবং পানি সাশ্রয়ী ফসল চাষ করুন।', 'medium', 1),
        ('মাঝারি বৃষ্টি', 'আগামী ২ দিন মাঝারি বৃষ্টির সম্ভাবনা। রোপণের জন্য উপযুক্ত সময়। সার প্রয়োগে বিলম্ব করুন।', 'low', 1)");
    $weather_advisory = $conn->query("SELECT * FROM weather_advisory WHERE is_active=1 ORDER BY priority DESC LIMIT 3");
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>কৃষক ড্যাশবোর্ড - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Animated Background */
        body {
            background: 
                linear-gradient(-45deg, 
                    rgba(34, 193, 195, 0.08), 
                    rgba(45, 253, 122, 0.08), 
                    rgba(255, 209, 102, 0.08), 
                    rgba(102, 126, 234, 0.08)
                ),
                url('../agrologo/ADC_climate_smart_agro-2-min.jpg') center center / cover fixed;
            background-size: cover;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../agrologo/IoT-in-agriculture-–-6-smart-farming-examples-1.png');
            background-position: bottom right;
            background-repeat: no-repeat;
            background-size: 400px;
            opacity: 0.05;
            pointer-events: none;
            z-index: 0;
        }

        /* Page Title Animation */
        h1.h2 {
            animation: slideInLeft 0.8s ease-out, colorPulse 3s infinite;
            text-shadow: 3px 3px 10px rgba(255, 255, 255, 1), 0 0 20px rgba(255, 255, 255, 1), 2px 2px 5px rgba(255, 255, 255, 1);
            color: #000;
            font-weight: 900;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes colorPulse {
            0%, 100% { color: #2c3e50; }
            50% { color: #16a085; }
        }

        /* Stats Cards Animation */
        .stat-card {
            animation: fadeInUp 0.8s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            backdrop-filter: blur(3px);
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }

        .stat-card h6 {
            font-weight: 900;
            text-shadow: 2px 2px 8px rgba(255, 255, 255, 0.5);
        }

        .stat-card:hover {
            transform: translateY(-15px) scale(1.05) rotate(2deg);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { left: -50%; }
            100% { left: 150%; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-icon i {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-10px) scale(1.1); }
        }

        /* Number Counter Animation */
        .stat-card h2 {
            font-size: 3rem;
            font-weight: 900;
            animation: countUp 1s ease-out;
            text-shadow: 2px 2px 10px rgba(255, 255, 255, 0.5), 0 0 15px rgba(255, 255, 255, 0.3);
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Cards with Glass Effect */
        .card {
            animation: fadeIn 0.8s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            backdrop-filter: blur(3px);
            background: rgba(255, 255, 255, 0);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.1);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
            border-color: rgba(102, 126, 234, 0.5);
            background: rgba(255, 255, 255, 0.03);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .card-header {
            border-radius: 20px 20px 0 0 !important;
            font-weight: 900;
            padding: 20px;
            animation: shimmer 3s infinite;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        @keyframes shimmer {
            0%, 100% { filter: brightness(1); }
            50% { filter: brightness(1.2); }
        }

        .card-header i {
            animation: rotate 3s infinite;
            display: inline-block;
        }

        @keyframes rotate {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(360deg); }
        }

        /* Action Buttons */
        .btn {
            border-radius: 15px;
            font-weight: 900;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .btn i {
            transition: transform 0.3s ease;
        }

        .btn:hover i {
            transform: scale(1.2) rotate(10deg);
        }

        .btn {
            color: #fff !important;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        }

        .btn-warning {
            color: #000 !important;
            text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.8);
        }

        /* Quick Action Buttons */
        .btn.py-3 {
            animation: pulse 2s infinite;
        }

        .btn.py-3:nth-child(1) { animation-delay: 0s; }
        .btn.py-3:nth-child(2) { animation-delay: 0.3s; }
        .btn.py-3:nth-child(3) { animation-delay: 0.6s; }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            50% {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }
        }

        /* Alert Animations */
        .alert {
            animation: slideInRight 0.6s ease-out;
            border-radius: 15px;
            border-left: 5px solid;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            font-weight: 900;
            backdrop-filter: blur(3px);
            color: #000 !important;
            text-shadow: 1px 1px 3px rgba(255, 255, 255, 1);
        }

        .alert h6 {
            font-weight: 900;
            color: #000 !important;
            text-shadow: 2px 2px 8px rgba(255, 255, 255, 1);
        }

        .alert p {
            color: #000 !important;
            font-weight: 800;
            text-shadow: 1px 1px 5px rgba(255, 255, 255, 1);
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Table Styling */
        .table {
            background: rgba(255, 255, 255, 0.75);
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            animation: fadeIn 0.5s ease-out;
        }

        .table tbody tr td,
        .table thead tr th {
            font-weight: 900;
            color: #000 !important;
            text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.8);
        }

        .table thead tr th {
            color: #fff !important;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.08);
            transform: scale(1.02);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        /* Badge Animations */
        .badge {
            animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-weight: 700;
            padding: 8px 12px;
        }

        @keyframes popIn {
            0% {
                opacity: 0;
                transform: scale(0);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Welcome Text Animation */
        .text-muted {
            animation: fadeIn 1s ease-out;
            font-weight: 900;
            color: #000 !important;
            text-shadow: 2px 2px 8px rgba(255, 255, 255, 1), 0 0 10px rgba(255, 255, 255, 1);
        }

        /* Main Content Area */
        main {
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            z-index: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-card h2 {
                font-size: 2rem;
            }
        }

        /* Loading Animation for Stats */
        .card-body {
            animation: fadeIn 1s ease-out;
        }

        /* Icon Glow Effect */
        .fa-tractor, .fa-seedling, .fa-check-circle, .fa-check-double {
            animation: glow 2s infinite;
        }

        @keyframes glow {
            0%, 100% {
                filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.5));
            }
            50% {
                filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.8));
            }
        }

        /* Hover Effect for Rows */
        .g-4 {
            animation: fadeIn 0.8s ease-out;
        }

        /* Text Shadow for Better Readability */
        h5, h6 {
            font-weight: 900;
            color: #000;
            text-shadow: 2px 2px 10px rgba(255, 255, 255, 1), 0 0 15px rgba(255, 255, 255, 1);
        }

        .card-header h5 {
            color: #fff !important;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4);
        }

        /* Card Body Background */
        .card-body {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(3px);
        }

        .card-body p {
            color: #000 !important;
            font-weight: 800;
            text-shadow: 1px 1px 5px rgba(255, 255, 255, 1);
        }

        /* Soft/Pastel Backgrounds for Stat Cards */
        .stat-card.bg-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.8), rgba(32, 134, 55, 0.8)) !important;
        }

        .stat-card.bg-primary {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.8), rgba(10, 88, 202, 0.8)) !important;
        }

        .stat-card.bg-info {
            background: linear-gradient(135deg, rgba(13, 202, 240, 0.8), rgba(10, 162, 192, 0.8)) !important;
        }

        .btn-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.8), rgba(32, 134, 55, 0.8)) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.8), rgba(10, 88, 202, 0.8)) !important;
        }

        .btn-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.8), rgba(204, 154, 6, 0.8)) !important;
            color: #000 !important;
            text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.8);
        }

        .card-header.bg-success,
        .card-header.bg-primary,
        .card-header.bg-info {
            background: linear-gradient(135deg, var(--bs-success) 0%, darken(var(--bs-success), 20%) 100%) !important;
        }

        /* Soft/Pastel Backgrounds for Stat Cards */
        .stat-card.bg-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.8), rgba(32, 134, 55, 0.8)) !important;
        }

        .stat-card.bg-primary {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.8), rgba(10, 88, 202, 0.8)) !important;
        }

        .stat-card.bg-info {
            background: linear-gradient(135deg, rgba(13, 202, 240, 0.8), rgba(10, 162, 192, 0.8)) !important;
        }

        .btn-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.8), rgba(32, 134, 55, 0.8)) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.8), rgba(10, 88, 202, 0.8)) !important;
        }

        .btn-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.8), rgba(204, 154, 6, 0.8)) !important;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2"><i class="fas fa-tractor"></i> কৃষক ড্যাশবোর্ড</h1>
                    <div>
                        <span class="text-muted">স্বাগতম, <?php echo $_SESSION['name']; ?></span>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">মোট পোস্ট</h6>
                                        <h2 class="mb-0"><?php echo $stats['total_posts']; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-seedling fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stat-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">সক্রিয় পোস্ট</h6>
                                        <h2 class="mb-0"><?php echo $stats['active_posts']; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stat-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">বিক্রিত</h6>
                                        <h2 class="mb-0"><?php echo $stats['sold_posts']; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-check-double fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-bolt"></i> দ্রুত কাজ</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <a href="add_crop_post.php" class="btn btn-success w-100 py-3">
                                            <i class="fas fa-plus fa-2x d-block mb-2"></i>
                                            নতুন ফসল পোস্ট করুন
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="view_prices.php" class="btn btn-primary w-100 py-3">
                                            <i class="fas fa-chart-line fa-2x d-block mb-2"></i>
                                            ফসলের দাম দেখুন
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="my_posts.php" class="btn btn-warning w-100 py-3">
                                            <i class="fas fa-list fa-2x d-block mb-2"></i>
                                            আমার পোস্ট দেখুন
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Weather Advisory -->
                <?php if ($weather_advisory->num_rows > 0): ?>
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-cloud-sun-rain"></i> আবহাওয়া পরামর্শ</h5>
                            </div>
                            <div class="card-body">
                                <?php while($advisory = $weather_advisory->fetch_assoc()): ?>
                                <div class="alert alert-<?php echo $advisory['priority'] === 'high' ? 'danger' : ($advisory['priority'] === 'medium' ? 'warning' : 'info'); ?>">
                                    <h6><strong><?php echo htmlspecialchars($advisory['weather_condition'], ENT_QUOTES, 'UTF-8'); ?></strong></h6>
                                    <p class="mb-0"><?php echo htmlspecialchars($advisory['advisory_bn'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row g-4">
                    <!-- Today's Crop Prices -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-chart-line"></i> আজকের ফসলের দাম</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($today_prices->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>ফসল</th>
                                                <th>এলাকা</th>
                                                <th>গড় দাম</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($price = $today_prices->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($price['crop_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($price['location'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><strong>৳<?php echo number_format($price['price_avg'], 2); ?>/<?php echo htmlspecialchars($price['unit'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="view_prices.php" class="btn btn-sm btn-primary">সব দাম দেখুন</a>
                                <?php else: ?>
                                <p class="text-muted">আজকের দাম এখনো আপডেট হয়নি</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-seedling"></i> আমার সাম্প্রতিক পোস্ট</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($recent_posts->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>ফসল</th>
                                                <th>পরিমাণ</th>
                                                <th>দাম</th>
                                                <th>অবস্থা</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($post = $recent_posts->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($post['crop_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($post['quantity'] . ' ' . $post['unit'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>৳<?php echo number_format($post['price'], 2); ?></td>
                                                <td>
                                                    <?php if($post['status'] == 'active'): ?>
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    <?php elseif($post['status'] == 'sold'): ?>
                                                        <span class="badge bg-info">বিক্রিত</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">মেয়াদ শেষ</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="my_posts.php" class="btn btn-sm btn-success">সব পোস্ট দেখুন</a>
                                <?php else: ?>
                                <p class="text-muted">আপনি এখনো কোনো পোস্ট করেননি</p>
                                <a href="add_crop_post.php" class="btn btn-success">প্রথম পোস্ট করুন</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Counter Animation for Stats
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.stat-card h2');
            
            counters.forEach(counter => {
                const target = parseInt(counter.innerText);
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;
                
                const updateCounter = () => {
                    current += step;
                    if (current < target) {
                        counter.innerText = Math.floor(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.innerText = target;
                    }
                };
                
                updateCounter();
            });

            // Add ripple effect to buttons
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    let ripple = document.createElement('span');
                    ripple.classList.add('ripple-effect');
                    this.appendChild(ripple);
                    
                    let x = e.clientX - e.target.offsetLeft;
                    let y = e.clientY - e.target.offsetTop;
                    
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                    ripple.style.width = '0';
                    ripple.style.height = '0';
                    ripple.style.transform = 'translate(-50%, -50%)';
                    ripple.style.animation = 'ripple 0.6s ease-out';
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Animate table rows on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animation = 'fadeIn 0.6s ease-out';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.card').forEach(card => {
                observer.observe(card);
            });
        });

        // Add ripple animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    width: 200px;
                    height: 200px;
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
