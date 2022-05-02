<?php
	session_start();

	require_once 'includes/dbh.inc.php';
	require_once 'includes/functions.inc.php';

	$results_per_page = 10;

	$sql='SELECT * FROM posts';
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0)
	{
		$number_of_results = mysqli_num_rows($result);
		$number_of_pages = ceil($number_of_results/$results_per_page);
	}
	else{
		$number_of_pages = 1;
	}

	if(!isset($_GET['page'])) {
		$page = 1;
		header("Location: index.php?page=" . $page); 
	}
	else if(empty($_GET['page'])){
		$page = 1;
		header("Location: index.php?page=" . $page); 
	}
	else if($_GET['page'] > $number_of_pages)
	{
		$page = 1;
		header("Location: index.php?page=" . $page);
	}
	else{
		$page = $_GET['page'];
	}

	if(!$page > 1)
	{
		$this_page_first_result = $page * $results_per_page;
	}
	else
	{
		$this_page_first_result = ($page-1)*$results_per_page;
	}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>De Wandelmannen</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="">
		<link rel="shortcut icon" type="image/jpg" href="images/favicon.ico"/>
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/carousel.css">
		<link rel="stylesheet" href="assets/css/index-posts.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    
		<style>
			.carousel-inner img {
				width: 100%;
				max-height: 30vmax;
				min-height: 30vmax;
			}
			.capt-title{
				box-shadow: none;
				color: #ffffff !important;
				font-size: 0.9rem;
				text-shadow: 3px 3px 5px black;
			}
			.carousel-control-prev, .carousel-control-next{
				border-bottom-color: transparent;
			}
			#vertrekdatum{
				padding: 4px 6px;
				border: solid 1px black;
				text-align: center;
				border-radius: 5px;
			}
        </style>
	</head>


	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper" class="fade-in">

				<!-- Intro -->
					<div id="intro">
						<h1 style="margin: 0;">De Wandel<br>mannen</h1>
						<img src="images/Sjefenben.jpg" style="max-height: 10rem; max-width: 10rem; margin: 1rem; border-radius: 12.5px;" alt="Sjefenben.jpg">
						<p style="font-size: 1rem">Voor persoonlijke redenen zijn de volgende gegevens weggehaald. Bedankt voor uw begrip.</p>

						<ul class="actions">
							<li><a href="#header" class="button icon solid solo fa-arrow-down scrolly">Continue</a></li>
						</ul>
					</div>

				<!-- Header -->
					<header id="header">
						<a href="index.php" class="logo">De Wandelmannen</a>
					</header>

				<!-- Nav -->
					<nav id="nav">
						<ul class="links">
							<li class="active"><a href="index.php">Home</a></li>
							<li><a href="about.php">Over ons</a></li>
						</ul>
						<ul class="icons">
							<li><a href="http://facebook.com" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
							<li><a href="login.php" class="icon regular fa-user"><span class="label">Login</span></a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">

						<article class="post featured" style="padding-bottom: 2rem; padding-top: 3.5rem;">
							<header class="major" style="margin: 0;">

								<h2>Welkom bij de Wandelmannen!</h2>

								<div id="myCarousel" class="carousel slide" data-ride="carousel">
									<div class="carousel-inner">

										<?php 

											$sql1 = 'SELECT * FROM posts ORDER BY `id` DESC LIMIT ' . $this_page_first_result . ',' .  $results_per_page;
											$result1 = mysqli_query($conn, $sql1);
											$i = 0;
		
											$array = array();
											while($row = mysqli_fetch_assoc($result1))
											{
												$array[] = $row;
											}
											if(empty($array)){
												echo '<div class="carousel-item active">';
												echo '<img src="images/placeholder.gif">';
												echo '<div class="carousel-caption d-none d-md-block">
														<h4><span class="capt-title">###</span></h4>
													</div>';
												echo '</div>';
											}
										
											foreach($array as $key => $value)
											{
                        // base string is $array[$key]
                        // doordat imagePath in json gecodeerd is, moet het gedecodeerd worden
												$imagePath = json_decode($array[$key]['pathToImage'], true);

												if($i == 0 ){echo '<div class="carousel-item active">';}
												else {echo '<div class="carousel-item">';}

                        echo '<img src="#" data-id="'. $array[$key]['id'] .'" class="skeleton">';

												echo '<div class="carousel-caption d-none d-md-block">
														<h5><span class="capt-title">' . $array[$key]['title'] . '</span></h5>
														<a class="button small primary" style="font-style: normal;" href="post.php?postId='. $array[$key]['id'] .'">Lees nu</a>
													</div>';

												echo '</div>';

												$i++;

											}
										?>

										<a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
											<span class="carousel-control-prev-icon"></span>
										</a>
										<a class="carousel-control-next" href="#myCarousel" data-slide="next">
											<span class="carousel-control-next-icon"></span>
										</a>
									</div>
								</div>

								<p style="line-height: 2rem; text-align: justify; margin: 1rem 0;">
									###
									
								</p>
								
								<div style="text-align: center;">
									<span id="vertrekdatum">###</span>
									<br style="display: inline;">
									<a class="button small" style="margin-top: 0.8rem; font-style: normal;" href="about.php">Over ons</a>
								</div>

							</header>
						</article>


						<!-- Posts -->
							<section class="posts">
								
								<?php

									if(isset($_GET['page']))
									{
										echo '<h2 style="margin-left: .75rem; margin-top: 2.25rem; margin-bottom: .75rem;">
                    Blogposts (pagina '. $_GET['page'] . '):
                    </h2>';
									}


									$sql1 = 'SELECT * FROM posts ORDER BY `id` DESC LIMIT ' . $this_page_first_result . ',' .  $results_per_page;
									$result1 = mysqli_query($conn, $sql1);

									$array = array();
									while($row = mysqli_fetch_assoc($result1))
									{
										$array[] = $row;
									}

									foreach($array as $key => $value)
									{
										$imagePath = json_decode($array[$key]['pathToImage'], true);

										echo '<a class="post-item" href="post.php?postId='. $array[$key]['id'] .'">';
                    
                    echo '<span class="post-image skeleton" data-id="'. $array[$key]['id'] .'"></span>';

										echo '<div class="post-title-outer">
												<span class="post-title">' . $array[$key]['title'] . '</span>
												<span class="post-description">
                          <span class="post-info"> ' . $array[$key]['author'] . ' / ' . $array[$key]['dateCreated'] . '</span>' . 
                          $array[$key]['description'] . 
                        '</span>
											</div>
											<span class="post-button">
												<img src="assets/img/chevron-right.svg">
											</span>
										</a>';
									}

								?>
							</section>

						<!-- Footer -->
							<footer>
								<div class="pagination">
									<?php
										
										for($page=1; $page<=$number_of_pages; $page++)
										{
											if(isset($_GET['page']) && $_GET['page'] == $page)
											{
												echo '<a href="index.php?page='. $page . '" class="page active">'. $page .' </a>';
											}
											else
											{
												echo '<a href="index.php?page='. $page . '">'. $page .' </a>';
											}
										}

									?>
								</div>
							</footer>

					</div>

				<!-- Footer -->
					<footer id="footer">
						
					</footer>

				<!-- Copyright -->
					<div id="copyright">
						<ul><li>###</li></li></ul>
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
      <script>

        $(() => {

          var this_page_first_result = "<?php echo $this_page_first_result; ?>";
          var results_per_page = "<?php echo $results_per_page; ?>";

          setTimeout(() => {
            
            $.ajax({
              type: "POST",
              url: "includes/return-images.inc.php",
              data: { resultsPerPage: results_per_page, thisPageFirstResult: this_page_first_result },
              
              success: (data, textStatus, jqXHR) => {

                data = $.parseJSON(data);

                for(var i=0; i < data.length; i++){

                  $(`[data-id=${data[i].id}]`).each( (index, elem) => {

                    $(elem).removeClass('skeleton');

                    console.log(data[i].pathToImage.replace(/\\/g, '/'));
                    
                    if($(elem).is('span')) {

                      $(elem).css('background-image', 'url(' + data[i].pathToImage.replace(/\\/g, '/') + ')');
                    }

                    if($(elem).is('img')) {
                      if(elem.complete) $(elem).attr('src', data[i].pathToImage);
                    }

                  });
                }

              }
            }); 
          }, 3000);

        });

      </script>

	</body>
</html>