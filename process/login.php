<?php
	require_once 'connect_db.php';
	session_start();
	if (ISSET($_POST['login'])) {
		// Login Berhasil
		$username = test_input($_POST['username']);
		$password = md5($_POST['password']);
		$query = mysqli_query($conn, "SELECT * FROM pengguna WHERE username='".$username."' AND password='".$password."'");
		$query2 = "SELECT * FROM pengguna_khusus WHERE username='".$username."' AND password='".$password."'";
		if(mysqli_num_rows($query) > 0){
			// Username dan Password Benar
			while($data = mysqli_fetch_assoc($query)){
				$level = $data['level'];
				$id = $data['id_pengguna'];
				$id_prodi = $data['id_prodi'];
			}
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
			$_SESSION['id'] = $id;
			$_SESSION['level'] = $level;
			$_SESSION['id_prodi'] = $id_prodi;
			// Klasifikasi Level Pengguna
			if ($level=='c'){ 
				// Admin Cabang
				$_SESSION['user'] = "Admin Prodi";
				header("location: ../beranda.php");
			} elseif ($level=='m') {
				// Manajer Cabang
				$_SESSION['user'] = "Manajer Cabang";
				header("location: ../beranda.php");
			}
		} elseif (mysqli_query($conn, $query2)) {
			$query2 = mysqli_query($conn, $query2);
			if (mysqli_num_rows($query2) > 0) {
				// Username dan Password Benar
				$data = mysqli_fetch_assoc($query2);
				$level = $data['level'];
				$id = $data['id_pengguna_khusus'];
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $password;
				$_SESSION['id'] = $id;
				$_SESSION['level'] = $level;
				if ($level == 'a') {
					// Superadmin
					$_SESSION['user'] = "Superadmin";
					header("location: ../beranda.php");
				} elseif ($level == 'p') {
					// Manajer Pusat
					$_SESSION['user'] = "Manajer Pusat";
					header("location: ../beranda.php");
				}
				
			} else {
				// Username tidak terdaftar
				$_SESSION['error'] = "<strong>Username</strong> tidak terdaftar";
				header("location: ../index.php");
			}
		} else {
			// Username dan atau Password Salah
			$_SESSION['error'] = "<strong>Username</strong> atau <strong>Password</strong> salah";
			header("location: ../index.php");
		}
	} else {
		// Login Gagal
		$_SESSION['error'] = 'Login Gagal';
		header("location: ../index.php");
	}
	
	function test_input($data) {
  		$data = trim($data);
  		$data = stripslashes($data);
  		$data = htmlspecialchars($data);
  		return $data;
	}
?>