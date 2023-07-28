<?php
include 'layout.php';
$id = $_GET['id'];
$query = mysqli_query($conn, 'SELECT * FROM prodi WHERE id_prodi=' . $id . '');
if (mysqli_num_rows($query) > 0) {
	// output data of each row
	while ($cabang = mysqli_fetch_assoc($query)) {
		$cabang_target = $cabang['cabang_prodi'];
		$alamat_target = $cabang['alamat'];
	}
}
?>
<div class="konten">
	<div class="col-sm-9">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-duplicate"></span> Mengelola Cabang</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="<?php echo "process/u_cabang.php?id=" . $id . ""; ?>">
					<fieldset>
						<legend>Ubah Cabang</legend>
						<?php
						if (isset($_GET['balasan']) and ($_GET['balasan'] == 1)) {
							echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Cabang</strong> atau <strong>alamat</strong> sudah terdaftar. Silahkan gunakan <strong>cabang</strong> atau <strong>alamat</strong> lain</div>';
						}
						?>
						<div class="form-group">
							<label class="col-sm-3 control-label">Cabang prodi</label>
							<div class="col-sm-6">
								<input class="form-control" name="cabang_prodi" placeholder="Panjang maksimal 50 karakter" type="text" maxlength="50" value="<?php echo $cabang_target; ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Alamat</label>
							<div class="col-sm-6">
								<textarea class="form-control" rows="3" name="alamat" placeholder="Panjang maksimal 100 karakter" type="text" maxlength="100" required><?php echo $alamat_target; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-9 col-sm-offset-3">
								<button type="button" onclick="goBack()" class="btn btn-info">Kembali</button>
								<button type="submit" class="btn btn-success">Simpan</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div> <!-- End of Panel -->
	</div> <!-- End of Main Content (Second col-sm-9) -->
</div>
<?php include 'layout2.php' ?>