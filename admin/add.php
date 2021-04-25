<?php
session_start();
require "../config/config.php";
if ($_SESSION['role'] === "0") {
	header("location: ../index.php");
}
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
	header("location: login.php");
}

if ($_POST) {
	$title = $_POST['title'];
	$content = $_POST['content'];
	$image= $_FILES['image']['name'];
	$file = 'images/'.($_FILES['image']['name']);
	$fileType = pathinfo($file, PATHINFO_EXTENSION);

	if ($fileType != 'png' && $fileType != 'jpg' && $fileType != 'jpeg') {
		echo "<script>alert('Image must be PNG,JPG,JPEG');</script>";
	} else {
		move_uploaded_file($_FILES['image']['tmp_name'], $file);
		$stmt = $pdo->prepare("INSERT INTO posts(title,content,author_id,image) VALUES (:title,:content,:author_id,:image)");
		$result = $stmt->execute(
			array(':title'=>$title, ':content'=>$content, ':author_id'=>$_SESSION['user_id'], ':image'=>$image)
		);
		if ($result) {
			echo "<script>alert('Successfully Uploaded');window.location.href='index.php'</script>";
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
							<form action="add.php" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label for="title">Title</label>
									<input type="text" name="title" value="" class="form-control" required>
								</div>
								<div class="form-group">
									<label for="">Content</label><br>
									<textarea class="form-control" name="content" rows="8" cols="80"></textarea>
								</div>
								<div class="form-group">
									<label for="image">Image</label>
									<input type="file" name="image" required>
								</div>
								<div class="form-group">
									<input type="submit" name="" value="SUBMIT" class="btn btn-success">
									<a href="index.php" class="btn btn-warning">Back</a>
								</div>
							</form>	
						</div>

					</table>
				</div>
			</div>
<?php include('footer.html') ?>