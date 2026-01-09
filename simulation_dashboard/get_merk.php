<?php
    // Hubungkan ke database (gantilah dengan koneksi database sesuai dengan pengaturan Anda)
    $mysqli = new mysqli("localhost", "root", "", "logbook");
    
    // Periksa koneksi
    if ($mysqli->connect_error) {
        die("Koneksi gagal: " . $mysqli->connect_error);
    }
    
    // Periksa apakah parameter 'id_alat' telah diterima melalui permintaan GET
    if (isset($_GET['id_alat'])) {
        $id_alat = $_GET['id_alat'];
    
        // Query untuk mengambil data 'merk' berdasarkan 'id_alat'
        $sql = "SELECT id_merk, nama_merk FROM merk WHERE id_alat = $id_alat";
        $result = $mysqli->query($sql);
    
        $merkData = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $merkData[] = $row;
            }
        }
    
        // Mengembalikan data 'merk' dalam format JSON
        header('Content-Type: application/json');
        echo json_encode($merkData);
    } else {
        // Jika parameter 'id_alat' tidak ada, kembalikan pesan error
        echo "Parameter 'id_alat' tidak ditemukan.";
    }
    
    // Tutup koneksi ke database
    $mysqli->close();
?>
