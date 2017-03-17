function showPosts(cat) {
	if (cat == "") {
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

function searchPosts() {
	var term = document.getElementById('txtSearch').value;
	// if there's no search term, show the filtered posts
	if (term == '') {
		cat = document.getElementById("ddlPost").value;
		showPosts(cat);
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
		xmlhttp.open("GET", "searchposts.php?searchterm=" + term, true);
		xmlhttp.send();
	}
}

document.onload = showPosts('mine');
