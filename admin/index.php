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
if ($_POST['search']) {
	setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
	if (empty($_GET['pageno'])) {
		unset($_COOKIE['search']);
		setcookie('search', null, -1, '/');
	}
}

?>
<?php include('header.html') ?>
		<div class="col-md-10">
			<div class="content">
				<div class="search">
					<div class="form-group">
						<form action="index.php" method="post">
							<input class="form-control" placeholder="search" type="text" name="search">
							<button type="submit" class="btn btn-outline-primary">Search</button>
						</form>
					</div>
				</div>
				<?php 
					if (!empty($_GET['pageno'])) {
						$pageno = $_GET['pageno'];
					} else {
						$pageno = 1;
					}
					$numOfrecs = 5;
					$offset = ($pageno - 1) * $numOfrecs;

					if (empty($_POST['search']) && empty($_COOKIE['search'])) {
						
						$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
						$stmt->execute();
						$rawresult = $stmt->fetchAll();
						$total_pages= ceil(count($rawresult) / $numOfrecs);

						$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
						$stmt->execute();
						$result = $stmt->fetchAll();
					
					} else {
						
						$searchKey = $_POST['search'] ? $_POST['search'] : $_COOKIE['search'];
						$stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
						$stmt->execute();
						$rawresult = $stmt->fetchAll();
						$total_pages= ceil(count($rawresult) / $numOfrecs);

						$stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
						$stmt->execute();
						$result = $stmt->fetchAll();


					}

				?>
				<div class="table">
					<table class="table table-bordered" align="center">
						<h3>Blog Listing</h3>
						<div>
							<a href="add.php" class="btn btn-success mb-2 ml-5">Create New Blog</a>
						</div>
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Title</th>
								<th scope="col">Description</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							if ($result) {
								$i = 1;
								foreach ($result as $value) { ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo $value['title']; ?></td>
									<td><?php echo substr($value['content'],0,10); ?></td>
									<td>
										<a href="edit.php?id=<?php echo $value['id']; ?>" class="btn btn-outline-warning">Edit</a>
										<a href="delete.php?id=<?php echo $value['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this item')">Delete</a>
									</td>
								</tr>	
							<?php
							$i++;
								}
							}
						?>
						</tbody>
					</table>
					<nav aria-label="Page navigation example" style="margin-left: 750px;"> 
	                	<ul class="pagination">
	                		<li class="page-item"><a href="?pageno=1" class="page-link">First</a></li>

	                		<li class="page-item <?php if($pageno <= 1) { echo 'disabled';} ?>">
	                			<a class="page-link" href="<?php if($pageno <= 1) {echo '#';} else { echo "?pageno=".($pageno - 1);} ?>" >Previous</a>
	                		</li>

	         
	                		<li class="page-item"><a href="#" class="page-link"><?php echo $pageno; ?></a></li>
	                		
	                		<li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
	                			<a href="<?php if($pageno >= $total_pages) {echo '#';} else { echo '?pageno='.($pageno+1);} ?>" class="page-link">Next</a>
	                		</li>
	                		
	                		<li class="page-item"><a href="<?php echo '?pageno='.$total_pages ?>" class="page-link">Last</a></li>
	                	</ul>
                	</nav>	
				</div>
			</div>

<?php include('footer.html') ?>