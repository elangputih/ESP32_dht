<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Sensor DHT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="10"> 
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="text-center mb-4">Sistem Monitoring Sensor DHT</h2>
    
    <?php
    // Ambil data terbaru
    $query_latest = "SELECT * FROM dht_data ORDER BY id DESC LIMIT 1";
    $result_latest = $conn->query($query_latest);
    $latest = $result_latest->fetch_assoc();
    
    $current_temp = $latest ? $latest['temperature'] : '--';
    $current_hum  = $latest ? $latest['humidity'] : '--';
    $current_time = $latest ? $latest['reading_time'] : '--';
    ?>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card text-white bg-danger shadow">
                <div class="card-body py-4">
                    <h5 class="card-title">Temperatur</h5>
                    <h1 class="display-4 fw-bold"><?= $current_temp; ?> °C</h1>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-info shadow">
                <div class="card-body py-4">
                    <h5 class="card-title">Kelembapan</h5>
                    <h1 class="display-4 fw-bold"><?= $current_hum; ?> %</h1>
                </div>
            </div>
        </div>
        <p class="text-muted text-end">Terakhir diperbarui: <strong><?= $current_time; ?></strong></p>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Riwayat Data Sensor</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Temperatur (°C)</th>
                            <th>Kelembapan (%)</th>
                            <th>Waktu Pengambilan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_history = "SELECT * FROM dht_data ORDER BY id DESC LIMIT 20";
                        $result_history = $conn->query($query_history);
                        if ($result_history->num_rows > 0) {
                            $no = 1;
                            while($row = $result_history->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['temperature']}</td>
                                        <td>{$row['humidity']}</td>
                                        <td>{$row['reading_time']}</td>
                                      </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>Belum ada data.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>