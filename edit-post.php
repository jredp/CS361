<?php
session_start();
if (!isset($_SESSION['user_name'])) {
	header("Location: index.php?loggedin=no");
}
?>
<p>need to create a form to edit post with id:
<?php echo $_GET['pid']; ?>
</p>
<p><a href="landing.php">back</a> to the landing page</p>
