<?php

session_start();

if(isset($_SESSION['userName']) || isset($_SESSION['userId']))
{
	header("Location: admin.php");
	exit();
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Inloggen</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="shortcut icon" type="image/jpg" href="images/favicon.ico"/>
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
				

					<header id="header">
						<h1 class="logo">Log in bij je account</h1>
					</header>
				

				<!-- Nav -->
					<nav id="nav">
						<ul class="links">
							<li><a href="index.php">Home</a></li>
							<li class="active"><a href="login.php">Inloggen</a></li>
						</ul>
						<ul class="icons">
							<li><a href="http://facebook.com" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
							<li><a href="login.php" class="icon regular fa-user"><span class="label">Login</span></a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
						<section>
							<h2>Log in bij je admin panel</h2>

							<form method="POST" action="includes/login.inc.php">
								<div class="fields">
									<div class="field">
										<label for="username">Gebruikersnaam</label>
										<input type="text" name="username" id="username"/>
									</div>
									<div class="field">
										<label for="password">Wachtwoord</label>
										<input type="password" name="password" id="password"/>
									</div>
								</div>

								<ul class="actions">
									<li><input type="submit" name="login-submit" value="Login"/></li>
								</ul>
							</form>

							<p ><i style="color: red; word-wrap: break-word;">
								<?php
									if(isset($_GET['error']))
									{
										if($_GET['error'] == "emptyFields")
										{
											echo 'Vul alsjeblieft alle gegevens in!';
										}
										else if($_GET['error'] == "sqlError")
										{
											echo 'Gefaald om verbinding te maken met de database. Neem contact op met de website-beheerder.';
										}
										else if($_GET['error'] == "userNotFound")
										{
											echo 'Geen overeenkomende gebruiker gevonden in de database!';
										}
										else if($_GET['error'] == "unvalidatedPhpActivation")
										{
											echo 'Ongeauthoriseerde toegang tot de database. Log hierboven aub in.';
										}
										else if($_GET['error'] == "incorrectPassword")
										{
											echo 'Vul alstublieft het correcte wachtwoord in.';
										}
										else if($_GET['error'] == "unknownLoginError")
										{
											echo 'Er is iets fout gegaan! Probeer opnieuw in te loggen.';
										}
									}
								?>
							</i></p>



						</section>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<section>
							<div class="box">
							<p><i>Voor persoonlijke redenen zijn de volgende gegevens weggehaald. Bedankt voor uw begrip.
								</i></p>
							</div>


						</section>
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

	</body>
</html>