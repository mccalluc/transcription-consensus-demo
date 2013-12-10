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
			'state_province',
			'country',
			'scientific_name',
			'location',
			'habitat',
			'collector',
			'month',
			'day',
			'year'),
	'calbug' => array(
			'country',
			'state_province',
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
			'state_province',
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
	array_unshift($field_map[$path],'url');
	array_unshift($field_map[$path],'user');

	echo "<h1>{$path}</h1>";

	echo "<form action='../submit.php' method='post'>";
	echo "<table>";
	foreach ($field_map[$path] as $field) {
		echo "<tr><td>$field</td><td><input name='$field'/></td></tr>";
	}
	echo "</table>";
	echo "<input type='submit' value='submit'/></form>";
}

?>
</body>
</html>
