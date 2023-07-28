<?php 
	session_start();
	include "connect_db.php";
	# Menghapus Semua Data Pada DB Tabel Perhitungan Efisiensi
	$q = mysqli_query($conn, 'DELETE FROM tb_perhitungan_efisiensi');
	
	// Mendapatkan semua id prodi
	$index_dmu = 0;
	$q = mysqli_query($conn, 'SELECT id_prodi FROM tb_detail_dmu GROUP BY id_prodi ORDER BY id_prodi');
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
	$q = mysqli_query($conn, 'SELECT d.id_variabel FROM tb_detail_dmu AS d, tb_variabel AS v WHERE d.id_variabel=v.id_variabel GROUP BY d.id_variabel ORDER BY v.jenis_variabel, v.id_variabel ASC');
	if (mysqli_num_rows($q) > 0) {
		$i = 0;
		while ($data = mysqli_fetch_assoc($q)) {
			$id_variabel[$i] = $data['id_variabel'];
			$i++;
		}
	}

	// Menghitung banyak var output
	$n_var_output = 0;
	$q = mysqli_query($conn, 'SELECT COUNT(*) as total FROM tb_variabel WHERE jenis_variabel="o"');
	$d = mysqli_fetch_assoc($q);
	$n_var_output = $d['total'];

	// Menghitung banyak var input
	$n_var_input = 0;
	$q = mysqli_query($conn, 'SELECT COUNT(*) as total FROM tb_variabel WHERE jenis_variabel="i"');
	$d = mysqli_fetch_assoc($q);
	$n_var_input = $d['total'];

	// Looping Utama Sejumlah DMU
	for ($y=0; $y < $n_dmu; $y++) {
	/*** BCC Model ***/ 
		// Mengubah var output menjadi fungsi tujuan
		$q = mysqli_query($conn, 'SELECT * FROM tb_detail_dmu d, tb_variabel v WHERE v.id_variabel = d.id_variabel AND d.id_prodi="'.$id_dmu_prodi[$index_dmu].'" ORDER BY d.id_prodi, v.jenis_variabel ASC, v.id_variabel');
		$index_output = $index_input = 1;
		$fungsi_tujuan = array();
		if (mysqli_num_rows($q) > 0) {
			while($d = mysqli_fetch_assoc($q)){
				if ($index_input <= $n_var_input) {
					$fungsi_tujuan['w'.$index_input] = 0;
					$index_input++;
				} elseif ($index_output < $n_var_output) {
					$fungsi_tujuan['u'.$index_output] = $d['nilai_variabel'];
					$index_output++;
				} elseif ($index_output == $n_var_output) {
					$fungsi_tujuan['u'.$index_output] = $d['nilai_variabel'];
					$fungsi_tujuan['u0'] = -1;
				}
			}
		}

		// Mengubah var input menjadi fungsi kendala = 1
		$q2 = mysqli_query($conn, 'SELECT * FROM tb_detail_dmu d, tb_variabel v WHERE v.id_variabel = d.id_variabel AND d.id_prodi="'.$id_dmu_prodi[$index_dmu].'" AND v.jenis_variabel="i" ORDER BY v.id_variabel');
		$index_input = 1;
		$index_kendala = 0;
		// Fungsi kendala final
		$fungsi_kendala = array();
		// Fungsi kendala masing-masing
		$f_kendala = array();
		if (mysqli_num_rows($q2) > 0) {
			while($d2 = mysqli_fetch_assoc($q2)){
				if ($index_input < $n_var_input) {
					$f_kendala['w'.$index_input] = $d2['nilai_variabel'];
					$index_input++;
				
				} elseif ($index_input == $n_var_input) {
					$f_kendala['w'.$index_input] = $d2['nilai_variabel'];
					if ($n_var_output > 1) {
						for ($i=1; $i <= $n_var_output; $i++) { 
							$f_kendala['u'.$i] = 0;
						}
					} else {
						$f_kendala['u1'] = 0;
					}
					$f_kendala['a1'] = 1;
					$f_kendala['notasi'] = '=';
					$f_kendala['xb'] = 1;
					// Ruas kanan disebut juga sebagai Xb
				}
			}
		}
		
		$index_input = $index_output = 1;
		$fungsi_kendala[$index_kendala] = $f_kendala;
		$index_kendala++;
		unset($f_kendala);
		$f_kendala = array();
		
		// Mengubah var input dan output menjadi fungsi kendal <= 0
		$index_slack_var = 1;
		$m = 0;
		$q3 = mysqli_query($conn, 'SELECT * FROM tb_detail_dmu d, tb_variabel v WHERE v.id_variabel = d.id_variabel ORDER BY d.id_prodi, v.jenis_variabel DESC, v.id_variabel');
		if (mysqli_num_rows($q3) > 0) {
			while($d3 = mysqli_fetch_assoc($q3)){
				// Mencari nilai max data untuk var M
				if ($d3['nilai_variabel'] > $m) {
					$m = $d3['nilai_variabel'];
				}
				// Jika pertama kali akan melakukan insert data pada array
				if (empty($f_kendala)) {
					// Jika jumlah var output hanya 1
					if ($n_var_output == 1) {
						$f_kendala['u'.$index_output] = $d3['nilai_variabel'];
					} elseif ($n_var_output > 1) {
						$f_kendala['u'.$index_output] = $d3['nilai_variabel'];
						$index_output++;
					}	
				// Jika jumlah var output >= 1
				} elseif ($index_output <= $n_var_output) {
					$f_kendala['u'.$index_output] = $d3['nilai_variabel'];
					$index_output++;
				// Jika jumlah var input >= 1
				} elseif ($index_input < $n_var_input) {
					$f_kendala['w'.$index_input] = -$d3['nilai_variabel'];
					$index_input++;
				// Jika var input sudah lengkap di dalam fungsi kendala
				} elseif ($index_input == $n_var_input) {
					$f_kendala['w'.$index_input] = -$d3['nilai_variabel'];
					$f_kendala['u0'] = -1;
					$f_kendala['s'.$index_slack_var] = 1;
					$f_kendala['notasi'] = '=';
					$f_kendala['xb'] = 0;
					$index_input = $index_output = 1;
					// Menyimpan fungsi kendala ke dalam 1 var induk
					$fungsi_kendala[$index_kendala] = $f_kendala;
					$index_kendala++; $index_slack_var++;
					unset($f_kendala);
					$f_kendala = array();
				}
			}
		}
		
		# Inisialisasi tabel simplex big M
		$tabel_simplex_bigm = tabel_simplex_bigm($fungsi_tujuan, $fungsi_kendala, $n_var_input, $n_var_output, $m);
		
		# Hasil inisialisasi tabel simplex
		$fungsi_tujuan = $tabel_simplex_bigm[0];
		$fungsi_kendala = $tabel_simplex_bigm[1];
		$cj = $tabel_simplex_bigm[2];
		$m = $tabel_simplex_bigm[3];
		$zj = $tabel_simplex_bigm[4];
		$cj_zj = $tabel_simplex_bigm[5];
		# Perhitungan simplex big M
		$bigm = simplex_bigm($fungsi_tujuan, $fungsi_kendala, $cj, $m, $zj, $cj_zj, $n_var_input, $n_var_output);
	/*** End Of BCC Model ***/ 

	/*** CCR Model ***/ 
		$q4=mysqli_query($conn, 'SELECT d.id_variabel FROM tb_detail_dmu AS d, tb_variabel AS v WHERE d.id_variabel=v.id_variabel GROUP BY d.id_variabel ORDER BY v.jenis_variabel, v.id_variabel');
		if (mysqli_num_rows($q4)>0) {
			$a=0; $c=0;
			$b=1; $d=1;
			$n_var = 1;
			$fungsi_ccr_z=array();
			while ($d4=mysqli_fetch_assoc($q4)) {
				$id_var=$d4['id_variabel'];
				if ($n_var <= $n_var_input) {
					// Fungsi Z (Var Input)
					$q5=mysqli_query($conn, 'SELECT d.nilai_variabel FROM tb_detail_dmu AS d, tb_variabel AS v WHERE d.id_variabel=v.id_variabel AND d.id_variabel="'.$id_var.'" AND v.jenis_variabel="i" ORDER BY d.id_variabel');
					if (mysqli_num_rows($q5) > 0) {
						while ($d5=mysqli_fetch_assoc($q5)) {
							if ($b > $n_dmu) {
								$a++;
								$b=1;
							}
							$fungsi_ccr_z[$a]['x'.$b]=$d5['nilai_variabel'];
							$b++;
						}
					}
				} else {
					// Fungsi Kendala (Var Output)
					$q6=mysqli_query($conn, 'SELECT d.nilai_variabel FROM tb_detail_dmu AS d, tb_variabel AS v WHERE d.id_variabel=v.id_variabel AND d.id_variabel="'.$id_var.'" AND v.jenis_variabel="o" ORDER BY d.id_variabel');
					if (mysqli_num_rows($q6) > 0) {
						while ($d6=mysqli_fetch_assoc($q6)) {
							if ($d > $n_dmu) {
								$c++;
								$d=1;
							}
							$fungsi_ccr_kendala[$c]['x'.$d]=$d6['nilai_variabel'];
							$d++;
						}
					}
				}
				$n_var++;
			}
		}

		// Menambahkan Var -Z di dalam Fungsi CCR Z sesuai DMU ke-N
		for ($m=0; $m < $n_var_input; $m++) { 
			$fungsi_ccr_z[$m]['z'] = -$fungsi_ccr_z[$m]['x'.$index_dmu_ccr];
			$fungsi_ccr_z[$m]['notasi'] = '=';
			$fungsi_ccr_z[$m]['ruas_kanan'] = 0;
		}

		// Menambahkan Notasi dan Ruas Kanan pada Fungsi CCR Kendala
		for ($o=0; $o < $n_var_output; $o++) { 
			$fungsi_ccr_kendala[$o]['notasi'] = '>=';
			$fungsi_ccr_kendala[$o]['ruas_kanan'] = $fungsi_ccr_kendala[$o]['x'.$index_dmu_ccr];
		}
	/*** End Of CCR Model ***/ 

		$tabel_ccr = tabel_dual_simplex($fungsi_ccr_z, $fungsi_ccr_kendala, $n_var_input, $n_dmu);
		$dual_simplex = dual_simplex($tabel_ccr, $n_dmu); 
		$rekomendasi = rekomendasi($dual_simplex, $n_dmu, $fungsi_ccr_z, $n_var_input);
		# Query Insert Nilai Efisiensi BCC dan Rekomendasi CCR
		# Jika Nilai Efisiensi BCC != 1 atau < 1
		# Berikan Rekomendasi CCR
			# Mendapatkan Nilai Awal
			$nilai_awal = array();
			$q = mysqli_query($conn, 'SELECT * FROM tb_detail_dmu AS d, tb_variabel AS v WHERE d.id_variabel=v.id_variabel AND v.jenis_variabel="i" AND id_prodi="'.$id_dmu_prodi[$index_dmu].'" ORDER BY v.jenis_variabel, d.id_variabel');
			if (mysqli_num_rows($q) > 0) {
				$o = 0;
				while ($n_awal = mysqli_fetch_assoc($q)) {
					$nilai_awal[$o] = $n_awal['nilai_variabel'];
					$o++;
				}
			}
			
			for ($i=1; $i <= $n_var_input; $i++) { 
				# Mendapatkan Rekomendasi Per Variabel Berdasarkan Jenis Variabel dan ID Variabel (ASC)
				$nilai_rekomendasi = $rekomendasi[0]['x'.$i];
				$ii = $i - 1; 
				# Menyimpan Nilai Efisiensi dan Rekomendasi Ke DB
				if ($bigm == 1) {
					$query_insert = mysqli_query($conn, "INSERT INTO tb_perhitungan_efisiensi (id_prodi, id_variabel, nilai_efisiensi, nilai_awal, rekomendasi) VALUES ('$id_dmu_prodi[$index_dmu]','$id_variabel[$ii]','$bigm','$nilai_awal[$ii]','$nilai_awal[$ii]')");
				} else {
					$query_insert = mysqli_query($conn, "INSERT INTO tb_perhitungan_efisiensi (id_prodi, id_variabel, nilai_efisiensi, nilai_awal, rekomendasi) VALUES ('$id_dmu_prodi[$index_dmu]','$id_variabel[$ii]','$bigm','$nilai_awal[$ii]','$nilai_rekomendasi')");
				}
			}
		$index_dmu_ccr++;
		$index_dmu++;
	}

	/**
	 * Fungsi Inisialisasi Tabel Simplex (Big M)
	 * @param 	[array] 	 $fungsi_tujuan			[array assoc 2 dimensi]
	 * @param 	[array] 	 $fungsi_kendala		[array assoc 2 dimensi]
	 * @param 	[integer]  	 $n_var_input			[jumlah var input]
	 * @param 	[integer]    $n_var_output  		[jumlah var output]
	 * @param 	[integer]    $m  					[nilai var M]
	 * @return 	[array] 	 $tabel_simplex_bigm	[array assoc 3 dimensi]
	 */
	function tabel_simplex_bigm($fungsi_tujuan, $fungsi_kendala, $n_var_input, $n_var_output, $m){
		$n_kendala = count($fungsi_kendala);
		# Note : Var $n_kendala adalahjumlah slack dan artificial var
		# +1 pada var $n_kendala dimaksudkan untuk Var u0
		$m = $m * 100;
		$cj = $fungsi_tujuan;
		$cj['a1'] = -1 * $m;
		for ($i=1; $i <= $n_kendala-1; $i++) { 
			$cj['s'.$i] = 0;
		}

		# Pembentukan Fungsi Tujuan
		$var = $n_var_input + $n_var_output + $n_kendala + 1;
		for ($i=1; $i <= $n_kendala; $i++) {
			if ($i == $n_kendala) {
			 	$fungsi_tujuan['a1'] = 0;
			 } else {
			 	$fungsi_tujuan['s'.$i] = 0;
			 }
		}

		# Pembentukan Fungsi Kendala
		for ($i=0; $i < $n_kendala; $i++) {
			$k = 1;
			if ($i < 1) {
				$fungsi_kendala[$i]['b'] = 'a1';
				$fungsi_kendala[$i]['u0'] = 0;
			} else {
				$fungsi_kendala[$i]['b'] = 's'.$i;
			}
			for ($j=1; $j < $n_kendala; $j++) {
			 	if (!ISSET($fungsi_kendala[$i]['s'.$k])) {
			 		$fungsi_kendala[$i]['s'.$k] = 0;
			 		$k++;
			 		if ((ISSET($fungsi_kendala[$i]['a1'])) AND ($k >= 3)) {
						if ($fungsi_kendala[$i]['a1'] == 1) {
							$fungsi_kendala[$i]['cb'] = $cj['a1'];
						}
			 		}
				} else {
					$fungsi_kendala[$i]['a1'] = 0;
					$k++;
					$fungsi_kendala[$i]['cb'] = 0;
				}
			 }
		}
		
		# Baris Zj
			$zj = array();
			$zj['z'] = 0;
			# Nilai Z
			for ($i=0; $i < $n_kendala; $i++) {
				if ($fungsi_kendala[$i]['b'] !== 'a1') {
				 	$zj['z'] += $fungsi_kendala[$i]['cb'] * $fungsi_kendala[$i]['xb'];
				 }
			}
			# Nilai Var X Jenis Input
			for ($i=1; $i <= $n_var_input; $i++) {
				$zj['w'.$i] = 0;
				for ($j=0; $j < $n_kendala; $j++) { 
					$zj['w'.$i] += $fungsi_kendala[$j]['w'.$i] * $fungsi_kendala[$j]['cb'];
				}
			}
			# Nilai Var X Jenis Output
			for ($i=1; $i <= $n_var_output; $i++) {
				$zj['u'.$i] = 0; 
				for ($j=0; $j < $n_kendala; $j++) { 
					$zj['u'.$i] += $fungsi_kendala[$j]['u'.$i] * $fungsi_kendala[$j]['cb'];
				}
			}
			# Nilai Slack dan Artificial Var
			for ($i=1; $i <= $n_kendala-1; $i++) {
				$zj['s'.$i] = 0; 
				for ($j=0; $j < $n_kendala; $j++) { 
					$zj['s'.$i] += $fungsi_kendala[$j]['s'.$i] * $fungsi_kendala[$j]['cb'];
				}
			}
			$zj['a1'] = $zj['u0'] = 0;
			for ($j=0; $j < $n_kendala; $j++) { 
				$zj['a1'] += $fungsi_kendala[$j]['a1'] * $fungsi_kendala[$j]['cb'];
				$zj['u0'] += $fungsi_kendala[$j]['u0'] * $fungsi_kendala[$j]['cb'];
			}
			
		# Baris Cj-Zj
			# Var W (Input)
			$cj_zj = array();
			for ($i=1; $i <= $n_var_input; $i++) {
				$cj_zj['w'.$i] = 0; 
				$cj_zj['w'.$i] = $cj['w'.$i] - $zj['w'.$i];
			}
			# Var U (Output)
			for ($i=0; $i <= $n_var_output; $i++) { 
				$cj_zj['u'.$i] = 0; 
				$cj_zj['u'.$i] = $cj['u'.$i] - $zj['u'.$i];
			}
			# Var U0, Slack, dan Artificial Var
			$cj_zj['u0'] = 0; 
			$cj_zj['u0'] = $cj['u0'] - $zj['u0'];
			for ($i=1; $i <= $n_kendala-1; $i++) { 
				$cj_zj['s'.$i] = 0; 
				$cj_zj['s'.$i] = $cj['s'.$i] - $zj['s'.$i];
			}
			$cj_zj['a1'] = 0; 
			$cj_zj['a1'] = $cj['a1'] - $zj['a1'];

		$tabel_simplex_bigm = array($fungsi_tujuan, $fungsi_kendala, $cj, $m, $zj, $cj_zj);
		return $tabel_simplex_bigm;
	}

	/**
	 * Fungsi Perhitungan Simplex (Big M)
	 * @param  [array]   $fungsi_tujuan  [array assoc 2 dimensi]
	 * @param  [array]   $fungsi_kendala [array assoc 2 dimensi]
	 * @param  [array]   $cj             [array assoc]
	 * @param  [integer] $m              [nilai var m]
	 * @param  [array]   $zj             [array assoc 2 dimensi]
	 * @param  [array]   $cj_zj          [array assoc 2 dimensi]
	 * @return [integer] $hasil_simplex  [nilai efisiensi BCC]
	 */
	function simplex_bigm($fungsi_tujuan, $fungsi_kendala, $cj, $m, $zj, $cj_zj, $n_var_input, $n_var_output){
		# Mengecek Kondisi Optimal (Terminasi)
		# Iterasi Berhenti Ketika Cj - Zj <= 0
		$n_kendala = count($fungsi_kendala);
		$max = max($cj_zj);
			if ($max > 0) {
			// Pencegahan Infinite Loop Pada $max Karena Nilai
			// Tidak berubah dan Masih Dalam Range 0-1
				$max = round($max,1);
				if ($max > 0) {
					$max = round($max);
					if ($max > 0) {
						$terminasi = 1; // False
					} else {
						$terminasi = 0; // True
					}
				}
			} else {
				$terminasi = 0; // True
			}
		$iterasi = 1;
		
		while ($terminasi == 1) {
		// Jika Kondisi Optimal Belum Tercapai
		// Dengan Kata Lain Tidak Memenuhi Cj - Zj <= 0
		// Lanjut Iterasi
			# Mencari Pivot Kolom
			$pivot_kolom = 0;
				# Var Input (W)
				for ($i=1; $i <= $n_var_input; $i++) { 
					if (($cj_zj['w'.$i] >= 0) AND ($cj_zj['w'.$i] > $pivot_kolom)) {
						$pivot_kolom = $cj_zj['w'.$i];
					}
				}
				# Var Output (U)
				for ($i=1; $i <= $n_var_output; $i++) { 
					if (($cj_zj['u'.$i] >= 0) AND ($cj_zj['u'.$i] > $pivot_kolom)) {
						$pivot_kolom = $cj_zj['u'.$i];
					}
				}
				# Index Pivot Kolom
				$index_pivot_kolom = array_search($pivot_kolom, $cj_zj);

			# Mencari Pivot Baris
				# Pembagian Rasio Positif Terkecil (>= 0)
				$pivot_baris = 100;
				for ($i=0; $i < $n_kendala; $i++) { 
					if ($fungsi_kendala[$i][$index_pivot_kolom] > 0) {
						$rasio = $fungsi_kendala[$i]['xb'] / $fungsi_kendala[$i][$index_pivot_kolom];
						if (($rasio >= 0) AND ($rasio <= $pivot_baris)) {
							$pivot_baris = $rasio;
							$index_pivot_baris = $i; 
							// Index Dimulai Dari 0-3 (Jika Banyak Kendalanya ada 4)
							// Karena Index $fungsi_kendala Dimulai Dari 0
						}
					}
				}

			# Menghitung Baris Baru
				# Baris Pivot
					# Var Input (W)
					for ($i=1; $i <= $n_var_input; $i++) { 
						$f_kendala_baru[$index_pivot_baris]['w'.$i] = $fungsi_kendala[$index_pivot_baris]['w'.$i] / $fungsi_kendala[$index_pivot_baris][$index_pivot_kolom];
					}
					# Var Output(U)
					for ($i=1; $i <= $n_var_output; $i++) { 
						$f_kendala_baru[$index_pivot_baris]['u'.$i] = $fungsi_kendala[$index_pivot_baris]['u'.$i] / $fungsi_kendala[$index_pivot_baris][$index_pivot_kolom];
					}
					# Var U0, Slack, Artificial, Xb, dan Cb
					$f_kendala_baru[$index_pivot_baris]['u0'] = $fungsi_kendala[$index_pivot_baris]['u0'] / $fungsi_kendala[$index_pivot_baris][$index_pivot_kolom];
					for ($i=1; $i < $n_kendala; $i++) { 
						$f_kendala_baru[$index_pivot_baris]['s'.$i] = $fungsi_kendala[$index_pivot_baris]['s'.$i] / $fungsi_kendala[$index_pivot_baris][$index_pivot_kolom];
					}
					$f_kendala_baru[$index_pivot_baris]['a1'] = $fungsi_kendala[$index_pivot_baris]['a1'] / $fungsi_kendala[$index_pivot_baris][$index_pivot_kolom];
					$f_kendala_baru[$index_pivot_baris]['xb'] = $fungsi_kendala[$index_pivot_baris]['xb'] / $fungsi_kendala[$index_pivot_baris][$index_pivot_kolom];
					$f_kendala_baru[$index_pivot_baris]['b'] = $index_pivot_kolom;
					$f_kendala_baru[$index_pivot_baris]['cb'] = $cj[$index_pivot_kolom];

				# Selain Baris Pivot
				for ($i=0; $i < $n_kendala; $i++) {
					if ($i !== $index_pivot_baris) {
						# Var Input (W)
						for ($j=1; $j <= $n_var_input; $j++) { 
						 	$f_kendala_baru[$i]['w'.$j] = $fungsi_kendala[$i]['w'.$j] + (-$fungsi_kendala[$i][$index_pivot_kolom] * $f_kendala_baru[$index_pivot_baris]['w'.$j]);
						}
						# Var Output(U)
						for ($j=1; $j <= $n_var_output; $j++) { 
						 	$f_kendala_baru[$i]['u'.$j] = $fungsi_kendala[$i]['u'.$j] + (-$fungsi_kendala[$i][$index_pivot_kolom] * $f_kendala_baru[$index_pivot_baris]['u'.$j]);
						}
						# Var U0, Slack, Artificial, Xb, B (Basis), dan Cb
						$f_kendala_baru[$i]['u0'] = $fungsi_kendala[$i]['u0'] + (-$fungsi_kendala[$i][$index_pivot_kolom] * $f_kendala_baru[$index_pivot_baris]['u0']);
						for ($j=1; $j < $n_kendala; $j++) { 
						 	$f_kendala_baru[$i]['s'.$j] = $fungsi_kendala[$i]['s'.$j] + (-$fungsi_kendala[$i][$index_pivot_kolom] * $f_kendala_baru[$index_pivot_baris]['s'.$j]);
						 }
						 $f_kendala_baru[$i]['a1'] = $fungsi_kendala[$i]['a1'] + (-$fungsi_kendala[$i][$index_pivot_kolom] * $f_kendala_baru[$index_pivot_baris]['a1']);
						 $f_kendala_baru[$i]['xb'] = $fungsi_kendala[$i]['xb'] + (-$fungsi_kendala[$i][$index_pivot_kolom] * $f_kendala_baru[$index_pivot_baris]['xb']);
						$f_kendala_baru[$i]['b'] = $fungsi_kendala[$i]['b'];
						$basis = $f_kendala_baru[$i]['b'];
						$f_kendala_baru[$i]['cb'] = $cj[$basis];
					} 	
				}

				# Baris Zj
				unset($zj);
				$zj = array();
				$zj['z'] = 0;
					# Nilai Z
					for ($i=0; $i < $n_kendala; $i++) {
						if ($f_kendala_baru[$i]['b'] !== 'a1') {
							$zj['z'] += $f_kendala_baru[$i]['cb'] * $f_kendala_baru[$i]['xb'];
						}
					}
					# Nilai Var X Jenis Input
					for ($i=1; $i <= $n_var_input; $i++) {
						$zj['w'.$i] = 0;
						for ($j=0; $j < $n_kendala; $j++) { 
							$zj['w'.$i] += $f_kendala_baru[$j]['w'.$i] * $f_kendala_baru[$j]['cb'];
						}
					}
					# Nilai Var X Jenis Output
					for ($i=1; $i <= $n_var_output; $i++) {
						$zj['u'.$i] = 0; 
						for ($j=0; $j < $n_kendala; $j++) { 
							$zj['u'.$i] += $f_kendala_baru[$j]['u'.$i] * $f_kendala_baru[$j]['cb'];
						}
					}
					# Nilai Slack dan Artificial Var
					for ($i=1; $i <= $n_kendala-1; $i++) {
						$zj['s'.$i] = 0; 
						for ($j=0; $j < $n_kendala; $j++) { 
							$zj['s'.$i] += $f_kendala_baru[$j]['s'.$i] * $f_kendala_baru[$j]['cb'];
						}
					}
					$zj['a1'] = $zj['u0'] = 0;
					for ($j=0; $j < $n_kendala; $j++) { 
						$zj['a1'] += $f_kendala_baru[$j]['a1'] * $f_kendala_baru[$j]['cb'];
						$zj['u0'] += $f_kendala_baru[$j]['u0'] * $f_kendala_baru[$j]['cb'];
					}
						
				# Baris Cj-Zj
				unset($cj_zj);
					# Var W (Input)
					$cj_zj = array();
					for ($i=1; $i <= $n_var_input; $i++) {
						$cj_zj['w'.$i] = 0; 
						$cj_zj['w'.$i] = $cj['w'.$i] - $zj['w'.$i];
					}
					# Var U (Output)
					for ($i=1; $i <= $n_var_output; $i++) { 
						$cj_zj['u'.$i] = 0; 
						$cj_zj['u'.$i] = $cj['u'.$i] - $zj['u'.$i];
					}
					# Var U0, Slack, dan Artificial Var
					$cj_zj['u0'] = 0; 
					$cj_zj['u0'] = $cj['u0'] - $zj['u0'];
					for ($i=1; $i <= $n_kendala-1; $i++) { 
						$cj_zj['s'.$i] = 0; 
						$cj_zj['s'.$i] = $cj['s'.$i] - $zj['s'.$i];
					}
					$cj_zj['a1'] = 0; 
					$cj_zj['a1'] = $cj['a1'] - $zj['a1'];	

				$iterasi++;
				$hasil_simplex = $zj['z'];
				$fungsi_kendala = $f_kendala_baru;
				
				# Mengecek Kondisi Optimal (Terminasi)
				# Iterasi Berhenti Ketika Cj - Zj <= 0
				$max = max($cj_zj);
				if ($max > 0) {
					// Pencegahan Infinite Loop Pada $max Karena Nilai
					// Tidak berubah dan Masih Dalam Range 0-1
					$max = round($max,1);
					if ($max > 0) {
						$max = round($max);
						if ($max > 0) {
							$terminasi = 1; // False
						} else {
							$terminasi = 0; // True
						}
					}
				} else {
					$terminasi = 0; // True
				}
		} 
		// Kondisi Optimal Telah Tercapai
		// Dengan Kata Lain Cj - Zj <= 0
		return $hasil_simplex;	
	}

	/**
	 * Fungsi Inisialisasi Tabel Dual Simplex
	 * @param  [array]   $fungsi_ccr_z       [array assoc 2 dimensi]
	 * @param  [array]   $fungsi_ccr_kendala [array assoc 2 dimensi]
	 * @param  [integer] $n_var_input        [jumlah var input]
	 * @param  [integer] $n_dmu              [jumlah dmu]
	 * @return [array]   $tabel_dual_simplex [array assoc 3 dimensi]
	 */
	function tabel_dual_simplex($fungsi_ccr_z, $fungsi_ccr_kendala, $n_var_input, $n_dmu) {
		# Menjumlahkan Fungsi Z
		$f_ccr_z = array();
		for ($i=1; $i <= $n_dmu; $i++) {
			$f_ccr_z[0]['x'.$i] = 0;
		}
		$f_ccr_z[0]['z'] = 0;
		$f_ccr_z[0]['notasi'] = $fungsi_ccr_z[0]['notasi'];
		$f_ccr_z[0]['ruas_kanan'] = $fungsi_ccr_z[0]['ruas_kanan'];
		for ($i=0; $i < $n_var_input; $i++) {
			$f_ccr_z[0]['z'] += $fungsi_ccr_z[$i]['z']; 
			for ($j=1; $j <= $n_dmu; $j++) { 
				$f_ccr_z[0]['x'.$j] += $fungsi_ccr_z[$i]['x'.$j];
			}
		}
		# Membagi Koefisien Fungsi Z dengan Koefisien Var Z
		# Sekaligus Mengkalikan Fungsi Z dengan -1
		for ($j=1; $j <= $n_dmu; $j++) { 
			$f_ccr_z[0]['x'.$j] = $f_ccr_z[0]['x'.$j] / abs($f_ccr_z[0]['z']);
			$f_ccr_z[0]['x'.$j] = $f_ccr_z[0]['x'.$j] * -1;
		}
		$f_ccr_z[0]['z'] = $f_ccr_z[0]['z'] / abs($f_ccr_z[0]['z']);
		
		# Pindah Ruas Z
		# Menambahkan Var Slack pada Fungsi Z
		$f_ccr_z[0]['ruas_kanan'] = 0;
		$f_ccr_z[0]['b'] = 'z';
		unset($f_ccr_z[0]['z']);
		$n_kendala_ccr = count($fungsi_ccr_kendala);
		for ($i=1; $i <= $n_kendala_ccr; $i++) { 
			$f_ccr_z[0]['s'.$i] = 0;
		}
		
		# Mengubah Notasi Fungsi Kendala dari >= Menjadi <= dengan Mengkalikan -1
		# Menambahkan Var Slack
		for ($i=0; $i < $n_kendala_ccr; $i++) { 
			for ($j=1; $j <= $n_dmu; $j++) { 
				$fungsi_ccr_kendala[$i]['x'.$j] *= -1;
			}
			$fungsi_ccr_kendala[$i]['notasi'] = '=';
			for ($k=1; $k <= $n_kendala_ccr; $k++) {
				if (($i+1) == $k) {
					$fungsi_ccr_kendala[$i]['s'.$k] = 1;
					$fungsi_ccr_kendala[$i]['b'] = 's'.$k;
				} else {
					$fungsi_ccr_kendala[$i]['s'.$k] = 0;
				}				
			}
			$fungsi_ccr_kendala[$i]['ruas_kanan'] = -$fungsi_ccr_kendala[$i]['ruas_kanan'];
		}
		$tabel_dual_simplex = array($f_ccr_z, $fungsi_ccr_kendala);
		return $tabel_dual_simplex;
	}
	
	/**
	 * Fungsi Perhitungan Dual Simplex
	 * @param  [array]   $tabel_ccr [array assoc 3 dimensi]
	 * @param  [integer] $n_dmu     [integer]
	 * @return [array] 	 $hasil     [array assoc 3 dimensi]
	 */
	function dual_simplex($tabel_ccr, $n_dmu) {
		$f_ccr_z = $tabel_ccr[0];
		$n_kendala_ccr = count($tabel_ccr[1]);
		$fungsi_ccr_kendala = array();
		for ($i=0; $i < $n_kendala_ccr; $i++) { 
			$fungsi_ccr_kendala[$i] = $tabel_ccr[1][$i];
		}
		
		# Mengecek Kondisi Terminasi
		# Ketika Semua Kolom Ruas Kanan >= 0 (Positif)
		$min = 100;
		for ($i=0; $i < $n_kendala_ccr; $i++) { 
			if ($min > $fungsi_ccr_kendala[$i]['ruas_kanan']) {
				$min = $fungsi_ccr_kendala[$i]['ruas_kanan'];
			}
		}
		if ($min > $f_ccr_z[0]['ruas_kanan']) {
			$min = $f_ccr_z[0]['ruas_kanan'];
		} 
		if ($min >= 0) {
			$terminasi_ccr = 0; // True
		} else {
			$terminasi_ccr = 1; // False
		}

		$iter = 0;
		# Perulangan Utama Dual Simplex
		while ($terminasi_ccr !== 0) {
			$iter++;
			# Perhitungan Iterasi
				# Mencari Pivot Baris
				$pivot_baris_ccr = 100;
				for ($i=0; $i < $n_kendala_ccr; $i++) { 
					if ($fungsi_ccr_kendala[$i]['ruas_kanan'] <= $pivot_baris_ccr) {
						$pivot_baris_ccr = $fungsi_ccr_kendala[$i]['ruas_kanan'];
						$index_pivot_baris_ccr = $i;
					}
				}
				# Mencari Pivot Kolom
				$pivot_kolom_ccr = 100;
				$rasio_ccr = 0;
				$index_pivot_kolom_ccr = '';
					# Non Basis Variabel
					for ($j=1; $j <= $n_dmu; $j++) { 
						if ($fungsi_ccr_kendala[$index_pivot_baris_ccr]['x'.$j] != 0) {
							$rasio_ccr = $f_ccr_z[0]['x'.$j] / $fungsi_ccr_kendala[$index_pivot_baris_ccr]['x'.$j];
							if (($rasio_ccr < $pivot_kolom_ccr) AND ($rasio_ccr > 0)) {
								$pivot_kolom_ccr = $rasio_ccr;
								$index_pivot_kolom_ccr = 'x'.$j;
							}
						}
					}
					# Basis Variabel
					for ($k=1; $k <= $n_kendala_ccr; $k++) { 
						if ($fungsi_ccr_kendala[$index_pivot_baris_ccr]['s'.$k] != 0) {
							$rasio_ccr = $f_ccr_z[0]['s'.$k] / $fungsi_ccr_kendala[$index_pivot_baris_ccr]['s'.$k];
							if (($rasio_ccr < $pivot_kolom_ccr) AND ($rasio_ccr > 0)) {
								$pivot_kolom_ccr = $rasio_ccr;
								$index_pivot_kolom_ccr = 's'.$k;
							}
						}
					}
				
				# Menghitung Baris Baru
					if (!ISSET($f_ccr_kendala)) {
						$f_ccr_kendala = array();
					}
					# Baris Yang Berperan Sebagai Pivot Baris
					for ($i=1; $i <= $n_dmu; $i++) { 
						$f_ccr_kendala[$index_pivot_baris_ccr]['x'.$i] = $fungsi_ccr_kendala[$index_pivot_baris_ccr]['x'.$i] / $fungsi_ccr_kendala[$index_pivot_baris_ccr][$index_pivot_kolom_ccr];
					}
					for ($j=1; $j <= $n_kendala_ccr; $j++) { 
						$f_ccr_kendala[$index_pivot_baris_ccr]['s'.$j] = $fungsi_ccr_kendala[$index_pivot_baris_ccr]['s'.$j] / $fungsi_ccr_kendala[$index_pivot_baris_ccr][$index_pivot_kolom_ccr];
					}
					$f_ccr_kendala[$index_pivot_baris_ccr]['ruas_kanan'] = $fungsi_ccr_kendala[$index_pivot_baris_ccr]['ruas_kanan'] / $fungsi_ccr_kendala[$index_pivot_baris_ccr][$index_pivot_kolom_ccr];
					$f_ccr_kendala[$index_pivot_baris_ccr]['b'] = $index_pivot_kolom_ccr;
					# Selain Pivot Baris
					if (!ISSET($f_ccr_z_baru)) {
						$f_ccr_z_baru = array();
					}
					for ($i=0; $i < $n_kendala_ccr; $i++) { 
						for ($j=1; $j <= $n_dmu; $j++) {
							if ($i !== $index_pivot_baris_ccr) {
							 	$f_ccr_kendala[$i]['x'.$j] = $fungsi_ccr_kendala[$i]['x'.$j] + (-$fungsi_ccr_kendala[$i][$index_pivot_kolom_ccr] * $f_ccr_kendala[$index_pivot_baris_ccr]['x'.$j]);
							}
							$f_ccr_z_baru[0]['x'.$j] = $f_ccr_z[0]['x'.$j] + (-$f_ccr_z[0][$index_pivot_kolom_ccr] * $f_ccr_kendala[$index_pivot_baris_ccr]['x'.$j]);
						}
						for ($k=1; $k <= $n_kendala_ccr; $k++) { 
							if ($i !== $index_pivot_baris_ccr) {
								$f_ccr_kendala[$i]['s'.$k] = $fungsi_ccr_kendala[$i]['s'.$k] + (-$fungsi_ccr_kendala[$i][$index_pivot_kolom_ccr] * $f_ccr_kendala[$index_pivot_baris_ccr]['s'.$k]);
							}
						}
						if ($i !== $index_pivot_baris_ccr) {
							$f_ccr_kendala[$i]['ruas_kanan'] = $fungsi_ccr_kendala[$i]['ruas_kanan'] + (-$fungsi_ccr_kendala[$i][$index_pivot_kolom_ccr] * $f_ccr_kendala[$index_pivot_baris_ccr]['ruas_kanan']);
							$f_ccr_kendala[$i]['b'] = $fungsi_ccr_kendala[$i]['b'];
						}
						$f_ccr_z_baru[0]['ruas_kanan'] = $f_ccr_z[0]['ruas_kanan'] + (-$f_ccr_z[0][$index_pivot_kolom_ccr] * $f_ccr_kendala[$index_pivot_baris_ccr]['ruas_kanan']);
					}
					for ($j=1; $j <= $n_kendala_ccr; $j++) { 
						$f_ccr_z_baru[0]['s'.$j] = $f_ccr_z[0]['s'.$j] + (-$f_ccr_z[0][$index_pivot_kolom_ccr] * $f_ccr_kendala[$index_pivot_baris_ccr]['s'.$j]);
					}

				$hasil = array($f_ccr_kendala, $f_ccr_z_baru);
				$fungsi_ccr_kendala = $f_ccr_kendala; 
				unset($f_ccr_kendala);
				$f_ccr_z = $f_ccr_z_baru; 
				unset($f_ccr_z_baru);

				# Mengecek Kondisi Terminasi
				# Ketika Semua Kolom Ruas Kanan >= 0 (Positif)
				$min = 100;
				for ($i=0; $i < $n_kendala_ccr; $i++) { 
					if ($min > $fungsi_ccr_kendala[$i]['ruas_kanan']) {
						$min = $fungsi_ccr_kendala[$i]['ruas_kanan'];
					}
				}
				if ($min > $f_ccr_z[0]['ruas_kanan']) {
					$min = $f_ccr_z[0]['ruas_kanan'];
				} 
				if ($min >= 0) {
					$terminasi_ccr = 0; // True
				} else {
					$terminasi_ccr = 1; // False
				}
		} /* End of While */
		return $hasil;
	}

	/**
	 * Menghitung Nilai Rekomendasi
	 * @param  [array] 	 $hasil_dual_simplex [array assoc 3 dimensi]
	 * @param  [integer] $n_dmu              [jumlah dmu]
	 * @param  [array]   $fungsi_ccr_z_awal  [array assoc 2 dimensi]
	 * @param  [integer] $n_var_input        [jumlah var input]
	 * @return [array] 	 $rekomendasi        [array assoc 2 dimensi]
	 */
	function rekomendasi($hasil_dual_simplex, $n_dmu, $fungsi_ccr_z_awal, $n_var_input) {
		$fungsi_ccr_z = $hasil_dual_simplex[1];
		$fungsi_ccr_kendala = $hasil_dual_simplex[0];
		$n_kendala_ccr = count($fungsi_ccr_kendala);
		$data_awal = $rekomendasi = array();

		for ($i=0; $i < $n_var_input; $i++) {
			$y = $i+1;
			for ($j=1; $j <= $n_dmu; $j++) { 
				$data_awal['dmu'.$j]['x'.$y] = $fungsi_ccr_z_awal[$i]['x'.$j];
			}
		}
		$benchmark = 0;
		for ($i=0; $i < $n_kendala_ccr; $i++) {
			for ($j=1; $j <= $n_dmu; $j++) { 
			 	if ($fungsi_ccr_kendala[$i]['b'] == 'x'.$j) {
			 		$benchmark++;
			 		if ($benchmark > 1) {
			 			# DMU yang menjadi Benchmark ada > 1
			 			for ($k=1; $k <= $n_var_input; $k++) { 
				 			$rekomendasi[0]['x'.$k] += round($fungsi_ccr_kendala[$i]['ruas_kanan'] * $data_awal['dmu'.$j]['x'.$k]);
				 		}
			 		} else {
			 			# DMU yang menjadi Benchmark hanya ada 1
			 			for ($k=1; $k <= $n_var_input; $k++) { 
				 			$rekomendasi[0]['x'.$k] = round($fungsi_ccr_kendala[$i]['ruas_kanan'] * $data_awal['dmu'.$j]['x'.$k]);
				 		}
			 		}
				}
			}
		}
		return $rekomendasi;
	}

	header('Location: ../beranda.php');
?>