<?php
	include 'connect_db.php';
	$id = $_GET['id'];
	$query = 'DELETE FROM pengguna WHERE id_pengguna='.$id.'';
	if (mysqli_query($conn, $query)) {
	    header('Location: ../mengelola_pengguna.php?balasan=3');
	} else {
	    header('Location: ../mengelola_pengguna.php?balasan=4');
	}
?>