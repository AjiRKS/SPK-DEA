<?php include 'layout.php'; ?>
<div class="konten">
	<div class="col-sm-9">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-tower"></span> Mengelola Data DMU</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="process/t_dmu.php">
					<fieldset>
						<legend>Tambah Data DMU</legend>
						<?php
						if (isset($_GET['balasan']) and ($_GET['balasan'] == 1)) {
							echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-exclamation-sign"></span> <strong>DMU</strong> sudah terdaftar. Tidak bisa menambah data lagi</div>';
						}
						?>
						<div class="form-group">
							<label class="col-sm-4 control-label">Nama</label>
							<div class="col-sm-6">
								<select name="id_prodi" class="form-control" disabled required>
									<option value=""> -- Pilih Cabang -- </option>
									<?php
									$query = mysqli_query($conn, 'SELECT * FROM pengguna WHERE id_pengguna="' . $id . '"');
									$data_user = mysqli_fetch_assoc($query);
									$user = $data_user['id_prodi'];
									$query = mysqli_query($conn, 'SELECT * FROM prodi');
									if (mysqli_num_rows($query) > 0) {
										// output data of each row
										while ($cabang = mysqli_fetch_assoc($query)) {
											$id_cabang = $cabang['id_prodi'];
											if ($user == $id_cabang) {
												echo '<option value="' . $id_cabang . '" selected>' . $cabang["cabang_prodi"] . '</option>';
											} else {
												echo '<option value="' . $id_cabang . '">' . $cabang["cabang_prodi"] . '</option>';
											}
										}
									}
									?>
								</select>
							</div>
						</div>
						<?php
						$input = 0;
						$output = 0;
						$query = mysqli_query($conn, "SELECT * FROM variabel ORDER BY jenis_variabel ASC, id_variabel ASC");
						if (mysqli_num_rows($query) > 0) {
							while ($var = mysqli_fetch_assoc($query)) {
								$name = str_replace(' ', '_', $var['nama_variabel']);
								$satuan = $var['satuan'];
								// Pemisah var
								if (($var['jenis_variabel'] == 'i') and ($input == 0)) {
									echo '<div class="form-group"><legend class="col-sm-8 col-sm-offset-2">Variabel Input</legend></div>';
									$input = 1;
								} elseif (($var['jenis_variabel'] == 'o') and ($output == 0)) {
									echo '<div class="form-group"><legend class="col-sm-8 col-sm-offset-2">Variabel Output</legend></div>';
									$output = 1;
								}
								echo '
													<div class="form-group">
												      	<label class="col-sm-4 control-label">' . $var["nama_variabel"] . '</label>
												      	<div class="col-sm-6">
												        	<input class="form-control" name="' . $name . '" type="number" min="1" placeholder="' . $satuan . '" required>
												      	</div>
												    </div>
												';
							}
						}
						?>
						<div class="form-group">
							<div class="col-sm-6 col-sm-offset-4">
								<button type="button" onclick="goBack()" class="btn btn-info">Kembali</button>
								<button type="submit" class="btn btn-success">Simpan</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div> <!-- End of Main Content (Second col-sm-9) -->
</div>
<?php include 'layout2.php' ?>