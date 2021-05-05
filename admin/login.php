<?php 
session_start();
require "../config/config.php";
require "../config/common.php";


if ($_POST) {
	$email = $_POST['email'];
	$password = $_POST['password'];

	$stmt = $pdo->prepare("SELECT * FROM user WHERE email=:email");
	$stmt->bindValue(':email',$email);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user) {

		// $user['password'] == $password
		if (password_verify($password,$user['password'])) {
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['name'];
			$_SESSION['logged_in'] = time(); 

			header("location: index.php");
		} 
	} else {
		echo "<script>alert('Username or Password is wrong');</script>";
	}
	
}

error_reporting(1);
if ($_SESSION['user_id']) {
	header("location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="../dist/css/bootstrap.min.css">
	<style type="text/css">
		body {
		font-family: Georgia, serif;
		}
		.form-group {
			width: 350px;
			background-color: #f4f6f9;
			margin-top: 150px;
			border-radius:10px;
		}
		.form-group form {
			padding: 0px 20px 0px 20px;
		}
		.form-group input[type="submit"] {
			margin-top: 10px;
			margin-bottom: 15px;
			width: 100px;
		}
		h3 {
			padding-top: 10px;
		}

	</style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div class="form-group">
				<h3 align="center">Admin Login</h3>
				<hr>
				<form action="" method="post">
					<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
					<label for="email">Email</label>
					<input type="email" name="email" class="form-control">
					<label for="password">Password</label>
					<input type="password" name="password" class="form-control">

					<input type="submit" class="btn btn-primary" name="submit" value="login">
				</form>
			</div>
		</div>
		<div class="col-md-4"></div>
		<div></div>
	</div>
	
</div>

</body>
</html>