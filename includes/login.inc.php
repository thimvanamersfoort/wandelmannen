<?php

if (isset($_POST['login-submit'])) {
	require 'dbh.inc.php';

	$username = $_POST['username'];
	$password = $_POST['password'];

	if (empty($username) || empty($password)) {
		header('Location: ../login.php?error=emptyFields');
		exit();
	} else {
		$sql = 'SELECT * FROM users WHERE username=?;';
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header('Location: ../login.php?error=sqlError');
			exit();
		} else {
			mysqli_stmt_bind_param($stmt, 's', $username);
			mysqli_stmt_execute($stmt);

			$result = mysqli_stmt_get_result($stmt);

			if ($row = mysqli_fetch_assoc($result)) {
				$pwdCheck = password_verify($password, $row['password']);

				if ($pwdCheck == false) {
					header('Location: ../login.php?error=incorrectPassword');
					exit();
				} elseif ($pwdCheck == true) {
					echo '<b>SQL database row data:</b> <br>';
					echo $row['id'];
					echo '<br>';
					echo $row['username'] . '<br>';

					session_start();
					$_SESSION['userId'] = $row['id'];
					$_SESSION['userName'] = $row['username'];

					echo '<br><b>Session data:</b><br>';
					echo $_SESSION['userId'];
					echo '<br>';
					echo $_SESSION['userName'];

					header('Location: ../admin.php?login=success');
					exit();
				} else {
					header('Location: ../login.php?error=unknownLoginError');
					exit();
				}
			} else {
				header('Location: ../login.php?error=userNotFound');
				exit();
			}
		}
	}
} else {
	header('Location: ../login.php?error=unvalidatedPhpActivation');
	exit();
}
