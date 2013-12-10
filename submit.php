<?php

// Fill in target:

$target = array();

if (!empty($_POST['source'])) {
	$target['source'] = $_POST['source'];
	unset($_POST['source']);
} else {
	die("source is required.");
}

if (!empty($_POST['x']) && !empty($_POST['y']) && !empty($_POST['w']) && !empty($_POST['h'])) {
	$target['fragmentSelector'] = "#xywh={$_POST['x']},{$_POST['y']},{$_POST['w']},{$_POST['h']}";
	unset($_POST['x']);
	unset($_POST['y']);
	unset($_POST['w']);
	unset($_POST['h']);
} else {
	# optional.
}


// Fill in body:

$body = array();

$map = array(
	'elevation' => 'verbatimElevation',
	'notes' => 'occurrenceRemarks',
	'locality' => 'verbatimLocality',
	'location' => 'verbatimLocality',
	'collector' => 'collector_name');

foreach ($map as $old => $new) {
	if (!empty($_POST[$old])) {
		$_POST[$new] = $_POST[$old];
		unset($_POST[$old]);
	}
}

// TODO: handle date range as ISO eventDate.
// TODO: handle ornith record_*

foreach($_POST as $key => $value) {
	$body[$key] = $value;
}

// Put the target and body together:

$annotation = array(
	'target' => $target,
	'body' => $body,
	'annotator_name' => 'demo script'
);

// and submit:

$json = json_encode($annotation); // JSON_PRETTY_PRINT available in 5.4

$url = 'http://fp1.acis.ufl.edu:8080/clientHelper/insertTranscribingAnnotation/';

$json_html = htmlspecialchars($json);
$url_html = htmlspecialchars($url);

?><html><body>
POSTing to this URL: <code><?php echo $url_html; ?></code><br/>
this JSON: <code><?php echo $json_html; ?></code><br/>
result:
<?php

$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => $json,
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo htmlspecialchars($result);
?>
</body></html>
