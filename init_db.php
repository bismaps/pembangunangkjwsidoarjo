<?php
include 'db_connect.php';

// Drop all tables for a full reset (As requested)
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("DROP TABLE IF EXISTS donatur_ac");
$conn->query("DROP TABLE IF EXISTS donatur_multimedia");
$conn->query("DROP TABLE IF EXISTS donations");
$conn->query("DROP TABLE IF EXISTS transactions");
$conn->query("DROP TABLE IF EXISTS programs");
$conn->query("DROP TABLE IF EXISTS users");
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

// Create Tables
$table_schema = "
CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    target_amount DECIMAL(15,2) DEFAULT 0,
    description TEXT,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    tanggalSetor DATE NOT NULL,
    namaSetor VARCHAR(255) NOT NULL,
    alias_name VARCHAR(255) DEFAULT 'Hamba Allah',
    krwSetor VARCHAR(50),
    jumlahSatuan DECIMAL(15,2), -- (Optional: for internal calculation)
    nominal DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_name VARCHAR(255),
    alias_name VARCHAR(255),
    target_category VARCHAR(255),
    program_id INT DEFAULT NULL,
    ocr_text TEXT,
    extracted_nominal DECIMAL(15,2) DEFAULT 0,
    extracted_date DATE,
    image_path VARCHAR(255),
    upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'
);
";

if ($conn->multi_query($table_schema)) {
    echo "Tables created successfully.\n";
    while ($conn->next_result()) {;} // Flush multi_query
} else {
    die("Error creating tables: " . $conn->error . "\n");
}

// 1. Seed Admin
$default_password = "admin123"; 
$admin_pass = password_hash($default_password, PASSWORD_DEFAULT);
$sql_user = "INSERT IGNORE INTO users (username, password) VALUES ('admin', '$admin_pass')";
$conn->query($sql_user);


// 2. Seed Programs
// We use INSERT IGNORE or Check existence to avoid duplicates on re-run
$programs = [
    [
        'id' => 1, 
        'title' => 'Pengadaan Air Conditioner', 
        'target' => 307991000, 
        'desc' => 'Dukungan untuk kenyamanan ibadah melalui pengadaan AC baru.',
        'image' => 'assets/images/hero.jpg' // Default placeholder
    ],
    [
        'id' => 2, 
        'title' => 'Pengadaan Multimedia', 
        'target' => 1900000000, 
        'desc' => 'Peningkatan kualitas audio visual dan alat musik gereja.',
        'image' => 'assets/images/hero.jpg'
    ]
];

foreach ($programs as $p) {
    $title = $conn->real_escape_string($p['title']);
    $desc = $conn->real_escape_string($p['desc']);
    $sql_prog = "INSERT INTO programs (id, title, target_amount, description, image_path) 
                 VALUES ({$p['id']}, '$title', {$p['target']}, '$desc', '{$p['image']}')
                 ON DUPLICATE KEY UPDATE title='$title', target_amount={$p['target']}";
    $conn->query($sql_prog);
}


// 3. Seed Donors (Migrated Data)
// We truncate `donations` to ensure clean seed state, or check if empty. 
// For dev/init, TRUNCATE is safer to avoid duplication.
$conn->query("TRUNCATE TABLE donations");

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

$donors_multimedia = []; // Empty for now

function insert_donations($conn, $program_id, $data) {
    if (empty($data)) return;
    
    $sql = "INSERT INTO donations (program_id, tanggalSetor, namaSetor, alias_name, krwSetor, jumlahSatuan, nominal) VALUES ";
    $values = [];
    $date = date('Y-m-d');
    
    foreach ($data as $d) {
        $name = $conn->real_escape_string($d[0]);
        $alias = substr($name, 0, 1) . '...'; // Auto-generate alias from first letter
        $nominal = $d[1];
        $krw = isset($d[2]) ? $conn->real_escape_string($d[2]) : 'Sidoarjo';
        $jumlah = $nominal / 1000; // Legacy logic
        
        $values[] = "($program_id, '$date', '$name', '$alias', '$krw', '$jumlah', '$nominal')";
    }
    
    if (!empty($values)) {
        $sql .= implode(", ", $values);
        if ($conn->query($sql) === TRUE) {
            echo "Donations for Program ID $program_id seeded.\n";
        } else {
            echo "Error seeding donations: " . $conn->error . "\n";
        }
    }
}

insert_donations($conn, 1, $donors_ac); // 1 = AC
insert_donations($conn, 2, $donors_multimedia); // 2 = Multimedia

$conn->close();
?>
