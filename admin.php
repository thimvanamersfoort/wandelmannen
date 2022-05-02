<?php

session_start();

if (!isset($_SESSION['userName']) || !isset($_SESSION['userId'])) {
	header('Location: login.php?error=unvalidatedPhpActivation');
	exit();
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Admin Panel</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="shortcut icon" type="image/jpg" href="images/favicon.ico"/>
		<link rel="stylesheet" href="assets/css/main.css" />
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<link rel="stylesheet" href="assets/trumbowyg/trumbowyg.min.css">
		<style>
			#texteditor * {
				margin: 0;
			}
			#texteditor > p > hr {
				margin: 0.5rem 0!important;
			}
		</style>

	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<a href="admin.php" class="logo">Admin Panel</a>
					</header>

				<!-- Nav -->
					<nav id="nav">
						<ul class="links">
							<li><a href="index.php">Home</a></li>
							<li class="active"><a href="admin.php">Admin panel</a></li>
							<li><a href="includes/logout.inc.php">Uitloggen</a></li>
						</ul>
						<ul class="icons">
							<li><a href="http://facebook.com" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
							<li><a href="login.php" class="icon regular fa-user"><span class="label">Login</span></a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
						<section>

							<?php if (isset($_GET['notif'])) {
       	if ($_GET['notif'] == 'upload') {
       		echo '<div class="box">
												<header>
													<h2 style="color:green">Het uploaden van je post is gelukt!</h2>
													<p>Je nieuwe post is <a href="post.php?postId=' .
       			$_GET['postId'] .
       			'" style="color:green">hier</a> te bekijken. Ook is de home-pagina met je laatste posts geupdated.</p>
													<p>Klik op de button hieronder om deze melding te sluiten.</p>
													<a href="admin.php" class="button small">Sluiten</a>
												</header>
											</div>';
       	} elseif ($_GET['notif'] == 'delete') {
       		echo '<div class="box">
												<header>
													<h2 style="color:red">Je post is verwijderd!</h2>
													<p>Je post is succesvol verwijderd van de website en uit de database.</p>
													<p>Klik op de button hieronder om deze melding te sluiten.</p>
													<a href="admin.php" class="button small">Sluiten</a>
												</header>
											</div>';
       	} elseif ($_GET['notif'] == 'edit') {
       		echo '<div class="box">
										<header>
											<h2 style="color:green">Je post is met succes aangepast!</h2>
											<p>Je aangepaste post is <a href="post.php?postId=' .
       			$_GET['postId'] .
       			'" style="color:green">hier</a> te bekijken. Ook is de home-pagina met je laatste posts geupdated.</p>
											<p>Klik op de button hieronder om deze melding te sluiten.</p>
											<a href="admin.php" class="button small">Sluiten</a>
										</header>
									</div>';
       	}
       } ?>

							<header>
								<h2>Welkom terug, <?php echo $_SESSION['userName']; ?></h2>
								<p>Begin nu met het maken van een nieuwe blogpost, of verander instellingen van je website.</p>
							</header>

							<!-- Facebook login part -->
							<div class="box">
								<h3 style="margin-bottom: 0.5rem;">Login met facebook:</h3>
								
								<?php
        include 'fb-init.php';

        $redirectURL =
        	'https://' . $_SERVER['SERVER_NAME'] . '/fb-callback.php';
        $permissions = [
        	'pages_manage_posts',
        	'pages_show_list',
        	'pages_manage_metadata',
        ];
        $loginUrl = $helper->getLoginUrl($redirectURL, $permissions);

        if (!empty($_SESSION['FBRLH_state']) && !empty($_SESSION['page'])) {
        	echo '<p style="line-height: 1.5rem;">Je bent succesvol ingelogd.<br> 
										Geselecteerde Facebookpagina: ' .
        		$_SESSION['page']['name'] .
        		'<br><br>
										<span style="font-size:0.8rem; font-style:italic">Zodra je een nieuwe post maakt, wordt er automatisch een notificatie geplaatst
										op je gelinkte facebook-account. Om dit uit te schakelen, moet je uitloggen en
										opnieuw inloggen op de blog.</span>
										</p>';
        } else {
        	echo '<a href="' .
        		htmlspecialchars($loginUrl) .
        		'" class="button solid" >Log in with Facebook!</a>';
        }
        ?>


							</div>

							<!-- Blog post part-->
								<div class="box"> 
									<h3>Maak een nieuwe blogpost:</h3>

									<form method="POST" action="includes/upload-photo.inc.php" enctype="multipart/form-data" id="newpostform" onsubmit="return getContent()">
										<div class="row gtr-uniform">

											<div class="col-9 col-12-xsmall">
												<h4>Titel:</h4>
												<input type="text" name="title" id="title" placeholder="De titel van je blogpost"/>
											</div>

											<div class="col-9 col-12-xsmall">
												<h4 id="descriptionHeader">Beschrijving:</h4>
												<textarea style="resize: none; font-size: 0.75rem;" name="description" id="description" placeholder="Een beschrijving van je blogpost" rows="4" maxlength="250"></textarea>
											</div>

											<div class="col-12">
												<h4>Inhoud:</h4>
												<div id="texteditor"></div>
												<textarea name="contents" id="contents" style="display: none;"></textarea>
											</div>

											<div class="col-6" style="padding-top: 1.5rem;">
												<h4>Upload een foto:</h4>
												<input type="file" accept=".png, .jpg, .jpeg, .gif" onchange="changePath()" name="image[]" id="image" style="display: none;" multiple> 
												<label for="image"  class="button primary small icon solid fa-search">Bestanden</label><br>
												<input type="checkbox" id="placeholder" name="placeholder">
												<label for="placeholder" style="font-size: 0.85rem;">Gebruik plaatsvervangende foto</label>
											</div>

											<div class="col-6" style="padding-top: 1.5rem;">
												<h4>Geselecteerde foto:</h4>
												<p id="pathToFile"><i>Geen pad bekend.</i></p>
											</div>

											<div class="col-4 col-6-small col-12-xsmall">
												<input type="submit" id="submit" name="submit" style="display: none;">
												<label for="submit" class="button primary fit solid">Post uploaden</label>
											</div>

											<div class="col-2 col-6-small col-12-xsmall">
												<input type="reset" id="reset" style="display: none;">
												<label for="reset" onclick="Reset()" class="button fit solid">Reset</label>
											</div>
											
										</div>
									</form>

									<?php if (isset($_GET['error'])) {
         	if ($_GET['error'] == 'unvalidatedPhpActivation') {
         		echo '<p><i style="color: red; word-wrap: break-word;">Er is een ongeauthoriseerde poging naar de PHP-servercode gemaakt. Gelieve hierboven gegevens in te vullen om een post te maken.</i></p>';
         	} elseif ($_GET['error'] == 'imageSizeTooBig') {
         		echo '<p><i style="color: red; word-wrap: break-word;">De grootte van de foto die u probeert te uploaden is te groot! Max. grootte: 5 MB.</i></p>';
         	} elseif ($_GET['error'] == 'imageUploadError') {
         		echo '<p><i style="color: red; word-wrap: break-word;">Er is iets fout gegaan met het uploaden van de foto! Foutcode: ' .
         			$_GET['errorType'] .
         			'</i></p>';
         	} elseif ($_GET['error'] == 'invalidImageType') {
         		echo '<p><i style="color: red; word-wrap: break-word;">Ongeldig bestand geselecteerd! Geldige bestandstypen: .PNG, .JPG, .JPEG, .GIF </i></p>';
         	} elseif ($_GET['error'] == 'emptyFields') {
         		echo '<p><i style="color: red; word-wrap: break-word;">Niet alle velden zijn ingevuld! Het is verplicht om alle tekstvelden in te vullen, maar een foto toevoegen is optioneel.</i></p>';
         	}
         } ?>
							</div>

							<p><i>Zodra je je blogpost gepubliceerd hebt, kijk dan op de <a href="index.php">homepagina</a>, waar je 
							vervolgens je post bovenaan zult zien verschijnen. Als je vervolgens op de post klikt, wordt je meegenomen
							naar de aparte pagina die is gecreërd voor je nieuwe post.</i></p>

							<!-- Blog edit part-->
								<div class="box" style="overflow:hidden;"> 
									<h3>Verander / verwijder blogposts</h3>
									<p><i>Hieronder zie je alle blogposts die momenteel op de website staan. Nu kan je er voor kiezen om blogposts aan te passen.</i></p>
									
									<?php require_once 'page-components/admin-blog-edit.php'; ?>
							</div>
							
							<!-- Password hash -->
							<?php if (isset($_SESSION['userName'])) {
       	if ($_SESSION['userName'] == 'admin') {
       		require_once 'page-components/admin-pwd-hash.php';
       	}
       } else {
       	header('Location: admin.php?error=unvalidatedPhpActivation');
       	exit();
       } ?>

							<!-- Date to Time -->
							<?php if (isset($_SESSION['userName'])) {
       	if ($_SESSION['userName'] == 'admin') {
       		require_once 'page-components/admin-id-convert.php';
       	}
       } else {
       	header('Location: admin.php?error=unvalidatedPhpActivation');
       	exit();
       } ?>
							
						</section>
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
		
			<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<script>window.jQuery || document.write('<script src="assets/js/jquery.min.js"><\/script>')</script>
			<script src="assets/trumbowyg/trumbowyg.min.js"></script>
			<script src="assets/trumbowyg/nl.min.js"></script>

			<script type="text/javascript">

				$('#texteditor').trumbowyg({
					svgPath: 'assets/trumbowyg/icons.svg',
					btns: [
						['undo', 'redo', 'removeformat'], // Only supported in Blink browsers
						['formatting'],
						['strong', 'em', 'del'],
						['superscript', 'subscript'],
						['link'],
						['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
						['unorderedList', 'orderedList'],
						['horizontalRule'],
            ['fullscreen']
					],
					lang: 'nl',
					tagsToRemove: ['script', 'link'],
					minimalLinks: true,
					urlProtocol: true,
					defaultLinkTarget: '_blank',
					autogrowOnEnter: true,
          removeformatPasted: true
				});

				function getContent(){
					document.getElementById("contents").value = $('#texteditor').trumbowyg('html');
				};

				function changePath()
				{
					var x = document.getElementById("image");

					if(x.files.length > 0)
					{
						document.getElementById("pathToFile").innerHTML = "";

						for(var i = 0; i <= x.files.length -1; i++)
						{
							var fname = x.files.item(i).name;
							var fsize = x.files.item(i).size;

							document.getElementById("pathToFile").innerHTML += fname + " (<b>Grootte: " + (fsize / 1000000).toFixed(2) + " MB</b>)<br>";
						}
					}
				}
				function Reset()
				{
					try{
						document.getElementById("pathToFile").innerHTML = "<i>Geen pad bekend.</i>";
						$('#texteditor').trumbowyg('empty');
						$('#newpostform')[0].reset();
					}
					catch(err){
					}

				}

			</script>

			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>