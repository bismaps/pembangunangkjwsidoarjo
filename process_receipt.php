<?php
include 'db_connect.php';

    // Check if form submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['receipt_image'])) {
        
        $sender_name = $conn->real_escape_string($_POST['sender_name']);
        $program_id = isset($_POST['program_id']) ? intval($_POST['program_id']) : 0;
        $ocr_text = $_POST['ocr_text'] ?? ''; // Raw text from Tesseract.js
        
        // File Validation
        $file = $_FILES['receipt_image'];
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_ext, $allowed_extensions)) {
            echo "<script>alert('Format file tidak valid. Gunakan JPG atau PNG.'); window.location.href='index.php';</script>";
            exit();
        }
        
        if ($file['size'] > 5 * 1024 * 1024) { // 5MB Limit
            echo "<script>alert('Ukuran file terlalu besar (Max 5MB).'); window.location.href='index.php';</script>";
            exit();
        }

        // --- VALIDATION: KEYWORD CHECK (Strict Mode) ---
        $keywords = ['GKJW', 'SIDOARJO', 'GEREJA', 'JAWI WETAN', '5956666669'];
        $is_valid_receipt = false;
        
        // Check if at least one keyword exists
        foreach ($keywords as $kw) {
             if (stripos($ocr_text, $kw) !== false) {
                 $is_valid_receipt = true;
                 break;
             }
        }

        if (!$is_valid_receipt) {
            echo "<script>alert('Bukti transfer tidak valid atau tujuan salah. Pastikan bukti transfer ditujukan ke GKJW Sidoarjo.'); window.location.href='index.php';</script>";
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
        // Matches: 20/12/2024, 20-12-24, 1/12/2024, 20 Dec 2024, 20 Januari 2024
        // Regex explanation:
        // (\d{1,2})       : Day (1 or 2 digits)
        // [\/\-\s]        : Separator (slash, dash, space)
        // ([a-zA-Z]+|\d{1,2}) : Month (Text or Digits)
        // [\/\-\s]        : Separator
        // (\d{2,4})       : Year (2 or 4 digits)
        if (preg_match('/(\d{1,2})[\/\-\s]([a-zA-Z]+|\d{1,2})[\/\-\s](\d{2,4})/', $ocr_text, $date_match)) {
            $day = str_pad($date_match[1], 2, '0', STR_PAD_LEFT);
            $month_str = $date_match[2]; // Can be '12', 'Jan', 'Januari'
            $year = $date_match[3];
            
            // Handle 2 digit year
            if (strlen($year) == 2) {
                $year = "20" . $year;
            }
    
            // Map months (Text to Number)
            // Normalize case to Title Case or Lower for matching
            $month_str_lower = strtolower($month_str);
            
            $months = [
                // Short
                'jan' => '01', 'peb' => '02', 'feb' => '02', 'mar' => '03', 'apr' => '04', 'mei' => '05', 'jun' => '06',
                'jul' => '07', 'agu' => '08', 'agt' => '08', 'sep' => '09', 'okt' => '10', 'nop' => '11', 'nov' => '11', 'des' => '12',
                // Full Indonesian
                'januari' => '01', 'februari' => '02', 'maret' => '03', 'april' => '04', 
                'juni' => '06', 'juli' => '07', 'agustus' => '08', 'september' => '09', 
                'oktober' => '10', 'november' => '11', 'desember' => '12',
                // English Context (Optional)
                'february' => '02', 'march' => '03', 'may' => '05', 'june' => '06', 'july' => '07', 
                'august' => '08', 'october' => '10', 'december' => '12'
            ];
            
            if (isset($months[$month_str_lower])) {
                $month = $months[$month_str_lower];
            } elseif (is_numeric($month_str)) {
                 $month = str_pad($month_str, 2, '0', STR_PAD_LEFT);
            } else {
                 // Fallback: Default to current month or error? 
                 // Let's keep extracted_date null if month invalid
                 $month = null;
            }
            
            if ($month) {
                $extracted_date = "$year-$month-$day";
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
        // Determine Program Name for Legacy 'target_category' column if you want to keep it filled, or just rely on program_id
        // Let's just look it up to be safe if other parts use target_category
        $target_lbl = "General";
        if ($program_id > 0) {
             $res_p = $conn->query("SELECT title FROM programs WHERE id='$program_id'");
             if ($res_p->num_rows > 0) $target_lbl = $res_p->fetch_assoc()['title'];
        }

        $sql = "INSERT INTO transactions (sender_name, target_category, program_id, ocr_text, extracted_nominal, extracted_date, status, image_path) 
                VALUES ('$sender_name', '$target_lbl', '$program_id', '" . $conn->real_escape_string($ocr_text) . "', '$extracted_nominal', " . ($extracted_date ? "'$extracted_date'" : "NULL") . ", 'pending', '$image_path')";
    
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
