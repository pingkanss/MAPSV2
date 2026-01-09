<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Sertakan jQuery dan Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <!-- Highchart JS -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
</head>
<body>
    <?php 
        include 'sidebar.php';
    ?>

    <!-- CONTENT -->
    <section id="content">
        <main>
            <div class="head-title">
				<div class="left">
					<h1>Chart Harian</h1>
					<ul class="breadcrumb">
						<li>
							<a href="dashboard.php">Home</a>
						</li>
                        <li>
                            <i class='bx bx-chevron-right'></i>
                        </li>
                        <li>
                            <a class="active" href="chartHarian.php">Chart Harian</a>
						</li>
					</ul>
				</div>
			</div>

            <div class="persen">
                <div class="container">
                <div class="head">
                        <div class="container" id="filter" style="margin-top: 20px;">
                            <form method="POST" action="">
                                <div class="row g-3">
                                    <div class="col-md">
                                        <div class="form-floating">
                                        <select class="select2 form-select" name="id_alat" id="id_alat" aria-label="Floating label select example">
                                            <option value="">-</option>
                                            <?php
                                                // Hubungkan ke database (gantilah dengan koneksi database sesuai dengan pengaturan Anda)
                                                $mysqli = new mysqli("localhost", "root", "", "logbook");

                                                // Periksa koneksi
                                                if ($mysqli->connect_error) {
                                                    die("Koneksi gagal: " . $mysqli->connect_error);
                                                }

                                                // Ambil data dari tabel 'alat' dalam database
                                                $sql = "SELECT id_alat, nama_alat FROM alat";
                                                $result = $mysqli->query($sql);

                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<option value="' . $row['id_alat'] . '">' . $row['nama_alat'] . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">Tidak ada data</option>';
                                                }

                                                // Tutup koneksi ke database
                                                $mysqli->close();
                                            ?>
                                        </select>
                                        <label for="id_alat">Pilih Alat</label>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-floating">
                                        <select class="select2 form-select" name="id_merk" id="id_merk" aria-label="Floating label select example">
                                            <option value="">-</option>
                                        </select>
                                        <label for="id_merk">Pilih Merk</label>
                                        </div>
                                    </div>

                                    <div class="col-md">
                                        <button type="submit" class="btn btn-primary btn-lg" style="margin: 5px;">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>                            
                    </div>
                    <div class="container" style="max-width: auto; margin-top: 50px;">
                        <div class="row">
                            <div class="col">
                                <figure class="highcharts-figure">
                                    <div id="gauge"></div>
                                </figure>
                                <button id="toggleHarian" class="btn btn-primary float-end" style="background: none; border: none;">
                                    <i class="fa fa-chevron-down" style="font-size:24px; color:black;"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="container" id="containerHarian" style="display: none;">
                        <?php
                            $mysqli = new mysqli("localhost", "root", "", "logbook");

                            // Periksa koneksi
                            if ($mysqli->connect_error) {
                                die("Koneksi gagal: " . $mysqli->connect_error);
                            }
                            
                            // Inisialisasi variabel nama_alat dan nama_merk
                            $nama_alat = "";
                            $nama_merk = "";
                            
                            // Mengambil id_alat dan id_merk dari parameter URL jika tersedia
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                $id_alat = $_POST['id_alat'];
                                $id_merk = $_POST['id_merk'];
                            
                                // Query untuk mengambil nama alat dan merk berdasarkan id_alat dan id_merk
                                $sql = "SELECT alat.nama_alat, merk.nama_merk 
                                        FROM ck_hari
                                        INNER JOIN alat ON ck_hari.id_alat = alat.id_alat
                                        INNER JOIN merk ON ck_hari.id_merk = merk.id_merk
                                        WHERE ck_hari.id_alat = $id_alat AND ck_hari.id_merk = $id_merk";
                            
                                $result = $mysqli->query($sql);
                            
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $nama_alat = $row["nama_alat"];
                                        $nama_merk = $row["nama_merk"];
                                    }
                                } else {
                                    $nama_alat = "Alat tidak ditemukan";
                                    $nama_merk = "Merk tidak ditemukan";
                                }
                            }
                            
                            $mysqli->close();                            
                        ?>
                        
                        <div class="row">
                            <div class="col-md">
                                <h2 id="harian">Persentase Harian</h2>
                            </div>
                            <div class="col-md">
                            <a href="addHarian.php" class="btn btn-primary float-end float-end">
                                <i class='bx bxs-plus-circle'></i>
                                <span class="text">Tambah Data</span>
                            </a>
                            </div>
                        </div>

                        <dl class="row">
                            <dt class="col-sm-2">Nama Alat :</dt>
                            <dd class="col-sm-10"><?php echo $nama_alat; ?></dd>

                            <dt class="col-sm-2">Nama Merk :</dt>
                            <dd class="col-sm-10"><?php echo $nama_merk; ?></dd>
                        </dl>
                        
                        <!-- Tabel Persentase Harian -->
                        <table id="tabelHarian" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Persentase Kesiapan</th>
                                    <th style='display: none;'>Catatan</th>
                                    <th style='display: none;'>Gambar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Tempat untuk menampilkan data dari database -->
                                <?php
                                    // Buat koneksi ke database
                                    $conn = new mysqli("localhost", "root", "", "logbook");
                                    
                                    // Periksa koneksi
                                    if ($conn->connect_error) {
                                        die("Koneksi gagal: " . $conn->connect_error);
                                    }

                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        $id_alat = $_POST['id_alat'];
                                        $id_merk = $_POST['id_merk'];
                                    }

                                    if (!empty($id_alat)) {
                                        // Filter data berdasarkan ID alat
                                        $sql = "SELECT * FROM ck_hari WHERE id_alat = '$id_alat' AND id_merk = '$id_merk' ORDER BY tanggal ASC";
                                    } else {
                                        // Jika ID alat tidak diisi, ambil semua data
                                        // $sql = "SELECT * FROM ck_hari ORDER BY tanggal ASC";
                                    }

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $day_number = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $persen = $row["persen"];
                                            $percentage = round($persen);
                                            // Menampilkan persentase sebagai progress bar (sesuai dengan persenan)
                                            if ($percentage <= 80) {  
                                            echo
                                                "<tr>
                                                    <td>" . date("d-m-Y", strtotime($row["tanggal"])) . "</td>
                                                    <td style=''>" . $row["status"] . "</td>
                                                    <td class='w-25'>
                                                        <div class='align-content-center'>
                                                            <div class='progress'>
                                                                <div class='progress-bar progress-bar-striped bg-danger progress-bar-animated' role='progressbar' style='width: " . $percentage . "%;' aria-valuenow='" . $percentage . "' aria-valuemin='0' aria-valuemax='100'>" . $percentage . "%</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style='display: none;'>" . $row["keterangan"] . "</td>
                                                    <td style='text-align: center; display: none;' class='h-25'>
                                                        <img src='img/". $row["gambar"] ."'>
                                                    </td>
                                                </tr>";
                                                $day_number++;
                                            } else {
                                                echo
                                                    "<tr>
                                                        <td>" . date("d-m-Y", strtotime($row["tanggal"])) . "</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class='w-25'>
                                                            <div class='progress'>
                                                                <div class='progress-bar progress-bar-striped bg-success progress-bar-animated' role='progressbar' style='width: " . $percentage . "%;' aria-valuenow='" . $percentage . "' aria-valuemin='0' aria-valuemax='100'>" . $percentage . "%</div>
                                                            </div>
                                                        </td>
                                                    </tr>";
                                                $day_number++;
                                            }
                                        }
                                    } else {
                                        echo "Hasil tidak ada";
                                    }
                                    $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </section>

        <script src="script.js"></script>
        
        <!-- Script untuk membuat fungsi toggle pada button -->
        <script>
            $(document).ready(function () {
                $("#toggleHarian").click(function() {
                    $("#containerHarian").slideToggle();
                });
            });

            // $(document).ready(function () {
            //     $('#datepicker').datepicker({
            //         format: "yyyy-mm", // Set format to display year and month
            //         startView: "months",
            //         minViewMode: "months",
            //         autoclose: true
            //     });
            // });
            
            // Menambahkan event listener untuk elemen 'alat' dalam memfilter merk
            document.getElementById('id_alat').addEventListener('change', function () {
                var selectedAlat = this.value; // Nilai yang dipilih dalam 'alat'

                // Mengirim permintaan ke server menggunakan XMLHttpRequest atau fetch API
                // Anda dapat menggunakan teknik ini untuk mengambil data 'merk' yang berkaitan
                // dari server berdasarkan 'selectedAlat' dan kemudian mengisi elemen 'merk'.
                
                // Contoh menggunakan fetch API:
                fetch('get_merk.php?id_alat=' + selectedAlat)
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        var merkSelect = document.getElementById('id_merk');
                        merkSelect.innerHTML = ''; // Mengosongkan elemen 'merk'

                        if (data.length > 0) {
                            // Memasukkan pilihan 'merk' berdasarkan data yang diterima dari server
                            data.forEach(function (id_merk) {
                                var option = document.createElement('option');
                                option.value = id_merk.id_merk;
                                option.textContent = id_merk.nama_merk;
                                merkSelect.appendChild(option);
                            });
                        } else {
                            // Menampilkan pesan jika tidak ada data yang ditemukan
                            var option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'Tidak ada data';
                            merkSelect.appendChild(option);
                        }
                    })
                    .catch(function (error) {
                        console.error('Error:', error);
                    });
            });
        </script>

        <!-- Script Gauge Harian -->
        <script>
            // Ambil data persentase dari tabel harian
            var data = [];
            var labels = [];
            
            // Loop melalui baris tabel harian (untuk mengambil data)
            $("#tabelHarian tbody tr").each(function () {
                var tanggal = $(this).find("td:first-child").text().trim();
                var persentase = parseFloat($(this).find(".progress-bar").attr("aria-valuenow"));
                labels.push(tanggal);
                data.push(persentase);
            });

            // Menghitung rata-rata persentase
            var totalPersentase = data.reduce(function (a, b) {
                return a + b;
            }, 0);
            
            var rataRataPersentase = Math.round(totalPersentase / data.length);

            var judulGrafik = 'Persentase Kesiapan Alat (Harian)'

            // Highchart Gauge Chart
            Highcharts.chart('gauge', {
                chart: {
                    type: 'gauge',
                    plotBackgroundColor: null,
                    plotBackgroundImage: null,
                    plotBorderWidth: 0,
                    plotShadow: false
                },
                title: {
                    text: judulGrafik
                },
                pane: {
                    startAngle: -90,
                    endAngle: 89.9,
                    background: null,
                    center: ['50%', '75%'],
                    size: '110%'
                },
                yAxis: {
                    min: 0,
                    max: 100, // Change the max value to 100
                    tickPixelInterval: 72, // Adjust the tick interval
                    tickPosition: 'inside',
                    tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
                    tickLength: 25, // Adjust tick length
                    tickWidth: 2, // Adjust tick width
                    minorTickInterval: null,
                    labels: {
                        distance: 20, // Sesuaikan jarak label
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold' // Menambahkan gaya tebal (bold)
                        }
                    },
                    lineWidth: 0,
                    plotBands: [
                        {
                            from: 0,
                            to: 40,
                            color: '#DF5353', // red
                            thickness: 25
                        },
                        {
                            from: 40,
                            to: 80,
                            color: '#FFFF00', // red
                            thickness: 25
                        },
                        {
                            from: 80,
                            to: 100,
                            color: '#55BF3B', // green
                            thickness: 25
                        }
                    ]
                },
                series: [{
                    name: 'Rata-rata Persentase Kinerja (%)',
                    data: [rataRataPersentase], // Tampilkan persentase kinerja dari data
                    dataLabels: {
                        format: '{y} %',
                        borderWidth: 0,
                        style: {
                            fontSize: '13px'
                        }
                    }
                }],
                credits: {
                    enabled: false
                }
            });

        </script>

        <script>
            // Memeriksa persentase harian dan menampilkan alert jika <= 80
            $(document).ready(function () {
                $("#tabelHarian tbody tr").each(function () {
                    var persentase = parseFloat($(this).find(".progress-bar").attr("aria-valuenow"));
                    if (persentase <= 80) {
                        var tanggal = $(this).find("td:first-child").text().trim();
                        var alertMessage = "Warning! Persentase alat tanggal " + tanggal + " kurang dari 80 %";
                        showAlert(alertMessage, this); // Menambahkan 'this' sebagai argumen
                    }
                });
            });

            function showAlert(message, tableRow) {
                // Mengambil catatan dari tabel harian atau mingguan
                var catatan = $(tableRow).find("td:nth-child(4)").text().trim();

                var alertHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                    '<div class="d-flex align-items-center">' +
                    '<span class="btn btn-link float-end disable" data-toggle="collapse" data-target="#alertContent">' +
                    '<i class="bi bi-exclamation-triangle-fill" style="color: red;"></i>' +
                    '</span>' +
                    message +
                    '</div>' +
                    '<div class="catatan" style="margin-left: 45px;font-weight: 600; color: red;">' +
                    catatan +
                    '</div>' +
                    '</div>';

                // Menambahkan alert di bawah baris tabel yang memenuhi kriteria
                $(tableRow).find("td:first-child").after(alertHTML);
            }

        </script>
</body>
</html>
