<?

include_once('../global/ranvier.php');

$motd = $_POST['motd'];

if (saveRanvierFile('/data/motd', $motd)) {
	echo json_encode(array('status'=>'OK'));
} else {
	echo json_encode(array('status'=>'FAILED'));
}

?>
