<?php
session_start();
include_once 'dbcn.php';
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php?loggedin=no");
}
if (isset($_POST['submit'])) {
    // print_r($_POST);
    // print_r($_FILES);
    $msg;
    $upload_dir = 'postImages/';
    $valid_files = array('.png', '.jpeg', '.jpg', '.gif');
    // just a guess, didn't do a lot of research on bytes -> mb conversion
    $max_size = 1024000;
    $filename = $upload_dir . $_FILES['post_image']['name'];

    $img_type = '.' . pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array($img_type, $valid_files)) {
	$msg = 'invalid image filetype';
    } else if ($_FILES['post_image']['size'] > $max_size) {
	$msg = 'image file over 1 Mb limit';
    } else {
	// assign unique name to image
	$filename = $_SESSION['user_name'] . '_' . date("YmdHis") . $img_type;
	if (move_uploaded_file($_FILES['post_image']['tmp_name'], $upload_dir . $filename)) {
	    if ($mypost->create($_SESSION['user_name'], $_POST['content'], $filename)) {
		$msg = 'post created';
	    } else {
		$msg = 'error creating post';
	    }
	} else {
	    $msg = 'error uploading file';
	}
    }
    echo $msg;
    header("Location: landing.php?action=insert&msg=".$msg);
}
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="">
</head>
<body>
<form enctype="multipart/form-data" method="post">
<table>
<tr>
<td valign="top">create post:</td><td><textarea name="content" cols="40" rows="5"></textarea></td>
</tr>
<tr>
<td>upload image:</td><td><input type="file" name="post_image"></td>
</tr>
<!--
<tr>
<td>rename file (optional):</td><td><input type="text" name="file_rename"></td>
</tr>
-->
<tr>
<td colspan="2" align="center"><input type="submit" name="submit" value="create post"></td>
</tr>
</table>
</form>
<p><a href="landing.php">back</a> to the landing page</p>
<p><a href="logout.php">logout</a></p>
</body>
</html> 
