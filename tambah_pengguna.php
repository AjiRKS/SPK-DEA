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
					echo '<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mengelola Manajer Prodi</h3>';
				}
				?>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="process/t_pengguna.php">
					<fieldset>
						<?php
						if ($level == 'a') {
							# Superadmin
							echo '<legend>Tambah Admin Prodi</legend>';
						} elseif ($level == 'c') {
							# Admin Cabang
							echo '<legend>Tambah Manajer Prodi</legend>';
							// Mendapatkan cabang prodi
							$q = mysqli_query($conn, 'SELECT * FROM pengguna WHERE id_pengguna="' . $id . '"');
							$d = mysqli_fetch_assoc($q);
						}

						# Notifikasi
						if (isset($_GET['balasan']) and ($_GET['balasan'] == 1)) {
							echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-remove"></span> <strong>Username</strong> sudah terdaftar. Silahkan gunakan <strong>username</strong> lain</div>';
						}
						?>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nama</label>
							<div class="col-sm-6">
								<input class="form-control" name="nama_pengguna" placeholder="Panjang maksimal 50 karakter" type="text" maxlength="50" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Username</label>
							<div class="col-sm-6">
								<input class="form-control" name="username" placeholder="Panjang username 5-20 karakter" type="text" minlength="5" maxlength="20" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Password</label>
							<div class="col-sm-6">
								<input class="form-control" name="password" placeholder="Panjang password 5-12 karakter" type="password" minlength="5" maxlength="12" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nama Prodi</label>
							<div class="col-sm-6">
								<select name="cabang_prodi" class="form-control" <?php if ($level == 'c') {
																						echo 'disabled';
																					} ?> required>
									<option value=""> -- Pilih Prodi -- </option>
									<?php
									$query = mysqli_query($conn, "SELECT * FROM prodi");
									if (mysqli_num_rows($query) > 0) {
										// output data of each row
										while ($cabang = mysqli_fetch_assoc($query)) {
											$id_cabang = $cabang['id_prodi'];
											if ($d['id_prodi'] == $id_cabang) {
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
						<div class="form-group">
							<div class="col-sm-6 col-sm-offset-3">
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