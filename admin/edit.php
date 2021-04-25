<?php
session_start();
require "../config/config.php";
if ($_SESSION['role'] === "0") {
	header("location: ../index.php");
}
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
	header("location: login.php");
}


// if ($_POST) {
// 	$id = $_POST['id'];
// 	$title = $_POST['title'];
// 	$content = $_POST['content'];

// 	if ($_FILES['image']['name'] != null) {
// 		$file = 'images/'.($_FILES['image']['name']);
// 		$fileType = pathinfo($file,PATHINFO_EXTENSION);

// 		if ($fileType != 'png' && $fileType != 'jpg' && $file != 'jpeg') {
// 			echo "<script>alert('Image must be PNG,JPG,JPEG')</script>";
// 		} else {
// 			$image = $_FILES['image']['name'];
// 			move_uploaded_file($_FILES['image']['tmp_name'], $file);

// 			$stmt = $pdo->prepare("UPDATE posts set title='$title',content='$content', image='$image' WHERE id='$id'");
// 			$result = $stmt->execute();

// 			if ($result) {
// 				echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
// 			}
// 		}
// 	} else {

// 		$stmt = $pdo->prepare("UPDATE posts set title='$title',content='$content' WHERE id='$id'");
// 		$result = $stmt->execute();

// 		if ($result) {
// 			echo "<script>alert('Updated Successfully');window.location.href='index.php'</script>";
// 		}
// 	}
// }

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

if ($_POST) {
	$id = $_POST['id'];
	$title = $_POST['title'];
	$content = $_POST['content'];
	$image = $_FILES['image']['name'];
	$file = 'images/'.($_FILES['image']['name']);
	$imageType = pathinfo($file, PATHINFO_EXTENSION);

	if ($_FILES['image']['name'] != null) {
		if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') {
			echo "<script>alert('Image must be PNG,JPG,JPEG')</script>";
		} else {
			move_uploaded_file($_FILES['image']['tmp_name'], $file);

			$stmt = $pdo->prepare("UPDATE posts set title='$title', content='$content', image='$image' WHERE id='$id'");
			$result = $stmt->execute();
			if ($result) {
				echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
			}
		}
	} else {
		$stmt = $pdo->prepare("UPDATE posts set title='$title', content='$content' WHERE id='$id'");
		$result = $stmt->execute();
		if ($result) {
			echo "<script>alert('Successfully Updated');window.location.href='index.php'</script>";
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
								<input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
								<div class="form-group">
									<label for="title">Title</label>
									<input type="text" name="title" value="<?php echo $result[0]['title']; ?>" class="form-control" required>
								</div>
								<div class="form-group">
									<label for="">Content</label><br>
									<textarea class="form-control" name="content" rows="8" cols="80"><?php echo $result[0]['content']; ?></textarea>
								</div>
								<div class="form-group">
									<label for="image">Image</label><br>
									<img src="images/<?php echo $result[0]['image']; ?>" width="150" height="150" alt=""><br><br>
									<input type="file" name="image">
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
		</div>
