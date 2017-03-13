<?php
session_start();
include_once 'dbcn.php';

if (!isset($_SESSION['user_name'])) {
	header("Location: index.php?loggedin=no");
}

if (isset($_POST['delete'])) {
    $status = '';
    if ($mypost->delete($_POST['id'], $_POST['post_img'])) {
	$status = 'success';
    } else {
	$status = 'fail';
    }
    header("Location: landing.php?action=delete&status=".$status);
}
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="">
</head>
<body>
<p>are you SURE you want to delete this record? <a href="landing.php?action=delete">cancel</a>
&nbsp;&nbsp;&nbsp;<a href="logout.php">logout</a></p>
<?php
// populate the form
if (isset($_GET['pid'])) {
    extract($mypost->getId($_GET['pid']));
?>
<table>
<tr><td>post image: </td><td>
<?php
    if (!is_null($post_img) && strlen(trim($post_img)) > 0) {
	echo '<img src="postImages/' . $post_img . '"></td>';
    } else {
	echo '&nbsp;</td>';
    }
?>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td>content: </td><td>
<?php
    echo $content;
?>
</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td>post create date: </td><td>
<?php
    echo $post_date;
?>
</td></tr>
</table>
<form method="post">
<input type="hidden" name="id" value="<?php echo $post_id; ?>">
<input type="hidden" name="post_img" value="<?php echo $post_img; ?>">
<input type="submit" name="delete" value="delete">
</form>
<?php
}
?>
</body>
</html> 
