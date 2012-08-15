<?

include_once('../global/ranvier.php');

function removeQuotes($str) {
	return str_replace(array('"', "'"), '', $str);
}

$js = readRanvierFile('/src/player.js');

preg_match('/self.attributes = \{(.*?)\};/s', $js, $contents);

$lines = explode("\n", $contents[1]);

$attr = array();

foreach ($lines as $l) {
	if (trim($l) != '') {
		preg_match('#(.*?):(.*?), // type: (.*)#', trim($l), $parts);
		$a = array('field'=>removeQuotes(trim($parts[1])), 'default'=>removeQuotes(trim($parts[2])), 'type'=>$parts[3], 'extra'=>'');
		
		if (strpos($a['type'], '[') !== false) {
			list($type, $stuff) = explode('[', $a['type']);
			$a['type'] = $type;
			$a['extra'] = explode(',', substr($stuff, 0, -1));
		}
		
		if ($a['field'] != '') $attr[] = $a;
	}
}

echo json_encode($attr);

?>