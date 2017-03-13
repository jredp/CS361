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
<?php
session_unset();
session_destroy();
?>
<p>you're now logged out!</p>
<p><a href="landing.php">back</a> to the landing page (should take you back to the login/reg'n page)</p>
</body>
</html> 
