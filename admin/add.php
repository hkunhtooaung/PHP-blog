<?php
session_start();
require "../config/config.php";
require "../config/common.php";

error_reporting(1);
if ($_SESSION['role'] === "0") {
	header("location: ../index.php");
}
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
	header("location: login.php");
}

if ($_POST) {

	if (empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image']['name'])) {
		if (empty($_POST['title'])) {
			$titleError = 'Title cannot be null';
		}
		if (empty($_POST['content'])) {
			$contentError = 'Content cannot be null';
		}
		if (empty($_FILES['image']['name'])) {
			$imageError = 'Image cannot be null';
		}
	} else {
		$file = 'images/'.($_FILES['image']['name']);
		$fileType = pathinfo($file, PATHINFO_EXTENSION);

		if ($fileType != 'png' && $fileType != 'jpg' && $fileType != 'jpeg') {
			echo "<script>alert('Image must be PNG,JPG,JPEG');</script>";
		} else {
			$title = $_POST['title'];
			$content = $_POST['content'];
			$image= $_FILES['image']['name'];
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
								<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
								<div class="form-group">
									<label for="title">Title</label><p style="color: red; margin:0; padding: 0;"><font size="2"> <?php echo empty($titleError) ? '' : '* '.$titleError; ?></font></p>
									<input type="text" name="title" value="" class="form-control">
								</div>
								<div class="form-group">
									<label for="">Content</label><p style="color: red; margin:0; padding: 0;"><font size="2"> <?php echo empty($contentError) ? '' : '* '.$contentError; ?></font></p>
									<textarea class="form-control" name="content" rows="8" cols="80"></textarea>
								</div>
								<div class="form-group">
									<label for="image">Image</label><p style="color: red; margin:0; padding: 0;"><font size="2"><?php echo empty($imageError) ? '' : '* '.$imageError; ?></font></p>
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
<?php include('footer.html') ?>