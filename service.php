<?php
function error_400($message) {
	header("HTTP/1.1 400 Bad Request");
	echo '<html><body>400: '.htmlspecialchars($message).'</body></html>';
	exit;
}

if (empty($_GET['json'])) {
	error_400('Missing json parameter.');
}

$records = json_decode($_GET['json']);

if (json_last_error()) {
	error_400('Bad format json parameter: error '.json_last_error().'; json: '.$_GET['json']);
}

if (! is_array($records)) {
	error_400('Should be JSON list, not map.');
}

if (count($records)==0) {
	echo '[]';
	exit;
}

if (empty($records[0]->body)) {
	error_400('Each map in the JSON must contain a "body".');
}
if (empty($records[0]->target)) {
	error_400('Each map in the JSON must contain a "target".');
}
if (empty($records[0]->target->source)) {
	error_400('Each "target" in the JSON must contain a "source".');
}

$consensus = $records[0]->body;
$target = array('source'=>$records[0]->target->source);

foreach($records as $record) {
	if (empty($record->body)) {
		error_400('Each map in the JSON must contain a "body".');
	}

	if ($record->body != $consensus) {
		echo  '[]';
		exit;
	}
}

echo json_encode(array(array(
	'body'=>$consensus,
	'target'=>$target
)));
