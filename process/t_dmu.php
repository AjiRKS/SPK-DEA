<?php 
	session_start();
	include 'connect_db.php';
	// Memastikan Data Terkirim Melalui Form
	if (ISSET($_SESSION['id_prodi'])) {
		$id_prodi = $_SESSION['id_prodi'];
		// Memastikan cabang prodi yang dipilih belum terdaftar
		$query = mysqli_query($conn, 'SELECT * FROM detail_dmu WHERE id_prodi='.$id_prodi.'');
		if (mysqli_num_rows($query) > 0) {
			// Ditemukan cabang prodi yang dipilih telah terdaftar
			header('Location: ../tambah_dmu.php?balasan=1');
		} else {
			$query1 = mysqli_query($conn, "SELECT * FROM variabel");
			if (mysqli_num_rows($query1) > 0) {
				while ($var = mysqli_fetch_assoc($query1)) {
					$name = str_replace(' ','_',$var['nama_variabel']);
					$variabel = $_POST[$name];
					// Translate nama var -> id var
					$query2 = mysqli_query($conn, "SELECT * FROM variabel WHERE nama_variabel='".$var['nama_variabel']."'");
					if (mysqli_num_rows($query2) > 0) {
						while ($idvar = mysqli_fetch_assoc($query2)) {
							$id_variabel = $idvar['id_variabel'];
						}
					} else {
						header('Location: ../mengelola_dmu.php?balasan=2A');
					}
					// Insert ke detail_dmu
					$query3 = mysqli_query($conn, "INSERT INTO detail_dmu (id_prodi, id_variabel, nilai_variabel) VALUES ('$id_prodi','$id_variabel','$variabel')");
				}
			} else {
				header('Location: ../mengelola_dmu.php?balasan=2B');
			}
			header('Location: ../mengelola_dmu.php?balasan=1');
		}
	} else {
		header('Location: ../mengelola_dmu.php?balasan=2C&id='.$id_prodi_user.'');
	}
?>