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
                        <div class="container" id="filter">
                            <form method="POST" action="">
                                <label for="id_alat">Filter Alat:</label>
                                <select name="id_alat" id="id_alat" class="select2" style="width: 100%;">
                                    <option value=""> Keseluruhan </option> <!-- Opsi untuk menampilkan semua data -->
                                    <option value="1"<?php if (isset($_POST['id_alat']) && $_POST['id_alat'] == '1') echo ' selected'; ?>>CCTV</option>
                                    <option value="2"<?php if (isset($_POST['id_alat']) && $_POST['id_alat'] == '2') echo ' selected'; ?>>ETD</option>
                                    <!-- Tambahkan opsi lain sesuai dengan ID alat yang ada di database -->
                                </select><br>
                                <br>
                                <button type="submit" class="btn btn-primary" id="filterButton">Filter</button>
                            </form>
                        </div>                            
                    </div>

                    <div class="container" style="max-width: auto;">
                        <div class="row">
                            <div class="col-12">
                                <figure class="highcharts-figure">
                                    <div id="gauge"></div>
                                </figure>
                                <!-- <button id="toggleTahunan" class="btn btn-primary float-end" style="background: none; border: none;">
                                    <i class="fa fa-chevron-down" style="font-size:24px; color:black;"></i>
                                </button> -->
                            </div>
                            <!-- <div class="col-6">
                                <figure class="highcharts-figure">
                                    <div id="gauge2"></div>
                                </figure>
                                <button id="toggleMingguan" class="btn btn-primary float-end" style="background: none; border: none;">
                                    <i class="fa fa-chevron-down" style="font-size:24px; color:black;"></i>
                                </button>
                            </div> -->
                        </div>
                    </div>

                    <br>
                    
                    <div class="container" id="containerTahunan" style="display: none;">
                        <!-- Tabel Persentase Harian -->
                        <h2 id="tahunan">Persentase Tahunan</h2>
                        <!-- <a href="addHarian.php" class="btn btn-primary float-end float-end">
                            <i class='bx bxs-plus-circle'></i>
                            <span class="text">Tambah Data</span>
                        </a> -->
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
                                    include "koneksi.php";

                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        $id_alat = $_POST['id_alat'];
                                    }

                                    if (!empty($id_alat)) {
                                        // Filter data berdasarkan ID alat
                                        $sql = "(
                                            SELECT * FROM ck_hari WHERE id_alat = '$id_alat'
                                        ) UNION (
                                            SELECT * FROM ck_minggu WHERE id_alat = '$id_alat'
                                        ) UNION (
                                            SELECT * FROM ck_bulan WHERE id_alat = '$id_alat'
                                        ) ORDER BY tanggal ASC";
                                    } else {
                                        // Jika ID alat tidak diisi, ambil semua data
                                        $sql = "(
                                            SELECT * FROM ck_hari
                                        ) UNION (
                                            SELECT * FROM ck_minggu 
                                        ) UNION (
                                            SELECT * FROM ck_bulan
                                        ) ORDER BY tanggal ASC";
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
    <!-- <script>
        $(document).ready(function () {
            // Inisialisasi toggle switch
            // $("#toggleHarian").addClass("active");
            $("#gauge").hide();
            // $("#tabelMingguan").hide();
            // $("#toggleHarian").click(function() {
            //     $("#containerHarian").slideToggle();
            //     $("#containerMingguan").hide(); // Toggle tampilan kontainer harian
            // });
            // $("#toggleMingguan").click(function() {
            //     $("#containerMingguan").slideToggle();
            //     $("#containerHarian").hide();
            // });
            // $("#toggleTahunan").click(function() {
            //     $("#containerTahunan").slideToggle();
            // });
            $("#filterButton").click(function(event) {
                event.preventDefault();
                $("#gauge").slideD();
            });
        });
    </script> -->

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

        var judulGrafik = 'Persentase Kesiapan Alat (Tahunan)'

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
        // Memeriksa persentase harian dan menampilkan alert jika <= 80
        // $(document).ready(function () {
        //     $("#tabelTahunan tbody tr").each(function () {
        //         var persentase = parseFloat($(this).find(".progress-bar").attr("aria-valuenow"));
        //         if (persentase <= 80) {
        //             var tanggal = $(this).find("td:first-child").text().trim();
        //             var alertMessage = "Warning! Persentase alat pada " + tanggal + " kurang dari 80 %";
        //             showAlert(alertMessage, this); // Menambahkan 'this' sebagai argumen
        //         }
        //     });
        // });

        function updateDateTime() {
            var currentDate = new Date();
            var dayOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            var timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };

            var currentDay = currentDate.toLocaleDateString(undefined, dayOptions);
            var currentTime = currentDate.toLocaleTimeString(undefined, timeOptions);

            $("#currentDay").text(currentDay);
            $("#currentTime").text(currentTime);
        }

        // Update the day and time initially and every second
        updateDateTime();
        setInterval(updateDateTime, 1000); // Update every second

        // function showAlert(message, tableRow) {
        //     // Mengambil catatan dari tabel harian atau mingguan
        //     var catatan = $(tableRow).find("td:nth-child(4)").text().trim();

        //     var alertHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
        //         '<div class="d-flex align-items-center">' +
        //         '<span class="btn btn-link float-end disable" data-toggle="collapse" data-target="#alertContent">' +
        //         '<i class="bi bi-exclamation-triangle-fill" style="color: red;"></i>' +
        //         '</span>' +
        //         message +
        //         '</div>' +
        //         '<div class="catatan" style="margin-left: 45px;font-weight: 600; color: red;">' +
        //         catatan +
        //         '</div>' +
        //         '</div>';

        //     // Menambahkan alert di bawah baris tabel yang memenuhi kriteria
        //     $(tableRow).find("td:first-child").after(alertHTML);
        // }

    </script>
</body>
</html>