<?php

session_start();

require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

if (!isset($_GET['postId'])) {
	header('Location: index.php?error=noPostFound');
	exit();
} elseif (empty($_GET['postId'])) {
	header('Location: index.php?error=noPostFound');
	exit();
} else {
	$postId = $_GET['postId'];

	$sql = 'SELECT * FROM `posts` WHERE `id` = ? LIMIT 1';
	$stmt = mysqli_stmt_init($conn);

	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header('Location: index.php?error=sqlError');
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, 's', $postId);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);

		$row = mysqli_fetch_assoc($result);

		if (empty($row)) {
			header('Location: index.php?error=noPostFound');
			exit();
		}
	}
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title> <?php echo $row['title']; ?> | De Wandelmannen</title>
		<meta property="og:title" content="De Wandelmannen | Nieuwe post" />
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" type="image/jpg" href="images/favicon.ico"/>
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<link rel="stylesheet" href="assets/css/carousel.css">
		<link rel="stylesheet" href="assets/css/comments.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>
			/* Make the image fully responsive */
			.carousel-inner img {
				width: 100%;
				max-height: 40vmax;
				min-height: 40vmax;
			}
			.carousel-control-prev, .carousel-control-next{
				border-bottom-color: transparent;
			}
			p, h1, h2, h3, h4, h5, h6{
				margin: 0;
			}
			p{
				line-height: 2rem;
			}
			header{
				margin-top: 3rem;
			}
			header > h1 {
				margin-bottom: 2rem!important;
			}
			.prev-next{
				margin-top: 3rem;
				min-height: 3rem;
				margin-bottom: 1rem;
			}
			#prev{
				float: left;
				display: inline-block;
			}
			#next{
				float: right;
				display: inline-block;
			}
        </style>
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<a href="index.php" class="logo">De Wandelmannen</a>
					</header>

				<!-- Nav -->
					<nav id="nav">
						<ul class="links">
							<li><a href="index.php">Home</a></li>
							<li class="active"><?php echo '<a href="post.php?postId=' .
       	$row['id'] .
       	'" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 300px;">'; ?> <?php echo $row[
 	'title'
 ]; ?> </li>
						</ul>
						<ul class="icons">
							<li><a href="http://princeepartyservice.nl" class="icon solid fa-globe"><span class="label">Website</span></a></li>
							<li><a href="login.php" class="icon regular fa-user"><span class="label">Login</span></a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">

						<!-- Post -->
							<section class="post">
								<header class="major">
									<h1><?php echo $row['title']; ?></h1>
									<p style="word-wrap: break-word;"><?php echo $row['description']; ?></p>
								</header>

								<?php
        $imageData = json_decode($row['pathToImage']);

        echo '<div id="myCarousel" class="carousel slide" data-ride="carousel">';

        echo '<ul class="carousel-indicators">';

        if (!empty($imageData) && is_array($imageData)) {
        	foreach ($imageData as $key => $val) {
        		if ($key == 0) {
        			echo '<li data-target="#myCarousel" data-slide-to="' .
        				$key .
        				'" class="active"></li>';
        		} else {
        			echo '<li data-target="#myCarousel" data-slide-to="' .
        				$key .
        				'"></li>';
        		}
        	}
        }

        echo '</ul>';

        echo '<div class="carousel-inner">';

        if (!empty($imageData) && is_array($imageData)) {
        	foreach ($imageData as $key => $val) {
        		if ($key == 0) {
        			echo '<div class="carousel-item active">
															<img src="' .
        				'uploads/images/' .
        				chopStringToImageName($val) .
        				'" alt="' .
        				chopStringToImageName($val) .
        				'">
														</div>';
        		} else {
        			echo '<div class="carousel-item">
															<img src="' .
        				'uploads/images/' .
        				chopStringToImageName($val) .
        				'" alt="' .
        				chopStringToImageName($val) .
        				'">
														</div>';
        		}
        	}
        } elseif (!empty($imageData) && !is_array($imageData)) {
        	if (chopStringToImageName($imageData) == 'placeholder.gif') {
        		echo '<div class="carousel-item active">
													<img src="' .
        			'images/' .
        			chopStringToImageName($imageData) .
        			'" alt="' .
        			chopStringToImageName($imageData) .
        			'">
												</div>';
        	} else {
        		echo '<div class="carousel-item active">
													<img src="' .
        			'uploads/images/' .
        			chopStringToImageName($imageData) .
        			'" alt="' .
        			chopStringToImageName($imageData) .
        			'">
												</div>';
        	}
        }

        echo '</div>';

        echo '<a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
												<span class="carousel-control-prev-icon"></span>
											</a>';

        echo '<a class="carousel-control-next" href="#myCarousel" data-slide="next">
												<span class="carousel-control-next-icon"></span>
											</a>';

        echo '</div>';
        ?>
								<br>
								<p style="word-wrap: break-word;"><?php echo $row['contents']; ?></p>

								<header>
									<p><?php echo $row['author'] . ' / ' . $row['dateCreated']; ?></p>
								</header>

								<?php include_once 'page-components/prev-next-btns.php'; ?>
								
							</section>

					</div>

				<!-- Footer -->
					<footer id="footer">
						<section>

							<h2 style="margin-bottom: 1rem;" id="allcomments-header">Comments:</h2>
							<div class="comments-list" id="comments-list">
								<?php
        $sql = 'SELECT * FROM posts WHERE id=?;';
        $stmt = mysqli_stmt_init($conn);
        $postId = $_GET['postId'];
        $i = 1;

        if (!mysqli_stmt_prepare($stmt, $sql)) {
        	header(
        		'Location: ../post.php?postId=' . $postId . '&error=sqlError1'
        	);
        	exit();
        } else {
        	mysqli_stmt_bind_param($stmt, 's', $postId);
        	mysqli_stmt_execute($stmt);

        	$result = mysqli_stmt_get_result($stmt);

        	if ($row = mysqli_fetch_assoc($result)) {
        		$allComments = json_decode($row['comments'], true);

        		if (empty($allComments)) {
        			echo '<p style="font-style: italic; font-size: 1.1rem;">Het lijkt erop dat er nog geen comments zijn! Plaats nu de eerste comment.</p>';
        		} else {
        			$allComments = array_reverse($allComments);

        			foreach ($allComments as $key => $val) {
        				echo '<blockquote id="comment' . $i . '">';
        				echo '<span class="comment-msg">' . $val . '</span><br>';
        				echo '<span class="comment-name">' . $key . '</span>';
        				echo '</blockquote>';

        				$i++;
        			}
        		}
        	}
        }
        ?>
							</div>
							
							<hr>

							<h2 style="margin-bottom: 1rem;" id="comment-header">Plaats een comment:</h2>
							<div class="new-comment">
								<form action="page-components/post-add-comment.php" method="POST">
									<div class="row gtr-uniform">
										
										<div class="col-9 col-12-xsmall">
											<?php if (isset($_GET['error']) && !empty($_GET['error'])) {
           	$error = $_GET['error'];

           	if ($error == 'emptyFields') {
           		echo '<p style="color:red; font-size: 1rem; font-style: italic;">Vul alsjeblieft alle velden in!</p>';
           	} elseif ($error == 'unauthorized') {
           		header('Location: index.php');
           		exit();
           	}
           } ?>
										</div>
										
										<div class="col-9 col-12-xsmall">
											<h4>Naam:</h4>
											<input type="text" name="name" id="name" placeholder="Je naam">
										</div>

										<div class="col-9 col-12-xsmall">
											<h4>Comment:</h4>
											<textarea style="resize: none;" name="comment" id="comment" placeholder="Je comment" rows="4" maxlength="250"></textarea>
										</div>
										<input type="text" id="postId" name="postId" value="<?php echo $_GET[
          	'postId'
          ]; ?>" style="display: none;">
										<div class="col-4 col-6-small col-12-xsmall">
											<input type="submit" id="submit" name="submit" style="display: none;">
											<label for="submit" class="button primary fit solid">Plaatsen</label>
										</div>

									</div>
								</form>
							</div>

						</section>
					</footer>
				<!-- Copyright -->
					<div id="copyright">
						<ul><li>Mede mogelijk gemaakt door Princee Partyservice</li></li></ul>
					</div>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>