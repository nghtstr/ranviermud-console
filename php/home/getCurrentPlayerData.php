<?

include_once(dirname(__FILE__) . '/../global/ranvier.php');

$last = $_POST['lastDate'];

$list = json_decode(readRanvierFile('/stats/current.counts'), true);
$ret = array();

foreach ($list as $l) {
	if ($l['dt'] > $last) $ret[] = $l;
}

echo json_encode($ret);

?>