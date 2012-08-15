<?

define('SETTING_FILE', dirname(__FILE__) . '/../../conf/mud.conf');

function loadSettings() {
	if (file_exists(SETTING_FILE)) {
		return json_decode(file_get_contents(SETTING_FILE), true);
	} else {
		return array();
	}
}

function saveSetting($key, $val) {
	$ar = loadSettings();
	$ar[$key] = $val;
	file_put_contents(SETTING_FILE, json_encode($ar), 0644);
}

function readRanvierFile($name) {
	$settings = loadSettings();
	
	return file_get_contents($settings['base_game'] . $name);
}

function saveRanvierFile($name, $data) {
	$settings = loadSettings();
	
	return file_put_contents($settings['base_game'] . $name, $data);
}

?>