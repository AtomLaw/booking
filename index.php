<?php
//require_once('reserva.php');

$jsonurl = "http://www.fundaciontelevisa.org/booking/booking.php";
$json = file_get_contents($jsonurl,0,null,null);
$json_output = json_decode($json, true);

//print_r($json_output);
$id = $json_output['id'];
$cita = $json_output['cita'];
$token = $json_output['token'];
//echo $id . " | " . $cita;

switch($id) {
	case 0:
		header('Location: landing.php?id=0');
		break;
	case -1:
		header('Location: landing.php?id=-1');
		break;
	default:
		session_start();
		$_SESSION['id'] = $id;
		$_SESSION['token'] = $token;
		header('Location: form.php');
}
?>
