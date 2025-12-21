<?php
include 'db_connect.php';

// Create tables if not exist
$table_schema = "
CREATE TABLE IF NOT EXISTS donatur (
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
";

if ($conn->multi_query($table_schema)) {
    echo "Tables created or already exist.\n";
    // Clear results from multi_query
    while ($conn->next_result()) {;}
} else {
    die("Error creating tables: " . $conn->error . "\n");
}

// Truncate tables
$conn->query("TRUNCATE TABLE donatur");
$conn->query("TRUNCATE TABLE donatur_multimedia");

// Data for AC (donatur table)
$donors_ac = [
    ["PHMJ 1 Unit AC", 17500000],
    ["Pdt Noven Rudy N", 500000],
    ["P. Agus Dwi", 1000000],
    ["P. Didik Sasmita", 1000000],
    ["P. Manteus", 3000000],
    ["P. Prana", 1000000],
    ["P. Adi Indarto", 500000],
    ["P. Sunarto, tib", 5000000],
    ["P. HP Lucas", 2000000],
    ["KUB u/Pembangunan", 205000],
    ["P. Negari Kurnia A (bertahap)", 20000000],
    ["P. Joko Purnomo", 1000000],
    ["Mb. Vanessa Regita Wahyu,Tib", 1000000],
    ["DV, Beth", 500000],
    ["NN, Beth", 400000],
    ["P. Sigit Budi Raharjo, Roma", 500000],
    ["GG, kor", 500000],
    ["NN", 200000],
    ["DP, Yer", 500000],
    ["Ang Lee,PT Surya Mandiri", 3000000],
    ["Dr. Dewi, Efr", 1000000],
    ["KUB u/ Pembangunan", 400000],
    ["P. Yudied Agung M", 1000000],
    ["Mba Ervina Aini Damayanti, Fil", 500000],
    ["Mba Erika Filipi", 10000000],
    ["Kelg. Bagus Raganata/Martha ,Ibrani", 1000000],
    ["Dr Dewi,Efr", 1000000],
    ["Bisma Putra Sulung,Tib", 250000]
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
        $jumlah = $nominal / 1000;
        $values[] = "('$date', '$name', 'Sidoarjo', '$jumlah', '$nominal')";
    }
    $sql .= implode(", ", $values);
    
    if ($conn->query($sql) === TRUE) {
        echo "Data inserted into $table successfully.\n";
    } else {
        echo "Error inserting into $table: " . $conn->error . "\n";
    }
}

insert_donors($conn, "donatur", $donors_ac);
insert_donors($conn, "donatur_multimedia", $donors_multimedia);

$conn->close();
?>
