<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Sertakan jQuery dan Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<!-- <li>
                            <a class="active" href="dashboard.php">Home</a>
						</li> -->
                        <li>
                            <a class="active" id="currentDay"></a>
						</li>
                        <li>
                            <a id="currentTime"></a>
						</li>
						<!-- <li><i class='bx bx-chevron-right'></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li> -->
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
                            <div class="col-12">
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
                                <dl class="row">
                                    <dt class="col-sm-2">Nama Alat :</dt>
                                    <dd class="col-sm-10"><?php echo $nama_alat; ?></dd>

                                    <dt class="col-sm-2">Nama Merk :</dt>
                                    <dd class="col-sm-10"><?php echo $nama_merk; ?></dd>
                                </dl>
                                <figure class="highcharts-figure">
                                    <div id="gauge"></div>
                                </figure>
                                <!-- <button id="toggleTahunan" class="btn btn-primary float-end" style="background: none; border: none;">
                                    <i class="fa fa-chevron-down" style="font-size:24px; color:black;"></i>
                                </button> -->
                            </div>
                        </div>
                    </div>

                    <br>
                    
                    <div class="container" id="containerTahunan" style="display: none;">
                        <!-- Tabel Persentase Harian -->
                        <h2 id="tahunan">Persentase Tahunan</h2>
                        <br>
                        <br>
                        
                        <table id="tabelTahunan" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Persentase Kesiapan</th>
                                    <th style='display: none;'>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Tempat untuk menampilkan data dari database -->
                                <?php
                                    // Buat koneksi ke database
                                    include "koneksi.php";

                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        $id_alat = $_POST['id_alat'];
                                        $id_merk = $_POST['id_merk'];
                                    }

                                    // Untuk memfilter berdasarkan variabel id_alat dan id_merk
                                    if (!empty($id_alat) && !empty($id_merk)) {
                                        // Filter data berdasarkan ID alat
                                        $sql = "(
                                            SELECT * FROM ck_hari WHERE id_alat = '$id_alat' AND id_merk = '$id_merk'
                                        ) UNION (
                                            SELECT * FROM ck_minggu WHERE id_alat = '$id_alat' AND id_merk = '$id_merk'
                                        ) UNION (
                                            SELECT * FROM ck_bulan WHERE id_alat = '$id_alat' AND id_merk = '$id_merk'
                                        ) ORDER BY tanggal ASC";
                                    } else {
                                        // Jika ID alat tidak diisi, ambil semua data
                                        // $sql = "(
                                        //     SELECT * FROM ck_hari
                                        // ) UNION (
                                        //     SELECT * FROM ck_minggu 
                                        // ) UNION (
                                        //     SELECT * FROM ck_bulan
                                        // ) ORDER BY tanggal ASC";
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
                                                    <td>" . $row["tanggal"] . "</td>
                                                    <td style='color: red; font-weight: bold;'>" . $row["status"] . "</td>
                                                    <td class='w-25'>
                                                        <div class='align-content-center'>
                                                            <div class='progress'>
                                                                <div class='progress-bar progress-bar-striped bg-danger progress-bar-animated' role='progressbar' style='width: " . $percentage . "%;' aria-valuenow='" . $percentage . "' aria-valuemin='0' aria-valuemax='100'>" . $percentage . "%</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style='display: none;'>" . $row["keterangan"] . "</td>
                                                </tr>";
                                                $day_number++;
                                            } else {
                                                echo
                                                    "<tr>
                                                        <td>" . $row["tanggal"] . "</td>
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
            </div>
        </main>
    </section>
    
    <!-- Script untuk membuat fungsi toggle pada button -->
    <script>
        // Menambahkan event listener untuk elemen 'alat'
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

    <!-- Script Gauge -->
    <script>
        // Ambil data persentase dari tabel harian
        var data = [];
        var labels = [];
        
        // Loop melalui baris tabel harian (untuk mengambil data)
        $("#tabelTahunan tbody tr").each(function () {
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

        var judul = 'Persentase Kesiapan Alat (Tahunan)';

        var judulGrafik = judul

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
                name: 'Rata-rata Persentase Kinerja',
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
        function updateDateTime() {
            var currentDate = new Date();
            var dayOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            var timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };

            var currentDay = currentDate.toLocaleDateString(undefined, dayOptions);
            var currentTime = currentDate.toLocaleTimeString(undefined, timeOptions);

            $("#currentDay").text(currentDay);
            $("#currentTime").text(currentTime);
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</body>
</html>
