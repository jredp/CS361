<?php
session_start();
if (!isset($_SESSION['user_name'])) {
	header("Location: index.php?loggedin=no");
}
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="">
</head>
<body>
<p>need to create a form with image upload, see the task list for info</p>
<p><a href="landing.php">back</a> to the landing page</p>
<p><a href="logout.php">logout</a></p>
</body>
</html> 
