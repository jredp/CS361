<?php
session_start();
include_once 'dbcn.php';

if (!isset($_SESSION['user_name'])) {
	// header("Location: index.php?loggedin=no");
	echo "<script>window.location = 'index.php?loggedin=no'</script>";
}

// TODO: major refactor!!
// lots of help from this post on the tricky parts
// http://stackoverflow.com/questions/18805497/php-resize-image-on-upload
if (isset($_POST['submit'])) {
	// if this is still zero-length at the end we're good
	$status = '';
	if (!isset($_POST['content']) || $_POST['content'] == '')
		$status = 'post content is required';
	$filename = null;
	$upload_dir = $mypost->getImgDir();
	$valid_files = $mypost->getValidFileTypes();
	// just a guess, didn't do a lot of research on bytes -> mb conversion
	$max_size = 1024000;
	$max_dim = 400;
	// if there is an image
	if ($_FILES['post_image']['error'] == 0) {

		// get the dims
		list($width, $height, $type, $attr) = getimagesize($_FILES['post_image']['tmp_name']);
		// only need this to get the extension
		$filename = $upload_dir . $_FILES['post_image']['name'];
		$img_type = '.' . strtolower(pathinfo($filename, PATHINFO_EXTENSION));

		$target_filename = $_FILES['post_image']['tmp_name'];
		$raw_file = $_FILES['post_image']['tmp_name'];

		// make sure it's valid and not too big
		$too_big = false;
		if (!in_array($img_type, $valid_files)) {
			$status = 'invalid image filetype';
		} else if ($_FILES['post_image']['size'] > $max_size) {
			$status = 'image file over 1 Mb limit';
		} else {
			// resize image if too large
			if ($width > $max_dim || $height > $max_dim) {
				// make sure we resize it
				$too_big = true;
				$size = getimagesize($raw_file);
				$ratio = $size[0] / $size[1];
				if ($ratio > 1) {
					$width = $max_dim;
					$height = $max_dim / $ratio;
				} else {
					$width = $max_dim * $ratio;
					$height = $max_dim;
				}
			}
		}

		// do this only if file too large
		if ($too_big) {
			$filename = $_SESSION['user_name'] . '_' . date("YmdHis") . $img_type;
			$src = imagecreatefromstring(file_get_contents($raw_file));
			$dst = imagecreatetruecolor($width, $height);
			imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			imagedestroy($src);

			if ($img_type == 'png')
				imagepng($dst, $target_filename);
			else if ($img_type == 'gif')
				imagegif($dst, $target_filename);
			else
				imagejpeg($dst, $target_filename);
			imagedestroy($dst);
		}

		if (move_uploaded_file($target_filename, $upload_dir . $filename)) {
		} else {
			$status = 'error uploading file';
		}
	}

	if ($status == '') {
		if ($mypost->create($_SESSION['user_name'], $_POST['content'], $filename)) {
			$status = 'success';
		} else {
			$status = 'fail';
		}
		// header("Location: landing.php?action=insert&status=".$status);
		echo "<script>window.location = 'landing.php?action=insert&status=" . $status . "'</script>";
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
