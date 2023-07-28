<?php
	include 'connect_db.php';
	$id = $_GET['id'];
	$query = 'DELETE FROM detail_dmu WHERE id_prodi='.$id.'';
	if (mysqli_query($conn, $query)) {
	    header('Location: ../mengelola_dmu.php?balasan=3');
	} else {
	    header('Location: ../mengelola_dmu.php?balasan=4');
	}
?>