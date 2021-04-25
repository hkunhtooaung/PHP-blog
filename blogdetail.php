<?php 
require "config/config.php";

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['post_id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Blog Detail</title>
	<link rel="stylesheet" type="text/css" href="dist/css/bootstrap.min.css">
	<style type="text/css">
		h4 {
			margin-top: 15px;
		}
		.card-header {
			height: 40px;
			/*background-color: red;*/
		}
		.card {
			background-color: #000;
			margin: 0;
			padding: 0;
			text-align: center;
			color: rgb(255,255,255, 0.5);	
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
						<h2 align="left">Comments</h2>

						<h6 align="left"><img src="images/blog.png" width="40" height="40">&nbsp;&nbsp;UserName</h6>
						<p align="left">
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
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