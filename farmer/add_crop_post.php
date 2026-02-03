<?php
require_once '../config/config.php';

if (!isLoggedIn() || getUserRole() !== 'farmer') {
    redirect('../auth/login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $crop_id = sanitize($_POST['crop_id']);
    $quantity = sanitize($_POST['quantity']);
    $unit = sanitize($_POST['unit']);
    $price = sanitize($_POST['price']);
    $description = sanitize($_POST['description']);
    $location = sanitize($_POST['location']);
    $contact_number = sanitize($_POST['contact_number']);
    $farmer_id = $_SESSION['user_id'];
    
    if (empty($crop_id) || empty($quantity) || empty($price)) {
        $error = 'সকল প্রয়োজনীয় ফিল্ড পূরণ করুন';
    } else {
        $stmt = $conn->prepare("INSERT INTO crop_posts (farmer_id, crop_id, quantity, unit, price, description, location, contact_number, status) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')");
        $stmt->bind_param("iidsssss", $farmer_id, $crop_id, $quantity, $unit, $price, $description, $location, $contact_number);
        
        if ($stmt->execute()) {
            $success = 'ফসল পোস্ট সফলভাবে যুক্ত হয়েছে!';
            // Clear form
            $_POST = array();
        } else {
            $error = 'পোস্ট করতে ত্রুটি হয়েছে';
        }
        $stmt->close();
    }
}

// Get all crops
$crops = $conn->query("SELECT * FROM crops ORDER BY name_bn");
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ফসল পোস্ট করুন - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Background with Image */
        body {
            background: 
                linear-gradient(-45deg, 
                    rgba(255, 107, 107, 0.15), 
                    rgba(78, 205, 196, 0.15), 
                    rgba(69, 183, 209, 0.15), 
                    rgba(150, 206, 180, 0.15), 
                    rgba(255, 234, 167, 0.15), 
                    rgba(253, 121, 168, 0.15)
                ),
                url('../agrologo/ADC_climate_smart_agro-2-min.jpg') center center / cover fixed;
            background-size: cover;
            min-height: 100vh;
            position: relative;
        }

        /* Floating Background Images */
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
            background-size: 350px;
            opacity: 0.12;
            pointer-events: none;
            z-index: 0;
        }

        /* Page Title Animation */
        .page-title {
            animation: slideInDown 0.6s ease-out, colorChange 3s infinite;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 0, 0, 0.3);
            color: #fff !important;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.5) 0%, rgba(118, 75, 162, 0.5) 100%);
            padding: 20px 30px;
            border-radius: 15px;
            display: inline-block;
            font-weight: bold;
            backdrop-filter: blur(25px);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes colorChange {
            0%, 100% { filter: hue-rotate(0deg); }
            50% { filter: hue-rotate(20deg); }
        }

        /* Card Animations */
        .card {
            animation: fadeInUp 0.8s ease-out;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            box-shadow: 
                0 8px 30px rgba(102, 126, 234, 0.1),
                0 10px 50px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1;
        }

        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 
                0 20px 50px rgba(102, 126, 234, 0.5),
                0 25px 80px rgba(0, 0, 0, 0.2) !important;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.03);
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

        /* Form Elements */
        .form-control, .form-select {
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 15px;
            padding: 15px 20px;
            transition: all 0.3s ease;
            font-size: 18px;
            font-weight: 900;
            background: rgba(255, 255, 255, 0.75);
            color: #000;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.08);
            text-shadow: 1px 1px 3px rgba(255, 255, 255, 1);
        }

        .form-control::placeholder, .form-select::placeholder {
            color: #b2bec3;
            font-weight: 600;
            font-style: italic;
        }

        .form-control:focus, .form-select:focus {
            border-color: rgba(102, 126, 234, 0.6);
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3), 0 5px 15px rgba(102, 126, 234, 0.2);
            transform: scale(1.02);
            background: rgba(255, 255, 255, 0.85);
            color: #000;
            text-shadow: 1px 1px 3px rgba(255, 255, 255, 1);
        }

        .form-select option {
            background: #fff;
            color: #2d3436;
            font-weight: 600;
            padding: 15px;
        }

        .form-label {
            font-weight: 900;
            color: #000;
            margin-bottom: 12px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 2px 2px 10px rgba(255, 255, 255, 1), 0 0 20px rgba(255, 255, 255, 1), 1px 1px 5px rgba(255, 255, 255, 1);
        }

        .form-label i {
            animation: bounce 2s infinite;
            font-size: 24px;
            color: rgba(102, 126, 234, 0.7);
            filter: drop-shadow(2px 2px 6px rgba(255, 255, 255, 0.9));
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-8px) rotate(-5deg); }
            75% { transform: translateY(-8px) rotate(5deg); }
        }

        /* Button Styles */
        .btn {
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-success {
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.8) 0%, rgba(56, 239, 125, 0.8) 100%);
            box-shadow: 0 5px 18px rgba(56, 239, 125, 0.25);
            animation: glow 2s infinite;
        }

        @keyframes glow {
            0%, 100% {
                box-shadow: 0 8px 25px rgba(56, 239, 125, 0.4);
            }
            50% {
                box-shadow: 0 8px 35px rgba(56, 239, 125, 0.7);
            }
        }

        .btn-success:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(56, 239, 125, 0.6);
        }

        .btn-secondary {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
            box-shadow: 0 5px 18px rgba(118, 75, 162, 0.25);
        }

        .btn-secondary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(118, 75, 162, 0.6);
        }

        /* Info Cards */
        .bg-info {
            background: rgba(255, 255, 255, 0.65) !important;
            border-radius: 20px;
            animation: fadeInRight 0.8s ease-out;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
            backdrop-filter: blur(3px);
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .card-header {
            border-radius: 20px 20px 0 0 !important;
            font-weight: 900;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            animation: shimmer 3s infinite;
            font-size: 18px;
            color: #ffffff !important;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        }

        .card-header h6 {
            color: #ffffff !important;
            font-weight: 900;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.4);
        }

        @keyframes shimmer {
            0%, 100% { filter: brightness(1); }
            50% { filter: brightness(1.2); }
        }

        .card p, .card .small {
            color: #000 !important;
            font-weight: 800;
            font-size: 15px;
            line-height: 1.8;
            text-shadow: 2px 2px 8px rgba(255, 255, 255, 1), 0 0 15px rgba(255, 255, 255, 1), 1px 1px 4px rgba(255, 255, 255, 1);
        }

        /* Alert Animations */
        .alert {
            animation: slideInDown 0.5s ease-out, pulse 2s infinite;
            border-radius: 15px;
            border: 3px solid;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            font-weight: 600;
            font-size: 16px;
        }

        .alert-success {
            background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
            border-color: #11998e;
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border-color: #fa709a;
            color: #fff;
        }

        /* Icon Pulse Animation */
        .fa-seedling, .fa-plus-circle {
            animation: pulse 2s infinite;
            color: #667eea;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }

        /* Input Group Styling */
        .mb-3 {
            animation: fadeIn 0.6s ease-out;
            animation-fill-mode: both;
        }

        .mb-3:nth-child(1) { animation-delay: 0.1s; }
        .mb-3:nth-child(2) { animation-delay: 0.2s; }
        .mb-3:nth-child(3) { animation-delay: 0.3s; }
        .mb-3:nth-child(4) { animation-delay: 0.4s; }
        .mb-3:nth-child(5) { animation-delay: 0.5s; }
        .mb-3:nth-child(6) { animation-delay: 0.6s; }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Card Body */
        .card-body {
            padding: 35px !important;
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(3px);
        }

        .card-body h5, .card-body h6 {
            color: #000;
            font-weight: 900;
            text-shadow: 2px 2px 10px rgba(255, 255, 255, 1), 0 0 20px rgba(255, 255, 255, 1), 1px 1px 5px rgba(255, 255, 255, 1);
        }

        /* Small Text Styling */
        .text-muted {
            font-size: 15px;
            color: #333 !important;
            margin-top: 8px;
            font-weight: 800;
            text-shadow: 2px 2px 8px rgba(255, 255, 255, 1), 0 0 12px rgba(255, 255, 255, 1);
        }

        /* List Styling in Tips Card */
        .bg-info ul li {
            margin-bottom: 15px;
            padding-left: 10px;
            transition: all 0.3s ease;
            font-weight: 900;
            font-size: 16px;
            color: #000;
            text-shadow: 2px 2px 10px rgba(255, 255, 255, 1), 0 0 18px rgba(255, 255, 255, 1), 1px 1px 5px rgba(255, 255, 255, 1);
        }

        .bg-info ul li:hover {
            transform: translateX(15px) scale(1.08);
            font-weight: 900;
            color: #000;
            text-shadow: 2px 2px 10px rgba(255, 255, 255, 1), 0 0 18px rgba(255, 255, 255, 0.9);
        }

        .bg-info h5 {
            color: #000 !important;
            font-weight: 900;
            font-size: 22px;
            text-shadow: 2px 2px 10px rgba(255, 255, 255, 1), 0 0 20px rgba(255, 255, 255, 1), 1px 1px 5px rgba(255, 255, 255, 1);
        }

        /* Floating Animation for Icons */
        .card-header i {
            animation: float 2s ease-in-out infinite;
            font-size: 24px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(10deg); }
        }

        /* Main Content Animation */
        main {
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            z-index: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card {
                margin-bottom: 20px;
            }
        }

        /* Select Dropdown Enhancement */
        select.form-select option {
            padding: 10px;
        }

        /* Textarea Enhancement */
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Success Message Shake */
        .alert-success {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Required Field Asterisk */
        .form-label::after {
            content: attr(data-required);
            color: #f44336;
            margin-left: 5px;
        }

        /* Loading Animation for Submit Button */
        .btn-success:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1 class="h2 mb-4 page-title"><i class="fas fa-plus-circle"></i> নতুন ফসল পোস্ট করুন</h1>

                <?php if ($error): ?>
                    <?php echo showAlert($error, 'danger'); ?>
                <?php endif; ?>
                <?php if ($success): ?>
                    <?php echo showAlert($success, 'success'); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <form method="POST" action="" class="needs-validation" novalidate>
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-seedling"></i> ফসল নির্বাচন করুন *</label>
                                        <select name="crop_id" class="form-select" required>
                                            <option value="">ফসল বেছে নিন</option>
                                            <?php 
                                            if($crops && $crops->num_rows > 0):
                                                while($crop = $crops->fetch_assoc()): 
                                                    $name_bn = isset($crop['name_bn']) ? $crop['name_bn'] : '';
                                                    $name_en = isset($crop['name_en']) ? $crop['name_en'] : '';
                                            ?>
                                            <option value="<?php echo htmlspecialchars($crop['id']); ?>">
                                                <?php 
                                                if(!empty($name_bn)) {
                                                    echo htmlspecialchars($name_bn, ENT_QUOTES, 'UTF-8');
                                                }
                                                if(!empty($name_en)) {
                                                    echo ' (' . htmlspecialchars($name_en, ENT_QUOTES, 'UTF-8') . ')';
                                                }
                                                ?>
                                            </option>
                                            <?php 
                                                endwhile;
                                            else:
                                            ?>
                                            <option value="" disabled>কোন ফসল পাওয়া যায়নি</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fas fa-weight"></i> পরিমাণ *</label>
                                            <input type="number" name="quantity" class="form-control" step="0.01" min="0" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="fas fa-balance-scale"></i> একক *</label>
                                            <select name="unit" class="form-select" required>
                                                <option value="kg">কেজি (kg)</option>
                                                <option value="ton">টন (ton)</option>
                                                <option value="maund">মণ (maund)</option>
                                                <option value="quintal">কুইন্টাল (quintal)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-money-bill-wave"></i> দাম (টাকা) *</label>
                                        <input type="number" name="price" class="form-control" step="0.01" min="0" placeholder="প্রতি একক দাম" required>
                                        <small class="text-muted">উদাহরণ: যদি প্রতি কেজি ৫০ টাকা হয়, তাহলে ৫০ লিখুন</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> এলাকা/স্থান</label>
                                        <input type="text" name="location" class="form-control" placeholder="যেমন: ঢাকা, ময়মনসিংহ">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-phone"></i> যোগাযোগের নম্বর</label>
                                        <input type="tel" name="contact_number" class="form-control" placeholder="01XXXXXXXXX">
                                        <small class="text-muted">খালি রাখলে আপনার রেজিস্টার করা নম্বর ব্যবহার হবে</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-info-circle"></i> বিবরণ</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="ফসল সম্পর্কে অতিরিক্ত তথ্য"></textarea>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-check"></i> পোস্ট করুন
                                        </button>
                                        <a href="my_posts.php" class="btn btn-secondary">
                                            <i class="fas fa-list"></i> আমার পোস্ট দেখুন
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-info text-white mb-3">
                            <div class="card-body">
                                <h5><i class="fas fa-lightbulb"></i> টিপস</h5>
                                <ul class="mb-0">
                                    <li>সঠিক ফসল নির্বাচন করুন</li>
                                    <li>বাজার দর অনুযায়ী দাম নির্ধারণ করুন</li>
                                    <li>পরিমাণ সঠিকভাবে উল্লেখ করুন</li>
                                    <li>যোগাযোগ নম্বর চালু রাখুন</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle"></i> গুরুত্বপূর্ণ তথ্য</h6>
                            </div>
                            <div class="card-body">
                                <p class="small">* চিহ্নিত ফিল্ডগুলো অবশ্যই পূরণ করতে হবে</p>
                                <p class="small mb-0">পোস্ট করার পর ক্রেতারা আপনার সাথে সরাসরি যোগাযোগ করতে পারবেন</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script>
        // Form validation animation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                        
                        // Shake animation for invalid form
                        form.classList.add('shake');
                        setTimeout(() => form.classList.remove('shake'), 500);
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Add floating label effect
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });

        // Number input animation
        const numberInputs = document.querySelectorAll('input[type="number"]');
        numberInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value) {
                    this.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);
                }
            });
        });

        // Smooth scroll for alerts
        window.addEventListener('load', function() {
            const alerts = document.querySelectorAll('.alert');
            if (alerts.length > 0) {
                alerts[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        // Add ripple effect on button click
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                let ripple = document.createElement('span');
                ripple.classList.add('ripple');
                this.appendChild(ripple);
                
                let x = e.clientX - e.target.offsetLeft;
                let y = e.clientY - e.target.offsetTop;
                
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Auto-hide success message after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.animation = 'fadeOut 0.5s ease-out';
                setTimeout(() => {
                    successAlert.remove();
                }, 500);
            }, 5000);
        }

        // Add CSS for fadeOut animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-20px); }
            }
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: rippleEffect 0.6s ease-out;
                pointer-events: none;
            }
            @keyframes rippleEffect {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            .shake {
                animation: shakeForm 0.5s ease-in-out;
            }
            @keyframes shakeForm {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
                20%, 40%, 60%, 80% { transform: translateX(10px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
