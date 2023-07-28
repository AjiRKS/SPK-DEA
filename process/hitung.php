<?php 
	session_start();
	include "connect_db.php";
	# Menghapus Semua Data Pada DB Tabel Perhitungan Efisiensi
	$q = mysqli_query($conn, 'DELETE FROM perhitungan_efisiensi');
	
	// Mendapatkan semua id prodi
	$index_dmu = 0;
	$q = mysqli_query($conn, 'SELECT id_prodi FROM detail_dmu GROUP BY id_prodi ORDER BY id_prodi');
	if (mysqli_num_rows($q) > 0) {
		while ($data = mysqli_fetch_assoc($q)) {
			$id_dmu_prodi[$index_dmu] = $data['id_prodi'];
			$index_dmu++;
		}
	}
	$index_dmu = 0;
	$index_dmu_ccr = 1;
	$n_dmu = count($id_dmu_prodi);

	// Mendapatkan Semua ID Variabel Terurut Berdasarkan Jenis Var dan ID
	$id_variabel = array();
	$q = mysqli_query($conn, 'SELECT d.id_variabel FROM detail_dmu AS d, variabel AS v WHERE d.id_variabel=v.id_variabel GROUP BY d.id_variabel ORDER BY v.jenis_variabel, v.id_variabel ASC');
	if (mysqli_num_rows($q) > 0) {
		$i = 0;
		while ($data = mysqli_fetch_assoc($q)) {
			$id_variabel[$i] = $data['id_variabel'];
			$i++;
		}
	}

	// Menghitung banyak var output
	$n_var_output = 0;
	$q = mysqli_query($conn, 'SELECT COUNT(*) as total FROM variabel WHERE jenis_variabel="o"');
	$d = mysqli_fetch_assoc($q);
	$n_var_output = $d['total'];

	// Menghitung banyak var input
	$n_var_input = 0;
	$q = mysqli_query($conn, 'SELECT COUNT(*) as total FROM variabel WHERE jenis_variabel="i"');
	$d = mysqli_fetch_assoc($q);
	$n_var_input = $d['total'];
