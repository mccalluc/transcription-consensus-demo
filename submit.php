<?php

$map = array(
	'elevation' => 'verbatimElevation');

foreach ($map as $old => $new) {
	if (!empty($_POST[$old])) {
		$_POST[$new] = $_POST[$old];
		unset($_POST[$old]);
	}
}

$annotation = array(
	'target' => array(),
	'body' => array()
);

foreach($_POST as $key => $value) {
	$annotation['body'][$key] = $value;
}

// TODO: fill in the details.

$json = json_encode($annotation);

$url = 'http://fp1.acis.ufl.edu:8080/clientHelper/insertTranscribingAnnotation/';

$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => $json,
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

var_dump($result);
