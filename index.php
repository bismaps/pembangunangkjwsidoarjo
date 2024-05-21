<?php

// Database connection details (replace with your actual details)
$servername = "194.233.85.141";
$username = "ubu4tx0w_webgkjw";
$password = "Dm}eXHZpIQcq";
$dbname = "ubu4tx0w_webgkjw";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Define the SQL query to fetch data
$sql = "SELECT SUM(nominal) AS totalDonasi, SUM(jumlahSatuan) AS kg FROM donatur";

$result = $conn->query($sql);

// Check for successful execution
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
  
    // Extract data from the retrieved row
    $progressPercentage = (float) $row['kg'] / 20000 * 100;

    $currentDonation = (float) $row['kg'];

    // $currentDonation = round($currentDonation,3);

    $currentDonation = number_format($currentDonation, 2, ',', '.');

    // Format the donation amount with three decimal places
    // $formattedDonation = number_format($row['kg'], 3, ',', '.');

    // Convert the formatted string back to a float (if needed)
    // $currentDonation = (float) $formattedDonation;
    // $currentDonation = $formattedDonation * 1000;

    // Close connection
    $conn->close();
} else {
  echo "No data found";
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>GKJW Sidoarjo</title>
    <link rel="icon" type="image/x-icon" href="img/GKJW.svg">
    <link rel="stylesheet" href="style.css" />
  </head>
    <body class="prevsel" oncontextmenu="return false;">
        <main class="main">
            <section class="headings section">
                <div class="headings__bg">
                    <div class="headings__container container grid">

                        <div class="div-wrapper">
                            <div class="text-wrapper">TAHAP VI.2 - RANGKA ATAP</div>
                        </div>

                        <img draggable="false" src="img/GKJW.svg" alt="" class="headings__img" oncontextmenu="return false;">

                    </div>

                    <div class="upper__container container grid">

                        <div class="upper__data">
                            <h1 class="upper__title">Gumregah Mbangun Greja</h1>
                            <!-- <h2 class="upper__subtitle">Tahap VI.2 - Rangka Atap</h2> -->
                            <p class="upper__description">Mari dukung pembangunan gedung Greja Kristen Jawi Wetan (GKJW) Jemaat Sidoarjo
                                agar dapat segera terbangun rumah ibadah yang layak, karena persembahan adalah wujud iman kita.</p>
                        </div>

                        <div class="photos__box">
                            <div class="photos__dream">
                                <div class="photos"><img draggable="false" src="https://lh3.googleusercontent.com/d/19bHPu5heXgsmOz4sAhwtgr4pVpJtAnvs"></div>
                            </div>
                        </div>
                    </div>

                    <div class="needs__container container grid">
                        <div class="group">
                        <p class="element-dari">
                        <span class="spanupdate"><?php echo $currentDonation; ?></span>
                            <span class="text-wrapper-5">dari keperluan 20000 kilogram</span>
                        </p>
                        </div>
                    </div>

                    <div class="progress__container container grid">

                        <div class="skill-bar">
                            <span class="skill-per" style="width: <?php echo $progressPercentage; ?>%;">
                                <span class="tooltip">
                                    <span class="tooltiptwo"></span>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="donate__container container grid">

                        <div class="donate__data">
                            <h2 class="donate__subtitle">Saya ingin berdonasi</h2>

                            <img draggable="false" src="img/QRIS.svg">

                            <h2 class="donate__subtitle">atau melalui BNI - 5956666669</h2>
                            
                            <a href="donatur_vi.2.php">
                                <div class="div-wrapper__donate">
                                    <div class="text-wrapper__donate">CEK DAFTAR DONATUR</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                </div>
            </section>
        </main>
    </body>
</html>