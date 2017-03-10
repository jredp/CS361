function showPosts(cat) {
	if (cat == "") {
		// document.getElementById("post-list").innerHTML = "";
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
