<?php 
	session_start();
	include 'connect_db.php';
	// Memastikan Data Terkirim Melalui Form
	if (ISSET($_POST["nama_variabel"])) {
		$nama = trim($_POST["nama_variabel"]);
		$jenis = trim($_POST["jenis_variabel"]);
		$satuan = trim($_POST["satuan_variabel"]);
		
		// Mengecek apakah data sudah pernah terdaftar
		$query = 'SELECT COUNT(nama_variabel) AS total FROM variabel WHERE LOWER(nama_variabel)=LOWER("'.$nama.'")';
		if (mysqli_query($conn, $query)) {
			$query = mysqli_query($conn, $query);
			$data = mysqli_fetch_assoc($query);
			// Jika data > 0 artinya sudah terdaftar
			if ($data['total'] > 0) {
		    	header('Location: ../tambah_variabel.php?balasan=1');
			} else { // Jika == 0 artinya belum pernah terdaftar
				
				/* Beginning of var-dmu validation */
				// Jika terjadi penambahan var dimana dmu sudah pernah ditambahkan sebelum var baru itu ada
				// Maka dilakukan insert value=0 pada var baru di semua dmu yg sudah ada dengan memastikan detail dmu tidak null
				$q4 = "INSERT INTO variabel (nama_variabel, jenis_variabel, satuan) VALUES ('$nama','$jenis','$satuan')";
				if (mysqli_query($conn, $q4)) {
					// Bandingkan jumlah var pd tb variabel dan tb detail dmu
					// Sinkronisasi var pada detail dmu
					// Menghitung jumlah var input dan output pada tb var
					$q = mysqli_query($conn, 'SELECT * FROM variabel ORDER BY jenis_variabel ASC, id_variabel ASC');
					$input = 0;
					$output = 0;
					if (mysqli_num_rows($q) > 0) {
						while($var = mysqli_fetch_assoc($q)) {
							if ($var['jenis_variabel'] == 'i') {
								$input++;
							} else {
								$output++;
							}
						}
						$total_var = $input + $output;
					}

					// Menghitung jumlah var pd tb detail dmu
					$var_dmu = 0;
					$q2 = mysqli_query($conn, 'SELECT * FROM detail_dmu GROUP BY id_variabel ORDER BY id_variabel ASC');
					if (mysqli_num_rows($q2) > 0) {
						while($var2 = mysqli_fetch_assoc($q2)) {
							$var_dmu++;
						}
					}

					$q5 = mysqli_query($conn, 'SELECT * FROM variabel WHERE nama_variabel="'.$nama.'" AND jenis_variabel="'.$jenis.'" AND satuan="'.$satuan.'"');
					if ((mysqli_num_rows($q5) > 0) AND ($total_var != $var_dmu)) {
						$var4 = mysqli_fetch_assoc($q5);
						$id_var_baru = $var4['id_variabel'];
					
						// Menghitung ada tidaknya data pada tb detail dmu
						$q3 = mysqli_query($conn, 'SELECT * FROM detail_dmu GROUP BY id_prodi');
						if (mysqli_num_rows($q3) > 0) {
							while($var3 = mysqli_fetch_assoc($q3)) {
								$id_prodi = $var3['id_prodi'];
								// Insert value=1 pada id_var=$id_var_baru dan id_prodi=$id_prodi
								$q5 = "INSERT INTO detail_dmu (id_prodi, id_variabel, nilai_variabel) VALUES ('$id_prodi','$id_var_baru','1')";
								if (mysqli_query($conn, $q5)) {
									header('Location: ../mengelola_variabel.php?balasan=1');
								} else {
									header('Location: ../mengelola_variabel.php?balasan=2');
								}
							}
						}
					}
					header('Location: ../mengelola_variabel.php?balasan=1');
				} else {
					header('Location: ../mengelola_variabel.php?balasan=2');
				}
				/* End of var-dmu validation */
			}
		} else {
			header('Location: ../mengelola_variabel.php?balasan=2');
		}
	}	
?>