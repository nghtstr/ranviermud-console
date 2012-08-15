<?

include_once('../global/ranvier.php');

$path = stripslashes($_POST['path']);

if (is_writable($path . '/data/motd')) {
	saveSetting('base_game', $path);
	echo json_encode(array('status'=>'OK'));
} else if (file_exists($path . '/data/motd')) {
	echo json_encode(array('status'=>'NOWRITE'));
} else {
	echo json_encode(array('status'=>'FAILED'));
}

?>
