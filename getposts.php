<?php
session_start();
include_once 'dbcn.php';

if (isset($_GET['postfilter'])) {
	$results = $mypost->view($_SESSION['user_name'], $_GET['postfilter']);
} else {
	$results = $mypost->view($_SESSION['user_name']);
}

echo $results;
?>
