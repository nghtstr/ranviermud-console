<?

include_once('ranvier.php');

$settings = loadSettings();

$filepath = $_POST['file'];
$script = $_POST['script'];

//$script = file_get_contents($setting['base_game'] . '/' . $filepath);

if (trim($script) != '') {
	echo 'OK';
	saveRanvierFile($filepath, $script);
} else {
	echo 'DEL';
	@unlink($setting['base_game'] . '/' . $filepath);
}

?>