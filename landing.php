<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="">
</head>
<body>
<form method="post">
<select name="cat" onchange="showPosts(this.value)">
<option value="">select post category</option>
<option value="all">all posts</option>
<option value="mine">my posts</option>
<option value="following">posts I'm following</option>
<option value="local">local posts</option>
</select>
</form>
<br>
<div id="post-list"></div>
<script src="js/misc.js"></script>
</body>
</html> 
