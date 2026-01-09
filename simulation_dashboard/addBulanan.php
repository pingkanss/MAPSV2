<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Mingguan Logbook</title>
    <!-- Tambahkan link CSS Bootstrap -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Gunakan Bootstrap 5.0 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Pastikan Anda memasukkan Popper.js dan jQuery (jika diperlukan) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Gunakan Bootstrap 5.0 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

    <!-- Tambahkan Icons versi 1.3.0 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- My CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- Sertakan jQuery dan Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
</head>
<body>
    <?php 
		include './sidebar.php';
		// include './navbar.php';
	?>
        	<!-- CONTENT -->
	<section id="content">
		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Tambah Data</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="dashboard.php">Home</a>
						</li>
                        <li>
                            <i class='bx bx-chevron-right'></i>
                        </li>
                        <li>
							<a href="#">Tambah Data Bulanan</a>
						</li>
					</ul>
				</div>
                <a href="dashboard.php" class="btn-download ml-auto">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Kembali</span>
                </a>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="container mt-5">
                        <form method="POST" action="svBulanan.php" class="form">
                            <div class="form-group">
                                <h1>Pemeliharaan Bulanan</h1>
                                <label for="alat">Pilih Alat:</label>
                                <select name="alat" id="alat" onchange="updateKegiatan(this.value)" class="form-control">
                                    <?php
                                        // Establish a database connection
                                        $servername = "localhost";
                                        $username = "root";
                                        $password = "";
                                        $dbname = "logbook";
                                        $conn = new mysqli($servername, $username, $password, $dbname);
                                        
                                        // Check the connection
                                        if ($conn->connect_error) {
                                            die("Koneksi gagal: " . $conn->connect_error);
                                        }
                                        
                                        // Fetch alat options from the database
                                        $sql = "SELECT id_alat, nama_alat FROM alat";
                                        $result = $conn->query($sql);
                                        
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['id_alat'] . "'>" . $row['nama_alat'] . "</option>";
                                            }
                                        }
                                        
                                        // Close the database connection
                                        $conn->close();
                                    ?>
                                </select>

                                <div id="kegiatan-container" class="mt-3">
                                        <!-- Checkbox kegiatan akan di-generate oleh JavaScript -->
                                </div>

                                <div class="form-group">
                                    <label for="tanggal">Tanggal:</label>
                                    <input type="month" name="tanggal" id="tanggal" required class="form-control">
                                </div>

                                <div class="form-group">
                                <label for="status">Status:</label>
                                    <input type="radio" name="status" id="statusSolve" value="Solve" required>
                                    <label for="statusSolve">
                                        Solve
                                    </label>
                                    <br>
                                    <input type="radio" name="status" id="statusRusak" value="Rusak" required>
                                    <label for="statusRusak">
                                        Rusak
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="keterangan">Ket:</label>
                                    <input style="width: 500px;" type="text" name="keterangan" id="keterangan" required class="form-control">
                                </div>

                                <div class="form-group">
                                        <label for="gambar">Upload Gambar:</label>
                                        <input type="file" name="gambar" id="gambar" required class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
            <script>
                // Fungsi untuk mengubah pilihan kegiatan berdasarkan alat yang dipilih
                function updateKegiatan(selectedAlat) {
                    const kegiatanContainer = document.getElementById("kegiatan-container");

                    // Menghapus semua checkbox kegiatan yang ada
                    kegiatanContainer.innerHTML = ""; // Clear the container first

                    // Send an AJAX request to fetch activities based on the selected equipment
                    $.ajax({
                        url: 'getMonthlyActivities.php',
                        type: 'POST',
                        data: { alat: selectedAlat },
                        dataType: 'json',
                        success: function (response) {
                            // Tambahkan fetched activities ke dalam container
                            response.forEach(function (kegiatan) {
                                const checkbox = document.createElement("input");
                                checkbox.type = "checkbox";
                                checkbox.name = "logbook[]";
                                checkbox.value = kegiatan;

                                const label = document.createElement("label");
                                
                                label.appendChild(checkbox);
                                label.appendChild(document.createTextNode(" ")); // Spasi
                                label.appendChild(document.createTextNode(kegiatan));

                                kegiatanContainer.appendChild(label);
                                kegiatanContainer.appendChild(document.createElement("br"));
                            });
                        },
                        error: function (error) {
                            console.error("Error:", error); // Cetak pesan kesalahan ke konsol
                        }
                    });
                }
                // Initial load of activities
                updateKegiatan(document.getElementById("alat").value);
            </script>

            <!-- Tambahkan tautan ke Bootstrap JS dan jQuery (sesuaikan dengan versi Bootstrap yang Anda gunakan) -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </main>
    </section>
</body>
</html>
