<?php

// Fill in target:

$target = array();

if (!empty($_POST['source'])) {
	if (strpos($_POST['source'],'http://') === 0) {
		$target['source'] = $_POST['source'];
		unset($_POST['source']);
	} else {
		die("source must begin with 'http://'.");
	}
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

if (isset($_POST['consenting'])) {
	$consenting = explode(' ',$_POST['consenting']);
	unset($_POST['consenting']);
}
if (isset($_POST['dissenting'])) {
	$dissenting = explode(' ',$_POST['dissenting']);
	unset($_POST['dissenting']);
}

foreach($_POST as $key => $value) {
	$body[$key] = $value;
}

// Put the target and body together:

$annotation = array(
	'target' => $target,
	'body' => $body,
	'annotator_name' => 'demo script'
);

if (isset($consenting) && isset($dissenting)) {
	$annotation['evidence'] = array(
		'consenting' => $consenting,
		'dissenting' => $dissenting
	);
}

// and submit:

$json = json_encode($annotation); // JSON_PRETTY_PRINT available in 5.4

$url = 'http://fp1.acis.ufl.edu:8080/clientHelper/insert'
	. ( isset($consenting)
		? 'Consensus'
		: 'Transcribing' )
	. 'Annotation/';

$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => $json,
    ),
);
$context  = stream_context_create($options);
$rdf_xml = file_get_contents($url, false, $context);

// XXX: THIS IS HORRIBLE.
$matches = array();
preg_match('!http://filteredpush.org/ontologies/annotation/[^"]+!',$rdf_xml,$matches);
$anno_url = $matches[0];

$query_url = 'http://fp1.acis.ufl.edu:8080/clientHelper/find'
	. ( isset($_POST['consenting'])
		? 'Consensus'
		: 'Transcribing' )
	. 'Annotations/?uri=' . $target['source'];

?><!DOCTYPE html><html><body>
POSTing to this URL: <code><?php echo htmlspecialchars($url); ?></code><br/>
this JSON: <code><?php echo htmlspecialchars($json); ?></code><br/><br/>

created new annotation: <code><?php echo htmlspecialchars($anno_url); ?></code><br/>
which you can <a href='<?php echo htmlspecialchars($query_url); ?>'>query for</a><br/>
or view as: <form method='POST' style='display:inline' target='_blank'>
<input type='hidden' name='content' value='<?php echo htmlspecialchars($rdf_xml); ?>'></input>
<?php
foreach(array('n3','rdf-json-pretty','json-ld','rdfa','microdata','pretty-xml') as $format){
	$url = "http://rdf-translator.appspot.com/convert/xml/{$format}/content";
	echo "<input type='submit' value='{$format}' formaction='{$url}'></input>";
}
?>
</form></br>
or as rdf-xml: <code><?php echo htmlspecialchars($rdf_xml); ?></code><br/>
</body></html>
