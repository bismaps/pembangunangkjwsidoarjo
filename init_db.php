<?php
include 'db_connect.php';

// Create tables if not exist
$table_schema = "
CREATE TABLE IF NOT EXISTS donatur_ac (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tanggalSetor DATE NOT NULL,
    namaSetor VARCHAR(255) NOT NULL,
    krwSetor VARCHAR(50),
    jumlahSatuan DECIMAL(15,2),
    nominal DECIMAL(15,2) NOT NULL
);
CREATE TABLE IF NOT EXISTS donatur_multimedia (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tanggalSetor DATE NOT NULL,
    namaSetor VARCHAR(255) NOT NULL,
    krwSetor VARCHAR(50),
    jumlahSatuan DECIMAL(15,2),
    nominal DECIMAL(15,2) NOT NULL
);
CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ocr_text TEXT,
    extracted_amount DECIMAL(15,2),
    extracted_date DATE,
    image_path VARCHAR(255),
    upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

if ($conn->multi_query($table_schema)) {
    echo "Tables created or already exist.\n";
    while ($conn->next_result()) {;}
} else {
    die("Error creating tables: " . $conn->error . "\n");
}

// Seed Admin User
// DEFAULT PASSWORD: admin123 (Please change immediately after login)
$default_password = "admin123"; 
$admin_pass = password_hash($default_password, PASSWORD_DEFAULT);

$sql_user = "INSERT IGNORE INTO users (username, password) VALUES ('admin', '$admin_pass')";
if ($conn->query($sql_user) === TRUE) {
    echo "Admin user seeded.\n";
} else {
    echo "Error seeding admin: " . $conn->error . "\n";
}

// Truncate tables (Reset Data)
$conn->query("TRUNCATE TABLE donatur_ac");
$conn->query("TRUNCATE TABLE donatur_multimedia");
$conn->query("TRUNCATE TABLE transactions");

// Data for AC (donatur table)
$donors_ac = [
    ["PHMJ", 17500000, "Sidoarjo"],
    ["NRN", 500000, "Sidoarjo"],
    ["AD", 1000000, "Sidoarjo"],
    ["DS", 1000000, "Sidoarjo"],
    ["M", 3000000, "Sidoarjo"],
    ["P", 1000000, "Sidoarjo"],
    ["AI", 500000, "Sidoarjo"],
    ["S", 5000000, "Tiberias"],
    ["HPL", 2000000, "Sidoarjo"],
    ["KUB", 205000, "Sidoarjo"],
    ["NKA", 20000000, "Sidoarjo"],
    ["JP", 1000000, "Sidoarjo"],
    ["VRW", 1000000, "Tiberias"],
    ["DV", 500000, "Bethlehem"],
    ["NN", 400000, "Bethlehem"],
    ["SBR", 500000, "Roma"],
    ["GG", 500000, "Korintus"],
    ["NN", 200000, "Sidoarjo"],
    ["DP", 500000, "Yerusalem"],
    ["AL", 3000000, "PT SM"],
    ["DD", 1000000, "Efrata"],
    ["KUB", 400000, "Sidoarjo"],
    ["YAM", 1000000, "Sidoarjo"],
    ["EAD", 500000, "Filipi"],
    ["EF", 10000000, "Filipi"],
    ["BRM", 1000000, "Ibrani"],
    ["DD", 1000000, "Efrata"],
    ["BPS", 250000, "Tiberias"]
];

// Data for Multimedia (donatur_multimedia table)
$donors_multimedia = [];


function insert_donors($conn, $table, $data) {
    if (empty($data)) return;
    $sql = "INSERT INTO $table (tanggalSetor, namaSetor, krwSetor, jumlahSatuan, nominal) VALUES ";
    $values = [];
    $date = date('Y-m-d');
    foreach ($data as $d) {
        $name = $conn->real_escape_string($d[0]);
        $nominal = $d[1];
        $krw = isset($d[2]) ? $conn->real_escape_string($d[2]) : 'Sidoarjo';
        $jumlah = $nominal / 1000;
        $values[] = "('$date', '$name', '$krw', '$jumlah', '$nominal')";
    }
    $sql .= implode(", ", $values);
    
    if ($conn->query($sql) === TRUE) {
        echo "Data inserted into $table successfully.\n";
    } else {
        echo "Error inserting into $table: " . $conn->error . "\n";
    }
}

insert_donors($conn, "donatur_ac", $donors_ac);
insert_donors($conn, "donatur_multimedia", $donors_multimedia);

$conn->close();
?>
