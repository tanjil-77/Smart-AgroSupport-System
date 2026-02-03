<?php
require_once '../config/config.php';

if (!isLoggedIn() || getUserRole() !== 'farmer') {
    redirect('../auth/login.php');
}

$error = '';
$success = '';
$farmer_id = $_SESSION['user_id'];

// Get post ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('my_posts.php');
}

$post_id = sanitize($_GET['id']);

// Get existing post data
$stmt = $conn->prepare("SELECT * FROM crop_posts WHERE id = ? AND farmer_id = ?");
$stmt->bind_param("ii", $post_id, $farmer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    redirect('my_posts.php');
}

$post = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $crop_id = sanitize($_POST['crop_id']);
    $quantity = sanitize($_POST['quantity']);
    $unit = sanitize($_POST['unit']);
    $price = sanitize($_POST['price']);
    $description = sanitize($_POST['description']);
    $location = sanitize($_POST['location']);
    $contact_number = sanitize($_POST['contact_number']);
    
    if (empty($crop_id) || empty($quantity) || empty($price)) {
        $error = 'সকল প্রয়োজনীয় ফিল্ড পূরণ করুন';
    } else {
        $stmt = $conn->prepare("UPDATE crop_posts SET crop_id = ?, quantity = ?, unit = ?, price = ?, description = ?, location = ?, contact_number = ? WHERE id = ? AND farmer_id = ?");
        $stmt->bind_param("idsssssii", $crop_id, $quantity, $unit, $price, $description, $location, $contact_number, $post_id, $farmer_id);
        
        if ($stmt->execute()) {
            $success = 'ফসল পোস্ট সফলভাবে আপডেট হয়েছে!';
            // Refresh post data
            $stmt2 = $conn->prepare("SELECT * FROM crop_posts WHERE id = ? AND farmer_id = ?");
            $stmt2->bind_param("ii", $post_id, $farmer_id);
            $stmt2->execute();
            $post = $stmt2->get_result()->fetch_assoc();
            $stmt2->close();
        } else {
            $error = 'আপডেট করতে ত্রুটি হয়েছে';
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
    <title>ফসল পোস্ট এডিট করুন - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Background Styling - Crop Calendar Pattern */
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
            background: url('../agrologo/iot1.jpg') center/cover;
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
        
        /* Page Title */
        .page-title {
            color: #1a237e;
            font-weight: 900;
            text-shadow: 
                3px 3px 6px rgba(255,255,255,1),
                -2px -2px 4px rgba(255,255,255,0.9),
                0 0 25px rgba(255,255,255,1);
            animation: fadeInLeft 0.8s ease-out;
        }
        
        .page-title i {
            animation: rotate 3s linear infinite;
            color: #7c4dff;
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
        
        .alert-success {
            border-color: rgba(56, 239, 125, 0.8) !important;
            color: #00897b !important;
        }
        
        .alert-danger {
            border-color: rgba(245, 87, 108, 0.8) !important;
            color: #c62828 !important;
        }
        
        /* Form Elements */
        .form-control, .form-select {
            border: 2px solid rgba(102, 126, 234, 0.5);
            border-radius: 15px;
            padding: 15px 20px;
            transition: all 0.3s ease;
            font-size: 16px;
            font-weight: 900;
            background: rgba(255, 255, 255, 0.95);
            color: #1a237e;
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.08);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #38ef7d;
            box-shadow: 0 0 20px rgba(56, 239, 125, 0.7);
            background-color: rgba(255,255,255,1);
            transform: scale(1.01);
        }
        
        .form-label {
            font-weight: 900;
            color: #1a237e;
            margin-bottom: 12px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 1);
        }
        
        .form-label i {
            color: #7c4dff;
            font-size: 20px;
            animation: bounce 2s infinite;
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
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
        }
        
        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(56, 239, 125, 0.7);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, rgba(102,126,234,0.95) 0%, rgba(118,75,162,0.95) 100%);
            border: 2px solid rgba(255,255,255,0.3);
            font-weight: 800;
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            padding: 12px 30px;
            border-radius: 10px;
        }
        
        .btn-secondary:hover {
            transform: translateY(-3px);
            color: white;
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
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1 class="page-title mb-4">
                    <i class="fas fa-edit"></i> ফসল পোস্ট এডিট করুন
                </h1>

                <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <strong><?php echo $success; ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong><?php echo $error; ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-seedling"></i> ফসলের নাম *
                                    </label>
                                    <select class="form-select" name="crop_id" required>
                                        <option value="">ফসল নির্বাচন করুন</option>
                                        <?php while($crop = $crops->fetch_assoc()): ?>
                                        <option value="<?php echo $crop['id']; ?>" <?php echo ($post['crop_id'] == $crop['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($crop['name_bn'], ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-weight"></i> পরিমাণ *
                                    </label>
                                    <input type="number" class="form-control" name="quantity" 
                                           value="<?php echo htmlspecialchars($post['quantity'], ENT_QUOTES, 'UTF-8'); ?>" 
                                           step="0.01" required>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-balance-scale"></i> একক
                                    </label>
                                    <select class="form-select" name="unit">
                                        <option value="kg" <?php echo ($post['unit'] == 'kg') ? 'selected' : ''; ?>>কেজি (kg)</option>
                                        <option value="ton" <?php echo ($post['unit'] == 'ton') ? 'selected' : ''; ?>>টন (ton)</option>
                                        <option value="quintal" <?php echo ($post['unit'] == 'quintal') ? 'selected' : ''; ?>>কুইন্টাল (quintal)</option>
                                        <option value="piece" <?php echo ($post['unit'] == 'piece') ? 'selected' : ''; ?>>পিস (piece)</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-money-bill-wave"></i> মূল্য (প্রতি একক) *
                                    </label>
                                    <input type="number" class="form-control" name="price" 
                                           value="<?php echo htmlspecialchars($post['price'], ENT_QUOTES, 'UTF-8'); ?>" 
                                           step="0.01" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-map-marker-alt"></i> এলাকা
                                    </label>
                                    <input type="text" class="form-control" name="location" 
                                           value="<?php echo htmlspecialchars($post['location'], ENT_QUOTES, 'UTF-8'); ?>">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-phone"></i> যোগাযোগ নম্বর
                                    </label>
                                    <input type="text" class="form-control" name="contact_number" 
                                           value="<?php echo htmlspecialchars($post['contact_number'], ENT_QUOTES, 'UTF-8'); ?>">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-comment"></i> বিবরণ
                                    </label>
                                    <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($post['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            </div>

                            <div class="d-flex gap-3 justify-content-end">
                                <a href="my_posts.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> বাতিল
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> আপডেট করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
