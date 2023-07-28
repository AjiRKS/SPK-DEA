<?php include 'layout.php'; ?>
<div class="konten">
	<div class="col-sm-9">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-tower"></span> Mengelola Data DMU</h3>
			</div>
			<div class="panel-body">
				<div class="dataTable_wrapper">
					<legend>Daftar DMU</legend>
					<br><br><br>
					<?php
					if (isset($_GET['balasan']) and ($_GET['balasan'] == 1)) {
						echo '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-ok"></span> Data berhasil ditambahkan</div>';
					} elseif (isset($_GET['balasan']) and ($_GET['balasan'] == 2)) {
						echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-exclamation-sign"></span> Kesalahan telah terjadi</div>';
					} elseif (isset($_GET['balasan']) and ($_GET['balasan'] == 3)) {
						echo '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-ok"></span> Data berhasil dihapus</div>';
					} elseif (isset($_GET['balasan']) and ($_GET['balasan'] == 4)) {
						echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-exclamation-sign"></span> Gagal menghapus data</div>';
					} elseif (isset($_GET['balasan']) and ($_GET['balasan'] == 5)) {
						echo '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-ok"></span> Data berhasil diubah</div>';
					} elseif (isset($_GET['balasan']) and ($_GET['balasan'] == 6)) {
						echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-exclamation-sign"></span> Gagal mengubah data</div>';
					}

					// Menghitung jumlah var input dan output
					$query = mysqli_query($conn, "SELECT * FROM variabel ORDER BY jenis_variabel ASC, id_variabel ASC");
					$list_var = '';
					$input = 0;
					$output = 0;
					if (mysqli_num_rows($query) > 0) {
						while ($var = mysqli_fetch_assoc($query)) {
							if ($var['jenis_variabel'] == 'i') {
								$input++;
							} else {
								$output++;
							}
							$nama = str_replace('_', ' ', $var['nama_variabel']);
							$list_var .= "<th>$nama</th>";
						}
					}

					# Menampilkan Data Tabel
					$i = 1;
					$query = mysqli_query($conn, 'SELECT k.cabang_prodi, d.id_prodi FROM prodi AS k, detail_dmu AS d WHERE k.id_prodi=d.id_prodi AND d.id_prodi="' . $_SESSION['id_prodi'] . '" GROUP BY id_prodi ORDER BY id_detail_dmu');
					if (mysqli_num_rows($query) > 0) {
						echo '
											<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
												<thead>
													<tr>
														<th rowspan="2">No</th>
														<th rowspan="2">DMU</th>
														<th colspan="' . $input . '">Input</th>
														<th colspan="' . $output . '">Output</th>
														<th rowspan="2">Aksi</th>
													</tr>
													<tr>
														' . $list_var . '
													</tr>
												</thead>
												<tbody>
										';
						while ($cabang = mysqli_fetch_assoc($query)) {
							echo '
												<tr>
													<td>' . $i . '</td>
													<td>' . $cabang["cabang_prodi"] . '</td>
											';
							$id_prodi = $cabang['id_prodi'];

							// Variabel Input
							$query_input = mysqli_query($conn, 'SELECT d.nilai_variabel FROM detail_dmu AS d, variabel AS v WHERE d.id_variabel=v.id_variabel AND id_prodi=' . $id_prodi . ' AND v.jenis_variabel="i" ORDER BY v.jenis_variabel ASC, d.id_variabel');
							$count = 0;
							if (mysqli_num_rows($query_input)) {
								while ($nilai_var = mysqli_fetch_assoc($query_input)) {
									echo '<td>' . $nilai_var["nilai_variabel"] . '</td>';
									$count++;
								}
							}
							if ($count < $input) {
								for ($k = $count; $k < $input; $k++) {
									echo '<td></td>';
								}
							}

							// Variabel Output
							$query_output = mysqli_query($conn, 'SELECT d.nilai_variabel FROM detail_dmu AS d, variabel AS v WHERE d.id_variabel=v.id_variabel AND id_prodi=' . $id_prodi . ' AND v.jenis_variabel="o" ORDER BY v.jenis_variabel ASC, d.id_variabel');
							$count = 0;
							if (mysqli_num_rows($query_output)) {
								while ($nilai_var = mysqli_fetch_assoc($query_output)) {
									echo '<td>' . $nilai_var["nilai_variabel"] . '</td>';
									$count++;
								}
							}
							if ($count < $output) {
								for ($j = $count; $j < $output; $j++) {
									echo '<td></td>';
								}
							}

							echo '
													<td>
														<a href="ubah_dmu.php?id=' . $id_prodi . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit"></span></a>
														<a href="process/hapus_dmu.php?id=' . $id_prodi . '" onclick="return hapus()" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></a>
											        </td>
												</tr>
											';
							$i++;
						}
						$q = mysqli_query($conn, 'SELECT id_prodi FROM detail_dmu GROUP BY id_prodi');
						if (mysqli_num_rows($q) == 1) {
							echo '
													</tbody>
												</table>
											<button class="btn btn-info" type="button" disabled>Hitung Efisiensi</button>
											';
						} else {
							echo '
													</tbody>
												</table>
											<a href="process/simplex.php" class="btn btn-info" type="button">Hitung Efisiensi</a>
											';
						}
					} else {
						echo '
											<div class="alert alert-dismissible alert-warning">
		  										<button type="button" class="close" data-dismiss="alert">&times;</button>
		  										<Strong>Data masih kosong</strong>. Silahkan tambah data DMU.
												  <a href="tambah_dmu.php" class="btn btn-sm btn-success" style="float: right;"><i class="fa fa-plus"></i> Tambah</a><br><br>
											</div>
										';
					}
					?>
				</div>
			</div>
		</div>
	</div> <!-- End of Main Content (Second col-sm-9) -->
</div>
<?php include 'layout2.php' ?>