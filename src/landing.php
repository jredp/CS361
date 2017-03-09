<?php
function getSelect($selIdx = NULL) {
	$cn = new mysqli();
	$list = '<option value="">select species</option>';
	$list .= '<option value="all">all species</option>';
	$rs = $cn->query("select species from pets group by 1 order by 1");
	while ($row = $rs->fetch_assoc()) {
		$list .= '<option value="' . $row['species'] . '"';
		if ($selIdx == $row['species']) {
			$list .= ' selected';
		}
		$list .= '>' . $row['species'] . '</option>';
	}
	echo $list;
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="">
<script>
function showPets(species) {
	if (species == "") {
		document.getElementById("pet-list").innerHTML = "";
		return;
	} else {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("pet-list").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "getpets.php?myspecies=" + species, true);
		xmlhttp.send();
	}
}
</script>
</head>
<body>
<form method="post">
<select name="species" onchange="showPets(this.value)">
<?php
getSelect();
?>
</select>
</form>
<br>
<div id="pet-list"></div>
</body>
</html> 
