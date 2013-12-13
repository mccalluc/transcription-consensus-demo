<?php
if (empty($_SERVER['PATH_INFO'])) {
	header("Location: {$_SERVER['PHP_SELF']}/");
	exit;
}
?>
<html>
<body>
<?php

$field_map = array(
	'herbarium' => array(
			'x','y','w','h',
			'country',
			'stateProvince',
			'county',
			'scientificName',
			'location',
			'habitat',
			'collector',
			'month',
			'day',
			'year'),
	'calbug' => array(
			'country',
			'stateProvince',
			'county',
			'locality',
			'month',
			'day',
			'year',
			'end_month',
			'end_day',
			'end_year',
			'collector',
			'elevation',
			'notes'),
	'ornithological' => array(
			'record_year',
			'record_batch',
			'record_number',
			'location',
			'month',
			'day',
			'year'),
	'macrofungi' => array(
			'country',
			'stateProvince',
			'county',
			'locality',
			'month',
			'day',
			'year'));

$path = substr($_SERVER['PATH_INFO'],1);

if (empty($field_map[$path])) {
	echo "Try one of these: <br/>";
	foreach (array_keys($field_map) as $a_path) {
		echo "<a href='./{$a_path}'>{$a_path}</a><br/>";
	}
} else {
	array_unshift($field_map[$path],'source');

	echo "<h1>{$path}</h1>";
	if (!isset($_GET['consensus'])) {
		echo "<p>Enter a new transcription, or <a href='?consensus'>record a consensus</a>.</p>";
	} else {
		echo "<p>Record a consensus, or <a href='?'>enter a new transcription</a>.</p>";
	}

	echo "<form action='../submit.php' method='post'>";
	echo "<table>";
	if (isset($_GET['consensus'])) {
		echo "<tr><td></td><td><small>(Separate with spaces.)</small></td></tr>";
		echo "<tr><td>consenting uris</td><td><input name='consenting'/></td></tr>";
		echo "<tr><td>dissenting uris</td><td><input name='dissenting'/></td></tr>";
	}
	foreach ($field_map[$path] as $field) {
		if ($field == 'source') {
			echo "<tr><td></td><td><small>(Enter a URI)</small></td></tr>";
		}
		echo "<tr><td>$field</td><td><input name='$field'/></td></tr>";
	}
	echo "<tr><td></td><td><input type='submit' value='submit'/></td></tr>";
	echo "</table></form>";
}

?>
</body>
</html>
