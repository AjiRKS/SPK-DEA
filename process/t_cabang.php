<?php 
	session_start();
	include 'connect_db.php';
	// Memastikan Data Terkirim Melalui Form
	if ((ISSET($_POST["cabang_prodi"])) AND (ISSET($_POST["alamat"]))) {
		$cabang = trim($_POST["cabang_prodi"]);
		$alamat = trim($_POST["alamat"]);
		// Mengecek apakah data sudah pernah terdaftar
		$query = 'SELECT COUNT(*) AS total FROM prodi WHERE LOWER(cabang_prodi)=LOWER("'.$cabang.'") AND LOWER(alamat)=LOWER("'.$alamat.'")';
		if (mysqli_query($conn, $query)) {
			$query = mysqli_query($conn, $query);
			$data = mysqli_fetch_assoc($query);
			// Jika > 0 artinya sudah terdaftar
			if ($data['total'] > 0) {
		    	header('Location: ../tambah_cabang.php?balasan=1');
			} elseif ($data['total'] == 0) { // Jika == 0 artinya belum pernah terdaftar
				$query2 = 'SELECT COUNT(*) AS total FROM prodi WHERE LOWER(cabang_prodi)=LOWER("'.$cabang.'") OR LOWER(alamat)=LOWER("'.$alamat.'")';
				if (mysqli_query($conn, $query2)) {
					$query2 = mysqli_query($conn, $query2);
					$data2 = mysqli_fetch_assoc($query2);
					if ($data2['total'] > 0) {
						header('Location: ../tambah_cabang.php?balasan=1');
					} else {
						$query = "INSERT INTO prodi (cabang_prodi, alamat) VALUES ('$cabang','$alamat')";
						if (mysqli_query($conn, $query)) {
						    header('Location: ../mengelola_cabang.php?balasan=1');
						} else {
						    header('Location: ../mengelola_cabang.php?balasan=2');
						}
					}
				}
			}
		} else {
			header('Location: ../mengelola_cabang.php?balasan=2');
		}
	}	
?>