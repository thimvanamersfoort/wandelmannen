<?php

if (isset($_POST['index']) && isset($_POST['postId'])) {
	$index = $_POST['index'];
	$postId = $_POST['postId'];

	require_once '../includes/dbh.inc.php';

	$sql = 'SELECT * FROM posts WHERE id=?;';
	$stmt = mysqli_stmt_init($conn);

	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo 'sqlError1';
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, 's', $postId);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);

		if ($row = mysqli_fetch_assoc($result)) {
			$allComments = json_decode($row['comments'], true);
			if (empty($allComments)) {
				echo 'emptyRow';
			}
			unset($allComments[$index]);

			$newCommentsList = json_encode($allComments);

			$sql = 'UPDATE `posts` SET `comments`=? WHERE `id`=?;';
			$stmt = mysqli_stmt_init($conn);

			if (!mysqli_stmt_prepare($stmt, $sql)) {
				echo 'sqlError2';
				exit();
			} else {
				mysqli_stmt_bind_param($stmt, 'ss', $newCommentsList, $postId);
				mysqli_stmt_execute($stmt);

				echo 'success';
				exit();
			}
		}
	}
}
