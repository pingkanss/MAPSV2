<!doctype html>
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
    <?php 
        include 'sidebar.php';
    ?>

<body>
    	<!-- CONTENT -->
	<section id="content">
		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Logbook</h1>
					<ul class="breadcrumb">
						<li>
							<a href="dashboard.php">Home</a>
						</li>
                        <li>
                            <i class='bx bx-chevron-right'></i>
                        </li>
                        <li>
                            <a class="active" href="logbook_pb.php">Logbook</a>
						</li>
					</ul>
				</div>
			</div>

        <div class="persen">
			<div class="container">
				<div class="head">
                    <div class="container" id="containerLogbook">
                        <!-- Tabel Persentase Logbook -->
                        <h2 id="logbook">Logbook Perbaikan</h2>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end">
                                <!-- Pagination links will be generated dynamically here -->
                            </ul>
                        </nav>

                        <table id="tabelLogbook" class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Persentase Kesiapan</th>
                                    <th style='display: none;'>Catatan</th>
                                    <th>Gambar</th>
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
                                    }

                                    if (!empty($id_alat)) {
                                        // Filter data berdasarkan ID alat
                                        $sql = "SELECT * FROM ck_hari WHERE id_alat = '$id_alat' ORDER BY tanggal ASC";
                                    } else {
                                        // Jika ID alat tidak diisi, ambil semua data
                                        $sql = "SELECT * FROM ck_hari ORDER BY tanggal ASC";
                                    }

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $day_number = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $persen = $row["persen"];
                                            $percentage = round($persen);
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
                                                        <td style='text-align: center;' class='h-25'>
                                                            <img src='img/". $row["gambar"] ."' style='max-width: 150px; max-height: 150px;'>
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
            </div>
        </main>
    </section>

    <script src="script.js"></script>
    <!-- Script untuk membuat fungsi toggle pada button -->
    <script>
        $(document).ready(function () {
            // Inisialisasi toggle switch
            $("#toggleHarian").addClass("active");
            $("#mingguan").hide();
            $("#tabelMingguan").hide();

            // Ketika tombol Harian diklik
            $("#toggleHarian").click(function () {
                if ($("#selectedToggle").val() !== "harian") {
                    $("#selectedToggle").val("harian");
                    $("#tabelMingguan").hide();
                    $("#mingguan").hide();
                    $("#harian").show();
                    $("#tabelHarian").show();
                    $("#toggleHarian").addClass("active");
                    $("#toggleMingguan").removeClass("active");
                }
            });

            // Ketika tombol Mingguan diklik
            $("#toggleMingguan").click(function () {
                if ($("#selectedToggle").val() !== "mingguan") {
                    $("#selectedToggle").val("mingguan");
                    $("#tabelHarian").hide();
                    $("#harian").hide();
                    $("#mingguan").show();
                    $("#tabelMingguan").show();
                    $("#toggleMingguan").addClass("active");
                    $("#toggleHarian").removeClass("active");
                }
            });
        });
    </script>

    <!-- Script Chart -->
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

        var judulGrafik = 'Persentase Kesiapan Alat'

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
                        to: 80,
                        color: '#DF5353', // red
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

        // Jumlah hari dalam bulan ini (misalnya, bulan Maret)
        var daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();

        // Menghitung rata-rata persentase per hari
        var rataRataPersentase = Math.round(totalPersentase / daysInMonth);

        var judulGrafik = 'Persentase Dalam 1 Bulan';

        // Highchart Gauge Chart
        Highcharts.chart('gauge2', {
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
                        to: 80,
                        color: '#DF5353', // red
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
                name: 'Rata-Rata Kinerja dalam 1 Bulan',
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
        // Memeriksa persentase harian dan menampilkan alert jika < 80
        $(document).ready(function () {
            $("#tabelLogbook tbody tr").each(function () {
                var persentase = parseFloat($(this).find(".progress-bar").attr("aria-valuenow"));
                if (persentase <= 80) {
                    var tanggal = $(this).find("td:first-child").text().trim();
                    var alertMessage = "Warning! Persentase alat tanggal " + tanggal + " kurang dari 80 %";
                    showAlert(alertMessage, this); // Menambahkan 'this' sebagai argumen
                }
            });
        });

        function showAlert(message, tableRow) {
            // Mengambil catatan dari tabel harian
            var catatan = $(tableRow).find("td:nth-child(4)").text().trim();
            
            // var alertHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
            //     '<div class="d-flex align-items-center">' +
            //     '<span class="btn btn-link float-end disable" data-toggle="collapse" data-target="#alertContent">' +
            //     '<i class="bi bi-exclamation-triangle-fill" style="color: red;"></i>' +
            //     '</span>' +
            //     message +
            //     '<button style="margin-right: 10px;" class="position-absolute top-15 end-0 btn btn-link float-end" data-bs-toggle="collapse" data-bs-target="#alertContent">' +
            //     '<i class="bi bi-caret-down-fill float-end" style="color: red;"></i>' +
            //     '</button>' +
            //     '</div>' +
            //     '<div id="alertContent" class="collapse">' +
            //     // Menampilkan catatan dari tabel harian sebagai konten yang diperluas
            //     catatan +
            //     '</div>' +
            //     '</div>';

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

    <!-- Tambahkan library Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script>
        // Inisialisasi Select2 pada elemen select dengan class "select2"
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</body>
</html>
