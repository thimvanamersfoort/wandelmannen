<?php
session_start();
require 'functions.inc.php';

if (isset($_POST['submit'])) {
	if (
		empty($_POST['title']) ||
		empty($_POST['description']) ||
		empty($_POST['contents'])
	) {
		header('Location: ../admin.php?error=emptyFields');
		exit();
	} else {
		$_SESSION['post_Id'] = idate('U');
		$_SESSION['post_Title'] = $_POST['title'];
		$_SESSION['post_Description'] = $_POST['description'];
		$_SESSION['post_Contents'] = $_POST['contents'];
		$_SESSION['post_Author'] = $_SESSION['userName'];
		$_SESSION['post_DateCreated'] = date('d-m-Y');

		if (!empty($_POST['placeholder'])) {
			$_SESSION['post_PathToImage'] =
				chopStringToRoot(__FILE__) . '\images\placeholder.gif';
			header('Location: upload-post.inc.php');
			exit();
		} else {
			$allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
			$imageNames = array_filter($_FILES['image']['name']);
			$post_PathToImage = [];
			$failedFiles = [];
			$index = 0;

			if (!empty($imageNames)) {
				foreach ($_FILES['image']['name'] as $key => $val) {
					$imageName = $_FILES['image']['name'][$key];
					$tmpFileExt = explode('.', $imageName);
					$fileExt = strtolower(end($tmpFileExt));

					if (in_array($fileExt, $allowedExt)) {
						if ($_FILES['image']['error'][$key] === 0) {
							if ($_FILES['image']['size'][$key] < 20000000) {
								date_default_timezone_set('Europe/Amsterdam');
								$imageNameNew =
									date('dmY_His') .
									'_' .
									$_SESSION['userName'] .
									'_' .
									$index .
									'.' .
									$fileExt;
								$imageDest = '../uploads/images/' . $imageNameNew;

								$index += 1;

								move_uploaded_file(
									$_FILES['image']['tmp_name'][$key],
									$imageDest
								);

								array_push(
									$post_PathToImage,
									chopStringToRoot(__FILE__ . $imageNameNew) .
										'\uploads\images\\' .
										$imageNameNew
								);
							} else {
								array_push($failedFiles, $_FILES['image']['name'][$key]);
							}
						} else {
							array_push($failedFiles, $_FILES['image']['name'][$key]);
						}
					} else {
						array_push($failedFiles, $_FILES['image']['name'][$key]);
					}
				}

				if (!empty($post_PathToImage)) {
					// geen errors gevonden bij min 1 foto
					$_SESSION['post_PathToImage'] = $post_PathToImage;

					header('Location: upload-post.inc.php');
					exit();
				}
				// errors gevonden bij alle fotos
				else {
					header('Location: upload-post.inc.php');
					exit();
				}
			}
			//geen fotos geupload
			else {
				header('Location: upload-post.inc.php');
				exit();
			}
		}
	}
} else {
	header('Location: ../admin.php?error=unvalidatedPhpActivation');
	exit();
}
