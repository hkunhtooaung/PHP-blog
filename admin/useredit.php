<?php
session_start();
require "../config/config.php";
require "../config/common.php";
error_reporting(1);
if ($_SESSION['role'] === "0") {
	echo "<script>alert('You are not an admin, Sorry');window.location.href='../index.php'</script>";
}

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
	header("location: login.php");
}


$stmt = $pdo->prepare("SELECT * FROM user WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

if ($_POST) {
	if (empty($_POST['name']) || empty($_POST['email']) ) {
		if (empty($_POST['name'])) {
			$nameError = 'Name should not be null';
		}
		if (empty($_POST['email'])) {
			$emailError = 'email should not be null';
		}
		
	}elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4 ) {
		$passwordError = 'Password should not be at least 4 charactor';
	}
	 else {
		$id = $_POST['id'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = password_hash($_POST['password'],PASSWORD_DEFAULT);
		
		$stmt = $pdo->prepare("SELECT * FROM user WHERE email=:email AND id!=:id");
		$stmt->execute(
			array(':email'=>$email, ':id'=>$id)
		);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($user) {
			echo "<script>alert('Email Duplicated')</script>";
		} else {
			
			if ($password != null) {
				$stmt = $pdo->prepare("UPDATE user set name='$name', email='$email', password='$password' WHERE id='$id'");
			} else {
				$stmt = $pdo->prepare("UPDATE user set name='$name', email='$email' WHERE id='$id'");
			}

			$result = $stmt->execute();
			if ($result) {
				echo "<script>alert('Successfully Updated');window.location.href='user.php'</script>";
			}
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
								<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
								<input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
								<div class="form-group">
									<label for="name">Username</label><p style="margin: 0; padding: 0;"><font color="red" size="2"><?php echo empty($nameError) ? '' : '* '.$nameError ?></font></p>
									<input type="text" name="name" value="<?php echo escape($result[0]['name']); ?>" class="form-control">
								</div>
								<div class="form-group">
									<label for="email">Email</label><p style="margin: 0; padding: 0;"><font color="red" size="2"><?php echo empty($emailError) ? '' : '* '.$emailError ?></font></p>
									<input type="email" name="email" value="<?php echo escape($result[0]['email']); ?>" class="form-control">
								</div>
								<div class="form-group">
									<label for="email">Password</label><p style="margin: 0; padding: 0;"><font color="red" size="2"><?php echo empty($passwordError) ? '' : '* '.$passwordError ?></font></p>
									<span style="font-size: 10px">User already has a password!</span>
									<input type="text" name="password" class="form-control">
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
		</div>
