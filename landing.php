<?php
session_start();
include_once 'dbcn.php';

if (!isset($_SESSION['user_name'])) {
	// header("Location: index.php?loggedin=no");
	echo "<script>window.location = 'index.php?loggedin=no'</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title></title>
<script src="js/misc.js"></script>
<link rel="stylesheet" type="text/css" href="">
</head>
<body> 
<?php 
if (isset($_GET['action'])) {
    if (isset($_GET['status']))
		echo '<p>' . $_GET['action'] . " " . $_GET['status'] . '</p>';
        // echo '<p>' . printMsg($_GET['action'], $_GET['status']) . '</p>';
} else {
    echo '<p>welcome back, ' . $_SESSION['user_name'] . '</p>';
}

if (isset($_POST['follow'])) {
    $status = '';
    if ($mypost->follow($_POST['post_id'], $_POST['user_id'])) {
		$status = 'success';
    } else {
		$status = 'fail';
    }
    echo "<script>window.location = 'landing.php?action=follow&status=" . $status . "'</script>";
}

if (isset($_POST['unfollow'])) {
    $status = '';
    if ($mypost->unfollow($_POST['post_id'], $_POST['user_id'])) {
		$status = 'success';
    } else {
		$status = 'fail';
    }
    echo "<script>window.location = 'landing.php?action=unfollow&status=" . $status . "'</script>";
}

?>
<table>
<tr>
<td>filter posts</td>
<td>
<select id="ddlPost" name="cat" onchange="showPosts(this.value);">
<option value="">select post category</option>
<option value="mine" selected="selected">my posts</option>
<option value="all">all posts</option>
<option value="following">posts I'm following</option>
<option value="local">local posts</option>
</select>
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>search by content</td>
<td><input type="text" name="search" id="txtSearch"></td>
<td><button id="btn-search" name="btn-search" onclick="searchPosts()">search</button></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><a href="create-post.php">create new post</a>&nbsp;&nbsp;&nbsp;<a href="logout.php">logout</a></td>
</tr>
</table>
<br>
<div id="post-title"></div>
<div id="post-list"></div>
</body>
</html> 
