<?

include_once(dirname(__FILE__) . '/../global/ranvier.php');

$list = json_decode(readRanvierFile('/stats/daily.counts'), true);
$ret = array('axis'=>array(), 'maxHigh'=>array(), 'avgHigh'=>array());

foreach ($list as $dt=>$l) {
	list($y,$m,$d) = explode('_', substr($dt, 1));
	$ret['axis'][] = date('M j', mktime(12,0,0,$m,$d,$y));
	$ret['maxHigh'][] = $l['high'];
	if (!is_array($l['hours'])) { $ret['avgHigh'][] = $l['high']; }
	else {
		$counts = array_filter($l['hours'], 'strlen');
		$ret['avgHigh'][] = array_sum($counts) / count($counts);
	}
}

echo json_encode($ret);

?>