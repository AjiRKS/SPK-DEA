<?php
session_start();
if ((!isset($_SESSION['username'])) and (!isset($_SESSION['password']))) {
	// Mencegah direct access melalui url
	header('Location: index.php');
} else {
	// Berhasil Login
	include "process/connect_db.php";
	$id = $_SESSION["id"];
	$level = $_SESSION["level"];
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>SPK Metode DEA</title>
	<!-- Bootstrap -->
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/layout.css" rel="stylesheet">
	<link href="assets/css/bootstrap.css" rel="stylesheet">
	<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
	<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-thin-rounded/css/uicons-thin-rounded.css'>
	<style>
		@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");

		h4 {
			font-weight: normal;
			font-size: 18px;
			font-family: "Poppins", sans-serif;
		}

		.dark h1 {
			color: white;
		}

		.dark h4 {
			color: white;
		}


		.dark .panel-body legend {
			color: white;
		}

		.control-label {
			font-weight: normal !important;
		}

		.dark .control-label {
			color : #fff
		}

		.panel {
			color: #fff;
			background-color: #337ab7;
			border-color: #337ab7;
			border-top-color: #337ab7;
			width: 135%;
		}

		.panel .panel-heading {
			color: #fff;
			background-color: #337ab7;
			border-color: #337ab7;
		}

		.panel-body img {
			margin-left: auto;
			margin-right: auto;
		}

		.togle {
			color: #4070f4;
			align-items: center;
		}

		.dark .togle {
			color: #fff;
		}

		#dataTables-example td:nth-child(1.2.3.4.5),
		#dataTables-example th:nth-child(1.2.3.4.5) {
			width: 10%;
			min-width: 80px;
		}

		.btn-disabled {
			pointer-events: none;
			opacity: 0.6;
			cursor: not-allowed;
		}

		.panel-body {
			background-color: #fff !important;
			color: #000 !important;
		}

		.dataTable_wrapper {
			background-color: #fff !important;
			color: #000 !important;
		}

		#dataTables-example tbody td {
			background-color: white !important;
			color: black !important;
		}

		.dark .panel-body {
			background-color: #3f3f3f !important;
			color: #333 !important;
		}

		.dark .dataTable_wrapper {
			background-color: #3f3f3f !important;
			color: #fff !important;
		}

		.dark .col-sm-7 {
			background-color: #3f3f3f;
			color: #fff;
		}

		.dark #dataTables-example tbody td {
			background-color: #3f3f3f !important;
			color: white !important;
		}

		.dark .col-sm-7 tbody td {
			background-color: #3f3f3f;
			color: white;
		}

	</style>

	<script>
		setInterval(() => {
			const time = document.querySelector(".display #time");
			let date = new Date();
			let hours = date.getHours();
			let minutes = date.getMinutes();
			let seconds = date.getSeconds();
			let day_night = "AM";
			if (hours > 12) {
				day_night = "PM";
				hours = hours - 12;
			}
			if (seconds < 10) {
				seconds = "0" + seconds;
			}
			if (minutes < 10) {
				minutes = "0" + minutes;
			}
			if (hours < 10) {
				hours = "0" + hours;
			}
			time.textContent = hours + ":" + minutes + ":" + seconds + " " + day_night;
		});
	</script>

</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<!-- Koding Bagian Navbar -->
			<nav class="navbar">
				<div class="logo_item">
					<i class="bx bx-menu" id="sidebarOpen"></i>
					<img src="assets/img/logo_polibest.jpg" alt="">SPK Metode DEA
				</div>

				<div class="wrappers">
					<div class="display">
						<div id="time"></div>
					</div>
					<span></span>
					<span></span>
				</div>

				<div class="navbar_content">
					<i class="bi bi-grid"></i>
					<i class='bx bx-sun' id="darkLight"></i>
					<i class='bx bx-bell'></i>
					<ul class="nav navbar-nav navbar-right">
						<a class="togle" href="ubah_pengguna.php?type=profile&id=<?php echo $id; ?>&lvl=<?php echo $level; ?>" aria-expanded="false"><?php echo $_SESSION['user']; ?></a>
					</ul>
				</div>
			</nav>
		</div> <!-- End of First Row -->


		<nav class="sidebar">
			<div class="menu_content">
				<ul class="menu_items">
					<div class="menu_title menu_dahsboard"></div>
					<!-- Koding Bagian Sidebar -->
					<li class="item">
						<a href="beranda.php" class="nav_link">
							<span class="navlink_icon">
								<i class="bx bx-home-alt"></i>
							</span>
							<span class="navlink">Beranda</span>
						</a>
					</li>
					<?php
					if ($level == 'a') {
						# Superadmin
						echo '
						<li class="item">
						 <a href="mengelola_cabang.php" class="nav_link">
						  <span class="navlink_icon">
						  <i class="bx bxs-school"></i>
						  </span>
						  <span class="navlink">Mengelola Prodi</span>
						 </a>
					  	</li>
						<li class="item">
						  <a href="mengelola_pengguna.php" class="nav_link">
							<span class="navlink_icon">
							<i class="bx bxs-user-detail"></i>
							</span>
							<span class="navlink">Mengelola Admin Prodi</span>
						  </a>
						</li>
						<li class="item">
						  <a href="mengelola_variabel.php" class="nav_link">
							<span class="navlink_icon">
							<i class="glyphicon glyphicon-list-alt"></i>
							</span>
							<span class="navlink">Mengelola Variabel</span>
						  </a>
						</li>
						<li class="item">
						  <a href="lihat_data.php" class="nav_link">
							<span class="navlink_icon">
							<i class="bx bx-data"></i>
							</span>
							<span class="navlink">Data DMUs</span>
						  </a>
						</li>
        				';
					} elseif ($level == 'c') {
						# Admin Prodi
						echo '
						<li class="item">
							<a href="mengelola_pengguna.php" class="nav_link">
						  	<span class="navlink_icon">
						  		<i class="bx bxs-user-detail"></i>
						  	</span>
						  	<span class="navlink">Mengelola Akun Prodi</span>
							</a>
						</li>
						<li class="item">
						 <a href="mengelola_dmu.php" class="nav_link">
						  <span class="navlink_icon">
						  <i class="fi fi-tr-input-numeric"></i>
						  </span>
						  <span class="navlink">Mengelola Data DMU</span>
						 </a>       
         				</li>
       					';
					} elseif ($level == 'p') {
						# Manajer Pusat
						echo '
						<li class="item">
						  <a href="lihat_data.php" class="nav_link">
							<span class="navlink_icon">
							<i class="bx bx-data"></i>
							</span>
							<span class="navlink">Data DMUs</span>
						  </a>
						</li>
       					';
					}
					?>

				</ul>

				<!-- duplicate this ul tag if you want to add or remove navlink with submenu -->
				<!-- Start -->
				<!-- duplicate this ul tag if you want to add or remove navlink without submenu -->

				<ul class="menu_items">
					<div class="menu_title menu_setting"></div>
					<li class="item">
						<a href="ubah_pengguna.php?type=profile&id=<?php echo $id; ?>&lvl=<?php echo $level; ?>" class="nav_link">
							<span class="navlink_icon">
								<i class='bx bx-user'></i>
							</span>
							<span class="navlink">Profil</span>
						</a>
					</li>
					<li class="item">
						<a href="#" class="nav_link" onclick="confirmLogout()">
							<span class="navlink_icon">
								<i class='bx bx-log-out'></i>
							</span>
							<span class="navlink">Logout</span>
						</a>
					</li>
				</ul>

				<!-- Sidebar Open / Close -->
				<div class="bottom_content">
					<div class="bottom expand_sidebar">
						<span>Expand</span>
						<i class='bx bx-expand'></i>
					</div>
					<div class="bottom collapse_sidebar">
						<span>Collapse</span>
						<i class='bx bx-collapse'></i>
					</div>
				</div>
			</div>
		</nav>