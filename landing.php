<?php
session_start();
if (!isset($_SESSION['user_name'])) {
	header("Location: index.php?loggedin=no");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title></title>
<script>
function showPosts(cat) {
	if (cat == "") {
		document.getElementById("post-list").innerHTML = "";
		return;
	} else {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("post-list").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "getposts.php?postfilter=" + cat, true);
		xmlhttp.send();
	}
}
document.onload = showPosts('mine');
</script>
<link rel="stylesheet" type="text/css" href="">
</head>
<body> 
<form method="post">
<select name="cat" onchange="showPosts(this.value);">
<option value="">select post category</option>
<option value="mine">my posts</option>
<option value="all">all posts</option>
<option value="following">posts I'm following</option>
<option value="local">local posts</option>
</select>
</form>
<br>
<div id="post-list">
</div>
</body>
</html> 
