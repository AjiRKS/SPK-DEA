<?php 
	include 'connect_db.php';
	session_start();
	$id = $_SESSION['id'];
	$level = $_SESSION['level'];
	if ($level == 'a') {
		# Login sebagai Superadmin
		$lvl = 'c';
	} else {
		# Login sebagai Admin Cabang
		$lvl = 'm';
		$query = mysqli_query($conn, 'SELECT id_prodi FROM pengguna WHERE id_pengguna = "'.$id.'"');
		$d = mysqli_fetch_assoc($query);
		$cabang = $d['id_prodi'];
	}

	// Memastikan Data Terkirim Melalui Form
	if (ISSET($_POST["nama_pengguna"])) {
		$nama = trim($_POST["nama_pengguna"]);
		$username = str_replace(" ","",$_POST["username"]);
		$password = md5(trim($_POST["password"]));
		// Jika aksi menambah manajer cabang
		if (is_null($cabang)) {
			$cabang = $_POST["cabang_prodi"];
		}
		// Mengecek apakah data sudah pernah terdaftar
		$query = 'SELECT COUNT(username) AS total FROM pengguna WHERE LOWER(username)=LOWER("'.$username.'")';
		if (mysqli_query($conn, $query)) {
			$query = mysqli_query($conn, $query);
			$data = mysqli_fetch_assoc($query);
			// Jika username > 0 artinya sudah terdaftar
			if ($data['total'] > 0) {
		    	header('Location: ../tambah_pengguna.php?balasan=1');
			} elseif ($data['total'] == 0) { // Jika == 0 artinya belum pernah terdaftar
				$query = "INSERT INTO pengguna (nama, username, password, id_prodi, level) VALUES ('$nama','$username','$password','$cabang','$lvl')";
				if (mysqli_query($conn, $query)) {
				    header('Location: ../mengelola_pengguna.php?balasan=1');
				} else {
				    header('Location: ../mengelola_pengguna.php?balasan=2');
				}
			}
		} else {
			header('Location: ../mengelola_pengguna.php?balasan=2');
		}
	}
?>