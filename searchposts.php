<?php
session_start();

include_once 'dbcn.php';
$curr_user = $_SESSION['user_name'];
$filter = $_GET['searchterm'];

$results = $mypost->search($curr_user, $filter);

echo $results;
?>
