<?

include_once('ranvier.php');

$settings = loadSettings();

$filepath = $_POST['file'];
$title = $_POST['title'];

$script = file_get_contents($settings['base_game'] . '/' . $filepath);

echo json_encode(array('title'=>$title, 'filepath'=>$filepath, 'script'=>(!$script ? '' : $script)));

?>