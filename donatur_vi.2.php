<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GKJW Sidoarjo</title>
    <link rel="icon" type="image/x-icon" href="img/GKJW.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body style="@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap'); margin: 50px; font-family: 'Poppins', sans-serif;">
    <h1 style="font-weight: 500;">List Donatur Tahap VI.2</h1>
    <br>
    <div class="table-responsive-sm">
        <table class="table">
            <thead>
                <tr>
                    <!-- <th>idSetor</th> -->
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Asal</th>
                    <!-- <th>tahapBangun</th> -->
                    <!-- <th>hargaBangun</th> -->
                    <th>Jumlah</th>
                    <th>Nominal</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $servername = "194.233.85.141";
                $username = "ubu4tx0w_webgkjw";
                $password = "Dm}eXHZpIQcq";
                $database = "ubu4tx0w_webgkjw";

                // Create Connection

                $connection = new mysqli($servername, $username, $password, $database);

                // Check Connection

                if ($connection->connect_error) {
                    die("Connection Failed: " . $connection->connect_error);
                }

                // Read All Row from Database Table

                $sql = "SELECT * FROM donatur";
                $result = $connection->query($sql);

                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                // Read Data of Each Row

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        
                        <td>" . $row["tanggalSetor"] . "</td>
                        <td>" . $row["namaSetor"] . "</td>
                        <td>" . $row["krwSetor"] . "</td>
                        
                        
                        <td>" . $row["jumlahSatuan"] . "</td>
                        <td>" . $row["nominal"] . "</td>
                    </tr>";
                }

                ?>
            </tbody>
        </table>
    </div>
</body>
</html>