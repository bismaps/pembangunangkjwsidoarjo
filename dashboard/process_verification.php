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
    $tanggal = $conn->real_escape_string($_POST['tanggal']);
    $nominal = floatval($_POST['nominal']);
    $krw = $conn->real_escape_string($_POST['krw']);
    $target_category = $_POST['target_category']; // AC or Multimedia

    // Decide Target Table
    if ($target_category == 'AC') {
        $table = "donatur_ac";
    } else {
        $table = "donatur_multimedia";
    }

    // Get Image Path first to delete it
    $q_img = $conn->query("SELECT image_path FROM transactions WHERE id='$id'");
    $row_img = $q_img->fetch_assoc();
    $image_path = isset($row_img['image_path']) ? "../" . $row_img['image_path'] : "";

    // Insert into Target Table
    $sql_insert = "INSERT INTO $table (tanggalSetor, namaSetor, krwSetor, jumlahSatuan, nominal) 
                   VALUES ('$tanggal', '$nama', '$krw', 1, '$nominal')";

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
