<?php include 'layout.php'; ?>
<div class="konten">
	<div class="col-sm-9">
		<?php
		if (isset($_GET['balasan']) and ($_GET['balasan'] == 1)) {
			echo '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-ok"></span> <strong>Profil</strong> berhasil diubah</div>';
		} elseif (isset($_GET['balasan']) and ($_GET['balasan'] == 2)) {
			echo '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-ok"></span> Perhitungan efisiensi berhasil</div>';
		}
		?>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span class="glyphicon glyphicon-home"></span> Beranda
			</div>
			<div class="panel-body">
				<?php
				if ($level == 'a') {
					echo '<h1 align="center">Sistem Pengukuran Efisiensi prodi</h1><hr />';
					echo '
										<h4 id="deskripsi">Sistem Pengukuran Efisiensi prodi merupakan sebuah sistem yang berfungsi untuk menghitung efisiensi kinerja masing-masing prodi. <em>Output</em> dari sistem ini berupa nilai efisiensi serta rekomendasi untuk dapat meningkatkan efisiensi prodi yang dinilai belum efisien. Program studi yang dinilai sudah efisien akan dijadikan sebagai <em>benchmarking</em> bagi program studi lain yang belum efisien melalui rekomendasi yang dihasilkan. Sistem ini dibuat guna membantu Politeknik Bhakti Semesta dalam mengambil keputusan sebagai upaya dalam meningkatkan mutu dan kualitas pelayanan program studi di Politeknik Bhakti Semesta.</h4>
										<br>
									';
				} else {
					echo '<h1 align="center">Hasil Perhitungan Efisiensi dan Rekomendasi</h1><hr />';
				}
				?>

				<br>

				<?php
				if ($level == 'p') {
					echo '<div class="row">';
					# Menghitung Banyak DMU
					$id_dmu = $cabang_prodi = $efisiensi = array();
					$q = mysqli_query($conn, 'SELECT p.id_prodi, k.cabang_prodi, p.nilai_efisiensi FROM perhitungan_efisiensi AS p, prodi AS k WHERE p.id_prodi=k.id_prodi GROUP BY p.id_prodi');
					$n_dmu = mysqli_num_rows($q);
					if ($n_dmu > 0) {
						$index = 0;
						while ($d = mysqli_fetch_assoc($q)) {
							$cabang_prodi[$index] = $d['cabang_prodi'];
							$id_dmu[$index] = $d['id_prodi'];
							$efisiensi[$index] = round($d['nilai_efisiensi'], 3);
							$index++;
						}
						for ($i = 0; $i < $n_dmu; $i++) {
							# Menampilkan Efisiensi dan Tabel Rekomendasi
							$persen = $efisiensi[$i] * 100;
							if ($efisiensi[$i] >= 0.9) {
								$alert = "alert-success";
								$progress = "progress-bar-success";
							} else {
								$alert = "alert-danger";
								$progress = "progress-bar-danger";
							}
							echo '
											  		<div class="col-sm-5">
														<div class="alert alert-dismissible ' . $alert . '">
																<div class="row">
																	<div class="col-sm-9 col-xs-9">
																		<h5 align="left"><strong>' . $cabang_prodi[$i] . '</strong></h5>
																	</div>
																	<div class="col-sm-3 col-xs-3">
																		<h5 align="right"><strong>' . $efisiensi[$i] . '</strong></h5>
																	</div>
																</div>
																<br>
																<div class="row">
																	<div class="col-sm-12">
																		<div class="progress">
																			<div class="progress-bar ' . $progress . '" role="progressbar" aria-valuenow="' . $persen . '" aria-valuemin="0" aria-valuemax="100" style="width:' . $persen . '%">' . $persen . '%
																			</div>
																		</div>
																	</div>
																</div>
														</div>
													</div>
											';
							echo '
											  		<div class="col-sm-7">
														<table class="table table-condensed table-striped table-bordered">
															<thead>
																<tr>
															      	<th>No</th>
															      	<th>Variabel</th>
															      	<th>Nilai Awal</th>
															      	<th>Rekomendasi</th>
															      	<th>Satuan</th>
															    </tr>
															</thead>
															<tbody>
											';
							$q = mysqli_query($conn, 'SELECT * FROM perhitungan_efisiensi AS p, variabel AS v, prodi AS k WHERE p.id_variabel=v.id_variabel AND k.id_prodi=p.id_prodi AND p.id_prodi="' . $id_dmu[$i] . '" ORDER BY v.id_variabel ASC');
							if (mysqli_num_rows($q) > 0) {
								$j = 1;
								while ($data = mysqli_fetch_assoc($q)) {
									$prodi = $data['cabang_prodi'];
									$var = $data['nama_variabel'];
									$nilai_awal = $data['nilai_awal'];
									$rekomendasi = $data['rekomendasi'];
									$satuan = $data['satuan'];
									echo '
													  	<tr>
													  		<td>' . $j . '</td>
													  		<td>' . $var . '</td>
													  		<td>' . $nilai_awal . '</td>
													  		<td>' . $rekomendasi . '</td>
													  		<td>' . $satuan . '</td>
													  	</tr>
													';
									$j++;
								}
							}
							echo '
											  				</tbody>
											  			</table>
											  			<br>
											  			<br>
											  		</div>
											';
						}
					} else {
						echo '
										  		<div class="col-sm-12">
										  			<div class="alert alert-dismissible alert-warning">
  														<button type="button" class="close" data-dismiss="alert">&times;</button>
  														Belum dilakukan perhitungan efisiensi.
													</div>
												</div>
										';
					}
					echo '</div>'; // End of row
				} elseif (($level == 'c') or ($level == 'm')) {
					echo '<div class="row">';
					# Menghitung Banyak DMU
					$id_prodi = $_SESSION["id_prodi"];
					$q = mysqli_query($conn, 'SELECT p.id_prodi, k.cabang_prodi, p.nilai_efisiensi FROM perhitungan_efisiensi AS p, prodi AS k WHERE p.id_prodi=k.id_prodi AND p.id_prodi="' . $id_prodi . '" GROUP BY p.id_prodi');
					if (mysqli_num_rows($q) > 0) {
						$d = mysqli_fetch_assoc($q);
						$cabang_prodi = $d['cabang_prodi'];
						$efisiensi = round($d['nilai_efisiensi'], 3);
						# Menampilkan Efisiensi dan Tabel Rekomendasi
						$persen = $efisiensi * 100;
						if ($efisiensi >= 0.9) {
							$alert = "alert-success";
							$progress = "progress-bar-success";
						} else {
							$alert = "alert-danger";
							$progress = "progress-bar-danger";
						}
						echo '
											  		<div class="col-sm-5">
														<div class="alert alert-dismissible ' . $alert . '">
																<div class="row">
																	<div class="col-sm-9 col-xs-9">
																		<h5 align="left"><strong>' . $cabang_prodi . '</strong></h5>
																	</div>
																	<div class="col-sm-3 col-xs-3">
																		<h5 align="right"><strong>' . $efisiensi . '</strong></h5>
																	</div>
																</div>
																<br>
																<div class="row">
																	<div class="col-sm-12">
																		<div class="progress">
																			<div class="progress-bar ' . $progress . '" role="progressbar" aria-valuenow="' . $persen . '" aria-valuemin="0" aria-valuemax="100" style="width:' . $persen . '%">' . $persen . '%
																			</div>
																		</div>
																	</div>
																</div>
														</div>
													</div>
											';
						echo '
											  	<div class="col-sm-7">
													<table class="table table-condensed table-striped table-bordered">
														<thead>
															<tr>
															    <th>No</th>
															    <th>Variabel</th>
															    <th>Nilai Awal</th>
															    <th>Rekomendasi</th>
															    <th>Satuan</th>
															</tr>
														</thead>
													<tbody>
										';
						$q = mysqli_query($conn, 'SELECT * FROM perhitungan_efisiensi AS p, variabel AS v, prodi AS k WHERE p.id_variabel=v.id_variabel AND k.id_prodi=p.id_prodi AND p.id_prodi="' . $id_prodi . '" ORDER BY p.id_variabel ASC');
						if (mysqli_num_rows($q) > 0) {
							$j = 1;
							while ($data = mysqli_fetch_assoc($q)) {
								$prodi = $data['cabang_prodi'];
								$var = $data['nama_variabel'];
								$nilai_awal = $data['nilai_awal'];
								$rekomendasi = $data['rekomendasi'];
								$satuan = $data['satuan'];
								echo '
													<tr>
													  	<td>' . $j . '</td>
													  	<td>' . $var . '</td>
													  	<td>' . $nilai_awal . '</td>
													  	<td>' . $rekomendasi . '</td>
													  	<td>' . $satuan . '</td>
													</tr>
												';
								$j++;
							}
						}
						echo '
											  			</tbody>
											  		</table>
											  		<br>
											  		<br>
											  	</div>
										';
					} else {
						echo '
										  		<div class="col-sm-12">
										  			<div class="alert alert-dismissible alert-warning">
  														<button type="button" class="close" data-dismiss="alert">&times;</button>
  														Belum dilakukan perhitungan efisiensi.
													</div>
												</div>
										';
					}
					echo '</div>'; // End of row
				}
				?>
			</div>
		</div>
	</div> <!-- End of Main Content (Second col-sm-9) -->
</div>
<?php include 'layout2.php' ?>