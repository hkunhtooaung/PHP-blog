<?php
session_start();
require "../config/config.php";
error_reporting(1);
if ($_SESSION['role'] === "0") {
	echo "<script>alert('You are not an admin, Sorry');window.location.href='../index.php'</script>";
}

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
	header("location: login.php");
}

if ($_POST) {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	$stmt = $pdo->prepare("SELECT * FROM user WHERE email=:email");
	$stmt->bindValue(':email',$email);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user) {
		echo "<script>alert('Email Duplicated')</script>";
	} else {
		$stmt = $pdo->prepare("INSERT INTO user(name,email,password) VALUES (:name,:email,:password)");
		$result = $stmt->execute(
			array(':name'=>$name, ':email'=>$email, ':password'=>$password)
		);
		if ($result) {
			echo "<script>alert('Successfully Created User');window.location.href='user.php'</script>";
		}
	}


	
	
		
}
?>

<?php include('header.html') ?>
		<div class="col-md-10">
			<div class="content">
				<div class="search">
					<div class="form-group">
						<form>
							<input class="form-control" placeholder="search" type="text" name="search">
							<button type="button" class="btn btn-outline-primary">Search</button>
						</form>
					</div>
				</div>

				<div class="table">
					<table class="table table-bordered" align="center">
						<div class="card-body">
							<form action="" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label for="name">Username</label>
									<input type="text" name="name" value="" class="form-control" required>
								</div>
								<div class="form-group">
									<label for="email">Email</label>
									<input type="email" name="email" value="" class="form-control" required>
								</div>
								<div class="form-group">
									<label for="password">Password</label>
									<input type="password" name="password" value="" class="form-control" required>
								</div>
								<div class="form-group">
									<input type="submit" name="" value="SUBMIT" class="btn btn-success">
									<a href="user.php" class="btn btn-warning">Back</a>
								</div>
							</form>	
						</div>

					</table>
				</div>
			</div>
<?php include('footer.html') ?>