assert_curl_grep() {
	URL=http://fp1.acis.ufl.edu/transcription-consensus-demo/service.php
	# I tried single quotes around there URL, but that made things more confusing.
	CMD="curl -s -g $URL?json=$1"
	OUT=`$CMD`
	if ( echo $OUT | grep -F $2 ); then
		echo "pass: got '$OUT'"
	else
		echo "fail: '$2' not output of '$CMD'; instead '$OUT'"
		exit 1
	fi
}

assert_curl_grep '[]' '[]'

ANNO='{%22body%22:{},%22target%22:{%22source%22:%22http://example.edu%22}}'

assert_curl_grep '['$ANNO']' \
		'[{"body":{},"target":{"source":"http:\/\/example.edu"}}]'
assert_curl_grep '['$ANNO,$ANNO']' \
		'[{"body":{},"target":{"source":"http:\/\/example.edu"}}]'

echo '(All passed!)';
