include 'auth_check.php';

include '../db_connect.php';

// Fetch AC Donors
$sql = "SELECT * FROM donatur_ac ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage AC Donors - Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom Wildvine Theme -->
    <link href="custom-wildvine.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-church me-3"></i> GKJW Admin
        </div>
        <div class="mt-4">
            <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="verification.php"><i class="fas fa-check-circle"></i> Verifikasi Transfer</a>
            <a href="ac.php" class="active"><i class="fas fa-snowflake"></i> Donatur AC</a>
            <a href="multimedia.php"><i class="fas fa-desktop"></i> Donatur Multimedia</a>
        </div>
        
        <div style="position: absolute; bottom: 30px; width: 100%;">
            <a href="../index.php"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
            <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-snowflake text-success"></i> Manajemen Donatur AC</h2>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i> Tambah Donatur
            </button>
        </div>



        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="donorsTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Asal (KRW)</th>
                                <th class="text-end">Nominal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['tanggalSetor']; ?></td>
                                <td><?php echo htmlspecialchars($row['namaSetor']); ?></td>
                                <td><?php echo htmlspecialchars($row['krwSetor']); ?></td>
                                <td class="text-end fw-bold text-success">Rp <?php echo number_format($row['nominal'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                            data-id="<?php echo $row['id']; ?>"
                                            data-nama="<?php echo htmlspecialchars($row['namaSetor']); ?>"
                                            data-tanggal="<?php echo $row['tanggalSetor']; ?>"
                                            data-krw="<?php echo htmlspecialchars($row['krwSetor']); ?>"
                                            data-nominal="<?php echo $row['nominal']; ?>"
                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="process_donor.php" method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="delete_ac">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger delete-btn">
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="process_donor.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Donatur AC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_ac">
                        <div class="mb-3">
                            <label>Tanggal Setor</label>
                            <input type="date" name="tanggal" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label>Nama Donatur</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Contoh: NN">
                        </div>
                        <div class="mb-3">
                            <label>Asal / KRW</label>
                            <input type="text" name="krw" class="form-control" placeholder="Contoh: BPS">
                        </div>
                        <div class="mb-3">
                            <label>Nominal (Rp)</label>
                            <input type="number" name="nominal" class="form-control" required placeholder="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="process_donor.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Donatur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_ac">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label>Tanggal Setor</label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nama Donatur</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Asal / KRW</label>
                            <input type="text" name="krw" id="edit_krw" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Nominal (Rp)</label>
                            <input type="number" name="nominal" id="edit_nominal" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#donorsTable').DataTable({
                "order": [[ 0, "desc" ]] // Default sort by ID desc
            });

            // Handle Edit Button Click (Delegated for DataTables)
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const tanggal = $(this).data('tanggal');
                const krw = $(this).data('krw');
                const nominal = $(this).data('nominal');

                $('#edit_id').val(id);
                $('#edit_nama').val(nama);
                $('#edit_tanggal').val(tanggal);
                $('#edit_krw').val(krw);
                $('#edit_nominal').val(nominal);
            });

            // Handle Delete Confirmation
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
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
                    case 'added':
                        text = 'Data donatur berhasil ditambahkan.';
                        break;
                    case 'updated':
                        text = 'Data donatur berhasil diperbarui.';
                        break;
                    case 'deleted':
                        text = 'Data donatur berhasil dihapus.';
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
