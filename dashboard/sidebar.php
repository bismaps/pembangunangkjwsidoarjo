<?php
// Count Pending Verifications
$sql_pending = "SELECT COUNT(*) as count FROM transactions WHERE status IS NULL OR status = 'pending'"; // Adjust based on your schema default
// My schema doesn't have status column yet? wait, verification.php deletes row upon approval.
// So all rows in 'transactions' table are effectively 'pending' because verified ones are moved to 'donations'.
// Let's verify 'transactions' schema first.
// Correction: In Step 22 (init_db.php), transactions table has no status column. It is a temporary holding table.
// So ALL rows in transactions are pending.

$sql_count = "SELECT COUNT(*) as total FROM transactions";
$res_count = $conn->query($sql_count);
$pending_count = 0;
if ($res_count) {
    $row_count = $res_count->fetch_assoc();
    $pending_count = $row_count['total'];
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="brand">
        <i class="fas fa-church me-3"></i> GKJW Admin
    </div>
    <div class="mt-4">
        <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="donations.php" class="<?php echo ($current_page == 'donations.php') ? 'active' : ''; ?>">
            <i class="fas fa-hand-holding-heart"></i> Data Donatur
        </a>
        <a href="programs.php" class="<?php echo ($current_page == 'programs.php') ? 'active' : ''; ?>">
            <i class="fas fa-tasks"></i> Kelola Program
        </a>
        <a href="verification.php" class="<?php echo ($current_page == 'verification.php') ? 'active' : ''; ?> d-flex justify-content-between align-items-center">
            <span><i class="fas fa-check-circle"></i> Verifikasi</span>
            <?php if ($pending_count > 0): ?>
                <span class="badge bg-danger rounded-pill"><?php echo $pending_count; ?></span>
            <?php endif; ?>
        </a>
    </div>
    
    <div style="position: absolute; bottom: 30px; width: 100%;">
        <a href="../index.php"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
        <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
