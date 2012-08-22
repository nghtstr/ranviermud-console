<?

include_once('../global/ranvier.php');

$types = array('npcs'=>'MOB', 'objects'=>'OBJECT');

$file = str_replace(' ', '', ucwords(strtolower($_POST['behavior'])));

saveRanvierFile('scripts/behaviors/' . $_POST['type'] . '/' . $file . '.js', '');

echo $types[$_POST['type']];

?>