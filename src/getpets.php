<body>
<?php
$species = $_GET['myspecies'];
$cn = new mysqli("localhost", "ironic", "getm0re!", "scratch");
$sql = "select name, species, age from pets";
if ($species != "all") $sql .= " where species = '" . $species . "'";
$rs = mysqli_query($cn, $sql);

$html = "<table border='1'><tr><th>name</th><th>species</th><th>age</th></tr>";
while ($row = mysqli_fetch_array($rs)) {
	$html .= "<tr>";
	$html .= "<td>" . $row['name'] . "</td>";
	$html .= "<td>" . $row['species'] . "</td>";
	$html .= "<td>" . $row['age'] . "</td>";
	$html .= "</tr>";
}
$html .= "</table>";
echo $html;
