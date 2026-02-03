<?php
require_once '../config/config.php';

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

// Crop calendar data
$crop_calendar = [
    'আউশ ধান' => ['বপন' => 'চৈত্র-বৈশাখ (মার্চ-এপ্রিল)', 'কাটা' => 'আষাঢ়-শ্রাবণ (জুন-জুলাই)', 'মৌসুম' => 'গ্রীষ্ম', 'সময়' => '৩-৪ মাস'],
    'আমন ধান' => ['বপন' => 'আষাঢ়-শ্রাবণ (জুন-জুলাই)', 'কাটা' => 'অগ্রহায়ণ-পৌষ (নভেম্বর-ডিসেম্বর)', 'মৌসুম' => 'বর্ষা', 'সময়' => '৪-৫ মাস'],
    'বোরো ধান' => ['বপন' => 'অগ্রহায়ণ-পৌষ (নভেম্বর-ডিসেম্বর)', 'কাটা' => 'বৈশাখ-জ্যৈষ্ঠ (এপ্রিল-মে)', 'মৌসুম' => 'শীত', 'সময়' => '৫-৬ মাস'],
    'গম' => ['বপন' => 'কার্তিক-অগ্রহায়ণ (অক্টোবর-নভেম্বর)', 'কাটা' => 'ফাল্গুন-চৈত্র (ফেব্রুয়ারি-মার্চ)', 'মৌসুম' => 'রবি', 'সময়' => '৪ মাস'],
    'ভুট্টা' => ['বপন' => 'কার্তিক-অগ্রহায়ণ (অক্টোবর-নভেম্বর)', 'কাটা' => 'ফাল্গুন-চৈত্র (ফেব্রুয়ারি-মার্চ)', 'মৌসুম' => 'রবি', 'সময়' => '৪ মাস'],
    'আলু' => ['বপন' => 'কার্তিক-অগ্রহায়ণ (অক্টোবর-নভেম্বর)', 'কাটা' => 'মাঘ-ফাল্গুন (জানুয়ারি-ফেব্রুয়ারি)', 'মৌসুম' => 'রবি', 'সময়' => '৩ মাস'],
    'পেঁয়াজ' => ['বপন' => 'অগ্রহায়ণ-পৌষ (নভেম্বর-ডিসেম্বর)', 'কাটা' => 'চৈত্র-বৈশাখ (মার্চ-এপ্রিল)', 'মৌসুম' => 'রবি', 'সময়' => '৪ মাস'],
    'রসুন' => ['বপন' => 'কার্তিক-অগ্রহায়ণ (অক্টোবর-নভেম্বর)', 'কাটা' => 'ফাল্গুন-চৈত্র (ফেব্রুয়ারি-মার্চ)', 'মৌসুম' => 'রবি', 'সময়' => '৪-৫ মাস'],
    'টমেটো' => ['বপন' => 'ভাদ্র-আশ্বিন (আগস্ট-সেপ্টেম্বর)', 'কাটা' => 'পৌষ-মাঘ (ডিসেম্বর-জানুয়ারি)', 'মৌসুম' => 'রবি', 'সময়' => '৩-৪ মাস'],
    'বেগুন' => ['বপন' => 'আশ্বিন-কার্তিক (সেপ্টেম্বর-অক্টোবর)', 'কাটা' => 'পৌষ-ফাল্গুন (ডিসেম্বর-ফেব্রুয়ারি)', 'মৌসুম' => 'রবি', 'সময়' => '৪-৫ মাস'],
    'মরিচ' => ['বপন' => 'ভাদ্র-আশ্বিন (আগস্ট-সেপ্টেম্বর)', 'কাটা' => 'অগ্রহায়ণ-ফাল্গুন (নভেম্বর-ফেব্রুয়ারি)', 'মৌসুম' => 'রবি', 'সময়' => '৪-৫ মাস'],
    'শিম' => ['বপন' => 'আশ্বিন-কার্তিক (সেপ্টেম্বর-অক্টোবর)', 'কাটা' => 'পৌষ-মাঘ (ডিসেম্বর-জানুয়ারি)', 'মৌসুম' => 'রবি', 'সময়' => '৩ মাস'],
    'লাউ' => ['বপন' => 'ভাদ্র-আশ্বিন (আগস্ট-সেপ্টেম্বর)', 'কাটা' => 'অগ্রহায়ণ-মাঘ (নভেম্বর-জানুয়ারি)', 'মৌসুম' => 'রবি', 'সময়' => '৩-৪ মাস'],
    'মিষ্টি কুমড়া' => ['বপন' => 'ফাল্গুন-চৈত্র (ফেব্রুয়ারি-মার্চ)', 'কাটা' => 'জ্যৈষ্ঠ-আষাঢ় (মে-জুন)', 'মৌসুম' => 'খরিফ', 'সময়' => '৩-৪ মাস'],
    'সরিষা' => ['বপন' => 'কার্তিক-অগ্রহায়ণ (অক্টোবর-নভেম্বর)', 'কাটা' => 'মাঘ-ফাল্গুন (জানুয়ারি-ফেব্রুয়ারি)', 'মৌসুম' => 'রবি', 'সময়' => '৩ মাস'],
    'মসুর ডাল' => ['বপন' => 'কার্তিক-অগ্রহায়ণ (অক্টোবর-নভেম্বর)', 'কাটা' => 'ফাল্গুন-চৈত্র (ফেব্রুয়ারি-মার্চ)', 'মৌসুম' => 'রবি', 'সময়' => '৪ মাস'],
    'ছোলা' => ['বপন' => 'কার্তিক-অগ্রহায়ণ (অক্টোবর-নভেম্বর)', 'কাটা' => 'ফাল্গুন-চৈত্র (ফেব্রুয়ারি-মার্চ)', 'মৌসুম' => 'রবি', 'সময়' => '৪-৫ মাস'],
    'পাট' => ['বপন' => 'চৈত্র-বৈশাখ (মার্চ-এপ্রিল)', 'কাটা' => 'শ্রাবণ-ভাদ্র (জুলাই-আগস্ট)', 'মৌসুম' => 'খরিফ', 'সময়' => '৪ মাস'],
];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ফসলের ক্যালেন্ডার - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Background Image Styling */
        body {
            background: url('../agrologo/calander.jpg');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            min-height: 100vh;
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

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        /* Main container */
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

        .season-tabs {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(5px);
            border: 2px solid rgba(40, 167, 69, 0.4);
            animation: slideIn 0.6s ease;
        }

        .season-btn {
            background: rgba(40, 167, 69, 0.7);
            border: 2px solid rgba(40, 167, 69, 0.6);
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 700;
            transition: all 0.3s ease;
            margin: 5px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        .season-btn:hover,
        .season-btn.active {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.85) 0%, rgba(32, 201, 151, 0.85) 100%);
            color: white;
            border-color: #28a745;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
        }

        .crop-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 2px solid rgba(40, 167, 69, 0.5);
            height: 100%;
            animation: fadeInUp 0.6s ease;
            animation-fill-mode: both;
            backdrop-filter: blur(5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 1.5rem;
        }

        .crop-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(40, 167, 69, 0.4);
            border-color: #28a745;
            background: rgba(255, 255, 255, 0.12);
        }

        .crop-header {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.75) 0%, rgba(32, 201, 151, 0.75) 100%);
            color: white;
            padding: 1.2rem;
            backdrop-filter: blur(10px);
            border-bottom: 3px solid rgba(255, 255, 255, 0.3);
        }

        .crop-body {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.85);
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 12px;
            margin-bottom: 10px;
            background: rgba(40, 167, 69, 0.7);
            border-radius: 10px;
            border-left: 4px solid #20c997;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: rgba(40, 167, 69, 0.85);
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .info-label {
            font-weight: 700;
            color: #ffffff;
            min-width: 80px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        .info-value {
            color: #ffffff;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        .season-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .season-রবি {
            background: rgba(255, 193, 7, 0.85);
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .season-খরিফ {
            background: rgba(40, 167, 69, 0.85);
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .season-গ্রীষ্ম {
            background: rgba(255, 87, 34, 0.85);
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .season-বর্ষা {
            background: rgba(33, 150, 243, 0.85);
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .season-শীত {
            background: rgba(158, 158, 158, 0.85);
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        /* Stagger animation for cards */
        .crop-card:nth-child(1) { animation-delay: 0.1s; }
        .crop-card:nth-child(2) { animation-delay: 0.2s; }
        .crop-card:nth-child(3) { animation-delay: 0.3s; }
        .crop-card:nth-child(4) { animation-delay: 0.4s; }
        .crop-card:nth-child(5) { animation-delay: 0.5s; }
        .crop-card:nth-child(6) { animation-delay: 0.6s; }

        .alert {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            border: 2px solid rgba(40, 167, 69, 0.5);
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Page Header -->
                <div class="page-header text-center">
                    <h1 class="display-5 fw-bold mb-2">
                        <i class="fas fa-calendar-alt icon-float"></i> ফসলের ক্যালেন্ডার
                    </h1>
                    <p class="lead mb-0">বিভিন্ন ফসলের বপন ও কাটার সময়সূচী জানুন</p>
                </div>

                <!-- Season Filter -->
                <div class="season-tabs text-center">
                    <button class="season-btn active" onclick="filterBySeason('all')">
                        <i class="fas fa-globe"></i> সব ফসল
                    </button>
                    <button class="season-btn" onclick="filterBySeason('রবি')">
                        <i class="fas fa-leaf"></i> রবি মৌসুম
                    </button>
                    <button class="season-btn" onclick="filterBySeason('খরিফ')">
                        <i class="fas fa-sun"></i> খরিফ মৌসুম
                    </button>
                    <button class="season-btn" onclick="filterBySeason('গ্রীষ্ম')">
                        <i class="fas fa-fire"></i> গ্রীষ্ম মৌসুম
                    </button>
                    <button class="season-btn" onclick="filterBySeason('বর্ষা')">
                        <i class="fas fa-cloud-rain"></i> বর্ষা মৌসুম
                    </button>
                    <button class="season-btn" onclick="filterBySeason('শীত')">
                        <i class="fas fa-snowflake"></i> শীত মৌসুম
                    </button>
                </div>

                <!-- Crop Cards -->
                <div class="row" id="cropContainer">
                    <?php foreach ($crop_calendar as $crop_name => $crop_info): ?>
                    <div class="col-md-6 col-lg-4 crop-item" data-season="<?php echo $crop_info['মৌসুম']; ?>">
                        <div class="crop-card">
                            <div class="crop-header">
                                <h5 class="mb-0 d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="fas fa-seedling"></i> <?php echo $crop_name; ?>
                                    </span>
                                    <span class="season-badge season-<?php echo $crop_info['মৌসুম']; ?>">
                                        <?php echo $crop_info['মৌসুম']; ?>
                                    </span>
                                </h5>
                            </div>
                            <div class="crop-body">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-cloud-sun"></i>
                                    </div>
                                    <div>
                                        <div class="info-label">মৌসুম</div>
                                        <div class="info-value"><?php echo $crop_info['মৌসুম']; ?></div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-calendar-plus"></i>
                                    </div>
                                    <div>
                                        <div class="info-label">বপনের সময়</div>
                                        <div class="info-value"><?php echo $crop_info['বপন']; ?></div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <div class="info-label">কাটার সময়</div>
                                        <div class="info-value"><?php echo $crop_info['কাটা']; ?></div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <div class="info-label">সময়কাল</div>
                                        <div class="info-value"><?php echo $crop_info['সময়']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Info Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info" style="border-radius: 15px; animation: fadeInUp 0.6s ease;">
                            <h5><i class="fas fa-info-circle"></i> গুরুত্বপূর্ণ তথ্য</h5>
                            <ul class="mb-0">
                                <li>ফসল চাষের সময় আবহাওয়া ও স্থানীয় পরিবেশ বিবেচনা করুন</li>
                                <li>মাটি পরীক্ষা করে সঠিক সার প্রয়োগ করুন</li>
                                <li>কৃষি বিশেষজ্ঞের পরামর্শ নিয়ে ফসল চাষ করুন</li>
                                <li>বীজ সংরক্ষণ ও সঠিক পরিচর্যা নিশ্চিত করুন</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    
    <script>
        function filterBySeason(season) {
            const cropItems = document.querySelectorAll('.crop-item');
            const buttons = document.querySelectorAll('.season-btn');
            
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter crops
            cropItems.forEach(item => {
                if (season === 'all' || item.dataset.season === season) {
                    item.style.display = 'block';
                    item.style.animation = 'fadeInUp 0.6s ease';
                } else {
                    item.style.display = 'none';
                }
            });
        }

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
    </script>
</body>
</html>
