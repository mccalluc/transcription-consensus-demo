assert_curl_grep() {
	URL=http://fp1.acis.ufl.edu/transcription-consensus-demo/service.php
	# I tried single quotes around there URL, but that made things more confusing.
	CMD="curl -s -g $URL?json=$1"
	OUT=`$CMD`
	echo "success: $OUT" | grep -F $2 || (echo "failed: '$2' not output of '$CMD'; instead '$OUT'"; exit 1)
}

assert_curl_grep '[]' '[]'
assert_curl_grep '[{%22body%22:{},%22target%22:{%22source%22:%22http://example.edu%22}}]' \
		'[{"body":{},"target":{"source":"http:\/\/example.edu"}}]'
