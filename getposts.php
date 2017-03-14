<?php
session_start();
include_once 'dbcn.php';
$curr_user = $_SESSION['user_name'];
$filter = $_GET['postfilter'];

if ($filter == 'mine')
	$results = $mypost->view($curr_user, $filter, $curr_user);
else if ($filter == 'following')
	$results = $mypost->view($curr_user, $filter, $curr_user);
else if ($filter == 'local')
	$results = $mypost->view($curr_user, $filter, $curr_user); // TODO
else // we want all posts
	$results = $mypost->view($curr_user, $filter);

echo $results;
?>
