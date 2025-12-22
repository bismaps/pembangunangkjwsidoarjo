<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../db_connect.php';

// Fetch AC Stats
$sql_ac = "SELECT Count(*) as count, SUM(nominal) as total FROM donatur_ac";
$result_ac = $conn->query($sql_ac);
$row_ac = $result_ac->fetch_assoc();
$total_ac = $row_ac['total'] ?? 0;
$count_ac = $row_ac['count'] ?? 0;

// Fetch Multimedia Stats
$sql_m = "SELECT Count(*) as count, SUM(nominal) as total FROM donatur_multimedia";
$result_m = $conn->query($sql_m);
$row_m = $result_m->fetch_assoc();
$total_m = $row_m['total'] ?? 0;
$count_m = $row_m['count'] ?? 0;

// Prepare Graphic Data (Daily Inflow)
$sql_graph = "SELECT tanggalSetor, SUM(nominal) as daily_total 
              FROM donatur_ac 
              GROUP BY tanggalSetor 
              ORDER BY tanggalSetor ASC";
$res_graph = $conn->query($sql_graph);
$dates = [];
$totals = [];
while($g = $res_graph->fetch_assoc()) {
    $dates[] = $g['tanggalSetor'];
    $totals[] = $g['daily_total'];
}
$dates_json = json_encode($dates);
$totals_json = json_encode($totals);

$admin_user = $_SESSION['username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GKJW Sidoarjo</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <title>Dashboard - GKJW Sidoarjo</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Wildvine Theme -->
    <link href="custom-wildvine.css" rel="stylesheet">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-church me-3"></i> GKJW Admin
        </div>
        <div class="mt-4">
            <a href="index.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
            <a href="verification.php"><i class="fas fa-check-circle"></i> Verifikasi Transfer</a>
            <a href="ac.php"><i class="fas fa-snowflake"></i> Donatur AC</a>
            <a href="multimedia.php"><i class="fas fa-desktop"></i> Donatur Multimedia</a>
            <!-- Users link removed -->
        </div>
        
        <div style="position: absolute; bottom: 30px; width: 100%;">
            <a href="../index.php"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
            <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard Overview</h2>
            <span class="text-muted">Welcome, <?php echo htmlspecialchars($admin_user); ?></span>
        </div>

        <?php
        include '../target_config.php';
        
        // Progress Logic
        $target_ac = $target_ac_amount;
        $pct_ac = ($total_ac / $target_ac) * 100;
        $rem_ac = $target_ac - $total_ac;

        $target_m = $target_multimedia_amount;
        $pct_m = ($total_m / $target_m) * 100;
        $rem_m = $target_m - $total_m;
        ?>

        <div class="row">
            <!-- AC Progress -->
            <div class="col-md-6">
                <div class="stat-card">
                    <h5 class="mb-3"><i class="fas fa-snowflake text-success me-2"></i> Progress Donasi AC</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Terkumpul: <strong>Rp <?php echo number_format($total_ac, 0, ',', '.'); ?></strong></span>
                        <span class="text-success fw-bold"><?php echo number_format($pct_ac, 1); ?>%</span>
                    </div>
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $pct_ac; ?>%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Target: Rp <?php echo number_format($target_ac, 0, ',', '.'); ?></span>
                        <span>Kurang: Rp <?php echo number_format($rem_ac, 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Multimedia Progress -->
            <div class="col-md-6">
                <div class="stat-card">
                    <h5 class="mb-3"><i class="fas fa-desktop text-info me-2"></i> Progress Multimedia</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Terkumpul: <strong>Rp <?php echo number_format($total_m, 0, ',', '.'); ?></strong></span>
                        <span class="text-info fw-bold"><?php echo number_format($pct_m, 1); ?>%</span>
                    </div>
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $pct_m; ?>%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Target: Rp <?php echo number_format($target_m, 0, ',', '.'); ?></span>
                        <span>Kurang: Rp <?php echo number_format($rem_m, 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <a href="https://docs.google.com/spreadsheets" target="_blank" class="text-decoration-none">
                    <div class="stat-card bg-success text-white text-center hover-scale" style="transition: transform 0.2s;">
                        <h4 class="m-0"><i class="fas fa-table me-2"></i> Buka Database Donatur Lengkap (Google Sheets)</h4>
                        <p class="m-0 mt-2 small text-white-50">Klik untuk melihat detail data donatur di Google Sheets</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
