<?php
require_once '../config/config.php';

$error = '';
$success = '';

// Get role from URL parameter (farmer or buyer)
$selected_role = isset($_GET['role']) ? $_GET['role'] : 'farmer';
if (!in_array($selected_role, ['farmer', 'buyer'])) {
    $selected_role = 'farmer';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = sanitize($_POST['role']);
    $location = sanitize($_POST['location']);
    
    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($location)) {
        $error = 'সকল ফিল্ড পূরণ করুন';
    } elseif ($password !== $confirm_password) {
        $error = 'পাসওয়ার্ড মিলছে না';
    } elseif (strlen($password) < 6) {
        $error = 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে';
    } else {
        // Check if email or phone already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
        $check_stmt->bind_param("ss", $email, $phone);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = 'এই ইমেইল বা ফোন নম্বর ইতিমধ্যে নিবন্ধিত';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role, location) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $phone, $hashed_password, $role, $location);
            
            if ($stmt->execute()) {
                $success = 'সফলভাবে নিবন্ধন সম্পন্ন হয়েছে! এখন লগইন করুন';
                // Optionally auto-login
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['is_verified'] = 0;
                
                // Redirect based on role
                if ($role === 'farmer') {
                    redirect('../farmer/dashboard.php');
                } else {
                    redirect('../buyer/dashboard.php');
                }
            } else {
                $error = 'নিবন্ধনে ত্রুটি হয়েছে';
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>রেজিস্টার - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
            background-image: linear-gradient(rgba(255, 193, 7, 0.3), rgba(255, 152, 0, 0.3), rgba(40, 167, 69, 0.3)), url('../agrologo/resister.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .auth-card {
            backdrop-filter: blur(4px);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 240, 180, 0.25) 50%, rgba(220, 255, 220, 0.25) 100%) !important;
            border: 2px solid rgba(255, 255, 255, 0.4) !important;
            border-radius: 25px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
        }
        
        .auth-header i {
            color: #ffc107;
            text-shadow: 0 5px 20px rgba(255, 193, 7, 0.4);
        }
        
        .form-control {
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.6);
        }
        
        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 20px rgba(255, 193, 7, 0.3);
            background: rgba(255, 255, 255, 1);
        }
        
        .btn-animated {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            border: none;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            padding: 14px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(255, 193, 7, 0.4);
        }
        
        .btn-animated:hover {
            background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%);
            box-shadow: 0 8px 30px rgba(255, 193, 7, 0.5);
        }
        
        .role-buttons .btn {
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .role-buttons .btn:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .role-buttons .btn-check:checked + .btn {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        
        .auth-links a {
            color: #ffc107;
            text-decoration: none;
            font-weight: 600;
        }
        
        .auth-links a:hover {
            color: #ff9800;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-label i {
            color: #ffc107;
            margin-right: 5px;
        }
        
        .card-body {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 235, 160, 0.15) 50%, rgba(210, 255, 210, 0.15) 100%) !important;
            border-radius: 25px;
        }
    </style>
</head>
<body class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4 auth-header">
                            <i class="fas fa-leaf fa-3x text-success"></i>
                            <h2 class="mt-3">নতুন অ্যাকাউন্ট তৈরি করুন</h2>
                            <p class="text-muted">Smart AgroSupport System</p>
                        </div>

                        <?php if ($error): ?>
                            <?php echo showAlert($error, 'danger'); ?>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <?php echo showAlert($success, 'success'); ?>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3 form-group">
                                <label class="form-label"><i class="fas fa-user-tag"></i> আপনি কে?</label>
                                <div class="btn-group w-100 role-buttons" role="group">
                                    <input type="radio" class="btn-check" name="role" id="farmer" value="farmer" <?php echo $selected_role === 'farmer' ? 'checked' : ''; ?> required>
                                    <label class="btn btn-outline-success" for="farmer">
                                        <i class="fas fa-tractor"></i> কৃষক
                                    </label>

                                    <input type="radio" class="btn-check" name="role" id="buyer" value="buyer" <?php echo $selected_role === 'buyer' ? 'checked' : ''; ?> required>
                                    <label class="btn btn-outline-warning" for="buyer">
                                        <i class="fas fa-shopping-cart"></i> ক্রেতা
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3 form-group">
                                <label class="form-label"><i class="fas fa-user"></i> নাম</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3 form-group">
                                <label class="form-label"><i class="fas fa-envelope"></i> ইমেইল</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3 form-group">
                                <label class="form-label"><i class="fas fa-phone"></i> মোবাইল নম্বর</label>
                                <input type="tel" name="phone" class="form-control" placeholder="01XXXXXXXXX" required>
                            </div>

                            <div class="mb-3 form-group">
                                <label class="form-label"><i class="fas fa-map-marker-alt"></i> এলাকা/জেলা</label>
                                <input type="text" name="location" class="form-control" placeholder="যেমন: ঢাকা, চট্টগ্রাম" required>
                            </div>

                            <div class="mb-3 form-group">
                                <label class="form-label"><i class="fas fa-lock"></i> পাসওয়ার্ড</label>
                                <input type="password" name="password" class="form-control" minlength="6" required>
                            </div>

                            <div class="mb-3 form-group">
                                <label class="form-label"><i class="fas fa-lock"></i> পাসওয়ার্ড নিশ্চিত করুন</label>
                                <input type="password" name="confirm_password" class="form-control" minlength="6" required>
                            </div>

                            <button type="submit" class="btn btn-animated w-100">
                                <i class="fas fa-user-plus"></i> রেজিস্টার করুন
                            </button>
                        </form>

                        <hr class="my-4">
                        
                        <div class="text-center auth-links">
                            <p>ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="login.php" class="text-success fw-bold">লগইন করুন</a></p>
                            <a href="../index.php" class="text-muted">হোমে ফিরে যান</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
