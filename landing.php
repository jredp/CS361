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
		/* document.getElementById("post-list").innerHTML = ""; */
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

// TODO: make this operational
function searchPosts() {
	var term = document.getElementById('txtSearch').value;
	alert(term);
}

document.onload = showPosts('mine');
</script>
<link rel="stylesheet" type="text/css" href="">
</head>
<body> 
<table>
<tr>
<form method="post">
<td>filter posts</td>
<td>
<select name="cat" onchange="showPosts(this.value);">
<option value="">select post category</option>
<option value="mine">my posts</option>
<option value="all">all posts</option>
<option value="following">posts I'm following</option>
<option value="local">local posts</option>
</select>
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>search</td>
<td><input type="text" name="search" id="txtSearch"></td>
<td><button id="btn-search" name="btn-search" onclick="searchPosts()">search</button></td>
</form>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><a href="create-post.php">create new post</a></td>
</tr>
</table>
<br>
<div id="post-list">
</div>
</body>
</html> 
