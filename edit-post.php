<?php
session_start();
include_once 'dbcn.php';

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
<p><a href="landing.php">cancel</a> go back to the landing page&nbsp;&nbsp;&nbsp;
<a href="logout.php">logout</a></p>
<?php echo $_GET['pid']; ?>
</body>
</html> 
