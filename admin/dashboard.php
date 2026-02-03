<?php
require_once '../config/config.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || getUserRole() !== 'admin') {
    redirect('../auth/login.php');
}

// Get dashboard statistics
$stats = [];

// Total Users
$stats['total_users'] = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$stats['total_farmers'] = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='farmer'")->fetch_assoc()['count'];
$stats['total_buyers'] = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='buyer'")->fetch_assoc()['count'];

// Total Crop Posts
$stats['total_posts'] = $conn->query("SELECT COUNT(*) as count FROM crop_posts")->fetch_assoc()['count'];
$stats['active_posts'] = $conn->query("SELECT COUNT(*) as count FROM crop_posts WHERE status='active'")->fetch_assoc()['count'];

// Recent Activities
$recent_users = $conn->query("SELECT name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");
$recent_posts = $conn->query("SELECT cp.*, u.name as farmer_name, c.name_bn as crop_name 
                               FROM crop_posts cp 
                               JOIN users u ON cp.farmer_id = u.id 
                               JOIN crops c ON cp.crop_id = c.id 
                               ORDER BY cp.created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2"><i class="fas fa-dashboard"></i> অ্যাডমিন ড্যাশবোর্ড</h1>
                    <div>
                        <span class="text-muted">স্বাগতম, <?php echo $_SESSION['name']; ?></span>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">মোট ব্যবহারকারী</h6>
                                        <h2 class="mb-0"><?php echo $stats['total_users']; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-users fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">কৃষক</h6>
                                        <h2 class="mb-0"><?php echo $stats['total_farmers']; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-tractor fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">ক্রেতা</h6>
                                        <h2 class="mb-0"><?php echo $stats['total_buyers']; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase mb-1">সক্রিয় পোস্ট</h6>
                                        <h2 class="mb-0"><?php echo $stats['active_posts']; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-seedling fa-3x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user-plus"></i> সাম্প্রতিক ব্যবহারকারী</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>নাম</th>
                                                <th>ইমেইল</th>
                                                <th>ভূমিকা</th>
                                                <th>তারিখ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($user = $recent_users->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $user['name']; ?></td>
                                                <td><?php echo $user['email']; ?></td>
                                                <td>
                                                    <?php if($user['role'] == 'farmer'): ?>
                                                        <span class="badge bg-success">কৃষক</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">ক্রেতা</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-seedling"></i> সাম্প্রতিক ফসল পোস্ট</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>কৃষক</th>
                                                <th>ফসল</th>
                                                <th>পরিমাণ</th>
                                                <th>দাম</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($post = $recent_posts->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $post['farmer_name']; ?></td>
                                                <td><?php echo $post['crop_name']; ?></td>
                                                <td><?php echo $post['quantity'] . ' ' . $post['unit']; ?></td>
                                                <td>৳<?php echo number_format($post['price'], 2); ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row g-4 mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-bolt"></i> দ্রুত কাজ</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <a href="users.php" class="btn btn-primary w-100 py-3">
                                            <i class="fas fa-users fa-2x d-block mb-2"></i>
                                            ব্যবহারকারী পরিচালনা
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="crop_prices.php" class="btn btn-success w-100 py-3">
                                            <i class="fas fa-chart-line fa-2x d-block mb-2"></i>
                                            ফসলের দাম আপডেট
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="crop_posts.php" class="btn btn-warning w-100 py-3">
                                            <i class="fas fa-seedling fa-2x d-block mb-2"></i>
                                            ফসল পোস্ট দেখুন
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="weather_advisory.php" class="btn btn-info w-100 py-3">
                                            <i class="fas fa-cloud-sun-rain fa-2x d-block mb-2"></i>
                                            আবহাওয়া পরামর্শ
                                        </a>
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
</body>
</html>
