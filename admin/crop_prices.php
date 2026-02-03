<?php
require_once '../config/config.php';

if (!isLoggedIn() || getUserRole() !== 'admin') {
    redirect('../auth/login.php');
}

$error = '';
$success = '';

// Handle Add/Update Price
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_price'])) {
    $crop_id = sanitize($_POST['crop_id']);
    $location = sanitize($_POST['location']);
    $price_min = sanitize($_POST['price_min']);
    $price_max = sanitize($_POST['price_max']);
    $price_avg = sanitize($_POST['price_avg']);
    $unit = sanitize($_POST['unit']);
    $date = sanitize($_POST['date']);
    $created_by = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("INSERT INTO crop_prices (crop_id, location, price_min, price_max, price_avg, unit, date_recorded, created_by) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdddssi", $crop_id, $location, $price_min, $price_max, $price_avg, $unit, $date, $created_by);
    
    if ($stmt->execute()) {
        $success = 'দাম সফলভাবে যুক্ত হয়েছে!';
    } else {
        $error = 'দাম যুক্ত করতে ত্রুটি';
    }
    $stmt->close();
}

// Handle Delete
if (isset($_POST['delete_price'])) {
    $price_id = sanitize($_POST['price_id']);
    $conn->query("DELETE FROM crop_prices WHERE id = $price_id");
}

// Get all crops
$crops = $conn->query("SELECT * FROM crops ORDER BY name_bn");

// Get recent prices
$prices = $conn->query("SELECT cp.*, c.name_bn as crop_name, u.name as added_by 
                        FROM crop_prices cp 
                        JOIN crops c ON cp.crop_id = c.id 
                        LEFT JOIN users u ON cp.created_by = u.id 
                        ORDER BY cp.date_recorded DESC, cp.created_at DESC 
                        LIMIT 50");
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ফসলের দাম পরিচালনা - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1 class="h2 mb-4"><i class="fas fa-chart-line"></i> ফসলের দাম পরিচালনা</h1>

                <?php if ($error): echo showAlert($error, 'danger'); endif; ?>
                <?php if ($success): echo showAlert($success, 'success'); endif; ?>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-plus"></i> নতুন দাম যুক্ত করুন</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label">ফসল *</label>
                                        <select name="crop_id" class="form-select" required>
                                            <option value="">নির্বাচন করুন</option>
                                            <?php 
                                            $crops->data_seek(0);
                                            while($crop = $crops->fetch_assoc()): 
                                            ?>
                                            <option value="<?php echo $crop['id']; ?>"><?php echo $crop['name_bn']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">এলাকা *</label>
                                        <input type="text" name="location" class="form-control" placeholder="যেমন: ঢাকা" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">সর্বনিম্ন দাম *</label>
                                        <input type="number" name="price_min" class="form-control" step="0.01" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">সর্বোচ্চ দাম *</label>
                                        <input type="number" name="price_max" class="form-control" step="0.01" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">গড় দাম *</label>
                                        <input type="number" name="price_avg" class="form-control" step="0.01" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">একক *</label>
                                        <select name="unit" class="form-select" required>
                                            <option value="kg">কেজি</option>
                                            <option value="ton">টন</option>
                                            <option value="maund">মণ</option>
                                            <option value="quintal">কুইন্টাল</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">তারিখ *</label>
                                        <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>

                                    <button type="submit" name="add_price" class="btn btn-success w-100">
                                        <i class="fas fa-check"></i> যুক্ত করুন
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-list"></i> সাম্প্রতিক দাম তালিকা</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>তারিখ</th>
                                                <th>ফসল</th>
                                                <th>এলাকা</th>
                                                <th>সর্বনিম্ন</th>
                                                <th>সর্বোচ্চ</th>
                                                <th>গড়</th>
                                                <th>কাজ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($price = $prices->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $price['date_recorded']; ?></td>
                                                <td><?php echo $price['crop_name']; ?></td>
                                                <td><?php echo $price['location']; ?></td>
                                                <td>৳<?php echo number_format($price['price_min'], 2); ?></td>
                                                <td>৳<?php echo number_format($price['price_max'], 2); ?></td>
                                                <td><strong class="text-success">৳<?php echo number_format($price['price_avg'], 2); ?></strong></td>
                                                <td>
                                                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                                        <input type="hidden" name="price_id" value="<?php echo $price['id']; ?>">
                                                        <input type="hidden" name="delete_price" value="1">
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
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
