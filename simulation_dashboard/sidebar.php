<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- ICONS -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <!-- STYLESHEET -->
  <link rel="stylesheet" href="style.css" />
  <title>Sidebar</title>
</head>

<body>

  <div class="container p-0" id="sidebar" style="position: sticky; top: 0;">
    <div class="sidebar active">
      <!-- <div class="menu-btn">
          <i class="ph-bold ph-caret-left"></i>
        </div> -->
      <div class="head">
        <div style="width: 44px;
                    height: 44px;
                    border-radius: 50%;
                    background-color: #016db8;
                    overflow: hidden;">
          <img src="./img/logoap1.png" style="width: 80%;
                                        margin-top: 10px;
                                        margin-left: 5px;
                                        object-fit: cover;">
        </div>
        <div class="user-details">
          <p class="tittle">MaPS V2.1</p>
          <p class="name">Equipment</p>
        </div>
      </div>
      <div class="side-nav">
        <div class="menu">
          <p class="tittle">Main</p>
          <ul>
            <li class="active">
              <a href="dashboard.php">
                <i class="icon ph-bold ph-house-simple"></i>
                <span class="text">Dashboard</span>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="icon ph-bold ph-chart-bar"></i>
                <span class="text">Charts</span>
                <i class="arrow ph-bold ph-caret-down"></i>
              </a>
              <ul class="sub-menu">
                <li>
                  <a href="chartHarian.php">
                    <span class="text">Harian</span>
                  </a>
                </li>
                <li>
                  <a href="chartMingguan.php">
                    <span class="text">Mingguan</span>
                  </a>
                </li>
                <li>
                  <a href="chartBulanan.php">
                    <span class="text">Bulanan</span>
                  </a>
                </li>
              </ul>
            </li>
            <li>
              <a href="#">
                <i class="icon ph-bold ph-file-text"></i>
                <span class="text">Logbook</span>
                <i class="arrow ph-bold ph-caret-down"></i>
              </a>
              <ul class="sub-menu">
                <li>
                  <a href="logbook_pb.php">
                    <span class="text">Perbaikan</span>
                  </a>
                </li>
                <li>
                  <a href="">
                    <span class="text">Pemeliharaan</span>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Jquery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js" integrity="sha512-8Z5++K1rB3U+USaLKG6oO8uWWBhdYsM3hmdirnOEWp8h2B1aOikj5zBzlXs8QOrvY9OxEnD2QDkbSKKpfqcIWw==" crossorigin="anonymous"></script>
  <script src="script.js"></script>
</body>

</html>
