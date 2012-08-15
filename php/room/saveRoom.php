<?

include_once('../global/ranvier.php');
include_once('../global/yaml/spyc.php');

$area = $_POST['area'];
$room = $_POST['room'];
$settings = loadSettings();

$array = Spyc::YAMLLoad($settings['base_game'] . '/entities/areas/' . $area . '/rooms.yml');

for ($x = 0; $x < count($array); $x++) {
	if ($array[$x]['location'] == $room['location']) {
		$array[$x] = $room;
		$x = count($array);
	}
}

$yaml = Spyc::YAMLDump($array,4,60);

saveRanvierFile('/entities/areas/' . $area . '/rooms.yml', $yaml);

?>