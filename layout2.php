			</div> <!-- End of Second Row -->
			</div> <!-- End of First Container-Fluid -->

			<script type="text/javascript">
				function hapus() {
					var konfirmasi = confirm("Apakah anda yakin untuk menghapus data ini ?");
					if (konfirmasi) {
						return true;
					} else {
						return false;
					}
				}
			</script>
			<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
			<!-- Include all compiled plugins (below), or include individual files as needed -->
			<script src="assets/js/bootstrap.min.js"></script>
			<script src="assets/js/script.js"></script>
			<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
			<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
			<script>
				$(document).ready(function() {
					$('#dataTables-example').DataTable();
				});

				function confirmLogout() {
					var confirmation = confirm("Apakah Anda yakin ingin logout?");
					if (confirmation) {
						// Lakukan tindakan logout di sini
						// Misalnya, arahkan ke halaman logout.php
						window.location.href = "process/logout.php";
					}
				}

				function goBack() {
					window.history.back();
				}
			</script>

			</body>

			</html>