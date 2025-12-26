<?php
include '../db_connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$action = $_POST['action'] ?? '';
$id = intval($_POST['id']);

    if ($action == 'approve') {
    $nama = $conn->real_escape_string($_POST['nama']);
    $alias = $conn->real_escape_string($_POST['alias']); // Capture Alias
    $tanggal = $conn->real_escape_string($_POST['tanggal']);
    $nominal = floatval($_POST['nominal']);
    $krw = $conn->real_escape_string($_POST['krw']);
    // $target_category is legacy string, we need program_id.
    // Ideally passed from form, or fetch from transaction record.
    
    // Fetch program_id from transaction to be safe
    $q_trans = $conn->query("SELECT program_id, image_path FROM transactions WHERE id='$id'");
    $row_trans = $q_trans->fetch_assoc();
    $program_id = $row_trans['program_id'];
    $image_path = isset($row_trans['image_path']) ? "../" . $row_trans['image_path'] : "";
    
    // Fallback if program_id is missing (Legacy data), try to map string
    if (empty($program_id)) {
        $cat = $_POST['target_category'];
        if ($cat == 'AC') $program_id = 1; // Default ID logic
        elseif ($cat == 'Multimedia') $program_id = 2;
        else {
             // Try to findByName
             $res_find = $conn->query("SELECT id FROM programs WHERE title='$cat'");
             if ($res_find->num_rows > 0) $program_id = $res_find->fetch_assoc()['id'];
        }
    }

    if (empty($program_id)) {
        die("Error: Could not determine Program ID for this transaction.");
    }

    // Insert into unified 'donations' table
    $sql_insert = "INSERT INTO donations (program_id, tanggalSetor, namaSetor, alias_name, krwSetor, jumlahSatuan, nominal) 
                   VALUES ('$program_id', '$tanggal', '$nama', '$alias', '$krw', 1, '$nominal')";

    if ($conn->query($sql_insert)) {
        // Delete Image File
        if (!empty($image_path) && file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete from transactions because it's verified
        $conn->query("DELETE FROM transactions WHERE id='$id'");
        header("Location: verification.php?msg=approved");
    } else {
        echo "Error: " . $conn->error;
    }

} elseif ($action == 'reject') {
    // Get Image Path first to delete it
    $q_img = $conn->query("SELECT image_path FROM transactions WHERE id='$id'");
    $row_img = $q_img->fetch_assoc();
    $image_path = isset($row_img['image_path']) ? "../" . $row_img['image_path'] : "";
    
    // Delete Image File
    if (!empty($image_path) && file_exists($image_path)) {
        unlink($image_path);
    }

    // Just delete
    $conn->query("DELETE FROM transactions WHERE id='$id'");
    header("Location: verification.php?msg=rejected");
} else {
    header("Location: verification.php");
}
?>
