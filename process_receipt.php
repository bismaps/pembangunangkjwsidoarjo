<?php
include 'db_connect.php';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['receipt_image'])) {
    
    $sender_name = $conn->real_escape_string($_POST['sender_name']);
    $target_category = $conn->real_escape_string($_POST['target_category']);
    $ocr_text = $_POST['ocr_text'] ?? ''; // Raw text from Tesseract.js
    
    // File Validation
    $file = $_FILES['receipt_image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    
    if (!in_array($file['type'], $allowed_types)) {
        echo "<script>alert('Format file tidak valid. Gunakan JPG atau PNG.'); window.location.href='index.php';</script>";
        exit();
    }
    
    if ($file['size'] > 5 * 1024 * 1024) { // 5MB Limit
        echo "<script>alert('Ukuran file terlalu besar (Max 5MB).'); window.location.href='index.php';</script>";
        exit();
    }

    // --- PARSING LOGIC (Regex) ---
    $extracted_nominal = 0;
    $extracted_date = null;

    // 1. Extract Nominal (Better Logic)
    // Capture Rp followed by any combination of digits, dots, and commas
    if (preg_match_all('/Rp\s?\.?\s?([0-9.,]+)/i', $ocr_text, $matches)) {
        $max_val = 0;
        foreach ($matches[1] as $raw_val) {
            // Clean up the value
            // Heuristic: 
            // 1. If it has both . and , -> ensure format 100.000,00
            // 2. If it has only . -> check if parts are 3 digits (100.000) -> remove dot
            // 3. If it has only , -> check if parts are 3 digits (50,000) -> remove comma (treat as English OCR artifact)
            
            $clean_val = $raw_val;
            
            // Remove trailing punctuation often scanned by mistake matches "50.000."
            $clean_val = rtrim($clean_val, ".,");
            
            if (strpos($clean_val, ',') !== false && strpos($clean_val, '.') !== false) {
                // Mixed (e.g. 50.000,00) -> Remove dot, replace comma with dot
                $clean_val = str_replace('.', '', $clean_val);
                $clean_val = str_replace(',', '.', $clean_val);
            } elseif (strpos($clean_val, '.') !== false) {
                // Dots only (e.g. 50.000 or 50.00)
                // If the last group has 3 digits (50.000), assume thousands separator
                if (preg_match('/\.\d{3}$/', $clean_val)) {
                    $clean_val = str_replace('.', '', $clean_val);
                } else {
                    // 50.00 -> assume decimal (rare for RP but possible)
                    // Do nothing, PHP floatval handles 50.00
                }
            } elseif (strpos($clean_val, ',') !== false) {
                // Commas only (e.g. 50,000 or 50,00)
                // If the last group has 3 digits (50,000), assume thousands separator (English style OCR)
                if (preg_match('/,\d{3}$/', $clean_val)) {
                    $clean_val = str_replace(',', '', $clean_val);
                } else {
                    // 50,00 -> Standard IDR decimal
                    $clean_val = str_replace(',', '.', $clean_val);
                }
            }

            $val = floatval($clean_val);
            if ($val > $max_val) {
                $max_val = $val;
            }
        }
        $extracted_nominal = $max_val;
    }

    // 2. Extract Date
    // Matches: 20/12/2024, 20-12-24, 1/12/2024, 20 Dec 2024
    if (preg_match('/(\d{1,2})[\/\-\s](\d{1,2}|Jan|Feb|Mar|Apr|Mei|Jun|Jul|Agt|Sep|Okt|Nov|Des)[\/\-\s](\d{2,4})/', $ocr_text, $date_match)) {
        $day = str_pad($date_match[1], 2, '0', STR_PAD_LEFT);
        $month_str = $date_match[2];
        $year = $date_match[3];
        
        // Handle 2 digit year
        if (strlen($year) == 2) {
            $year = "20" . $year;
        }

        // Map months if text
        $months = [
            'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'Mei' => '05', 'Jun' => '06',
            'Jul' => '07', 'Agt' => '08', 'Sep' => '09', 'Okt' => '10', 'Nov' => '11', 'Des' => '12'
        ];
        if (isset($months[$month_str])) {
            $month = $months[$month_str];
        } else {
             $month = str_pad($month_str, 2, '0', STR_PAD_LEFT);
        }

        $extracted_date = "$year-$month-$day";
    }

    // --- FILE STORAGE ---
    $target_dir = "uploads/receipts/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Generate unique filename to prevent overwrite
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // File saved successfully
        $image_path = $target_file;
    } else {
        echo "<script>alert('Gagal menyimpan gambar.'); window.location.href='index.php';</script>";
        exit();
    }
    
    // Insert into DB
    $sql = "INSERT INTO transactions (sender_name, target_category, ocr_raw_text, extracted_nominal, extracted_date, status, image_path) 
            VALUES ('$sender_name', '$target_category', '" . $conn->real_escape_string($ocr_text) . "', '$extracted_nominal', " . ($extracted_date ? "'$extracted_date'" : "NULL") . ", 'pending', '$image_path')";

    if ($conn->query($sql)) {
        // Success
        header("Location: index.php?msg=upload_success");
        exit();
    } else {
        header("Location: index.php?msg=upload_failed");
        exit();
    }

} else {
    header("Location: index.php");
}
?>
