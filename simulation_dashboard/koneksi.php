<?php
    // Koneksi ke database MySQL
    $servername = "localhost";  // Ganti dengan nama host MySQL Anda
    $username = "root"; // Ganti dengan username MySQL Anda
    $password = "";   // Ganti dengan kata sandi MySQL Anda
    $dbname = "logbook";  // Ganti dengan nama database Anda

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
?>