<?php
include 'layout.php';
$id = $_GET['id'];
?>
<div class="konten">
	<div class="col-sm-9">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-tower"></span> Mengelola Data DMU</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="<?php echo "process/u_dmu.php?id=" . $id . ""; ?>">
					<fieldset>
						<legend>Ubah Data DMU</legend>
						<div class="form-group">
							<label class="col-sm-4 control-label">Nama</label>
							<div class="col-sm-6">
								<select name="id_prodi" class="form-control" required disabled>
									<option value=""> -- Pilih Cabang -- </option>
									<?php
									$query = mysqli_query($conn, "SELECT k.cabang_prodi, d.id_prodi FROM prodi AS k, detail_dmu AS d WHERE k.id_prodi=d.id_prodi GROUP BY id_prodi");
									if (mysqli_num_rows($query) > 0) {
										// output data of each row
										while ($cabang = mysqli_fetch_assoc($query)) {
											$id_cabang = $cabang['id_prodi'];
											if ($id == $id_cabang) { // Jika sesuai id yang sedang diubah
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
						<?php
						$input = 0;
						$output = 0;
						$query = mysqli_query($conn, 'SELECT * FROM variabel v, detail_dmu d WHERE v.id_variabel=d.id_variabel AND d.id_prodi=' . $id . ' ORDER BY v.jenis_variabel ASC, v.id_variabel ASC');
						while ($var = mysqli_fetch_assoc($query)) {
							$name =	 str_replace(' ', '_', $var['nama_variabel']);
							$satuan = $var['satuan'];
							$value = $var["nilai_variabel"];
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
												        <input class="form-control" name="' . $name . '" type="number" min="1" placeholder="' . $satuan . '" value="' . $value . '" required>
												    </div>
												</div>
											';
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