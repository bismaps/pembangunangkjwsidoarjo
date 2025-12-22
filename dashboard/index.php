<?php
include 'auth_check.php';
include '../db_connect.php';

// Prepare Graphic Data (Daily Inflow - Global)
$sql_graph = "SELECT tanggalSetor, SUM(nominal) as daily_total 
              FROM donations 
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

// Fetch Programs for Cards
$sql_programs = "SELECT * FROM programs ORDER BY id ASC";
$res_programs = $conn->query($sql_programs);
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
    <!-- Custom Wildvine Theme -->
    <link href="custom-wildvine.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-church me-3"></i> GKJW Admin
        </div>
        <div class="mt-4">
            <a href="index.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
            <a href="donations.php"><i class="fas fa-hand-holding-heart"></i> Data Donatur</a>
            <a href="programs.php"><i class="fas fa-tasks"></i> Kelola Program</a>
            <a href="verification.php"><i class="fas fa-check-circle"></i> Verifikasi Transfer</a>
        </div>
        
        <div style="position: absolute; bottom: 30px; width: 100%;">
            <a href="../index.php"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
            <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-1">Selamat Datang, <?php echo htmlspecialchars($admin_user); ?>! 👋</h2>
                <p class="text-muted">Berikut adalah ringkasan progres pembangunan gereja.</p>
            </div>
            <a href="https://docs.google.com/spreadsheets/u/0/" target="_blank" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i> Buka Google Sheets
            </a>
        </div>

        <!-- Dynamic Program Cards -->
        <div class="row">
            <?php 
            if ($res_programs->num_rows > 0):
                while($row = $res_programs->fetch_assoc()):
                    // Calculate Stats per Program
                    $p_id = $row['id'];
                    $sql_sum = "SELECT SUM(nominal) as total, COUNT(*) as count FROM donations WHERE program_id='$p_id'";
                    $res_sum = $conn->query($sql_sum);
                    $stats = $res_sum->fetch_assoc();
                    
                    $total_collected = $stats['total'] ?? 0;
                    $count_donors = $stats['count'] ?? 0;
                    $target = $row['target_amount'];
                    $shortfall = $target - $total_collected;
                    $percentage = ($target > 0) ? ($total_collected / $target) * 100 : 0;
            ?>
            <div class="col-md-6 mb-4">
                <a href="donations.php?program_id=<?php echo $p_id; ?>" class="text-decoration-none">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-muted mb-0"><i class="fas fa-chart-pie me-2"></i> <?php echo htmlspecialchars($row['title']); ?></h5>
                                <span class="badge bg-primary rounded-pill"><?php echo $count_donors; ?> Transaksi</span>
                            </div>
                            
                            <h2 class="fw-bold mb-3">Rp <?php echo number_format($total_collected, 0, ',', '.'); ?></h2>
                            
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between text-muted small">
                                <span><?php echo number_format($percentage, 1); ?>% Terkumpul</span>
                                <span>Target: Rp <?php echo number_format($target, 0, ',', '.'); ?></span>
                            </div>
                            
                            <?php if ($shortfall > 0): ?>
                            <div class="mt-2 text-danger small">
                                <i class="fas fa-exclamation-circle"></i> Kurang: Rp <?php echo number_format($shortfall, 0, ',', '.'); ?>
                            </div>
                            <?php else: ?>
                            <div class="mt-2 text-success small">
                                <i class="fas fa-check-circle"></i> Target Tercapai!
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; endif; ?>
        </div>

        <!-- Chart Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Grafik Donasi Harian (Global)</h5>
                        <canvas id="donationChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('donationChart').getContext('2d');
        const donationChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo $dates_json; ?>,
                datasets: [{
                    label: 'Pemasukan Harian (Rp)',
                    data: <?php echo $totals_json; ?>,
                    borderColor: '#94C766', // Wildvine Green
                    backgroundColor: 'rgba(148, 199, 102, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#444' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>
