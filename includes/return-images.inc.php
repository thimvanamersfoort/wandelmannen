<?php

require_once 'dbh.inc.php';

if (isset($_POST['resultsPerPage']) && isset($_POST['thisPageFirstResult'])) {
	$resultsPerPage = $_POST['resultsPerPage'];
	$thisPageFirstResult = $_POST['thisPageFirstResult'];

	$sql =
		'SELECT id, pathToImage FROM posts ORDER BY `id` DESC LIMIT ' .
		$thisPageFirstResult .
		',' .
		$resultsPerPage;
	$result = mysqli_query($conn, $sql);

	$array = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$array[] = $row;
	}

	foreach ($array as $key => $value) {
		$imagePath = json_decode($array[$key]['pathToImage'], true);
		$first_item = reset($imagePath);
		$array[$key]['pathToImage'] = $first_item;
	}

	echo json_encode($array);
}
