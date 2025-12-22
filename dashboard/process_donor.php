<?php
include '../db_connect.php';

// Enable Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add_donation") {
        $program_id = $_POST['program_id'];
        $tanggal = $_POST['tanggal'];
        $nama = $conn->real_escape_string($_POST['nama']);
        $krw = $conn->real_escape_string($_POST['krw']);
        $nominal = str_replace('.', '', $_POST['nominal']); // Clean number
        $jumlah = $nominal; // Simplified, or calculate /1000 if needed

        $sql = "INSERT INTO donations (program_id, tanggalSetor, namaSetor, krwSetor, jumlahSatuan, nominal) 
                VALUES ('$program_id', '$tanggal', '$nama', '$krw', '$jumlah', '$nominal')";

        if ($conn->query($sql) === TRUE) {
            header("Location: donations.php?msg=added");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } 
    
    elseif ($action == "edit_donation") {
        $id = $_POST['id'];
        $program_id = $_POST['program_id'];
        $tanggal = $_POST['tanggal'];
        $nama = $conn->real_escape_string($_POST['nama']);
        $krw = $conn->real_escape_string($_POST['krw']);
        $nominal = str_replace('.', '', $_POST['nominal']);

        $sql = "UPDATE donations SET 
                program_id='$program_id', 
                tanggalSetor='$tanggal', 
                namaSetor='$nama', 
                krwSetor='$krw', 
                nominal='$nominal' 
                WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            header("Location: donations.php?msg=updated");
        } else {
            echo "Error: " . $conn->error;
        }
    } 
    
    elseif ($action == "delete_donation") {
        $id = $_POST['id'];
        $sql = "DELETE FROM donations WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            header("Location: donations.php?msg=deleted");
            exit(); // Ensure exit
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}
$conn->close();
?>
