<?php
include 'db_connect.php';

// Define the SQL query to fetch data
// Define the SQL query to fetch data for AC
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


// Define the SQL query to fetch data for Multimedia
$sql_m = "SELECT SUM(nominal) AS totalDonasi FROM donatur_multimedia";
$result_m = $conn->query($sql_m);

$target_m = 1900000000; // Updated target
$currentDonationValue_m = 0;

if ($result_m->num_rows > 0) {
    $row_m = $result_m->fetch_assoc();
    $currentDonationValue_m = (float) $row_m['totalDonasi'];
}

$progressPercentage_m = ($currentDonationValue_m / $target_m) * 100;
$currentDonation_m = "Rp " . number_format($currentDonationValue_m, 0, ',', '.');
$targetFormatted_m = "Rp " . number_format($target_m, 0, ',', '.');
$toGoValue_m = $target_m - $currentDonationValue_m;
$toGo_m = "Rp " . number_format($toGoValue_m, 0, ',', '.');

// Fetch list of donors for AC
$sql_list_ac = "SELECT * FROM donatur ORDER BY tanggalSetor DESC";
$result_list_ac = $conn->query($sql_list_ac);

// Fetch list of donors for Multimedia
$sql_list_m = "SELECT * FROM donatur_multimedia ORDER BY tanggalSetor DESC";
$result_list_m = $conn->query($sql_list_m);
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
            <a href="#home" class="navbar-link" data-nav-link>
              <span>Beranda</span>

              <ion-icon name="chevron-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#about" class="navbar-link" data-nav-link>
              <span>Tentang Kami</span>

              <ion-icon name="chevron-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#donate" class="navbar-link" data-nav-link>
              <span>Donasi</span>

              <ion-icon name="chevron-forward-outline" aria-hidden="true"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#contact" class="navbar-link" data-nav-link>
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

      <!-- 
        - #HERO
      -->

      <section class="hero" id="home">
        <div class="container">

          <p class="section-subtitle">
            <img src="./assets/images/subtitle-img-white.png" width="32" height="7" alt="Wavy line">

            <span>Panitia Pembangunan</span>
          </p>

          <h2 class="h1 hero-title">
            Give Love for <strong>GKJW Sidoarjo</strong>
          </h2>

          <p class="hero-text">
            Mari dukung pembangunan gedung Greja Kristen Jawi Wetan (GKJW) Jemaat Sidoarjo
            agar dapat segera terbangun rumah ibadah yang layak, karena persembahan adalah wujud iman kita.
          </p>

          <button class="btn btn-primary">
            <span>Donation</span>

            <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
          </button>

        </div>
      </section>





      <!-- 
        - #FEATURES
      -->

      <section class="section features">
        <div class="container">

          <ul class="features-list">

            <li class="features-item">
              <div class="item-icon">
                <ion-icon name="home-outline"></ion-icon>
              </div>

              <div>
                <h3 class="h4 item-title">Ibadah Nyaman</h3>

                <p class="item-text">
                  Gedung yang sejuk dan luas untuk kenyamanan ibadah.
                </p>
              </div>
            </li>

            <li class="features-item">
              <div class="item-icon">
                <ion-icon name="happy-outline"></ion-icon>
              </div>

              <div>
                <h3 class="h4 item-title">Lingkungan Ramah</h3>

                <p class="item-text">
                  Lingkungan yang ramah untuk kegiatan gerejawi.
                </p>
              </div>
            </li>

            <li class="features-item">
              <div class="item-icon">
                <ion-icon name="grid-outline"></ion-icon>
              </div>

              <div>
                <h3 class="h4 item-title">Fasilitas Lengkap</h3>

                <p class="item-text">
                  Dilengkapi ruang serbaguna, parkir luas, dan aksesibilitas.
                </p>
              </div>
            </li>

            <li class="features-item">
              <div class="item-icon">
                <ion-icon name="map-outline"></ion-icon>
              </div>

              <div>
                <h3 class="h4 item-title">Lokasi Strategis</h3>

                <p class="item-text">
                  Mudah diakses oleh jemaat dari berbagai wilayah Sidoarjo.
                </p>
              </div>
            </li>

          </ul>

        </div>
      </section>





      <!-- 
        - #ABOUT
      -->

      <section class="section about" id="about">
        <div class="container">

          <div class="about-banner">

            <h2 class="deco-title">About Us</h2>

            <img src="./assets/images/deco-img.png" width="58" height="261" alt="" class="deco-img">

            <div class="banner-row">

              <div class="banner-col">
                <img src="./assets/images/about-banner-1.jpg" width="315" height="380" loading="lazy" alt="Tiger"
                  class="about-img w-100">

                <img src="./assets/images/about-banner-2.jpg" width="386" height="250" loading="lazy" alt="Panda"
                  class="about-img about-img-2 w-100">
              </div>

              <div class="banner-col">
                <img src="./assets/images/about-banner-3.jpg" width="250" height="277" loading="lazy" alt="Elephant"
                  class="about-img about-img-3 w-100">

                <img src="./assets/images/about-banner-4.jpg" width="315" height="380" loading="lazy" alt="Deer"
                  class="about-img w-100">
              </div>

            </div>

          </div>

          <div class="about-content">

            <p class="section-subtitle">
              <img src="./assets/images/subtitle-img-green.png" width="32" height="7" alt="Wavy line">

              <span>Tentang Kami</span>
            </p>

            <h2 class="h2 section-title">
              Greja Kristen Jawi Wetan <br> <strong>Jemaat Sidoarjo</strong>
            </h2>

            <ul class="tab-nav">

              <li>
                <button class="tab-btn active">Sejarah</button>
              </li>

              <li>
                <button class="tab-btn">Visi</button>
              </li>

              <li>
                <button class="tab-btn">Misi</button>
              </li>

            </ul>

            <div class="tab-content">

              <p class="section-text">
                Greja Kristen Jawi Wetan (GKJW) Sidoarjo secara resmi berdiri pada tanggal 10 Juli 1988. 
                Sebelumnya, jemaat ini merupakan bagian dari GKJW Mlathen dan GKJW Waru. 
                Sejarah kekristenan di Sidoarjo sendiri telah berakar sejak tahun 1950-an.
                Saat ini, GKJW Sidoarjo beralamat di Jl. Kombes Pol Moh. Duryat No. 66, Sidoarjo, 
                dan terus melayani jemaat serta masyarakat sekitar.
              </p>

              <ul class="tab-list">

                <li class="tab-item">
                  <div class="item-icon">
                    <ion-icon name="checkmark-circle"></ion-icon>
                  </div>

                  <p class="tab-text">Persekutuan yang Erat</p>
                </li>

                <li class="tab-item">
                  <div class="item-icon">
                    <ion-icon name="checkmark-circle"></ion-icon>
                  </div>

                  <p class="tab-text">Pelayanan Kasih</p>
                </li>

                <li class="tab-item">
                  <div class="item-icon">
                    <ion-icon name="checkmark-circle"></ion-icon>
                  </div>

                  <p class="tab-text">Kesaksian Injil</p>
                </li>

                <li class="tab-item">
                  <div class="item-icon">
                    <ion-icon name="checkmark-circle"></ion-icon>
                  </div>

                  <p class="tab-text">Pembangunan Jemaat</p>
                </li>

              </ul>

              <button class="btn btn-secondary" onclick="window.location.href='https://gkjwsidoarjo.org'">
                <span>Selengkapnya</span>

                <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
              </button>

            </div>

          </div>

        </div>
      </section>

















      <!-- 
        - #DONATE
      -->

      <section class="section donate" id="donate">
        <div class="container">

          <ul class="donate-list">

            <li>
              <div class="donate-card">

                <figure class="card-banner">
                  <img src="./assets/images/donate-1.jpg" width="520" height="325" loading="lazy" alt="Air Conditioner"
                    class="img-cover">
                </figure>

                <div class="card-content">

                  <div class="progress-wrapper">
                    <p class="progress-text">
                      <span>Terkumpul</span>

                      <data value="<?php echo $currentDonationValue; ?>"><?php echo $currentDonation; ?></data>
                    </p>

                    <data class="progress-value" value="<?php echo $progressPercentage; ?>"><?php echo number_format($progressPercentage, 1); ?>%</data>
                  </div>

                  <div class="progress-box">
                    <div class="progress" style="width: <?php echo $progressPercentage; ?>%"></div>
                  </div>

                  <h3 class="h3 card-title">Pengadaan Air Conditioner</h3>

                  <div class="card-wrapper">

                    <p class="card-wrapper-text">
                      <span>Target</span>

                      <data class="green" value="<?php echo $target; ?>"><?php echo $targetFormatted; ?></data>
                    </p>
                    
                    <p class="card-wrapper-text">
                      <span>Kurang</span>

                      <data class="cyan" value="<?php echo $toGoValue; ?>"><?php echo $toGo; ?></data>
                    </p>

                  </div>

                  <button class="btn btn-secondary" onclick="toggleDonors('ac')">
                    <span>Cek Daftar Donatur</span>

                    <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                  </button>

                </div>

              </div>
            </li>

            <li>
              <div class="donate-card">

                <figure class="card-banner">
                  <img src="./assets/images/donate-2.jpg" width="520" height="325" loading="lazy" alt="Multimedia"
                    class="img-cover">
                </figure>

                <div class="card-content">

                  <div class="progress-wrapper">
                    <p class="progress-text">
                      <span>Terkumpul</span>

                      <data value="<?php echo $currentDonationValue_m; ?>"><?php echo $currentDonation_m; ?></data>
                    </p>

                    <data class="progress-value" value="<?php echo $progressPercentage_m; ?>"><?php echo number_format($progressPercentage_m, 1); ?>%</data>
                  </div>

                  <div class="progress-box">
                    <div class="progress" style="width: <?php echo $progressPercentage_m; ?>%"></div>
                  </div>

                  <h3 class="h3 card-title">Pengadaan Multimedia</h3>

                  <div class="card-wrapper">

                    <p class="card-wrapper-text">
                      <span>Target</span>

                      <data class="green" value="<?php echo $target_m; ?>"><?php echo $targetFormatted_m; ?></data>
                    </p>

                    <p class="card-wrapper-text">
                      <span>Kurang</span>

                      <data class="cyan" value="<?php echo $toGoValue_m; ?>"><?php echo $toGo_m; ?></data>
                    </p>

                  </div>

                  <button class="btn btn-secondary" onclick="toggleDonors('multimedia')">
                    <span>Cek Daftar Donatur</span>

                    <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                  </button>

                </div>

              </div>
            </li>

          </ul>

        </div>
      </section>

      <!-- Hidden Donors List Section -->
      <section id="donors-list-section" style="display: none; padding-bottom: 50px;">
        <div class="container">
            
            <!-- AC Donors Table -->
            <div id="donors-ac" style="display: none;">
                <h2 class="h2 section-title" style="margin-bottom: 30px; text-align: center;">Donatur Pengadaan AC</h2>
                
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
                            if ($result_list_ac && $result_list_ac->num_rows > 0) {
                                while ($row = $result_list_ac->fetch_assoc()) {
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
            </div>

            <!-- Multimedia Donors Table -->
            <div id="donors-multimedia" style="display: none;">
                <h2 class="h2 section-title" style="margin-bottom: 30px; text-align: center;">Donatur Pengadaan Multimedia</h2>
                
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
                            if ($result_list_m && $result_list_m->num_rows > 0) {
                                while ($row = $result_list_m->fetch_assoc()) {
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
            </div>

        </div>
      </section>

      <script>
      function toggleDonors(type) {
          const section = document.getElementById('donors-list-section');
          const acDiv = document.getElementById('donors-ac');
          const mDiv = document.getElementById('donors-multimedia');

          if (type === 'ac') {
              if (acDiv.style.display === 'block' && section.style.display === 'block') {
                   // Already open, toggle close
                   section.style.display = 'none';
                   acDiv.style.display = 'none';
              } else {
                   // Open AC
                   section.style.display = 'block';
                   acDiv.style.display = 'block';
                   mDiv.style.display = 'none';
                   section.scrollIntoView({behavior: "smooth"});
              }
          } else if (type === 'multimedia') {
              if (mDiv.style.display === 'block' && section.style.display === 'block') {
                   // Already open, toggle close
                   section.style.display = 'none';
                   mDiv.style.display = 'none';
              } else {
                   // Open Multimedia
                   section.style.display = 'block';
                   mDiv.style.display = 'block';
                   acDiv.style.display = 'none';
                   section.scrollIntoView({behavior: "smooth"});
              }
          }
      }
      </script>























      <!-- 
        - #PAYMENT CHANNELS
      -->

      <section class="section" style="padding-bottom: 80px;">
        <div class="container" style="text-align: center;">

          <p class="section-subtitle" style="justify-content: center;">
            <img src="./assets/images/subtitle-img-green.png" width="32" height="7" alt="Wavy line">
            <span>Saluran Donasi</span>
          </p>

          <h2 class="h2 section-title" style="margin-bottom: 30px;">
            Cara <strong>Donasi</strong>
          </h2>

          <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; margin-top: 40px; align-items: stretch;">
            
            <!-- QRIS Card -->
            <div class="service-card" style="padding: 30px; width: 350px; display: flex; flex-direction: column; align-items: center;">
              <h3 class="h3 card-title" style="margin-bottom: 20px;">Scan QRIS</h3>
              <div style="flex-grow: 1; display: flex; align-items: center; justify-content: center; width: 100%;">
                <img src="./assets/images/QRIS.svg" alt="QRIS GKJW Sidoarjo" style="width: 100%; max-width: 250px;">
              </div>
              <p class="card-text" style="margin-top: 20px; font-size: 0.95rem; line-height: 1.5; min-height: 3rem; display: flex; align-items: center;">
                Scan menggunakan aplikasi e-wallet atau mobile banking Anda.
              </p>
            </div>

            <!-- Bank Transfer Card -->
            <div class="service-card" style="padding: 30px; width: 350px; display: flex; flex-direction: column; align-items: center;">
              <h3 class="h3 card-title" style="margin-bottom: 20px;">Transfer Bank BNI</h3>
              
              <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%;">
                <p class="card-text" style="margin-bottom: 5px;">
                  No. Rekening:
                </p>
                <h3 class="h3 card-title" style="color: var(--pistachio); font-size: 2.2rem; margin-bottom: 5px;">
                  5956666669
                </h3>
                <p class="card-text">
                  An. GKJW Jemaat Sidoarjo
                </p>
              </div>

              <p class="card-text" style="margin-top: 20px; font-size: 0.95rem; line-height: 1.5; min-height: 3rem; display: flex; align-items: center;">
                Konfirmasi bukti transfer melalui WhatsApp ke kontak di bawah.
              </p>
            </div>

          </div>

        </div>
      </section>

      <!-- 
        - #CONTACT
      -->

      <section class="section category" id="contact" style="background-color: #f9f9f9; padding-bottom: 80px;">
        <div class="container" style="text-align: center;">

          <p class="section-subtitle" style="justify-content: center;">
            <img src="./assets/images/subtitle-img-green.png" width="32" height="7" alt="Wavy line">
            <span>Butuh Bantuan?</span>
          </p>

          <h2 class="h2 section-title" style="margin-bottom: 50px;">
            Hubungi <strong>Kami</strong>
          </h2>

          <ul class="service-list" style="justify-content: center;">

            <li>
              <div class="service-card">
                <div class="card-icon">
                  <ion-icon name="logo-whatsapp"></ion-icon>
                </div>

                <h3 class="h3 card-title">Agus Dwi</h3>

                <p class="card-text">
                  Panitia Pembangunan
                </p>

                <a href="https://wa.me/628115807088" class="btn btn-primary" style="margin-top: 20px; width: 100%; justify-content: center;">
                  <span>Chat via WhatsApp</span>
                </a>
              </div>
            </li>

            <li>
              <div class="service-card">
                <div class="card-icon">
                  <ion-icon name="logo-whatsapp"></ion-icon>
                </div>

                <h3 class="h3 card-title">Prana</h3>

                <p class="card-text">
                  Panitia Pembangunan
                </p>

                <a href="https://wa.me/628113456746" class="btn btn-primary" style="margin-top: 20px; width: 100%; justify-content: center;">
                  <span>Chat via WhatsApp</span>
                </a>
              </div>
            </li>

            <li>
              <div class="service-card">
                <div class="card-icon">
                  <ion-icon name="logo-whatsapp"></ion-icon>
                </div>

                <h3 class="h3 card-title">Sih Pireno</h3>

                <p class="card-text">
                  Panitia Pembangunan
                </p>

                <a href="https://wa.me/6281230522844" class="btn btn-primary" style="margin-top: 20px; width: 100%; justify-content: center;">
                  <span>Chat via WhatsApp</span>
                </a>
              </div>
            </li>

          </ul>

        </div>
      </section>

    </article>
  </main>





  <!-- 
    - #FOOTER
  -->

  <footer class="footer">
    <div class="container">

      <div class="footer-contact" style="margin-bottom: 20px; text-align: center; color: var(--white-50);">
        <p style="margin-bottom: 10px;">
          Jl. Kombes Pol. Moh. Duryat No.66, Gabahan, Sidoklumpuk,<br>
          Kec. Sidoarjo, Kabupaten Sidoarjo, Jawa Timur 61218
        </p>
        <p>
          <ion-icon name="call-outline" style="vertical-align: middle; margin-right: 5px;"></ion-icon> 
          (031) 8941605
        </p>
      </div>

      <p class="copyright">
        Copyright 2025 <a href="#" class="copyright-link">GKJW Sidoarjo</a>. All Rights Reserved.
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