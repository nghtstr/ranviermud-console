<?

include_once('../global/ranvier.php');
include_once('../global/yaml/spyc.php');

$area = $_POST['area'];
$settings = loadSettings();

$array = Spyc::YAMLLoad($settings['base_game'] . '/entities/areas/' . $area . '/rooms.yml');

$rooms = array();

foreach ($array as $a) { $rooms[$a['location']] = $a; }

echo json_encode($rooms);

?>