<?php 
require 'config/config.php';
require 'config/common.php';
session_start();
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
	header("location: login.php");
}

if (!empty($_GET['pageno'])) {
		$pageno = $_GET['pageno'];
	} else {
		$pageno = 1;
	}
	$numOfrecs = 6;
	$offset = ($pageno - 1) * $numOfrecs;
$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
$stmt->execute();
$rawresult = $stmt->fetchAll();
$total_pages= ceil(count($rawresult) / $numOfrecs);

$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
$stmt->execute();
$result = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Blog</title>
	<link rel="stylesheet" type="text/css" href="dist/css/bootstrap.min.css">
	<style type="text/css">
		.card {
			background-color: #fff;
			margin: 0;
			padding: 0;
			text-align: center;
			cursor: pointer;
		}
		.img {
			border-bottom: 1px solid gray;

		}
		.card-header {
			margin: 0!important;
			padding: 0!important;
			height: 50px;
		}
		.card-header h5 {
			margin-top: 10px;
		}
		.card div {
			width: 100%;
		}
		.card {
			/*height: 500px;*/
			margin-left: 10px;	
			width: 400px;
			margin-top: 5px;
		}	
		.nav {
			margin: 20px;
			display: relative !important;
			top: 20px;
		}
		.nav nav{
			margin-left: 500px !important;
		}
		a {
			text-decoration: none;
			color: black;
		}
		a:hover {
			text-decoration: none;
			color: black;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<h2 align="center">Blogs</h2>
		<div class="row">
			<?php 
			foreach ($result as $value) {
			?>
			<div class="col-md-4">
				<a href="blogdetail.php?post_id=<?php echo $value['id'] ?>">
					<div class="card" >
					<div class="card-header">
						<h5><?php echo escape($value['title']); ?></h5>
					</div>
					<div class="card-body">
						<img src="admin/images/<?php echo $value['image'] ?>" width="350" height="200">
					</div>
				</div>
				</a>
			</div>
			<?php	
			}
			?>
		</div>
		<div class="nav">
			<nav aria-label="Page navigation example" style="margin-left: 750px;"> 
	        	<ul class="pagination">
	        		<li class="page-item"><a href="?pageno=1" class="page-link">First</a></li>

	        		<li class="page-item <?php if($pageno <= 1) { echo 'disabled';} ?>">
	        			<a class="page-link" href="<?php if($pageno <= 1) {echo '#';} else { echo "?pageno=".($pageno - 1);} ?>" ><< Previous</a>
	        		</li>

	 
	        		<li class="page-item"><a href="#" class="page-link"><?php echo $pageno; ?></a></li>
	        		
	        		<li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
	        			<a href="<?php if($pageno >= $total_pages) {echo '#';} else { echo '?pageno='.($pageno+1);} ?>" class="page-link">Next >></a>
	        		</li>
	        		
	        		<li class="page-item"><a href="<?php echo '?pageno='.$total_pages ?>" class="page-link">Last</a></li>
	        	</ul>
	    	</nav>
	    	
		</div>
				

		<div class="footer">
			<h6 align="center">Copyright &copy; 2021 <a href="#">HKUNHTOOAUNG</a>.</h6>
		</div>
	</div>
	
</body>
</html>