<?php
	include 'connect_db.php';
	if (ISSET($_GET['id'])) {
		$id = $_GET['id']; // Id baris data yang dipilih untuk diubah
		//$id_prodi = $_POST['id_prodi']; // Id prodi dari form dropdown

		$query = "SELECT * FROM variabel";
		if (mysqli_query($conn, $query)) {
			$query = mysqli_query($conn, $query);
		 	if (mysqli_num_rows($query) > 0) {
				while($var = mysqli_fetch_assoc($query)){
					$name = str_replace(' ','_',$var['nama_variabel']);
					$nilai_var = $_POST[$name];
					$query2 = mysqli_query($conn, 'UPDATE detail_dmu SET nilai_variabel="'.$nilai_var.'" WHERE id_prodi="'.$id.'" AND id_variabel="'.$var['id_variabel'].'"');
				}
			}
			header('Location: ../mengelola_dmu.php?balasan=5');
		} else {
		    header('Location: ../mengelola_dmu.php?balasan=6&a');
		}
	} else {
		header('Location: ../mengelola_dmu.php?balasan=6');
	}	
?>