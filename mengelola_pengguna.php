<?php include 'layout.php'; ?>
<div class="konten">
	<div class="col-sm-9">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?php
				if ($level == 'a') {
					# Superadmin
					echo '<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mengelola Admin Prodi</h3>';
				} elseif ($level == 'c') {
					# Admin Cabang
					echo '<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mengelola Akun Pengurus Prodi</h3>';
				}
				?>
			</div>
			<div class="panel-body">
				<div class="dataTable_wrapper">
					<?php
					if ($level == 'a') {
						# Superadmin
						echo '<br><legend>Daftar Admin Prodi</legend>';
						echo '				<a href="tambah_pengguna.php" class="btn btn-sm btn-success" style="float: right;"><i class="fa fa-plus"></i> Tambah</a>
						<br><br><br </div>';
					} elseif ($level == 'c') {
						# Admin Cabang
						echo '<br><legend>Daftar Akun Pengurus Prodi</legend>';
						// Mendapatkan cabang prodi
						$q = mysqli_query($conn, 'SELECT * FROM pengguna WHERE id_pengguna="' . $id . '"');
						$d = mysqli_fetch_assoc($q);
						$cabang_prodi = $d['id_prodi'];
						echo '				<a href="tambah_pengguna.php" class="btn btn-sm btn-success" style="float: right;"><i class="fa fa-plus"></i> Tambah</a>
						<br><br><br </div>';
					}

					# Notifikasi
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

					# Menampilkan Data Tabel
					$i = 1;
					if ($level == 'a') {
						# Login sebagai Superadmin
						$query = mysqli_query($conn, "SELECT * FROM pengguna AS p, prodi AS k WHERE p.id_prodi = k.id_prodi AND p.level = 'c' ORDER BY p.id_pengguna DESC");
					} else {
						# Login sebagai Admin Cabang
						$query = mysqli_query($conn, 'SELECT * FROM pengguna AS p, prodi AS k WHERE p.id_prodi = k.id_prodi AND p.level = "m" AND p.id_prodi = "' . $cabang_prodi . '" ORDER BY p.id_pengguna DESC');
					}

					if (mysqli_num_rows($query) > 0) {
						echo '
										<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
											<thead>
												<tr>
													<th style="text-align: center;">No</th>
													<th style="text-align: center;">Nama</th>
													<th style="text-align: center;">Username</th>
													<th style="text-align: center;">Nama Prodi</th>
													<th style="text-align: center;">Aksi</th>
												</tr>
											</thead>
											<tbody>
									';
						while ($pengguna = mysqli_fetch_assoc($query)) {
							// Query untuk mengkonversi id_prodi menjadi nama cabangnya
							echo '
											<tr>
												<td>' . $i . '</td>
												<td>' . $pengguna['nama'] . '</td>
												<td>' . $pengguna['username'] . '</td>
												<td>' . $pengguna['cabang_prodi'] . '</td>
												<td>
													<a href="ubah_pengguna.php?type=ubah&id=' . $pengguna['id_pengguna'] . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit"></span></a>
													<a href="process/hapus_pengguna.php?id=' . $pengguna['id_pengguna'] . '" onclick="return hapus()" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></a>
											       </td>
											</tr>
										';
							$i++;
						}
						echo '
											</tbody>
												</table>
									';
					} else {
						echo '
											<div class="alert alert-dismissible alert-warning">
		  										<button type="button" class="close" data-dismiss="alert">&times;</button>
		  										<Strong>Data masih kosong</strong>. Silahkan tambah data pengguna.
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