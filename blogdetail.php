<?php 
require "config/config.php";
session_start();
$blogId = $_GET['post_id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$blogId);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
$stmtcmt->execute();
$cmResult = $stmtcmt->fetchAll();

$auResult = [];
if ($cmResult) {
	foreach ($cmResult as $key => $value) {
		$author_id = $cmResult[$key]['author_id'];
		$stmtau = $pdo->prepare("SELECT * FROM user WHERE id=$author_id");
		$stmtau->execute();
		$auResult[] = $stmtau->fetchAll(); 
	}
}
// echo "<pre>";
// print_r($auResult);
// print_r($auResult[2][0]['name']);
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
	<link rel="stylesheet" type="text/css" href="plugins/blogdetail.css">
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

						<?php 
						if($cmResult) {
							foreach($cmResult as $key => $value){
						?>
							<h6 align="left" style="display:inline-block; float:left;">
								<strong><?php echo $auResult[$key][0]['name'] ?></strong>
							</h6>
							 
							<span style="float: right;"><font size="2"><?php echo $value['created_at'] ?></font></span>

							<p align="left" style="clear: both;"> 
								<?php echo $value['content']; ?>
							</p><hr>

						<?php	
							}
						}
						?>

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