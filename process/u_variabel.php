<?php
	include 'connect_db.php';
	if ((ISSET($_GET['id'])) AND ($_POST['nama_variabel'])) {
		$id = $_GET['id'];
		$nama = trim($_POST["nama_variabel"]);
		$jenis = trim($_POST["jenis_variabel"]);
		$satuan = trim($_POST["satuan_variabel"]);

		// Mengecek apakah data sudah pernah terdaftar
		$query = 'SELECT COUNT(nama_variabel) AS total FROM variabel WHERE LOWER(nama_variabel)=LOWER("'.$nama.'")';
		if (mysqli_query($conn, $query)) {
			$query = mysqli_query($conn, $query);
			$data = mysqli_fetch_assoc($query);
			// Jika > 0 artinya sudah terdaftar
			if ($data['total'] > 0) {
				// Ditemukan bahwa data telah terdaftar
				// Tapi dalam kondisi khusus jika batal ubah (data belum disentuh) tapi tetap klik simpan
				$query = 'SELECT COUNT(*) AS total FROM variabel WHERE LOWER(nama_variabel)=LOWER("'.$nama.'") AND id_variabel="'.$id.'"';
				$query = mysqli_query($conn, $query);
				$d = mysqli_fetch_assoc($query);
				if ($d['total'] > 0) {
					// Mengecek jika nama var tidak berubah, tp atribut lain berubah
					$query = 'SELECT COUNT(*) AS total FROM variabel WHERE LOWER(nama_variabel)=LOWER("'.$nama.'") AND id_variabel="'.$id.'" AND jenis_variabel="'.$jenis.'" AND satuan="'.$satuan.'"';
					$query = mysqli_query($conn, $query);
					$data = mysqli_fetch_assoc($query);
					if ($data['total'] == 0) {
						$query = 'UPDATE variabel SET nama_variabel="'.$nama.'", jenis_variabel="'.$jenis.'", satuan="'.$satuan.'" WHERE id_variabel="'.$id.'"';
						if (mysqli_query($conn, $query)) {
						    header('Location: ../mengelola_variabel.php?balasan=5');
						} else {
						    header('Location: ../mengelola_variabel.php?balasan=6');
						}
					} else {
						// Maka tidak ada notifikasi karna data tidak berubah sama sekali
						header('Location: ../mengelola_variabel.php');
					}
				} else {
					// Jika variabel a diubah menjadi variabel b, sedangkan variabel b sudah terdaftar
					header('Location: ../ubah_variabel.php?id='.$id.'&balasan=1');
				}
			} elseif ($data['total'] == 0) { // Jika == 0 artinya belum pernah terdaftar
				$query = 'UPDATE variabel SET nama_variabel="'.$nama.'", jenis_variabel="'.$jenis.'", satuan="'.$satuan.'" WHERE id_variabel="'.$id.'"';
				if (mysqli_query($conn, $query)) {
				    header('Location: ../mengelola_variabel.php?balasan=5');
				} else {
				    header('Location: ../mengelola_variabel.php?balasan=6');
				}
			}
		} else {
			header('Location: ../mengelola_variabel.php?balasan=6');
		}
	}
?>