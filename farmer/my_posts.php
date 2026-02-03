<?php
require_once '../config/config.php';

if (!isLoggedIn() || getUserRole() !== 'farmer') {
    redirect('../auth/login.php');
}

$farmer_id = $_SESSION['user_id'];

// Handle status update
if (isset($_POST['update_status'])) {
    $post_id = sanitize($_POST['post_id']);
    $new_status = sanitize($_POST['status']);
    
    $stmt = $conn->prepare("UPDATE crop_posts SET status = ? WHERE id = ? AND farmer_id = ?");
    $stmt->bind_param("sii", $new_status, $post_id, $farmer_id);
    $stmt->execute();
    $stmt->close();
}

// Handle delete
if (isset($_POST['delete_post'])) {
    $post_id = sanitize($_POST['post_id']);
    
    $stmt = $conn->prepare("DELETE FROM crop_posts WHERE id = ? AND farmer_id = ?");
    $stmt->bind_param("ii", $post_id, $farmer_id);
    $stmt->execute();
    $stmt->close();
}

// Get all posts
$posts = $conn->query("SELECT cp.*, c.name_bn as crop_name 
                       FROM crop_posts cp 
                       JOIN crops c ON cp.crop_id = c.id 
                       WHERE cp.farmer_id = $farmer_id 
                       ORDER BY cp.created_at DESC");
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>আমার পোস্ট - <?php echo SITE_NAME; ?></title>
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
        
        .alert i {
            animation: bounce 2s infinite;
            color: #667eea;
        }
        
        .alert h4 {
            font-weight: 900;
            color: #1a237e;
            text-shadow: 3px 3px 5px rgba(255,255,255,1);
        }
        
        /* Table Styling */
        .table-responsive {
            background: rgba(255, 255, 255, 0);
            border-radius: 10px;
        }
        
        .table {
            font-weight: 900;
            margin-bottom: 0;
        }
        
        .table thead {
            background: linear-gradient(135deg, rgb(17, 153, 142) 0%, rgb(56, 239, 125) 100%);
            color: white;
            font-weight: 900;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .table tbody tr {
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease-out backwards;
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.4s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.5s; }
        .table tbody tr:nth-child(n+6) { animation-delay: 0.6s; }
        
        .table tbody tr:hover {
            background: rgba(56, 239, 125, 0.15) !important;
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .table td, .table th {
            vertical-align: middle;
            font-weight: 900;
            color: #1a237e;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8);
            padding: 1rem;
        }
        
        .table th {
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .text-success {
            color: #00897b !important;
            text-shadow: 0 0 10px rgba(0,137,123,0.3);
            font-weight: 900;
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
        }
        
        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(56, 239, 125, 0.7);
            color: white;
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
        
        .btn-info {
            background: linear-gradient(135deg, rgb(102,126,234) 0%, rgb(118,75,162) 100%);
            border: 2px solid rgba(255,255,255,0.3);
            font-weight: 800;
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .btn-info:hover {
            transform: scale(1.1);
            color: white;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, rgba(255,193,7,0.95) 0%, rgba(255,152,0,0.95) 100%);
            border: 2px solid rgba(255,255,255,0.3);
            font-weight: 800;
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .btn-warning:hover {
            transform: scale(1.1);
            color: white;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, rgba(240,147,251,0.95) 0%, rgba(245,87,108,0.95) 100%);
            border: 2px solid rgba(255,255,255,0.3);
            font-weight: 800;
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .btn-danger:hover {
            transform: scale(1.1);
            color: white;
        }
        
        /* Form Select Styling */
        .form-select {
            font-weight: 900;
            border: 2px solid rgba(102, 126, 234, 0.5);
            background-color: rgba(255,255,255,0.95);
            color: #1a237e;
            transition: all 0.3s ease;
        }
        
        .form-select:focus {
            border-color: #38ef7d;
            box-shadow: 0 0 20px rgba(56, 239, 125, 0.7);
            background-color: rgba(255,255,255,1);
        }
        
        .form-select-sm {
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Icon Animations */
        .fa-seedling {
            animation: colorPulse 3s infinite;
            color: #00897b;
        }
        
        .fa-list {
            animation: shimmer 2s infinite;
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
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 5px 15px rgba(56, 239, 125, 0.5);
            }
            50% {
                box-shadow: 0 5px 25px rgba(56, 239, 125, 0.8);
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
                text-shadow: 0 0 5px rgba(255,255,255,0.5);
            }
            50% {
                text-shadow: 0 0 20px rgba(255,255,255,1), 0 0 30px rgba(102, 126, 234, 0.8);
            }
            100% {
                text-shadow: 0 0 5px rgba(255,255,255,0.5);
            }
        }
        
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2"><i class="fas fa-list"></i> আমার ফসল পোস্ট</h1>
                    <a href="add_crop_post.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> নতুন পোস্ট
                    </a>
                </div>

                <?php if ($posts->num_rows > 0): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th>#</th>
                                        <th>ফসল</th>
                                        <th>পরিমাণ</th>
                                        <th>দাম</th>
                                        <th>এলাকা</th>
                                        <th>অবস্থা</th>
                                        <th>তারিখ</th>
                                        <th>কাজ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sl = 1;
                                    while($post = $posts->fetch_assoc()): 
                                    ?>
                                    <tr>
                                        <td><?php echo $sl++; ?></td>
                                        <td>
                                            <i class="fas fa-seedling text-success"></i>
                                            <?php echo htmlspecialchars($post['crop_name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($post['quantity'] . ' ' . $post['unit'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><strong class="text-success">৳<?php echo number_format($post['price'], 2); ?></strong></td>
                                        <td><?php echo htmlspecialchars($post['location'] ?: '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                                <input type="hidden" name="update_status" value="1">
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="active" <?php echo $post['status']=='active'?'selected':''; ?>>সক্রিয়</option>
                                                    <option value="sold" <?php echo $post['status']=='sold'?'selected':''; ?>>বিক্রিত</option>
                                                    <option value="expired" <?php echo $post['status']=='expired'?'selected':''; ?>>মেয়াদ শেষ</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($post['created_at'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="viewDetails(<?php echo $post['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="edit_crop_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                                <input type="hidden" name="delete_post" value="1">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>আপনি এখনো কোনো পোস্ট করেননি</h4>
                    <a href="add_crop_post.php" class="btn btn-success mt-3">
                        <i class="fas fa-plus"></i> প্রথম পোস্ট করুন
                    </a>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script>
        // Ripple effect for buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
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
            });
        });
        
        // Enhanced confirm delete with animation
        function confirmDelete() {
            return confirm('আপনি কি নিশ্চিত যে এই পোস্টটি মুছে ফেলতে চান?\nThis action cannot be undone!');
        }
        
        function viewDetails(postId) {
            // Animate alert
            const alertBox = document.createElement('div');
            alertBox.className = 'alert alert-info position-fixed top-50 start-50 translate-middle';
            alertBox.style.zIndex = '9999';
            alertBox.style.animation = 'fadeInUp 0.5s ease-out';
            alertBox.innerHTML = `
                <i class="fas fa-info-circle fa-2x mb-2"></i>
                <h5>পোস্ট বিস্তারিত</h5>
                <p>পোস্ট বিস্তারিত দেখার ফিচার শীঘ্রই যুক্ত হবে</p>
                <p><small>Post ID: ${postId}</small></p>
            `;
            document.body.appendChild(alertBox);
            
            setTimeout(() => {
                alertBox.style.animation = 'fadeInUp 0.5s ease-out reverse';
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }
        
        // Smooth scroll observer for table rows
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('tbody tr').forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            row.style.transition = `all 0.5s ease ${index * 0.1}s`;
            observer.observe(row);
        });
    </script>
</body>
</html>
