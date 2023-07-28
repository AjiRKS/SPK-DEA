<?php
	include 'connect_db.php';
	$id = $_GET['id'];
	$query = 'DELETE FROM variabel WHERE id_variabel='.$id.'';
	if (mysqli_query($conn, $query)) {
		$query = 'DELETE FROM detail_dmu WHERE id_variabel='.$id.'';
		if (mysqli_query($conn, $query)) {
			$query = 'DELETE FROM perhitungan_efisiensi WHERE id_variabel='.$id.'';
			if (mysqli_query($conn, $query)) {
				header('Location: ../mengelola_variabel.php?balasan=3');
			} else {
				header('Location: ../mengelola_variabel.php?balasan=4');
			}
		} else {
			header('Location: ../mengelola_variabel.php?balasan=4');
		}
	} else {
	    header('Location: ../mengelola_variabel.php?balasan=4');
	}
?>