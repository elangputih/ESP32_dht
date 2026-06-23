<?php
include 'koneksi.php';

// Pastikan request menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari parameter POST
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    
    // Validasi data tidak kosong
    if(!empty($temperature) && !empty($humidity)) {
        $sql = "INSERT INTO dht_data (temperature, humidity) VALUES ('$temperature', '$humidity')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Data berhasil disimpan ke database";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Data suhu atau kelembapan kosong";
    }
} else {
    echo "Metode request harus POST";
}

$conn->close();
?>