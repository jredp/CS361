<?php
session_start();
include_once 'dbcn.php';

$filter = $_GET['postfilter'];

if ($filter == 'mine')
	$results = $mypost->view($filter, $_SESSION['user_name']);
else if ($filter == 'following')
	$results = $mypost->view($filter, $_SESSION['user_name']);
else if ($filter == 'local')
	$results = $mypost->view($filter, $_SESSION['user_zip']);
else // we want all posts
	$results = $mypost->view($filter);

echo $results;
?>
