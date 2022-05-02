<?php

if (isset($_POST['submit']) && isset($_POST['postId'])) {
	$postId = $_POST['postId'];

	if (
		isset($_POST['name']) &&
		!empty($_POST['name']) &&
		isset($_POST['comment']) &&
		!empty($_POST['comment'])
	) {
		$name = $_POST['name'];
		$comment = $_POST['comment'];

		require_once '../includes/dbh.inc.php';

		$sql = 'SELECT * FROM posts WHERE id=?;';
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header('Location: ../post.php?postId=' . $postId . '&error=sqlError1');
			exit();
		} else {
			mysqli_stmt_bind_param($stmt, 's', $postId);
			mysqli_stmt_execute($stmt);

			$result = mysqli_stmt_get_result($stmt);

			if ($row = mysqli_fetch_assoc($result)) {
				$allComments = json_decode($row['comments'], true);
				if (empty($allComments)) {
					$allComments = [];
				}

				$newComment = [$name => $comment];
				$newCommentsList = json_encode(array_merge($allComments, $newComment));

				$sql = 'UPDATE `posts` SET `comments`=? WHERE `id`=?;';
				$stmt = mysqli_stmt_init($conn);

				if (!mysqli_stmt_prepare($stmt, $sql)) {
					header(
						'Location: ../post.php?postId=' . $postId . '&error=sqlError2'
					);
					exit();
				} else {
					mysqli_stmt_bind_param($stmt, 'ss', $newCommentsList, $postId);
					mysqli_stmt_execute($stmt);

					header(
						'Location: ../post.php?postId=' . $postId . '#allcomments-header'
					);
					exit();
				}
			}
		}
	} else {
		header(
			'Location: ../post.php?postId=' .
				$postId .
				'&error=emptyFields#newcomment-header'
		);
		exit();
	}
} else {
	header('Location: ../post.php?postId=' . $postId . '&error=unauthorized');
	exit();
}
