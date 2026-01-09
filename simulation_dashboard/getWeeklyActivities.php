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

    // Fetch daily activities (tipe int 1) based on the selected equipment (alat)
    if (isset($_POST["alat"])) {
        $selectedAlat = $_POST["alat"];

        // Perform a database query to fetch daily activities based on $selectedAlat
        $sql = "SELECT id_keg, nama_keg FROM kegiatan WHERE id_alat = '$selectedAlat' AND tipe_keg = 2";
        $result = $conn->query($sql);

        $weeklyActivities = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Menggunakan id_keg sebagai kunci dan nama_keg sebagai nilai
                $weeklyActivities[] = $row["nama_keg"];
            }
        }

        // Close the database connection
        $conn->close();

        // Return the daily activities as JSON response
        echo json_encode($weeklyActivities);
    }
?>
