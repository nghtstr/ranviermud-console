<?

include_once('../global/ranvier.php');

function removeQuotes($str) {
	return str_replace(array('"', "'"), '', $str);
}

$attr = $_POST['attr'];

$attrStr = "self.attributes = {\n";
$attFmt = "\t\t'%s': %s, // type: %s\n";

foreach ($attr as $a) {
	$t = $a['type'];
	if ($t == 'enum') {
		$t .= '[' . implode(',', $a['extra']) . ']';
	}
	if (!is_numeric($a['default'])) $a['default'] = "'" . $a['default'] . "'";
	$attrStr .= sprintf($attFmt, $a['field'], $a['default'], $t);
}

$attrStr .= "\t};";

$js = readRanvierFile('/src/player.js');

$js = preg_replace('/self.attributes = \{.*?\};/s', $attrStr, $js);

saveRanvierFile('/src/player.js', $js);

?>
OK