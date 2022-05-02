<?php

session_start();

if (!isset($_SESSION['userName']) || !isset($_SESSION['userId'])) {
	unset($_SESSION['tempId']);
	unset($_SESSION['tempPath']);
	header('Location: ../admin.php?error=unvalidatedPhpActivation');
	exit();
} elseif (!isset($_SESSION['tempId']) || !isset($_SESSION['tempPath'])) {
	unset($_SESSION['tempId']);
	unset($_SESSION['tempPath']);
	header('Location: ../admin.php?error=unvalidatedPhpActivation');
	exit();
} else {
	require_once 'functions.inc.php';
	require_once 'dbh.inc.php';

	$sql = 'DELETE FROM `posts` WHERE `id` = ?;';
	$stmt = mysqli_stmt_init($conn);

	if (!mysqli_stmt_prepare($stmt, $sql)) {
		unset($_SESSION['tempId']);
		unset($_SESSION['tempPath']);
		header('Location: ../admin.php?error=sqlError');
		exit();
	} else {
		$photoData = json_decode($_SESSION['tempPath']);

		if (!empty($photoData)) {
			foreach ($photoData as $key => $val) {
				unlink('../uploads/images/' . chopStringToImageName($val));
			}
		}

		mysqli_stmt_bind_param($stmt, 's', $_SESSION['tempId']);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);

		if ($result == false) {
			unset($_SESSION['tempId']);
			unset($_SESSION['tempPath']);
			header('Location: ../admin.php?notif=delete');
			exit();
		} else {
			unset($_SESSION['tempId']);
			unset($_SESSION['tempPath']);
			header('Location: ../admin.php?error=sqlError');
			exit();
		}
	}
}
