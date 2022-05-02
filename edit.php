<?php

session_start();
require_once 'includes/functions.inc.php';

if (!isset($_SESSION['userName']) || !isset($_SESSION['userId'])) {
	header('Location: admin.php?error=unvalidatedPhpActivation');
	exit();
} elseif (!isset($_GET['postId'])) {
	require_once 'includes/dbh-posts.inc.php';

	header(
		'Location: admin.php?error=postIdNotFound&threadId=' .
			mysqli_thread_id($conn)
	);
	exit();
} else {
	require_once 'includes/dbh.inc.php';

	$sql = 'SELECT * FROM `posts` WHERE `id`=?;';

	$stmt = mysqli_stmt_init($conn);

	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header('Location: ../admin.php?error=sqlError');
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, 's', $_GET['postId']);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);

		$row = mysqli_fetch_assoc($result);

		if (empty($row)) {
			header('Location: admin.php?error=noMatchingRowFound');
			exit();
		} else {
			$postId = $row['id'];
			$postTitle = $row['title'];
			$postDescription = $row['description'];
			$postContents = $row['contents'];
			$postAuthor = $row['author'];
			$postPathToImage = $row['pathToImage'];
			$postDateCreated = $row['dateCreated'];

			$_SESSION['tempId'] = $postId;
			$_SESSION['tempPath'] = $postPathToImage;
		}
	}
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Blogpost aanpassen</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="shortcut icon" type="image/jpg" href="images/favicon.ico"/>
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<link rel="stylesheet" href="assets/trumbowyg/trumbowyg.min.css">
		<style>
			#texteditor * {
				margin: 0;
			}
			#texteditor > p > hr {
				margin: 0.5rem 0!important;
			}
			.comment-td{
				line-height: 1.1rem;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis; 
			}
			a{
				cursor: pointer;
			}
		</style>
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<a href=<?php echo 'edit.php?postId=' .
      	$postId; ?> class="logo">Blogposts aanpassen</a>
					</header>

				<!-- Nav -->
					<nav id="nav">
						<ul class="links">
							<li><a href="index.php">Home</a></li>
							<li><a href="admin.php">Admin Panel</li>
                            <li class="active"><a href="edit.php">Blogpost Aanpassen</li>
						</ul>
						<ul class="icons">
							<li><a href="http://facebook.com" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
							<li><a href="login.php" class="icon regular fa-user"><span class="label">Login</span></a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
    	                <section>
							
						<?php if (isset($_GET['error'])) {
      	if ($_GET['error'] == 'imageUploadError') {
      		echo '<div class="box">
												<header>
													<h2 style="color:red">Error:</h2>
													<p>Er is een onverwachte fout opgetreden tijdens het uploaden van je foto. Kijk in de URL voor meer informatie.</p>
													<p>Klik op de button hieronder om deze melding te sluiten.</p>
													<a href="edit.php" class="button small">Sluiten</a>
												</header>
											</div>';
      	} elseif ($_GET['error'] == 'imageSizeTooBig') {
      		echo '<div class="box">
												<header>
													<h2 style="color:red">Error:</h2>
													<p>De grootte van je geuploade foto overschrijdt de maximale grootte van 5 MB. Upload aub een foto met een kleinere bestandsgrootte.</p>
													<p>Klik op de button hieronder om deze melding te sluiten.</p>
													<a href="edit.php" class="button small">Sluiten</a>
												</header>
											</div>';
      	} elseif ($_GET['error'] == 'edit') {
      		echo '<div class="box">
												<header>
													<h2 style="color:green">Je post is met succes aangepast!</h2>
													<p>Je aangepaste post is <a href="admin.php" style="color:green">hier</a> te bekijken. Ook is de home-pagina met je laatste posts geupdated.</p>
													<p>Klik op de button hieronder om deze melding te sluiten.</p>
													<a href="admin.php" class="button small">Sluiten</a>
												</header>
											</div>';
      	}
      } ?>

							<h2>Aanpassen / verwijderen van blogpost:</h2>
							<p><b>Momenteel geselecteerde blogpost: </b><?php echo '<i>' .
       	$postTitle .
       	'</i>'; ?><br>
							<b>Datum van laatste aanpassing: </b><?php echo '<i>' .
       	$postDateCreated .
       	'</i>'; ?></p>
							
                            <div class="box">

                            	<h3>Blogpost aanpassen:</h3>

                                <form method="POST" action="includes/edit-post.inc.php" enctype="multipart/form-data" id="editpostform" onsubmit="return getContent()">
									<div class="row gtr-uniform">

										<div class="col-9 col-12-xsmall">
											<h4>Titel:</h4>
											<textarea style="resize: none;" name="title" id="title" rows="2"><?php echo $postTitle; ?></textarea>
										</div>

										<div class="col-9 col-12-xsmall">
											<h4 id="descriptionHeader">Beschrijving:</h4>
											<textarea style="resize: none; font-size: 0.75rem;" name="description" id="description" placeholder="Een beschrijving van je blogpost" rows="4" maxlength="250"><?php echo $postDescription; ?></textarea>
										</div>

										<div class="col-12">
											<h4>Inhoud:</h4>
											<div id="texteditor"></div>
											<textarea name="contents" id="contents" style="display: none;"></textarea>
										</div>

										<div class="col-6">
											<h4>Foto aanpassen:</h4>
											<input type="file" accept=".png, .jpg, .jpeg, .gif" onchange="changePath()" name="image[]" id="image" style="display: none;" multiple> 
											<label for="image"  class="button primary small icon solid fa-search">Bestanden</label>
										</div>

										<div class="col-6">
											<h4>Geselecteerde foto's:</h4>
											<p id="pathToFile" style="word-wrap: break-word;"><i>

											<?php
           $tempPath = json_decode($postPathToImage);

           if (!empty($tempPath) && is_array($tempPath)) {
           	foreach ($tempPath as $key => $val) {
           		echo chopStringToImageName($val) . '<br>';
           	}
           } elseif (!empty($tempPath) && !is_array($tempPath)) {
           	echo chopStringToImageName($tempPath) . '<br>';
           } else {
           	echo 'Geen foto geupload';
           }
           ?>
											
											</i></p>
										</div>

										<div class="col-4 col-6-small col-12-xsmall">
											<input type="submit" id="submit" name="submit" style="display: none;">
											<label for="submit" class="button primary fit solid">Post Aanpassen</label>
										</div>
										<div class="col-4 col-6-small col-12-xsmall">
											<a href="includes/delete-post.inc.php" class="button primary fit solid">Post verwijderen</a>
										</div>
									</div>
								</form>

								<?php if (isset($_GET['error'])) {
        	if ($_GET['error'] == 'unvalidatedPhpActivation') {
        		echo '<p><i style="color: red; word-wrap: break-word;">Er is een ongeauthoriseerde poging naar de PHP-servercode gemaakt. Gelieve hierboven gegevens in te vullen om een post te maken.</i></p>';
        	} elseif ($_GET['error'] == 'imageSizeTooBig') {
        		echo '<p><i style="color: red; word-wrap: break-word;">De grootte van de foto die u probeert te uploaden is te groot! Max. grootte: 2,5 MB.</i></p>';
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
								<br>
								<h3 id="deleteComments-header">Comments verwijderen:</h3>

								<div class="table-wrapper">
									<table class="alt">
										<thead>
											<tr>
												<th>Naam:</th>
												<th>Comment:</th>
											</tr>
										</thead>
										<tbody>
											<?php
           $sql = 'SELECT * FROM posts WHERE id=?;';
           $stmt = mysqli_stmt_init($conn);
           $postId = $_GET['postId'];
           $i = 1;

           if (!mysqli_stmt_prepare($stmt, $sql)) {
           	header(
           		'Location: edit.php?postId=' .
           			$postId .
           			'&error=sqlCommentError1'
           	);
           	exit();
           } else {
           	mysqli_stmt_bind_param($stmt, 's', $postId);
           	mysqli_stmt_execute($stmt);

           	$result = mysqli_stmt_get_result($stmt);

           	if ($row = mysqli_fetch_assoc($result)) {
           		$allComments = json_decode($row['comments'], true);

           		if (empty($allComments)) {
           			echo '<p style="font-style: italic; font-size: 1.1rem;">Er zijn nog geen comments geplaatst.</p>';
           		} else {
           			foreach ($allComments as $key => $val) {
           				$key1 = "'" . $key . "'";

           				echo '<tr id="comment' . $i . '">';
           				echo '<td class="comment-td" style="width: 20%; max-width: 200px;">' .
           					$key .
           					'</td>';
           				echo '<td class="comment-td" style="width: 75%; max-width: 500px;">' .
           					$val .
           					'</td>';
           				echo '<td style="width: 5%; line-height: 1.1rem; text-align: center;"><a onclick="removeComment(' .
           					$key1 .
           					')" class="icon regular fa-minus-square"></td>';
           				echo '</tr>';
           				$i++;
           			}
           		}
           	}
           }
           ?>

										</tbody>
									</table>
								</div>

                            </div>

                        
                        
                        </section>
					</div>

				<!-- Footer -->
					<footer id="footer">
					
					</footer>
				<!-- Copyright -->
					<div id="copyright">
						<ul><li>###

			</div>

		<!-- Scripts -->
			<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<script>window.jQuery || document.write('<script src="assets/js/jquery.min.js"><\/script>');</script>
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

				$('#texteditor').trumbowyg('html', <?php echo "'" .
    	addslashes($postContents) .
    	"'"; ?>);

				function getContent(){
					document.getElementById("contents").value = $('#texteditor').trumbowyg('html');
				}

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
						$('#editpostform')[0].reset();
					}
					catch(err){
					}

				}

				function removeComment(i){
					
					var _postId = "<?php echo $_GET['postId']; ?>";

					$.ajax({
						type: "POST",
						url: 'page-components/edit-remove-comment.php',
						data:{index:i, postId: _postId},
						success:function(result) {
							if(result != "success"){
								alert(result);
							}
							window.location.reload(true);
							window.location.href = "#deleteComments-header";
						}
					});
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