<?php 
require "config/config.php";
session_start();
$blogId = $_GET['post_id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$blogId);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
$stmtcmt->execute();
$cmresult = $stmtcmt->fetch(PDO::FETCH_ASSOC);

$author_id = $cmresult['author_id'];
$stmtau = $pdo->prepare("SELECT * FROM user WHERE id=$author_id");
$stmtau->execute();
$auresult = $stmtau->fetch(PDO::FETCH_ASSOC);


if ($_POST) {
	$comment = $_POST['comment']; 
	$stmt = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
	$result = $stmt->execute(
			array(':content'=>$comment, ':author_id'=>$_SESSION['user_id'],':post_id'=>$blogId)
	);
	if ($result) {
	       header('Location: blogdetail.php?post_id='.$blogId);
	    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Blog Detail</title>
	<link rel="stylesheet" type="text/css" href="dist/css/bootstrap.min.css">
	<style type="text/css">
		h4 {
			margin-top: 10px;
		}
		.card-header {
			height: 50px;
			/*background-color: red;*/
		}
		.card {
			/*background-color: #000;*/
			margin: 0;
			padding: 0;
			text-align: center;/*
			color: rgb(255,255,255, 0.5);	*/
		}

		img {
			border-radius: 10px;	
		}
		.card-header {
			margin: 0!important;
			padding: 0!important;
		}
		.card-footer h6 img {
			border-radius: 20px;
		}
		.card div {
			width: 100%;
		}

		.nav {
			margin: 20px;
			display: relative !important;
			top: 20px;
		}
		.nav nav{
			margin-left: 500px !important;
		}
		.footer {
			margin-top: 20px;
		}
		p {
			text-decoration: justify;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4><?php echo $result['title'] ?></h4>
					</div>
					<div class="card-body">
						<img src="admin/images/<?php echo $result['image'] ?>" width="700" height="500"><br><br>
						<p>
							<?php echo $result['content']; ?>
						</p>
					</div>
					<div class="card-footer">
						<a class="btn btn-warning" href="index.php">Go Back</a>
						<h2 align="left">Comments</h2>

						<h6 align="left" style="display:inline-block; float:left;"><strong><?php echo $auresult['name'] ?></strong></h6><span style="float: right;"><font size="2"><?php echo $cmresult['created_at'] ?></font></span>
						<p align="left" style="clear: both;"> 
							<?php echo $cmresult['content']; ?>
						</p><hr>

						<div class="form-group">
							<form action="" method="post">
								<input type="text" name="comment" class="form-control" value="" placeholder="Press enter to post comment">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
			<h6 align="center">Copyright &copy; 2021 <a href="#">HKUNHTOOAUNG</a>.</h6>
		</div>
	</div>
	
</body>
</html>