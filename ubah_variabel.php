<?php
include 'layout.php';
$id = $_GET['id'];
$query = mysqli_query($conn, 'SELECT * FROM variabel WHERE id_variabel=' . $id . '');
if (mysqli_num_rows($query) > 0) {
	while ($var = mysqli_fetch_assoc($query)) {
		$nama = $var['nama_variabel'];
		$jenis = $var['jenis_variabel'];
		$satuan = $var['satuan'];
	}
}
?>
<div class="konten">
	<div class="col-sm-9">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span> Mengelola Variabel</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="<?php echo "process/u_variabel.php?id=" . $id . ""; ?>">
					<fieldset>
						<legend>Ubah Variabel</legend>
						<?php
						if (isset($_GET['balasan']) and ($_GET['balasan'] == 1)) {
							echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Nama variabel</strong> sudah terdaftar. Silahkan gunakan <strong>nama variabel</strong> lain</div>';
						}
						?>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nama</label>
							<div class="col-sm-6">
								<input class="form-control" name="nama_variabel" placeholder="Panjang maksimal 50 karakter" maxlength="50" type="text" value="<?php echo $nama; ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Jenis</label>
							<div class="col-sm-6">
								<select name="jenis_variabel" class="form-control" required>
									<option value="" selected="selected"> -- Pilih Jenis -- </option>
									<?php
									if ($jenis == 'i') {
										echo '<option value="i" selected="selected">Input</option><option value="o">Output</option>';
									} elseif ($jenis == 'o') {
										echo '<option value="i">Input</option><option value="o" selected="selected">Output</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Satuan</label>
							<div class="col-sm-6">
								<input class="form-control" name="satuan_variabel" placeholder="Panjang maksimal 50 karakter" maxlength="50" type="text" value="<?php echo $satuan; ?>" required>
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