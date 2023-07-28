<?php
include 'layout.php';
if (isset($_GET['id'])) {
	$id = $_GET['id'];
}
if (isset($_GET['type'])) {
	$type = $_GET['type'];
}
if (isset($_GET['lvl'])) {
	$lvl = $_GET['lvl'];
	if (($lvl == 'a') or ($lvl == 'p')) {
		# Jika ingin mengubah profil
		$query = mysqli_query($conn, 'SELECT * FROM pengguna_khusus WHERE id_pengguna_khusus="' . $id . '"');
		if (mysqli_num_rows($query) > 0) {
			while ($data = mysqli_fetch_assoc($query)) {
				$nama = $data['nama'];
				$username = $data['username'];
			}
		}
	} else {
		$query = mysqli_query($conn, 'SELECT * FROM pengguna AS p, prodi AS k WHERE p.id_prodi = k.id_prodi AND p.id_pengguna="' . $id . '"');
		if (mysqli_num_rows($query) > 0) {
			$pengguna = mysqli_fetch_assoc($query);
			$nama = $pengguna['nama'];
			$username = $pengguna['username'];
			$id_prodi = $pengguna['id_prodi'];
			$cabang_prodi = $pengguna['cabang_prodi'];
			$lvl = $pengguna['level'];
		}
	}
} else { # Jika yang login Superadmin -> mengelola Admin Cabang atau Admin Cabang -> mengelola Manajer Cabang
	$query = mysqli_query($conn, 'SELECT * FROM pengguna AS p, prodi AS k WHERE p.id_prodi = k.id_prodi AND p.id_pengguna="' . $id . '"');
	if (mysqli_num_rows($query) > 0) {
		$pengguna = mysqli_fetch_assoc($query);
		$nama = $pengguna['nama'];
		$username = $pengguna['username'];
		$id_prodi = $pengguna['id_prodi'];
		$cabang_prodi = $pengguna['cabang_prodi'];
		$lvl = $pengguna['level'];
	}
}
?>
<div class="konten">
	<div class="col-sm-9">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?php
				if ($level == 'a') {
					# Superadmin
					$user = 'Admin Cabang';
					$profil = 'Superadmin';
					if ($type == 'profile') {
						echo '<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mengelola Profil Superadmin</h3>';
					} else {
						echo '<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mengelola Admin Cabang</h3>';
					}
				} elseif ($level == 'c') {
					# Admin Cabang
					$user = 'Manajer Cabang';
					$profil = 'Admin Cabang';
					if ($type == 'profile') {
						echo '<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mengelola Profil Admin Cabang</h3>';
					} else {
						echo '<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mengelola Manajer Cabang</h3>';
					}
				} elseif ($level == 'p') {
					# Manajer Pusat
					$profil = 'Manajer Pusat';
					echo '<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mengelola Profil Manajer Pusat</h3>';
				} else {
					# Manajer Cabang
					$profil = 'Manajer Cabang';
					echo '<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Mengelola Profil Manajer Cabang</h3>';
				}
				?>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="<?php echo "process/u_pengguna.php?type=" . $type . "&id=" . $id . "&lvl=" . $lvl . ""; ?>">
					<fieldset>
						<?php
						if ($type == 'profile') {
							echo '<legend>Profil ' . $profil . '</legend>';
						} else {
							echo '<legend>Ubah ' . $user . '</legend>';
						}
						?>
						<?php
						if (isset($_GET['balasan']) and ($_GET['balasan'] == 1)) {
							echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-remove"></span> <strong>Username</strong> sudah terdaftar. Silahkan gunakan <strong>username</strong> lain</div>';
						} elseif (isset($_GET['balasan']) and ($_GET['balasan'] == 2)) {
							echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-exclamation-sign"></span> Gagal mengubah data</div>';
						}
						?>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nama</label>
							<div class="col-sm-6">
								<input class="form-control" name="nama_pengguna" placeholder="Panjang maksimal 50 karakter" type="text" maxlength="50" value="<?php echo $nama; ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Username</label>
							<div class="col-sm-6">
								<input class="form-control" name="username" placeholder="Panjang username 5-20 karakter" type="text" minlength="5" maxlength="20" value="<?php echo $username; ?>" disabled required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Password</label>
							<div class="col-sm-6">
								<input class="form-control" name="password" placeholder="Panjang password 5-12 karakter" type="password" minlength="5" maxlength="12" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Cabang prodi</label>
							<div class="col-sm-6">
								<select name="cabang_prodi" class="form-control" disabled required>
									<option value=""> -- Pilih Cabang -- </option>
									<?php
									$query = mysqli_query($conn, "SELECT * FROM prodi");
									if (mysqli_num_rows($query) > 0) {
										while ($cabang = mysqli_fetch_assoc($query)) {
											$id_cabang = $cabang['id_prodi'];
											if ($id_prodi == $id_cabang) { // Jika sesuai id yang sedang diubah
												echo '<option value="' . $id_cabang . '" selected>' . $cabang["cabang_prodi"] . '</option>';
											} else { // Jika bukan id yang sedang diubah
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