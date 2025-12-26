<?php
include 'auth_check.php';
include '../db_connect.php';

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    
    // File Upload Handler
    $image_path = "";
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/images/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Allow certain file formats
        if(in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif', 'webp'])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = "./assets/images/" . $file_name; // Relative for DB
            }
        }
    }

    if ($action == "add_program") {
        $title = $conn->real_escape_string($_POST['title']);
        $target = $_POST['target'];
        $desc = $conn->real_escape_string($_POST['description']);
        // Default image if none uploaded
        if(empty($image_path)) $image_path = "./assets/images/service-1.jpg";
        
        $sql = "INSERT INTO programs (title, target_amount, description, image_path) VALUES ('$title', '$target', '$desc', '$image_path')";
        if ($conn->query($sql)) {
            header("Location: programs.php?msg=added");
            exit();
        }
    } 
    elseif ($action == "edit_program") {
        $id = $_POST['id'];
        $title = $conn->real_escape_string($_POST['title']);
        $target = $_POST['target'];
        $desc = $conn->real_escape_string($_POST['description']);
        
        // Update queries
        $update_img = "";
        if (!empty($image_path)) {
            $update_img = ", image_path='$image_path'";
        }
        
        $sql = "UPDATE programs SET title='$title', target_amount='$target', description='$desc' $update_img WHERE id='$id'";
        if ($conn->query($sql)) {
            header("Location: programs.php?msg=updated");
            exit();
        }
    }
    elseif ($action == "delete_program") {
        $id = $_POST['id'];
        
        // Fetch image path first
        $sql_get = "SELECT image_path FROM programs WHERE id='$id'";
        $res_get = $conn->query($sql_get);
        if ($res_get->num_rows > 0) {
            $row = $res_get->fetch_assoc();
            $img = $row['image_path'];
            
            // Delete file if it exists and is not a default asset
            // Adjust path logic since $img is like "./assets/images/..."
            $file_on_disk = "." . $img; // Context: dashboard is inside 'dashboard/', so we go up one level? 
            // Wait, in DB it is stored as "./assets/images/filename.jpg" (relative to root?)
            // No, look at add_program: $image_path = "./assets/images/" . $file_name;
            // From dashboard/programs.php, we need to go `../assets/images/`.
            // The stored path starts with `./`.
            
            // Let's ensure we map it correctly. 
            // Stored: ./assets/images/foo.jpg
            // Real path from dashboard: ../assets/images/foo.jpg
            $real_path = str_replace("./assets", "../assets", $img);
            
            if (file_exists($real_path) && !strpos($real_path, 'service-')) {
                unlink($real_path);
            }
        }

        $sql = "DELETE FROM programs WHERE id='$id'";
        if ($conn->query($sql)) {
            header("Location: programs.php?msg=deleted");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Program - Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Wildvine -->
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
            <h2><i class="fas fa-tasks text-info"></i> Kelola Program Donasi</h2>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i> Tambah Program Baru
            </button>
        </div>

        <div class="row">
            <?php
            $sql = "SELECT * FROM programs ORDER BY id ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()):
                    // Calculate Progress
                    $p_id = $row['id'];
                    $sql_sum = "SELECT SUM(nominal) as total FROM donations WHERE program_id='$p_id'";
                    $res_sum = $conn->query($sql_sum);
                    $total_collected = $res_sum->fetch_assoc()['total'] ?? 0;
                    $percent = ($row['target_amount'] > 0) ? ($total_collected / $row['target_amount']) * 100 : 0;
            ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <!-- Image Preview Header -->
                    <?php if(!empty($row['image_path'])): ?>
                    <div style="height: 150px; overflow: hidden; background: #f8f9fa;">
                         <img src=".<?php echo htmlspecialchars($row['image_path']); ?>" alt="Cover" class="w-100" style="height: 100%; object-fit: cover; opacity: 0.8;">
                    </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h4 class="card-title fw-bold"><?php echo htmlspecialchars($row['title']); ?></h4>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button class="dropdown-item edit-btn" 
                                            data-id="<?php echo $row['id']; ?>"
                                            data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                            data-target="<?php echo $row['target_amount']; ?>"
                                            data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                                            data-bs-toggle="modal" data-bs-target="#editModal">
                                            Edit Program
                                        </button>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="delete_program">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="button" class="dropdown-item text-danger delete-btn" data-title="<?php echo htmlspecialchars($row['title']); ?>">Hapus Program</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <p class="text-muted mt-2 small"><?php echo htmlspecialchars($row['description']); ?></p>
                        
                        <div class="mt-3">
                            <label class="small text-muted mb-1">Target Dana</label>
                            <h5 class="text-primary fw-bold">Rp <?php echo number_format($row['target_amount'], 0, ',', '.'); ?></h5>
                        </div>
                        
                        <div class="mt-3">
                            <label class="small text-muted mb-1">Terkumpul</label>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percent; ?>%"></div>
                            </div>
                            <small class="d-block mt-1 text-end text-success fw-bold">
                                Rp <?php echo number_format($total_collected, 0, ',', '.'); ?> (<?php echo number_format($percent, 1); ?>%)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Program Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_program">
                        <div class="mb-3">
                            <label>Nama Program</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Target Dana (Rp)</label>
                            <input type="number" name="target" class="form-control" required placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi Singkat</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Gambar Cover (Optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
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
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Program</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_program">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label>Nama Program</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Target Dana (Rp)</label>
                            <input type="number" name="target" id="edit_target" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi Singkat</label>
                            <textarea name="description" id="edit_desc" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Ganti Gambar Cover (Biarkan kosong jika tetap)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
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
    <script>
        $(document).ready(function() {
            // Edit Button Handler
            $('.edit-btn').click(function() {
                $('#edit_id').val($(this).data('id'));
                $('#edit_title').val($(this).data('title'));
                $('#edit_target').val($(this).data('target'));
                $('#edit_desc').val($(this).data('desc'));
            });

            // Delete Confirmation
            // Delete Confirmation with Text Verification
            $('.delete-btn').click(function() {
                const form = $(this).closest('form');
                const title = $(this).data('title');
                
                Swal.fire({
                    title: 'Hapus Program?',
                    html: `Ketik <strong>${title}</strong> untuk konfirmasi.<br><br><span class="text-danger">Semua data donatur terkait akan dihapus permanen!</span>`,
                    icon: 'warning',
                    input: 'text',
                    inputPlaceholder: title,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33',
                    preConfirm: (inputValue) => {
                        if (inputValue !== title) {
                            Swal.showValidationMessage('Nama program tidak sesuai!')
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });

            // Success Toast
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('msg')) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
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
