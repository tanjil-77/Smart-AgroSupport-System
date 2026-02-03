<?php
require_once '../config/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_type = sanitize($_POST['login_type']); // 'email' or 'phone'
    $login_value = '';
    
    if ($login_type == 'email') {
        $login_value = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    } else {
        $login_value = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
    }
    
    $password = $_POST['password'];
    
    if (empty($login_value) || empty($password)) {
        $error = 'সকল ফিল্ড পূরণ করুন';
    } else {
        // Check login by email or phone
        if ($login_type == 'email') {
            $stmt = $conn->prepare("SELECT id, name, email, phone, password, role, is_verified FROM users WHERE email = ?");
        } else {
            $stmt = $conn->prepare("SELECT id, name, email, phone, password, role, is_verified FROM users WHERE phone = ?");
        }
        
        $stmt->bind_param("s", $login_value);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['is_verified'] = $user['is_verified'];
                
                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        redirect('../admin/dashboard.php');
                        break;
                    case 'farmer':
                        redirect('../farmer/dashboard.php');
                        break;
                    case 'buyer':
                        redirect('../buyer/dashboard.php');
                        break;
                }
            } else {
                $error = 'ভুল ' . ($login_type == 'email' ? 'ইমেইল' : 'ফোন নম্বর') . ' বা পাসওয়ার্ড';
            }
        } else {
            $error = 'ভুল ' . ($login_type == 'email' ? 'ইমেইল' : 'ফোন নম্বর') . ' বা পাসওয়ার্ড';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন - <?php echo SITE_NAME; ?></title>
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
            background-image: linear-gradient(rgba(40, 167, 69, 0.3), rgba(32, 201, 151, 0.3)), url('../agrologo/login.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .auth-card {
            backdrop-filter: blur(4px);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(240, 255, 240, 0.25) 50%, rgba(230, 250, 240, 0.25) 100%) !important;
            border: 2px solid rgba(255, 255, 255, 0.4) !important;
            border-radius: 25px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
        }
        
        .auth-header i {
            color: #28a745;
            text-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
        }
        
        .form-control {
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.6);
        }
        
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.3);
            background: rgba(255, 255, 255, 0.8);
        }
        
        .btn-animated {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            padding: 14px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(40, 167, 69, 0.4);
        }
        
        .btn-animated:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            box-shadow: 0 8px 30px rgba(40, 167, 69, 0.5);
        }
        
        .auth-links a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
        }
        
        .auth-links a:hover {
            color: #20c997;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-label i {
            color: #28a745;
            margin-right: 5px;
        }
        
        .card-body {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(240, 255, 245, 0.15) 50%, rgba(230, 250, 240, 0.15) 100%) !important;
            border-radius: 25px;
        }
    </style>
</head>
<body class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card auth-card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4 auth-header">
                            <i class="fas fa-leaf fa-3x"></i>
                            <h2 class="mt-3">লগইন করুন</h2>
                            <p class="text-muted">Smart AgroSupport System</p>
                        </div>

                        <div class="mb-4">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="login_type" id="email_login" value="email" checked onchange="toggleLoginType('email')">
                                <label class="btn btn-outline-success" for="email_login" style="font-weight: 600;">
                                    <i class="fas fa-envelope"></i> ইমেইল
                                </label>
                                
                                <input type="radio" class="btn-check" name="login_type" id="phone_login" value="phone" onchange="toggleLoginType('phone')">
                                <label class="btn btn-outline-success" for="phone_login" style="font-weight: 600;">
                                    <i class="fas fa-phone"></i> ফোন
                                </label>
                            </div>
                        </div>

                        <?php if ($error): ?>
                            <?php echo showAlert($error, 'danger'); ?>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <input type="hidden" name="login_type" id="login_type_input" value="email">
                            
                            <div class="mb-3 form-group" id="email_field">
                                <label class="form-label"><i class="fas fa-envelope"></i> ইমেইল</label>
                                <input type="email" name="email" id="email_input" class="form-control" placeholder="example@email.com" required>
                            </div>
                            
                            <div class="mb-3 form-group" id="phone_field" style="display: none;">
                                <label class="form-label"><i class="fas fa-phone"></i> ফোন নম্বর</label>
                                <input type="tel" name="phone" id="phone_input" class="form-control" placeholder="01700000000">
                            </div>
                            
                            <div class="mb-3 form-group">
                                <label class="form-label"><i class="fas fa-lock"></i> পাসওয়ার্ড</label>
                                <input type="password" name="password" class="form-control" required placeholder="********">
                            </div>
                            <button type="submit" class="btn btn-animated w-100">
                                <i class="fas fa-sign-in-alt"></i> লগইন করুন
                            </button>
                        </form>

                        <hr class="my-4">
                        
                        <div class="text-center auth-links">
                            <p>নতুন ব্যবহারকারী? <a href="register.php">রেজিস্টার করুন</a></p>
                            <a href="../index.php" class="text-muted">হোমে ফিরে যান</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleLoginType(type) {
            const emailField = document.getElementById('email_field');
            const phoneField = document.getElementById('phone_field');
            const emailInput = document.getElementById('email_input');
            const phoneInput = document.getElementById('phone_input');
            const loginTypeInput = document.getElementById('login_type_input');
            
            if (type === 'email') {
                emailField.style.display = 'block';
                phoneField.style.display = 'none';
                emailInput.required = true;
                phoneInput.required = false;
                loginTypeInput.value = 'email';
            } else {
                emailField.style.display = 'none';
                phoneField.style.display = 'block';
                emailInput.required = false;
                phoneInput.required = true;
                loginTypeInput.value = 'phone';
            }
        }
    </script>
</body>
</html>
