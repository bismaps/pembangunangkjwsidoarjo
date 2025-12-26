<?php
include '../db_connect.php';
include 'auth_check.php';

// Fetch Pending Transactions
$sql = "SELECT * FROM transactions WHERE status='pending' ORDER BY upload_time DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Bukti Transfer - GKJW Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="custom-wildvine.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-check-circle text-success"></i> Verifikasi Bukti Transfer</h2>
        </div>



        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="verificationTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal Upload</th>
                                <th>Nama Pengirim</th>
                                <th>Tujuan</th>
                                <th class="text-end">OCR Nominal</th>
                                <th>OCR Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['upload_time']; ?></td>
                                    <td><?php echo htmlspecialchars($row['sender_name']); ?></td>
                                    <td><span class="badge bg-info text-dark"><?php echo $row['target_category']; ?></span></td>
                                    <td class="fw-bold text-end">Rp <?php echo number_format($row['extracted_nominal'], 0, ',', '.'); ?></td>
                                    <td><?php echo $row['extracted_date'] ? $row['extracted_date'] : '<span class="text-muted">-</span>'; ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#approveModal<?php echo $row['id']; ?>" title="Review & Verifikasi">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="process_verification.php" method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="reject">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger reject-btn" title="Tolak">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal<?php echo $row['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="process_verification.php" method="POST">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi & Edit Data</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="approve">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="target_category" value="<?php echo $row['target_category']; ?>">
                                                    
                                                    <div class="text-center mb-3">
                                                        <?php if (!empty($row['image_path']) && file_exists("../" . $row['image_path'])): ?>
                                                            <div style="border: 1px solid #ddd; padding: 5px; border-radius: 8px; display: inline-block;">
                                                                <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="Bukti Transfer" class="img-fluid" style="max-height: 300px; border-radius: 4px;">
                                                            </div>
                                                            <div class="mt-2">
                                                                <a href="../<?php echo htmlspecialchars($row['image_path']); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fas fa-external-link-alt"></i> Buka Full Size
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle"></i> Foto bukti tidak ditemukan (mungkin terhapus).
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="alert alert-info py-2" style="font-size: 0.9rem;">
                                                        <i class="fas fa-info-circle me-1"></i> 
                                                        OCR Raw Text: <br>
                                                        <small class="text-muted"><?php echo substr(htmlspecialchars($row['ocr_text'] ?? ''), 0, 200) . '...'; ?></small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label>Nama Donatur (Asli)</label>
                                                        <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($row['sender_name']); ?>" required oninput="updateAlias(this)">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Nama Samaran (Alias)</label>
                                                        <input type="text" name="alias" class="form-control" value="<?php echo htmlspecialchars(substr($row['sender_name'], 0, 1) . '...'); ?>" required>
                                                        <small class="text-muted">Untuk tampilan publik (Privasi).</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Tanggal Transaksi</label>
                                                        <input type="date" name="tanggal" class="form-control" value="<?php echo $row['extracted_date'] ? $row['extracted_date'] : date('Y-m-d', strtotime($row['upload_time'])); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Nominal (Rp)</label>
                                                        <input type="number" name="nominal" class="form-control" value="<?php echo $row['extracted_nominal']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Asal / KRW (Opsional)</label>
                                                        <input type="text" name="krw" class="form-control" placeholder="Contoh: Majelis Agung">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Verifikasi & Tambah</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">Tidak ada transaksi yang perlu diverifikasi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        function updateAlias(input) {
            let name = input.value.trim();
            let alias = name.length > 0 ? name.charAt(0).toUpperCase() + "..." : "NN";
            // Find the alias input in the same form
            let form = input.closest('form');
            let aliasInput = form.querySelector('input[name="alias"]');
            if (aliasInput) {
                aliasInput.value = alias;
            }
        }

        $(document).ready(function() {
            $('#verificationTable').DataTable({
                "order": [[ 0, "desc" ]] // Sort by Upload Time (Column 0) Descending
            });

            // Handle Reject Confirmation
            $(document).on('click', '.reject-btn', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Tolak Transaksi?',
                    text: "Data akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // SweetAlert2 Toast Logic
            const urlParams = new URLSearchParams(window.location.search);
            const msg = urlParams.get('msg');

            if (msg) {
                let icon = 'success';
                let title = 'Berhasil!';
                let text = '';

                switch(msg) {
                    case 'approved':
                        title = 'Diverifikasi!';
                        text = 'Transaksi disetujui dan ditambahkan.';
                        break;
                    case 'rejected':
                        icon = 'info';
                        title = 'Ditolak!';
                        text = 'Transaksi telah dihapus.';
                        break;
                    default:
                        text = 'Operasi berhasil.';
                }

                Swal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>
