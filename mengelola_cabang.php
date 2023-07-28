<?php include 'layout.php'; ?>
<div class="konten">
	<div class="col-sm-9">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-duplicate"></span> Mengelola Prodi</h3>
			</div>
			<div class="panel-body">
				<br>
				<div class="dataTable_wrapper">
					<legend>Daftar Prodi</legend>
					<a href="tambah_cabang.php" class="btn btn-sm btn-success" style="float: right;"><i class="fa fa-plus"></i> Tambah</a>
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

					# Menampilkan Data Tabel
					$i = 1;
					$query = mysqli_query($conn, "SELECT * FROM prodi ORDER BY id_prodi DESC");
					if (mysqli_num_rows($query) > 0) {
						echo '
										        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
													<thead>
														<tr>
															<th style="text-align: center;">No</th>
															<th style="text-align: center;">Nama Prodi</th>
															<th style="text-align: center;">Keterangan</th>
															<th style="text-align: center;">Aksi</th>
														</tr>
													</thead>
													<tbody>';
						while ($cabang = mysqli_fetch_assoc($query)) {
							echo '		<tr>
															<td>' . $i . '</td>
															<td>' . $cabang['cabang_prodi'] . '</td>
															<td>' . $cabang['alamat'] . '</td>
															<td>
																<a href="ubah_cabang.php?id=' . $cabang['id_prodi'] . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit"></span></a>
																<a href="process/hapus_cabang.php?id=' . $cabang['id_prodi'] . '" onclick="return hapus()" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></a>
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
  												<Strong>Data masih kosong</strong>. Silahkan tambah data cabang.
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