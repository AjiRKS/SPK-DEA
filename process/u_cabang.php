<?php
	include 'connect_db.php';
	if ((ISSET($_GET['id'])) AND (ISSET($_POST['cabang_prodi'])) AND (ISSET($_POST['alamat']))) {
		$id = $_GET['id'];
		$cabang = trim($_POST['cabang_prodi']);
		$alamat = trim($_POST['alamat']);

		// Mengecek apakah data sudah pernah terdaftar
		$query = 'SELECT COUNT(cabang_prodi) AS total FROM prodi WHERE LOWER(cabang_prodi)=LOWER("'.$cabang.'") AND LOWER(alamat)=LOWER("'.$alamat.'")';
		if (mysqli_query($conn, $query)) {
			$query = mysqli_query($conn, $query);
			$data = mysqli_fetch_assoc($query);
			// Jika > 0 artinya sudah terdaftar
			if ($data['total'] > 0) {
				// Ditemukan bahwa data telah terdaftar
				// Tapi dalam kondisi khusus jika batal ubah (data belum disentuh) tapi tetap klik simpan
				$query = 'SELECT COUNT(*) AS total FROM prodi WHERE LOWER(cabang_prodi)=LOWER("'.$cabang.'") AND LOWER(alamat)=LOWER("'.$alamat.'") AND id_prodi="'.$id.'"';
				$query = mysqli_query($conn, $query);
				$d = mysqli_fetch_assoc($query);
				if ($d['total'] > 0) {
					// Maka tidak ada notifikasi karna data tidak berubah sama sekali
					header('Location: ../mengelola_cabang.php');
				} else {
					$query2 = 'SELECT COUNT(*) AS total FROM prodi WHERE LOWER(cabang_prodi)=LOWER("'.$cabang.'") OR LOWER(alamat)=LOWER("'.$alamat.'")';
					if (mysqli_query($conn, $query2)) {
						$query2 = mysqli_query($conn, $query2);
						$data2 = mysqli_fetch_assoc($query2);
						if ($data2['total'] > 0) {
							// Jika cabang a diubah menjadi cabang b, sedangkan cabang b sudah terdaftar
							header('Location: ../ubah_cabang.php?id='.$id.'&balasan=1');
						}
					}
				}
			} elseif ($data['total'] == 0) { // Jika == 0 artinya belum pernah terdaftar
				$query3 = 'SELECT COUNT(*) AS total FROM prodi WHERE LOWER(cabang_prodi)=LOWER("'.$cabang.'") OR LOWER(alamat)=LOWER("'.$alamat.'")';
				if (mysqli_query($conn, $query3)) {
					$query3 = mysqli_query($conn, $query3);
					$data3 = mysqli_fetch_assoc($query3);
					if ($data3['total'] > 0) {
						// Jika hanya mengubah salah satu diantara nama cabang atau alamat cabang
						// Dan tidak ada data yang diubah tidak duplikat terhadap data di db
						$q = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM prodi WHERE LOWER(cabang_prodi)=LOWER("'.$cabang.'") AND LOWER(alamat)=LOWER("'.$alamat.'") AND id_prodi="'.$id.'"');
						$d = mysqli_fetch_assoc($q);
						if ($d['total'] == 0) {
							// Data disimpan
							$query = 'UPDATE prodi SET cabang_prodi="'.$cabang.'", alamat="'.$alamat.'" WHERE id_prodi="'.$id.'"';
							if (mysqli_query($conn, $query)) {
								header('Location: ../mengelola_cabang.php?balasan=5');
							} else {
								header('Location: ../mengelola_cabang.php?balasan=6');
							}
						} else {
							// Jika salah satu data antara cabang atau alamatnya sudah terdaftar
							header('Location: ../ubah_cabang.php?id='.$id.'&balasan=1');
						}
					} else {
						// Data belum terdaftar sama sekali
						$query = 'UPDATE prodi SET cabang_prodi="'.$cabang.'", alamat="'.$alamat.'" WHERE id_prodi="'.$id.'"';
						if (mysqli_query($conn, $query)) {
						    header('Location: ../mengelola_cabang.php?balasan=5');
						} else {
						    header('Location: ../mengelola_cabang.php?balasan=6');
						}
					}
				}
			}
		} else {
			header('Location: ../mengelola_cabang.php?balasan=6');
		}
	}
?>