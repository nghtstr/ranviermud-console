<?

include_once('../global/ranvier.php');
include_once('../global/yaml/spyc.php');

$area = $_POST['area'];
$room = $_POST['room'];
$settings = loadSettings();

$foundTheRoom = false;
$array = readRanvierFile('/entities/areas/' . $area . '/rooms.yml', IS_YAML);

for ($x = 0; $x < count($array); $x++) {
	if ($array[$x]['location'] == $room['location']) {
		$foundTheRoom = true;
		if (ranvierFileExists('/scripts/rooms/' . $room['location'] . '.js')) {
			$room['script'] = $room['location'] . '.js';
		} else {
			unset($room['script']);
		}
		$array[$x] = $room;
		$x = count($array);
	}
}

if (!$foundTheRoom) { $array[] = $room; }

$yaml = Spyc::YAMLDump($array,4,60);

saveRanvierFile('/entities/areas/' . $area . '/rooms.yml', $yaml);

?>