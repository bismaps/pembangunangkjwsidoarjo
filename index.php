<?php
include 'db_connect.php';

include 'target_config.php';

// Donatur AC
$sql_ac = "SELECT SUM(nominal) AS total_donasi FROM donatur_ac";
$result_ac = $conn->query($sql_ac);
$total_donasi_ac = $result_ac->fetch_assoc()['total_donasi'] ?? 0;
// Target AC Query
$target_ac = $target_ac_amount; 
$kurang_ac = $target_ac - $total_donasi_ac;
$persen_ac = ($target_ac > 0) ? ($total_donasi_ac / $target_ac) * 100 : 0;

// Donatur Multimedia
$sql_multimedia = "SELECT SUM(nominal) AS total_donasi FROM donatur_multimedia";
$result_multimedia = $conn->query($sql_multimedia);
$total_donasi_multimedia = $result_multimedia->fetch_assoc()['total_donasi'] ?? 0;
// Target Multimedia Query
$target_multimedia = $target_multimedia_amount;
$kurang_multimedia = $target_multimedia - $total_donasi_multimedia;
$persen_multimedia = ($target_multimedia > 0) ? ($total_donasi_multimedia / $target_multimedia) * 100 : 0;

// Fetch ALL donors 
$donors_ac_list = [];
$sql_list_ac = "SELECT * FROM donatur_ac ORDER BY id ASC";
$res_list_ac = $conn->query($sql_list_ac);
if ($res_list_ac->num_rows > 0) {
    while($row = $res_list_ac->fetch_assoc()) {
        $donors_ac_list[] = $row;
    }
}
// donatur_multimedia list...
$donors_multi_list = [];
$sql_list_m = "SELECT * FROM donatur_multimedia ORDER BY id ASC";
$res_list_m = $conn->query($sql_list_m);
if ($res_list_m->num_rows > 0) {
    while($row = $res_list_m->fetch_assoc()) {
        $donors_multi_list[] = $row;
    }
}
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



        <a href="./dashboard/login.php" class="btn btn-primary">
          <span>Login</span>

          <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
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

          <button class="btn btn-primary" onclick="window.location.href='#donate'">
            <span>Donasi</span>

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

      <!-- OCR Upload Modal -->
      <div id="uploadModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
          <div class="modal-content" style="background: var(--white); padding: 40px; border-radius: 16px; width: 100%; max-width: 480px; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <button onclick="closeUploadModal()" style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 32px; color: var(--granite-gray); cursor: pointer; line-height: 1; transition: 0.2s;">&times;</button>
            
            <h3 class="h3" style="margin-bottom: 10px; color: var(--eerie-black-2); text-align: center;">Upload Bukti</h3>
            <p style="text-align: center; color: var(--granite-gray); margin-bottom: 30px; font-size: 1.4rem;">Verifikasi donasi Anda secara otomatis</p>
            
            <form id="uploadForm" action="process_receipt.php" method="POST" enctype="multipart/form-data">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: var(--eerie-black-2); font-weight: 500;">Nama Pengirim</label>
                    <input type="text" name="sender_name" required style="width: 100%; padding: 12px 15px; border: 1px solid var(--light-gray); border-radius: 8px; font-family: var(--ff-inter); font-size: 1.5rem; color: var(--eerie-black-2); outline: none; transition: 0.2s;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: var(--eerie-black-2); font-weight: 500;">Tujuan Donasi</label>
                    <select name="target_category" required style="width: 100%; padding: 12px 15px; border: 1px solid var(--light-gray); border-radius: 8px; font-family: var(--ff-inter); font-size: 1.5rem; color: var(--eerie-black-2); outline: none; background-color: var(--white);">
                        <option value="AC">Pengadaan AC</option>
                        <option value="Multimedia">Pengadaan Multimedia</option>
                    </select>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; color: var(--eerie-black-2); font-weight: 500;">Foto Bukti Transfer</label>
                    <div style="position: relative; overflow: hidden; display: inline-block; width: 100%;">
                        <input type="file" id="receiptFile" name="receipt_image" accept="image/*" required style="width: 100%; padding: 12px; border: 2px dashed var(--light-gray); border-radius: 8px; cursor: pointer; background: var(--baby-powder);">
                    </div>
                </div>

                <!-- Hidden Input for OCR Text -->
                <input type="hidden" name="ocr_text" id="ocrText">

                <div id="progressContainer" style="display: none; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span id="progressStatus" style="font-size: 1.3rem; color: var(--pistachio); font-weight: 500;">Scanning receipt...</span>
                        <span id="progressPercent" style="font-size: 1.3rem; color: var(--granite-gray);">0%</span>
                    </div>
                    <div style="width: 100%; background: var(--light-gray); height: 6px; border-radius: 3px; overflow: hidden;">
                        <div id="progressBar" style="width: 0%; height: 100%; background: var(--pistachio); border-radius: 3px; transition: width 0.2s;"></div>
                    </div>
                </div>

                <button type="button" onclick="processAndSubmit()" id="btnSubmit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 15px; font-size: 1.6rem;">
                    <span>Kirim & Verifikasi</span>
                    <ion-icon name="arrow-forward-outline"></ion-icon>
                </button>
            </form>
          </div>
      </div>

      <!-- Tesseract.js CDN -->
      <script src='https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js'></script>
      <script>
        function openUploadModal() {
            document.getElementById('uploadModal').style.display = 'flex';
        }
        function closeUploadModal() {
            document.getElementById('uploadModal').style.display = 'none';
        }

        async function processAndSubmit() {
            const fileInput = document.getElementById('receiptFile');
            const file = fileInput.files[0];
            
            if (!file) {
                alert('Silakan pilih foto bukti transfer terlebih dahulu.');
                return;
            }

            // Show UI Loading
            document.getElementById('progressContainer').style.display = 'block';
            document.getElementById('btnSubmit').disabled = true;
            document.getElementById('btnSubmit').innerText = 'Sedang Memproses...';
            const progressBar = document.getElementById('progressBar');
            const progressStatus = document.getElementById('progressStatus');

            try {
                // Run Tesseract
                progressStatus.innerText = "Membaca teks dari gambar...";
                const result = await Tesseract.recognize(
                    file,
                    'ind', // Indonesian Language
                    {
                        logger: m => {
                            if (m.status === 'recognizing text') {
                                progressBar.style.width = (m.progress * 100) + '%';
                            }
                        }
                    }
                );

                // Success
                console.log("OCR Result:", result.data.text);
                document.getElementById('ocrText').value = result.data.text;
                
                progressStatus.innerText = "Selesai! Mengirim data...";
                setTimeout(() => {
                    document.getElementById('uploadForm').submit();
                }, 500);

            } catch (error) {
                console.error(error);
                alert('Gagal membaca gambar. Silakan coba lagi atau upload manual.');
                // Submit anyway so server can handle image without OCR if needed, 
                // but for this flow we want OCR. 
                // Let's allow submit even if OCR fails so user isn't stuck.
                document.getElementById('uploadForm').submit();
            }
        }
      </script>





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

                      <data value="<?php echo $total_donasi_ac; ?>">Rp <?php echo number_format($total_donasi_ac, 0, ',', '.'); ?></data>
                    </p>

                    <data class="progress-value" value="<?php echo $persen_ac; ?>"><?php echo number_format($persen_ac, 1); ?>%</data>
                  </div>

                  <div class="progress-box">
                    <div class="progress" style="width: <?php echo $persen_ac; ?>%"></div>
                  </div>

                  <h3 class="h3 card-title">Pengadaan Air Conditioner</h3>

                  <div class="card-wrapper">

                    <p class="card-wrapper-text">
                      <span>Target</span>

                      <data class="green" value="<?php echo $target_ac; ?>">Rp <?php echo number_format($target_ac, 0, ',', '.'); ?></data>
                    </p>
                    
                    <p class="card-wrapper-text">
                      <span>Kurang</span>

                      <data class="cyan" value="<?php echo $kurang_ac; ?>">Rp <?php echo number_format($kurang_ac, 0, ',', '.'); ?></data>
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

                      <data value="<?php echo $total_donasi_multimedia; ?>">Rp <?php echo number_format($total_donasi_multimedia, 0, ',', '.'); ?></data>
                    </p>

                    <data class="progress-value" value="<?php echo $persen_multimedia; ?>"><?php echo number_format($persen_multimedia, 1); ?>%</data>
                  </div>

                  <div class="progress-box">
                    <div class="progress" style="width: <?php echo $persen_multimedia; ?>%"></div>
                  </div>

                  <h3 class="h3 card-title">Pengadaan Multimedia</h3>

                  <div class="card-wrapper">

                    <p class="card-wrapper-text">
                      <span>Target</span>

                      <data class="green" value="<?php echo $target_multimedia; ?>">Rp <?php echo number_format($target_multimedia, 0, ',', '.'); ?></data>
                    </p>

                    <p class="card-wrapper-text">
                      <span>Kurang</span>

                      <data class="cyan" value="<?php echo $kurang_multimedia; ?>">Rp <?php echo number_format($kurang_multimedia, 0, ',', '.'); ?></data>
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
                            if (!empty($donors_ac_list)) {
                                foreach ($donors_ac_list as $row) {
                                    echo "<tr style='border-bottom: 1px solid #dee2e6;'>
                                        <td style='padding: 12px;'>" . htmlspecialchars($row["tanggalSetor"]) . "</td>
                                        <td style='padding: 12px;'>" . htmlspecialchars($row["namaSetor"]) . "</td>
                                        <td style='padding: 12px;'>" . htmlspecialchars($row["krwSetor"] ?? '-') . "</td>
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
                            if (!empty($donors_multi_list)) {
                                foreach ($donors_multi_list as $row) {
                                    echo "<tr style='border-bottom: 1px solid #dee2e6;'>
                                        <td style='padding: 12px;'>" . htmlspecialchars($row["tanggalSetor"]) . "</td>
                                        <td style='padding: 12px;'>" . htmlspecialchars($row["namaSetor"]) . "</td>
                                        <td style='padding: 12px;'>" . htmlspecialchars($row["krwSetor"] ?? '-') . "</td>
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

            <!-- Upload Bukti Card -->
            <div class="service-card" style="padding: 30px; width: 350px; display: flex; flex-direction: column; align-items: center;">
              <h3 class="h3 card-title" style="margin-bottom: 20px;">Verifikasi Otomatis</h3>
              
              <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%;">
                <div style="font-size: 80px; color: var(--pistachio); margin-bottom: 20px;">
                  <ion-icon name="cloud-upload-outline"></ion-icon>
                </div>
                <p class="card-text" style="text-align: center;">
                   Sudah Transfer? Upload bukti transaksi Anda di sini.
                </p>
              </div>

              <div style="margin-top: 20px; min-height: 3rem; display: flex; align-items: center;">
                 <button class="btn btn-primary" onclick="openUploadModal()">
                    <span>Upload Bukti</span>
                    <ion-icon name="cloud-upload-outline" aria-hidden="true"></ion-icon>
                  </button>
              </div>
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





  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');

        if (msg === 'upload_success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Upload!',
                text: 'Bukti transfer Anda telah diterima. Admin akan segera memverifikasi.',
                confirmButtonColor: '#94C766',
                confirmButtonText: 'OK'
            });
            // Clean URL
            window.history.replaceState({}, document.title, window.location.pathname);
        } else if (msg === 'upload_failed') {
             Swal.fire({
                icon: 'error',
                title: 'Gagal Upload',
                text: 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
                confirmButtonColor: '#d33'
            });
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });

    // Custom Script
    const navOpenBtn = document.querySelector("[data-nav-open-btn]");
    const navbar = document.querySelector("[data-navbar]");
    const navCloseBtn = document.querySelector("[data-nav-close-btn]");

    const navElemArr = [navOpenBtn, navCloseBtn];

    for (let i = 0; i < navElemArr.length; i++) {
        navElemArr[i].addEventListener("click", function () {
        navbar.classList.toggle("active");
        });
    }

    const navbarLinks = document.querySelectorAll("[data-nav-link]");

    for (let i = 0; i < navbarLinks.length; i++) {
        navbarLinks[i].addEventListener("click", function () {
        navbar.classList.remove("active");
        });
    }

    const header = document.querySelector("[data-header]");

    window.addEventListener("scroll", function () {
        window.scrollY >= 50 ? header.classList.add("active")
        : header.classList.remove("active");
    });
  </script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>