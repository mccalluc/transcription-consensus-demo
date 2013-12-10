<?php
$records = json_decode($_GET['json']);

if (json_last_error() || empty($_GET['json'])) {
	header("HTTP/1.1 400 Bad Request");
	echo '400: Bad or missing json param';
	die;
}

if (! is_array($records)) {
	header("HTTP/1.1 400 Bad Request");
	echo '400: Should be JSON list, not map';
	die;
}

if  (count($records)==0) {
	echo '[]';
	die;
}

$consensus = $records[0];

foreach($records as $record) {
	if ($record != $consensus) {
		echo  '[]';
		die;
	}
}

echo json_encode(array($consensus));
