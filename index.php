<?php 
	include 'process/connect_db.php'; 
	session_start();
	// Mencegah kembali ke halaman Login sebelum melakukan Logout
	if (ISSET($_SESSION['level'])) {
		header('Location: beranda.php');
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>SPK Metode DEA</title>
		<!-- Bootstrap -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
	</head>

	<body>
		<div class="container">
            <div class="row">
				<div class="col-sm-4 col-sm-offset-4" id="login-box">
					<div class="panel panel-default">
						<div class="panel-body">
						<?php 
							if (ISSET($_SESSION['error'])) {
								// Terdapat Error Saat Login
								echo '<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><span class="glyphicon glyphicon-exclamation-sign"></span>  '.$_SESSION['error'].'</div>';
							}
						?>	
							<h2 id="login-text"> <img src="assets/img/logo_polibest.jpg" width="180px" class="img-responsive center-block"> </h2>
					        <form role="form" action="process/login.php" method="post">
					            <div class="form-group">
						            <div class="input-group input-group-md">
						                <label class="sr-only">Username</label>
						                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
						                <input type="text" class="form-control" placeholder="Username" name="username" required>
						            </div>
					            </div>
					            <div class="form-group">
					            	<div class="input-group input-group-md">
						                <label class="sr-only">Password</label>
						                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
						                <input type="password" class="form-control" placeholder="Password" name="password" required>
						            </div>
					            </div>
					            <button type="submit" class="btn btn-primary" name="login" value="login" id="login-button">Login</button>
					        </form>
					        <hr />
					        <h5 id="copyright">&copy; 2023 All Rights Reserved.</h5>
				        </div>
			        </div>
				</div>
<?php include 'layout2.php' ?>