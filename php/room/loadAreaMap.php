<?

include_once('../global/ranvier.php');
include_once('../global/yaml/spyc.php');

$area = $_POST['area'];
$settings = loadSettings();

$array = Spyc::YAMLLoad($settings['base_game'] . '/entities/areas/' . $area . '/rooms.yml');

$rooms = array();

foreach ($array as $a) { $rooms[$a['location']] = $a; }

foreach ($rooms as $r) {
	foreach ($r['exits'] as $i=>$e) {
		if (!is_array($rooms[$e['location']])) {
			if (!is_array($e['transition'])) {
				$rooms[$r['location']]['exits'][$i]['transition'] = array('x'=>10, 'y'=>10);
			}
		}
	}
}

echo json_encode($rooms);

?>