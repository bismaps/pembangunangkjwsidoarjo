<?php
// Enable Error Reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include '../db_connect.php';

$action = $_REQUEST['action'] ?? '';
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

// Escape inputs
$nama = isset($_POST['nama']) ? $conn->real_escape_string($_POST['nama']) : '';
$tanggal = isset($_POST['tanggal']) ? $conn->real_escape_string($_POST['tanggal']) : date('Y-m-d');
$krw = isset($_POST['krw']) ? $conn->real_escape_string($_POST['krw']) : '';
$nominal = isset($_POST['nominal']) ? (float)$_POST['nominal'] : 0;
// Jumlah Satuan default logic
$jumlahSatuan = 1; 

// Debugging check (optional, remove in production)
if (!$action) {
    die("Error: No Action Specified. Please contact admin.");
}

// --- AC ACTIONS ---
if ($action == 'add_ac') {
    $sql = "INSERT INTO donatur_ac (tanggalSetor, namaSetor, krwSetor, jumlahSatuan, nominal) 
            VALUES ('$tanggal', '$nama', '$krw', 1, '$nominal')";
    if ($conn->query($sql)) {
        header("Location: ac.php?msg=added");
        exit();
    } else {
        echo "<h3>Error Adding AC Donor</h3>";
        echo "Message: " . $conn->error . "<br>";
        echo "SQL: " . $sql;
        exit();
    }
} 
elseif ($action == 'edit_ac') {
    if ($id <= 0) die("Error: Invalid ID for Update.");
    
    $sql = "UPDATE donatur_ac SET 
            tanggalSetor='$tanggal', namaSetor='$nama', krwSetor='$krw', nominal='$nominal' 
            WHERE id='$id'";
    if ($conn->query($sql)) {
        header("Location: ac.php?msg=updated");
        exit();
    } else {
        echo "<h3>Error Updating AC Donor</h3>";
        echo "Message: " . $conn->error . "<br>";
        echo "SQL: " . $sql;
        exit();
    }
} 
elseif ($action == 'delete_ac') {
    if ($id <= 0) die("Error: Invalid ID for Delete.");

    $sql = "DELETE FROM donatur_ac WHERE id='$id'";
    if ($conn->query($sql)) {
        header("Location: ac.php?msg=deleted");
        exit();
    } else {
        echo "<h3>Error Deleting AC Donor</h3>";
        echo "Message: " . $conn->error . "<br>";
        exit();
    }
}

// --- MULTIMEDIA ACTIONS ---
elseif ($action == 'add_multimedia') {
    $sql = "INSERT INTO donatur_multimedia (tanggalSetor, namaSetor, krwSetor, jumlahSatuan, nominal) 
            VALUES ('$tanggal', '$nama', '$krw', 1, '$nominal')";
    if ($conn->query($sql)) {
        header("Location: multimedia.php?msg=added");
        exit();
    } else {
        echo "<h3>Error Adding Multimedia Donor</h3>";
        echo "Message: " . $conn->error . "<br>";
        echo "SQL: " . $sql;
        exit();
    }
} 
elseif ($action == 'edit_multimedia') {
    if ($id <= 0) die("Error: Invalid ID for Update.");

    $sql = "UPDATE donatur_multimedia SET 
            tanggalSetor='$tanggal', namaSetor='$nama', krwSetor='$krw', nominal='$nominal' 
            WHERE id='$id'";
    if ($conn->query($sql)) {
        header("Location: multimedia.php?msg=updated");
        exit();
    } else {
        echo "<h3>Error Updating Multimedia Donor</h3>";
        echo "Message: " . $conn->error . "<br>";
        echo "SQL: " . $sql;
        exit();
    }
} 
elseif ($action == 'delete_multimedia') {
    if ($id <= 0) die("Error: Invalid ID for Delete.");

    $sql = "DELETE FROM donatur_multimedia WHERE id='$id'";
    if ($conn->query($sql)) {
        header("Location: multimedia.php?msg=deleted");
        exit();
    } else {
        echo "<h3>Error Deleting Multimedia Donor</h3>";
        echo "Message: " . $conn->error . "<br>";
        exit();
    }
}
else {
    header("Location: index.php");
    exit();
}
?>
