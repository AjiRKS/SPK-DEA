<?php include 'layout.php'; ?>
<div class="konten">
	<div class="col-sm-9">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-duplicate"></span> Mengelola Prodi</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="process/t_cabang.php">
					<fieldset>
						<legend>Tambah Prodi</legend>
						<?php
						if (isset($_GET['balasan']) and ($_GET['balasan'] == 1)) {
							echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Cabang</strong> atau <strong>alamat</strong> sudah terdaftar. Silahkan gunakan <strong>cabang</strong> atau <strong>alamat</strong> lain</div>';
						}
						?>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nama Prodi</label>
							<div class="col-sm-6">
								<input class="form-control" name="cabang_prodi" placeholder="Panjang maksimal 50 karakter" type="text" maxlength="50" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Keterangan</label>
							<div class="col-sm-6">
								<textarea class="form-control" rows="3" name="alamat" placeholder="Panjang maksimal 100 karakter" type="text" maxlength="100" required></textarea>
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