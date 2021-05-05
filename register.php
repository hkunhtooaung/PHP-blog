<?php 
session_start();
require "config/config.php";
require "config/common.php";


if ($_POST) {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = password_hash($_POST['password'],PASSWORD_DEFAULT);
	if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
		if (empty($_POST['name'])) {
			$nameError = 'Name should not be null';
		}
		if (empty($_POST['email'])) {
			$emailError = 'Email should not be null';
		}
		
		if (strlen($_POST['password']) < 4) {
			if (empty($_POST['password'])) {
			$passwordError = "password should not be null";
			}else {
				$passwordError = "password should at least 4";
			}	
		}
	} else {
		$stmt = $pdo->prepare("SELECT * FROM user WHERE email=:email");
		$stmt->bindValue(':email',$email);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// print_r($user);

		if($user) {
			 echo "<script>alert('Email Duplicated')</script>";
		} else {
			$stmt = $pdo->prepare("INSERT into user(name,email,password) VALUES (:name,:email,:password)");
			$result = $stmt->execute(
				array(':name'=>$name, ':email'=>$email, ':password'=>$password)
			);
			if ($result) {
				echo "<script>alert('Successfully register, Please login');window.location.href='login.php'</script>";
			}
		}
	}

	
}

// error_reporting(1);
// if ($_SESSION['user_id']) {
// 	header("location: index.php");
// }
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="dist/css/bootstrap.min.css">
	<style type="text/css">
		body {
		font-family: Georgia, serif;
		}
		.form {
			width: 350px;
			background-color: #f4f6f9;
			margin-top: 150px;
			border-radius:10px;
		}
		.form-group {
			/*padding: 0px 20px 0px 20px;*/
			margin-right: 10px;
			margin-left: 10px;
		}
		.form-group input[type="submit"] {
			margin-top: 10px;
			margin-bottom: 15px;
			width: 100px;
		}
		h3 {
			padding-top: 10px;
		}
		a {
			width: 	100%;
			margin-bottom: 10px;
		}
		.container input {
			width: 100%;
			margin-bottom: 	10px;
		}

	</style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div class="form">
				<h3 align="center">Register New Account</h3>
				<hr>
				<form action="register.php" method="post">
					<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
					<div class="form-group">
						<p style="margin: 0; padding: 0;"><font color="red" size="2"><?php echo empty($nameError) ? '' : '* '.$nameError ?></font></p>
						<input type="text" name="name" class="form-control" placeholder="Username">
					</div>
					<div class="form-group"> 
						<p style="margin: 0; padding: 0;"><font color="red" size="2"><?php echo empty($emailError) ? '' : '* '.$emailError ?></font></p>
						<input type="email" name="email" class="form-control" placeholder="Enter your Email">
					</div>
					<div class="form-group">
						<p style="margin: 0; padding: 0;"><font color="red" size="2"><?php echo empty($passwordError) ? '' : '* '.$passwordError ?></font></p>
						<input type="password" name="password" class="form-control" placeholder="Password">
					</div>
					
					<div class="container">
						<input type="submit" class="btn btn-outline-primary" name="submit" value="Register">
					</div>
					<div class="container">
						<a href="login.php" class="btn btn-outline-primary">Sign In</a>
					</div>
					
				</form>
			</div>
		</div>
		<div class="col-md-4"></div>
		<div></div>
	</div>
	
</div>

</body>
</html>