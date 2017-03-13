<?php
session_start();
include_once 'dbcn.php';

if (!isset($_SESSION['user_name'])) {
	header("Location: index.php?loggedin=no");
}

if (isset($_POST['submit'])) {
	$status = '';
	if (!isset($_POST['content']) || $_POST['content'] == '')
		$status = 'post content is required';
	$filename = null;
	$upload_dir = $mypost->getImgDir();
	$valid_files = $mypost->getValidFileTypes();
	// just a guess, didn't do a lot of research on bytes -> mb conversion
	$max_size = 1024000;
	if ($_FILES['post_image']['error'] == 0) {

		$filename = $upload_dir . $_FILES['post_image']['name'];
		$img_type = '.' . pathinfo($filename, PATHINFO_EXTENSION);

		if (!in_array(strtolower($img_type), $valid_files)) {
			$status = 'invalid image filetype';
		} else if ($_FILES['post_image']['size'] > $max_size) {
			$status = 'image file over 1 Mb limit';
		} else {
			// assign unique name to image
			$filename = $_SESSION['user_name'] . '_' . date("YmdHis") . $img_type;
			if (move_uploaded_file($_FILES['post_image']['tmp_name'], $upload_dir . $filename)) {
			} else {
				$status = 'error uploading file';
			}
		}

	}

	if ($status == '') {
		if ($mypost->create($_SESSION['user_name'], $_POST['content'], $filename)) {
			$status = 'success';
		} else {
			$status = 'fail';
		}
		header("Location: landing.php?action=insert&status=".$status);
	} else {
		echo $status;
	}
}
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="">
</head>
<body>
<p><a href="landing.php">cancel</a> go back to the landing page&nbsp;&nbsp;&nbsp;
<a href="logout.php">logout</a></p>
<form enctype="multipart/form-data" method="post">
<table>
<tr>
<td valign="top">create post:</td><td><textarea name="content" cols="40" rows="5"></textarea></td>
</tr>
<tr>
<td>upload image:</td><td><input type="file" name="post_image"></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" name="submit" value="create post"></td>
</tr>
</table>
</form>
</body>
</html> 
