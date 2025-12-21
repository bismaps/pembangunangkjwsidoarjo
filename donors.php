<?php
include 'db_connect.php';

// Define the SQL query to fetch data
$sql = "SELECT SUM(nominal) AS totalDonasi FROM donatur";
$result = $conn->query($sql);

$target = 307991000;
$currentDonationValue = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentDonationValue = (float) $row['totalDonasi'];
}

$progressPercentage = ($currentDonationValue / $target) * 100;
$currentDonation = "Rp " . number_format($currentDonationValue, 0, ',', '.');
$targetFormatted = "Rp " . number_format($target, 0, ',', '.');
$toGoValue = $target - $currentDonationValue;
$toGo = "Rp " . number_format($toGoValue, 0, ',', '.');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GKJW Sidoarjo - Pembangunan Greja</title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="./assets/images/GKJW.svg" type="image/svg+xml">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="./assets/css/style.css">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Roboto:wght@300;400;500;700&family=Oswald:wght@600&display=swap"
    rel="stylesheet">
</head>

<body>

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
    <div class="container">

      <h1>
        <a href="#" class="logo">GKJW Sidoarjo</a>
      </h1>



      <button class="nav-open-btn" aria-label="Open Menu" data-nav-open-btn>
        <ion-icon name="menu-outline"></ion-icon>
      </button>

      <nav class="navbar" data-navbar>

        <button class="nav-close-btn" aria-label="Close Menu" data-nav-close-btn>
          <ion-icon name="close-outline"></ion-icon>
        </button>

        <a href="#" class="logo">GKJW Sidoarjo</a>

        <ul class="navbar-list">

          <li>
            <a href="index.php" class="navbar-link" data-nav-link>
              <span>Beranda</span>

              <ion-icon name="chevron-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </li>

          <li>
            <a href="index.php#about" class="navbar-link" data-nav-link>
              <span>Tentang Kami</span>

              <ion-icon name="chevron-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </li>

          <li>
            <a href="index.php#donate" class="navbar-link" data-nav-link>
              <span>Donasi</span>

              <ion-icon name="chevron-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </li>

          <li>
            <a href="index.php#contact" class="navbar-link" data-nav-link>
              <span>Kontak</span>

              <ion-icon name="chevron-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </li>

        </ul>

      </nav>

      <div class="header-action">



        <a href="#donate" class="btn btn-primary">
          <span>Donasi</span>

          <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
        </a>

      </div>

    </div>
  </header>





  <main>
    <article>
      <section class="section" style="padding-top: 120px;">
        <div class="container">
            <h2 class="h2 section-title" style="margin-bottom: 30px; text-align: center;">List Donatur Tahap VI.2</h2>
            
            <div class="table-responsive-sm" style="overflow-x: auto;">
                <table class="table" style="width: 100%; border-collapse: collapse; margin-bottom: 1rem; color: #555;">
                    <thead>
                        <tr style="background-color: #f8f9fa;">
                            <th style="padding: 15px; border-bottom: 2px solid #dee2e6; text-align: left; font-weight: 600;">Tanggal</th>
                            <th style="padding: 15px; border-bottom: 2px solid #dee2e6; text-align: left; font-weight: 600;">Nama</th>
                            <th style="padding: 15px; border-bottom: 2px solid #dee2e6; text-align: left; font-weight: 600;">Asal</th>
                            <th style="padding: 15px; border-bottom: 2px solid #dee2e6; text-align: center; font-weight: 600;">Jumlah</th>
                            <th style="padding: 15px; border-bottom: 2px solid #dee2e6; text-align: right; font-weight: 600;">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM donatur ORDER BY tanggalSetor DESC";
                        $result = $conn->query($sql);

                        if (!$result) {
                            die("<tr><td colspan='5'>Invalid query: " . $conn->error . "</td></tr>");
                        }

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr style='border-bottom: 1px solid #dee2e6;'>
                                    <td style='padding: 12px;'>" . htmlspecialchars($row["tanggalSetor"]) . "</td>
                                    <td style='padding: 12px;'>" . htmlspecialchars($row["namaSetor"]) . "</td>
                                    <td style='padding: 12px;'>" . htmlspecialchars($row["krwSetor"]) . "</td>
                                    <td style='padding: 12px; text-align: center;'>" . htmlspecialchars($row["jumlahSatuan"]) . "</td>
                                    <td style='padding: 12px; text-align: right; font-weight: 500;'>Rp " . number_format($row["nominal"], 0, ',', '.') . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align: center; padding: 20px;'>Belum ada data donatur</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 30px; text-align: center;">
                <a href="index.php" class="btn btn-primary">
                  <span>Kembali ke Beranda</span>
                  <ion-icon name="arrow-back-outline" aria-hidden="true"></ion-icon>
                </a>
            </div>
        </div>
      </section>
    </article>
  </main>





  <!-- 
    - #FOOTER
  -->

  <footer class="footer">
    <div class="container">

      <ul class="footer-list">

        <li>
          <a href="#" class="footer-link">Terms of use</a>
        </li>

        <li>
          <a href="#" class="footer-link">Privacy & Policy</a>
        </li>

      </ul>

      <p class="copyright">
        Copyright 2022 <a href="#" class="copyright-link">codewithsadee</a>. All Rights Reserved.
      </p>

    </div>
  </footer>





  <!-- 
    - custom js link
  -->
  <script src="./assets/js/script.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>