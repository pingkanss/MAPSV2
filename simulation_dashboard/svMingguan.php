<?php
    include "koneksi.php";

    function upload() {
        $namaFile = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $tipeFile = $_FILES['gambar']['type'];
        $error = $_FILES['gambar']['error'];
        $tmpName = $_FILES['gambar']['tmp_name'];
    
        // Cek apakah tidak ada gambar yang diupload
        if ($error === 4) {
            echo "<script>
                alert('Pilih gambar dahulu!')
            </script>";
            return false;
        }
    
        // Cek apakah file yang dikirim adalah gambar
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
    
        // if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        //     echo "<script>
        //         alert('File yang diupload bukan gambar!')
        //     </script>";
        //     return false;
        // }
    
        // Jika lolos pengecekan, gambar siap diupload
        // Generate nama gambar baru
        $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    
        // Pindahkan gambar ke direktori tujuan
        if (move_uploaded_file($tmpName, 'img/' . $namaFileBaru)) {
            // Jika berhasil diunggah, Anda dapat mengubah tipe file di database sesuai ekstensi gambar yang diunggah
            // Contoh: Mengubah tipe file di database menjadi ekstensi gambar yang diunggah (JPG, JPEG, atau PNG)
            $tipeFileDiDatabase = strtoupper($ekstensiGambar);
    
            // Simpan $namaFileBaru dan $tipeFileDiDatabase ke database
            // Lakukan operasi penyimpanan di sini
    
            return $namaFileBaru;
        } else {
            // echo "<script>
            //     alert('Gagal mengunggah gambar!')
            // </script>";
            // return false;
        }
    }

    // Periksa apakah formulir telah dikirim (tombol Kirim ditekan)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ambil data dari formulir
        $selectedAlat = $_POST["alat"];
        $selectedMerk = $_POST["id_merk"];
        $tanggal = $_POST["tanggal"];
        $logbook = isset($_POST["logbook"]) ? $_POST["logbook"] : [];
        
        // Hitung jumlah checkbox yang dicentang
        $jumlah_checkbox_dicentang = count($logbook);

        // Hitung jumlah kegiatan berdasarkan alat yang dipilih
        $query = "SELECT COUNT(*) as total_kegiatan FROM kegiatan WHERE id_alat = (SELECT id_alat FROM alat WHERE id_alat = '$selectedAlat') AND id_alat IN (SELECT id_alat FROM merk WHERE id_merk = '$selectedMerk') AND tipe_keg = 2";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_checkbox = $row["total_kegiatan"];
        }

        // Hitung persentase progres
        $persentase_progres = 0; // Default jika tidak ada kegiatan yang sesuai

        if ($total_checkbox > 0) {
            $persentase_progres = ($jumlah_checkbox_dicentang / $total_checkbox) * 100;
        }
        
        // Ambil nilai dari radio button "Solve" atau "Rusak"
        $status = $_POST["status"];
        $keterangan = $_POST["keterangan"];
        //upload gambar
        $gambar = upload();
        if( !$gambar ){
            return false;
        }

        // Siapkan pernyataan SQL untuk memasukkan data ke database
        $sql = "INSERT INTO ck_minggu (id_alat, id_merk, tanggal, status, persen, keterangan, gambar) VALUES ('$selectedAlat','$selectedMerk','$tanggal', '$status','$persentase_progres', '$keterangan','$gambar')";
          
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    // Tutup koneksi database
    header('Location: dashboard.php');
    $conn->close();
?>
