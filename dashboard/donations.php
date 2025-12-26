<?php
include 'auth_check.php';
include '../db_connect.php';

// Filter Logic
$program_filter = $_GET['program_id'] ?? 'all';
$where_clause = "";
if ($program_filter != 'all') {
    $p_id = $conn->real_escape_string($program_filter);
    $where_clause = "WHERE d.program_id = '$p_id'";
}

// Fetch Donations with Program Name
$sql = "SELECT d.*, p.title as program_title 
        FROM donations d 
        JOIN programs p ON d.program_id = p.id 
        $where_clause 
        ORDER BY d.id DESC";
$result = $conn->query($sql);

// Fetch Programs for Dropdown
$sql_progs = "SELECT * FROM programs";
$res_progs = $conn->query($sql_progs);
$programs_list = [];
while($p = $res_progs->fetch_assoc()) {
    $programs_list[] = $p;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Donatur - Dashboard</title>
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="custom-wildvine.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><i class="fas fa-church me-3"></i> GKJW Admin</div>
        <div class="mt-4">
            <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="donations.php" class="active"><i class="fas fa-hand-holding-heart"></i> Data Donatur</a>
            <a href="programs.php"><i class="fas fa-tasks"></i> Kelola Program</a>
            <a href="verification.php"><i class="fas fa-check-circle"></i> Verifikasi</a>
        </div>
        <div style="position: absolute; bottom: 30px; width: 100%;">
            <a href="../index.php"><i class="fas fa-external-link-alt"></i> Lihat Website</a>
            <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users text-info"></i> Data Donatur</h2>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i> Tambah Donatur
            </button>
        </div>

        <!-- Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body py-2">
                <form method="GET" class="row align-items-center">
                    <div class="col-auto"><label class="fw-bold">Filter Program:</label></div>
                    <div class="col-auto">
                        <select name="program_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all">Semua Program</option>
                            <?php foreach($programs_list as $prog): ?>
                                <option value="<?php echo $prog['id']; ?>" <?php if($program_filter == $prog['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($prog['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="donorsTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Tanggal</th>
                                <th>Nama (Alias)</th>
                                <th>Program</th>
                                <th>Asal</th>
                                <th class="text-end">Nominal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($result->num_rows > 0):
                                while($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggalSetor'])); ?></td>
                                <td class="fw-500">
                                    <strong><?php echo htmlspecialchars($row['namaSetor']); ?></strong><br>
                                    <small class="text-muted">Alias: <?php echo htmlspecialchars($row['alias_name'] ?? '-'); ?></small>
                                </td>
                                <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['program_title']); ?></span></td>
                                <td><?php echo htmlspecialchars($row['krwSetor']); ?></td>
                                <td class="text-end fw-bold text-success">Rp <?php echo number_format($row['nominal'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                            data-id="<?php echo $row['id']; ?>"
                                            data-program="<?php echo $row['program_id']; ?>"
                                            data-nama="<?php echo htmlspecialchars($row['namaSetor']); ?>"
                                            data-alias="<?php echo htmlspecialchars($row['alias_name'] ?? ''); ?>"
                                            data-tanggal="<?php echo $row['tanggalSetor']; ?>"
                                            data-krw="<?php echo htmlspecialchars($row['krwSetor']); ?>"
                                            data-nominal="<?php echo $row['nominal']; ?>"
                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="process_donor.php" method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="delete_donation">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="button" class="btn btn-sm btn-danger delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; endif; ?>
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
                        <h5 class="modal-title">Tambah Donatur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_donation">
                        <div class="mb-3">
                            <label>Pilih Program Donasi</label>
                            <select name="program_id" class="form-select" required>
                                <?php foreach($programs_list as $prog): ?>
                                    <option value="<?php echo $prog['id']; ?>"><?php echo htmlspecialchars($prog['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal Setor</label>
                            <input type="date" name="tanggal" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label>Nama Donatur (Asli)</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Contoh: Ali Mustofa">
                        </div>
                        <div class="mb-3">
                            <label>Nama Samaran (Alias)</label>
                            <input type="text" name="alias" class="form-control" placeholder="Contoh: Hamba Allah">
                            <small class="text-muted">Kosongkan jika ingin menggunakan Hamba Allah.</small>
                        </div>
                        <div class="mb-3">
                            <label>Asal / Keterangan</label>
                            <input type="text" name="krw" class="form-control" placeholder="Contoh: Sidoarjo">
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
                        <input type="hidden" name="action" value="edit_donation">
                        <input type="hidden" name="id" id="edit_id">
                        
                        <div class="mb-3">
                            <label>Program Donasi</label>
                            <select name="program_id" id="edit_program" class="form-select" required>
                                <?php foreach($programs_list as $prog): ?>
                                    <option value="<?php echo $prog['id']; ?>"><?php echo htmlspecialchars($prog['title']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal Setor</label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nama Donatur</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nama Samaran (Alias)</label>
                            <input type="text" name="alias" id="edit_alias" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Asal / Keterangan</label>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#donorsTable').DataTable({ "order": [[ 0, "desc" ]] });

            // Edit Handler
            // Edit Handler (Delegated for Pagination)
            $(document).on('click', '.edit-btn', function() {
                $('#edit_id').val($(this).data('id'));
                $('#edit_program').val($(this).data('program'));
                $('#edit_tanggal').val($(this).data('tanggal'));
                $('#edit_nama').val($(this).data('nama'));
                $('#edit_alias').val($(this).data('alias'));
                $('#edit_krw').val($(this).data('krw'));
                $('#edit_nominal').val($(this).data('nominal'));
            });

            // Delete Handler (Delegated for Pagination)
            $(document).on('click', '.delete-btn', function() {
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Yakin hapus?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if(result.isConfirmed) form.submit();
                });
            });

            // Toast Handler
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('msg')) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: urlParams.get('msg') == 'deleted' ? 'Data dihapus' : 'Data disimpan',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>
</html>
