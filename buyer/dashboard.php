<?php
require_once '../config/config.php';

// Check if user is logged in and is buyer
if (!isLoggedIn() || getUserRole() !== 'buyer') {
    redirect('../auth/login.php');
}

// Get all active crop posts with farmer details
$filter_crop = isset($_GET['crop']) ? sanitize($_GET['crop']) : '';
$filter_location = isset($_GET['location']) ? sanitize($_GET['location']) : '';

$query = "SELECT cp.*, u.name as farmer_name, u.phone as farmer_phone, u.location as farmer_location, 
          c.name_bn as crop_name, c.name_en as crop_name_en
          FROM crop_posts cp 
          JOIN users u ON cp.farmer_id = u.id 
          JOIN crops c ON cp.crop_id = c.id 
          WHERE cp.status = 'active'";

if ($filter_crop) {
    $query .= " AND c.id = $filter_crop";
}
if ($filter_location) {
    $query .= " AND (cp.location LIKE '%$filter_location%' OR u.location LIKE '%$filter_location%')";
}

$query .= " ORDER BY cp.created_at DESC";
$crop_posts = $conn->query($query);

// Get all crops for filter
$all_crops = $conn->query("SELECT * FROM crops ORDER BY name_bn");

// Get statistics
$stats = [];
$stats['total_crops'] = $conn->query("SELECT COUNT(*) as count FROM crop_posts WHERE status='active'")->fetch_assoc()['count'];
$stats['total_farmers'] = $conn->query("SELECT COUNT(DISTINCT farmer_id) as count FROM crop_posts WHERE status='active'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ক্রেতা ড্যাশবোর্ড - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Background Styling - Crop Guide Pattern */
        body {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
            background-attachment: fixed;
            position: relative;
            font-weight: 600;
            min-height: 100vh;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('../assets/images/kreta.jpg') center/cover;
            background-attachment: fixed;
            opacity: 1;
            z-index: -2;
        }
        
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(240, 250, 255, 0.07) 100%);
            z-index: -1;
        }
        
        /* Header Styling */
        .h2 {
            color: #1a237e;
            font-weight: 900;
            text-shadow: 
                3px 3px 6px rgba(255,255,255,1),
                -2px -2px 4px rgba(255,255,255,0.9),
                0 0 25px rgba(255,255,255,1);
            animation: fadeInLeft 0.8s ease-out;
        }
        
        .h2 i {
            animation: bounce 2s infinite;
            color: #7c4dff;
        }
        
        .text-muted {
            color: #00695c !important;
            text-shadow: 
                3px 3px 5px rgba(255,255,255,1),
                -2px -2px 3px rgba(255,255,255,0.9);
            font-weight: 900;
        }
        
        /* Stat Cards Styling */
        .stat-card {
            background: rgba(255, 255, 255, 0.02) !important;
            border: 2px solid rgba(255, 255, 255, 0.4) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .stat-card .card-body {
            background: linear-gradient(135deg, rgb(17, 153, 142) 0%, rgb(56, 239, 125) 100%) !important;
            padding: 2rem;
            backdrop-filter: blur(15px);
        }
        
        .stat-card.bg-warning .card-body {
            background: linear-gradient(135deg, rgb(240, 147, 251) 0%, rgb(245, 87, 108) 100%) !important;
        }
        
        .stat-card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .stat-card h2 {
            font-weight: 900;
            font-size: 3rem;
            text-shadow: 3px 3px 8px rgba(0,0,0,0.8), -1px -1px 3px rgba(0,0,0,0.5), 0 0 15px rgba(0,0,0,0.6);
            animation: countUp 1s ease-out;
            color: #ffffff !important;
        }
        
        .stat-card h6 {
            font-weight: 900;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.7), -1px -1px 2px rgba(0,0,0,0.5);
            color: #ffffff !important;
            font-size: 1.1rem;
        }
        
        .stat-icon i {
            animation: rotate 4s linear infinite;
        }
        
        /* Card Styling */
        .card {
            background: rgba(255, 255, 255, 0) !important;
            border: 2px solid rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border-radius: 15px;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .card-body {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
        }
        
        .card-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%) !important;
            font-weight: 900;
            text-shadow: 
                2px 2px 3px rgba(255,255,255,0.4),
                -1px -1px 2px rgba(255,255,255,0.3);
            border: none !important;
            backdrop-filter: blur(10px);
        }
        
        /* Crop Cards */
        .crop-card {
            transition: all 0.3s ease;
            animation: slideIn 0.8s ease-out;
        }
        
        .crop-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border-color: rgba(102, 126, 234, 0.7) !important;
        }
        
        .crop-card .card-title {
            font-weight: 900;
            text-shadow: 
                3px 3px 5px rgba(255,255,255,1),
                0 0 20px rgba(17, 153, 142, 0.5);
            color: #00897b;
        }
        
        .crop-card .card-title i {
            animation: colorPulse 3s infinite;
        }
        
        .crop-details p {
            font-weight: 900;
            transition: all 0.3s ease;
            color: #1a237e;
            text-shadow: 3px 3px 5px rgba(255,255,255,1);
        }
        
        .crop-details p:hover {
            transform: translateX(5px);
            color: #7c4dff;
            text-shadow: 3px 3px 6px rgba(255,255,255,1);
        }
        
        .crop-details i {
            width: 20px;
            text-align: center;
            color: #7c4dff;
        }
        
        .crop-details strong {
            color: #00695c;
        }
        
        /* Badge Styling */
        .badge {
            font-weight: 900;
            padding: 0.5rem 1rem;
            animation: pulse 2s infinite;
            backdrop-filter: blur(10px);
        }
        
        /* Button Styling */
        .btn-success {
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.95) 0%, rgba(56, 239, 125, 0.95) 100%);
            border: 2px solid rgba(255, 255, 255, 0.4);
            font-weight: 900;
            box-shadow: 0 5px 15px rgba(56, 239, 125, 0.5);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(56, 239, 125, 0.7);
        }
        
        .btn-success::before {
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
        
        .btn-success:active::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
            border: 2px solid rgba(255, 255, 255, 0.4);
            font-weight: 900;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.5);
            transition: all 0.3s ease;
            animation: glow 2s infinite;
            backdrop-filter: blur(10px);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.7);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, rgba(134, 143, 150, 0.95) 0%, rgba(89, 97, 100, 0.95) 100%);
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-weight: 800;
            backdrop-filter: blur(10px);
        }
        
        .btn-secondary:hover {
            transform: scale(1.05);
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            font-weight: 800;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: scale(1.05);
        }
        
        /* Form Styling */
        .form-select, .form-control {
            font-weight: 900;
            border: 3px solid rgba(102, 126, 234, 0.7);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.98);
            color: #1a237e;
        }
        
        .form-select:focus, .form-control:focus {
            border-color: #38ef7d;
            box-shadow: 0 0 20px rgba(56, 239, 125, 0.7);
            transform: scale(1.02);
            background: rgba(255, 255, 255, 1);
        }
        
        .form-label {
            font-weight: 900;
            color: #1a237e;
            text-shadow: 
                3px 3px 5px rgba(255,255,255,1),
                -2px -2px 3px rgba(255,255,255,0.9);
        }
        
        /* Alert Styling */
        .alert {
            background: rgba(255, 255, 255, 0.97) !important;
            backdrop-filter: blur(25px);
            border: 4px solid rgba(102, 126, 234, 0.8);
            border-radius: 15px;
            animation: slideInRight 0.8s ease-out;
            font-weight: 900;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .alert i {
            animation: bounce 2s infinite;
            color: #667eea;
        }
        
        .alert h4 {
            font-weight: 900;
            color: #1a237e;
            text-shadow: 
                3px 3px 5px rgba(255,255,255,1),
                -2px -2px 3px rgba(255,255,255,1);
        }
        
        .alert p {
            color: #00695c;
            text-shadow: 2px 2px 4px rgba(255,255,255,1);
        }
        
        /* Price Styling */
        .fs-5 {
            font-size: 1.5rem !important;
            animation: shimmer 2s infinite;
        }
        
        /* Small text */
        small {
            color: #004d40 !important;
            font-weight: 900 !important;
            text-shadow: 2px 2px 4px rgba(255,255,255,1) !important;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
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
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
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
        
        @keyframes colorPulse {
            0%, 100% {
                color: #38ef7d;
            }
            50% {
                color: #11998e;
            }
        }
        
        @keyframes shimmer {
            0% {
                text-shadow: 0 0 5px rgba(56, 239, 125, 0.5);
            }
            50% {
                text-shadow: 0 0 20px rgba(56, 239, 125, 1), 0 0 30px rgba(17, 153, 142, 0.8);
            }
            100% {
                text-shadow: 0 0 5px rgba(56, 239, 125, 0.5);
            }
        }
        
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.5);
            }
            50% {
                box-shadow: 0 5px 30px rgba(102, 126, 234, 0.9);
            }
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
        
        /* Staggered animations for crop cards */
        .crop-card:nth-child(1) { animation-delay: 0.1s; }
        .crop-card:nth-child(2) { animation-delay: 0.2s; }
        .crop-card:nth-child(3) { animation-delay: 0.3s; }
        .crop-card:nth-child(4) { animation-delay: 0.4s; }
        .crop-card:nth-child(5) { animation-delay: 0.5s; }
        .crop-card:nth-child(6) { animation-delay: 0.6s; }
        .crop-card:nth-child(7) { animation-delay: 0.7s; }
        .crop-card:nth-child(8) { animation-delay: 0.8s; }
        .crop-card:nth-child(9) { animation-delay: 0.9s; }
        
        /* Ripple Effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
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
                    <h1 class="h2"><i class="fas fa-shopping-cart"></i> ফসল মার্কেটপ্লেস</h1>
                    <div>
                        <span class="text-muted">স্বাগতম, <?php echo $_SESSION['name']; ?></span>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">উপলব্ধ ফসল</h6>
                                        <h2 class="mb-0"><?php echo $stats['total_crops']; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-seedling fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card stat-card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">মোট কৃষক</h6>
                                        <h2 class="mb-0"><?php echo $stats['total_farmers']; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-users fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-filter"></i> ফিল্টার করুন</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">ফসল নির্বাচন করুন</label>
                                    <select name="crop" class="form-select">
                                        <option value="">সব ফসল</option>
                                        <?php while($crop = $all_crops->fetch_assoc()): ?>
                                        <option value="<?php echo $crop['id']; ?>" <?php echo $filter_crop == $crop['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($crop['name_bn'], ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">এলাকা</label>
                                    <input type="text" name="location" class="form-control" placeholder="যেমন: ঢাকা" value="<?php echo $filter_location; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> খুঁজুন</button>
                                        <a href="dashboard.php" class="btn btn-secondary">রিসেট</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Crop Listings -->
                <div class="row g-4">
                    <?php if ($crop_posts->num_rows > 0): ?>
                        <?php while($post = $crop_posts->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card crop-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title text-success mb-0">
                                            <i class="fas fa-seedling"></i> <?php echo htmlspecialchars($post['crop_name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </h5>
                                        <span class="badge bg-success">সক্রিয়</span>
                                    </div>
                                    
                                    <div class="crop-details">
                                        <p class="mb-2">
                                            <i class="fas fa-weight"></i> 
                                            <strong>পরিমাণ:</strong> <?php echo htmlspecialchars($post['quantity'] . ' ' . $post['unit'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-money-bill-wave text-success"></i> 
                                            <strong>দাম:</strong> 
                                            <span class="text-success fw-bold fs-5">৳<?php echo number_format($post['price'], 2); ?></span>
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt"></i> 
                                            <strong>এলাকা:</strong> <?php echo htmlspecialchars($post['location'] ?: $post['farmer_location'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-user"></i> 
                                            <strong>কৃষক:</strong> <?php echo htmlspecialchars($post['farmer_name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                        
                                        <?php if($post['description']): ?>
                                        <p class="text-muted small mb-3">
                                            <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($post['description'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button class="btn btn-success" onclick="showContactOptions(this, '<?php echo $post['contact_number'] ?: $post['farmer_phone']; ?>')"> 
                                            <i class="fas fa-phone"></i> যোগাযোগ করুন: <?php echo $post['contact_number'] ?: $post['farmer_phone']; ?>
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" 
                                                onclick="viewDetails(<?php echo $post['id']; ?>, this)"
                                                data-crop-name="<?php echo htmlspecialchars($post['crop_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-quantity="<?php echo htmlspecialchars($post['quantity'] . ' ' . $post['unit'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-price="<?php echo $post['price']; ?>"
                                                data-location="<?php echo htmlspecialchars($post['location'] ?: $post['farmer_location'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-farmer-name="<?php echo htmlspecialchars($post['farmer_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                data-phone="<?php echo $post['contact_number'] ?: $post['farmer_phone']; ?>"
                                                data-description="<?php echo htmlspecialchars($post['description'] ?: 'কোনো বর্ণনা নেই', ENT_QUOTES, 'UTF-8'); ?>"
                                                data-date="<?php echo date('d M Y, h:i A', strtotime($post['created_at'])); ?>">
                                            <i class="fas fa-eye"></i> বিস্তারিত দেখুন
                                        </button>
                                    </div>

                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-clock"></i> পোস্ট করা হয়েছে: 
                                        <?php echo date('d M Y, h:i A', strtotime($post['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-3x mb-3"></i>
                                <h4>কোনো ফসল পাওয়া যায়নি</h4>
                                <p>এই মুহূর্তে কোনো ফসল পোস্ট উপলব্ধ নেই। পরে আবার চেক করুন।</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Copy phone to clipboard
        function copyToClipboard(phone) {
            navigator.clipboard.writeText(phone).then(() => {
                alert('ফোন নম্বর কপি হয়েছে: ' + phone);
            }).catch(err => {
                alert('কপি করতে ব্যর্থ হয়েছে');
            });
        }
        
        // Open WhatsApp
        function openWhatsApp(phone) {
            const message = 'আমি ফসল কিনতে আগ্রহী। কি তথ্য দিতে পারেন?';
            const encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/${phone}?text=${encodedMessage}`, '_blank');
        }
        
        // Show contact options with stylish modal
        function showContactOptions(button, phone) {
            const modal = document.createElement('div');
            modal.className = 'position-fixed top-50 start-50 translate-middle';
            modal.style.zIndex = '9999';
            modal.style.minWidth = '320px';
            modal.style.maxWidth = '400px';
            modal.style.animation = 'fadeInUp 0.6s ease-out';
            modal.innerHTML = `
                <div style="
                    background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(240,250,255,0.98) 100%);
                    backdrop-filter: blur(20px);
                    border: 3px solid rgba(102,126,234,0.6);
                    border-radius: 20px;
                    padding: 2.5rem;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                ">
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <i class="fas fa-phone-alt" style="
                            font-size: 3.5rem;
                            color: #7c4dff;
                            animation: bounce 2s infinite;
                            text-shadow: 0 0 20px rgba(124,77,255,0.4);
                        "></i>
                    </div>
                    
                    <h2 style="
                        color: #1a237e;
                        font-weight: 900;
                        text-align: center;
                        margin-bottom: 1.5rem;
                        font-size: 1.5rem;
                        text-shadow: 2px 2px 4px rgba(255,255,255,1);
                    ">আপনার যোগাযোগ বিকল্প</h2>
                    
                    <div style="background: rgba(124,77,255,0.08); border-left: 4px solid #7c4dff; padding: 1.5rem; border-radius: 10px; margin-bottom: 1.5rem;">
                        <div style="margin-bottom: 1.2rem;">
                            <p style="
                                color: #1a237e;
                                font-weight: 800;
                                margin: 0.5rem 0;
                                font-size: 0.95rem;
                                text-shadow: 1px 1px 2px rgba(255,255,255,1);
                            ">
                                <i class="fab fa-whatsapp" style="width: 25px; color: #34a853; font-size: 1.2rem;\"></i>
                                <strong>WhatsApp:</strong>
                            </p>
                            <p style="
                                color: #00695c;
                                font-weight: 900;
                                margin: 0.5rem 0 0.5rem 2rem;
                                font-size: 1rem;
                                word-break: break-all;
                            ">wa.me/${phone}</p>
                        </div>
                        
                        <div>
                            <p style="
                                color: #1a237e;
                                font-weight: 800;
                                margin: 0.5rem 0;
                                font-size: 0.95rem;
                                text-shadow: 1px 1px 2px rgba(255,255,255,1);
                            ">
                                <i class="fas fa-phone" style="width: 25px; color: #7c4dff;\"></i>
                                <strong>ফোন নম্বর:</strong>
                            </p>
                            <p style="
                                color: #00897b;
                                font-weight: 900;
                                margin: 0.5rem 0 0 2rem;
                                font-size: 1.1rem;
                                letter-spacing: 2px;
                            ">${phone}</p>
                        </div>
                    </div>
                    
                    <p style="
                        color: #666;
                        font-size: 0.9rem;
                        text-align: center;
                        margin: 1.5rem 0;
                        font-weight: 600;
                        font-style: italic;
                    ">নিচের বাটন ব্যবহার করে যোগাযোগ করুন</p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.8rem;">
                        <button style="
                            background: linear-gradient(135deg, rgb(34, 177, 76) 0%, rgb(56, 239, 125) 100%);
                            color: white;
                            padding: 1rem;
                            border-radius: 10px;
                            font-weight: 900;
                            border: 2px solid rgba(255,255,255,0.4);
                            transition: all 0.3s ease;
                            box-shadow: 0 5px 15px rgba(56,239,125,0.5);
                            cursor: pointer;
                            font-size: 0.9rem;
                        " onclick="openWhatsApp('${phone}')" 
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(56,239,125,0.7)';" 
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(56,239,125,0.5)';">
                            <i class="fab fa-whatsapp" style="margin-right: 0.5rem;\"></i> এখনই চ্যাট করুন
                        </button>
                        <button style="
                            background: linear-gradient(135deg, rgb(102,126,234) 0%, rgb(118,75,162) 100%);
                            color: white;
                            padding: 1rem;
                            border-radius: 10px;
                            font-weight: 900;
                            border: 2px solid rgba(255,255,255,0.4);
                            transition: all 0.3s ease;
                            box-shadow: 0 5px 15px rgba(102,126,234,0.5);
                            cursor: pointer;
                            font-size: 0.9rem;
                        " onclick="copyToClipboard('${phone}')" 
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.7)';" 
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102,126,234,0.5)';">
                            <i class="fas fa-copy" style="margin-right: 0.5rem;\"></i> নম্বর কপি করুন
                        </button>
                    </div>
                    
                    <button style="
                        width: 100%;
                        background: linear-gradient(135deg, rgba(240,147,251,0.95) 0%, rgba(245,87,108,0.95) 100%);
                        color: white;
                        padding: 0.8rem;
                        border-radius: 10px;
                        border: 2px solid rgba(255,255,255,0.4);
                        font-weight: 900;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        box-shadow: 0 5px 15px rgba(245,87,108,0.5);
                    " onclick="this.closest('div').parentElement.remove();" 
                        onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(245,87,108,0.7)';" 
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(245,87,108,0.5)';">
                        <i class="fas fa-times" style="margin-right: 0.5rem;\"></i> বন্ধ করুন
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Auto-remove after 15 seconds
            setTimeout(() => {
                if (modal.parentElement) {
                    modal.style.animation = 'fadeInUp 0.5s ease-out reverse';
                    setTimeout(() => modal.remove(), 500);
                }
            }, 15000);
        }
        
        // Ripple effect for buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!this.classList.contains('btn-outline-primary')) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                }
            });
        });
        
        // Counter animation for stat cards
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 30);
        }
        
        // Animate stat counters on load
        document.addEventListener('DOMContentLoaded', () => {
            const statCards = document.querySelectorAll('.stat-card h2');
            statCards.forEach(card => {
                const target = parseInt(card.textContent);
                card.textContent = '0';
                setTimeout(() => animateCounter(card, target), 300);
            });
        });
        
        // Enhanced view details function with stylish modal
        function viewDetails(postId, button) {
            const cropName = button.dataset.cropName;
            const quantity = button.dataset.quantity;
            const price = button.dataset.price;
            const location = button.dataset.location;
            const farmerName = button.dataset.farmerName;
            const phone = button.dataset.phone;
            const description = button.dataset.description;
            const date = button.dataset.date;
            
            const alertBox = document.createElement('div');
            alertBox.className = 'position-fixed top-50 start-50 translate-middle';
            alertBox.style.zIndex = '9999';
            alertBox.style.minWidth = '320px';
            alertBox.style.maxWidth = '450px';
            alertBox.style.animation = 'fadeInUp 0.6s ease-out';
            alertBox.innerHTML = `
                <div style="
                    background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(240,250,255,0.98) 100%);
                    backdrop-filter: blur(20px);
                    border: 3px solid rgba(102,126,234,0.6);
                    border-radius: 20px;
                    padding: 2.5rem;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                ">
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <i class="fas fa-leaf" style="
                            font-size: 3.5rem;
                            color: #00897b;
                            animation: bounce 2s infinite;
                            text-shadow: 0 0 20px rgba(0,137,123,0.4);
                        "></i>
                    </div>
                    
                    <h3 style="
                        color: #1a237e;
                        font-weight: 900;
                        margin-bottom: 1.5rem;
                        text-shadow: 2px 2px 4px rgba(255,255,255,1);
                        font-size: 1.8rem;
                    ">${cropName}</h3>
                    
                    <div style="background: rgba(17,153,142,0.08); border-left: 4px solid #00897b; padding: 1.2rem; border-radius: 10px; margin-bottom: 1.5rem;">
                        <p style="
                            color: #1a237e;
                            font-weight: 800;
                            margin: 0.8rem 0;
                            font-size: 1rem;
                        ">
                            <i class="fas fa-weight" style="width: 25px; color: #7c4dff;"></i>
                            <strong>পরিমাণ:</strong> ${quantity}
                        </p>
                        <p style="
                            color: #1a237e;
                            font-weight: 800;
                            margin: 0.8rem 0;
                            font-size: 1rem;
                        ">
                            <i class="fas fa-money-bill-wave" style="width: 25px; color: #38ef7d;"></i>
                            <strong>দাম:</strong> <span style="color: #00897b; font-size: 1.2rem;">৳${parseFloat(price).toFixed(2)}</span>
                        </p>
                        <p style="
                            color: #1a237e;
                            font-weight: 800;
                            margin: 0.8rem 0;
                            font-size: 1rem;
                        ">
                            <i class="fas fa-map-marker-alt" style="width: 25px; color: #7c4dff;"></i>
                            <strong>এলাকা:</strong> ${location}
                        </p>
                    </div>
                    
                    <div style="background: rgba(56,239,125,0.08); border-left: 4px solid #38ef7d; padding: 1.2rem; border-radius: 10px; margin-bottom: 1.5rem;">
                        <p style="
                            color: #1a237e;
                            font-weight: 800;
                            margin: 0.8rem 0;
                            font-size: 1rem;
                        ">
                            <i class="fas fa-user-tie" style="width: 25px; color: #00695c;"></i>
                            <strong>কৃষক:</strong> ${farmerName}
                        </p>
                        <p style="
                            color: #1a237e;
                            font-weight: 800;
                            margin: 0.8rem 0;
                            font-size: 1rem;
                        ">
                            <i class="fas fa-phone" style="width: 25px; color: #7c4dff;"></i>
                            <strong>যোগাযোগ:</strong> ${phone}
                        </p>
                    </div>
                    
                    <div style="background: rgba(240,147,251,0.08); border-left: 4px solid rgba(240,147,251); padding: 1.2rem; border-radius: 10px; margin-bottom: 1.5rem;">
                        <p style="
                            color: #1a237e;
                            font-weight: 800;
                            margin: 0;
                            font-size: 0.95rem;
                            line-height: 1.6;
                        ">
                            <i class="fas fa-file-alt" style="width: 25px; color: #7c4dff; margin-bottom: 0.5rem;"></i>
                            <strong>বিবরণ:</strong><br>
                            ${description}
                        </p>
                    </div>
                    
                    <p style="
                        color: #004d40;
                        font-weight: 700;
                        font-size: 0.9rem;
                        margin: 1rem 0 1.5rem 0;
                        text-align: center;
                    ">
                        <i class="fas fa-clock" style="color: #7c4dff;"></i> ${date}
                    </p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <button style="
                            background: linear-gradient(135deg, rgb(34, 177, 76) 0%, rgb(56, 239, 125) 100%);
                            color: white;
                            padding: 0.8rem;
                            border-radius: 10px;
                            font-weight: 900;
                            border: 2px solid rgba(255,255,255,0.4);
                            transition: all 0.3s ease;
                            box-shadow: 0 5px 15px rgba(56,239,125,0.5);
                            cursor: pointer;
                        " onclick="openWhatsApp('${phone}')" 
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(56,239,125,0.7)';" 
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(56,239,125,0.5)';">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </button>
                        <button style="
                            background: linear-gradient(135deg, rgba(102,126,234,0.95) 0%, rgba(118,75,162,0.95) 100%);
                            color: white;
                            padding: 0.8rem;
                            border-radius: 10px;
                            border: 2px solid rgba(255,255,255,0.4);
                            font-weight: 900;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            box-shadow: 0 5px 15px rgba(102,126,234,0.5);
                        " onclick="copyToClipboard('${phone}')" 
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.7)';" 
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102,126,234,0.5)';">
                            <i class="fas fa-copy"></i> কপি করুন
                        </button>
                    </div>
                    <button style="
                        width: 100%;
                        background: linear-gradient(135deg, rgba(240,147,251,0.95) 0%, rgba(245,87,108,0.95) 100%);
                        color: white;
                        padding: 0.8rem;
                        border-radius: 10px;
                        border: 2px solid rgba(255,255,255,0.4);
                        font-weight: 900;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        box-shadow: 0 5px 15px rgba(245,87,108,0.5);
                        margin-top: 0.8rem;
                    " onclick="this.closest('div').parentElement.remove();" 
                        onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(245,87,108,0.7)';" 
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(245,87,108,0.5)';">
                        <i class="fas fa-times"></i> বন্ধ করুন
                    </button>
                </div>
            `;
            document.body.appendChild(alertBox);
            
            // Auto-remove after 10 seconds
            setTimeout(() => {
                if (alertBox.parentElement) {
                    alertBox.style.animation = 'fadeInUp 0.5s ease-out reverse';
                    setTimeout(() => alertBox.remove(), 500);
                }
            }, 10000);
        }
        
        // Smooth scroll observer for crop cards
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.crop-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `all 0.5s ease ${index * 0.1}s`;
            observer.observe(card);
        });
        
        // Add hover effect to crop details
        document.querySelectorAll('.crop-details p').forEach(p => {
            p.addEventListener('mouseenter', function() {
                this.style.paddingLeft = '10px';
            });
            p.addEventListener('mouseleave', function() {
                this.style.paddingLeft = '0';
            });
        });
    </script>
</body>
</html>
