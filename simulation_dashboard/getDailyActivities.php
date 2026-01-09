<?php
    include "koneksi.php";

    // Fetch daily activities (tipe int 1) based on the selected equipment (alat)
    if (isset($_POST["alat"])) {
        $selectedAlat = $_POST["alat"];

        // Perform a database query to fetch daily activities based on $selectedAlat
        $sql = "SELECT id_keg, nama_keg FROM kegiatan WHERE id_alat = '$selectedAlat' AND tipe_keg = 1";
        $result = $conn->query($sql);

        $dailyActivities = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Menggunakan id_keg sebagai kunci dan nama_keg sebagai nilai
                $dailyActivities[] = $row["nama_keg"];
            }
        }

        // Close the database connection
        $conn->close();

        // Return the daily activities as JSON response
        echo json_encode($dailyActivities);
    }
?>
