<?php

function rand_string( $length ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

	$size = strlen( $chars );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}

	return $str;
}

function isavailable() {
	require('db.php');
	
	$conn = mysql_connect($dbhost, $dbuser, $dbpasswd) or die(mysql_error());
	mysql_select_db($db, $conn) or die(mysql_error());
	$available_sql = "SELECT 1 as available FROM booking_config WHERE setting = 'available' AND DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') BETWEEN SUBSTRING(value,1,14) AND SUBSTRING(value,16,14);";
	$available_result = mysql_query($available_sql);
	$available = 0;
	while($available_row = mysql_fetch_array($available_result)) {
		$available = $available_row['available'];
	}
	if($available == 1) {
		return true;
	} else {
		return false;
	}
	mysql_close();
}

if(isavailable()){
if( isset($_REQUEST['email']) && $_REQUEST['email'] != '' && isset($_REQUEST['nombre']) && $_REQUEST['nombre'] != '' && isset($_REQUEST['apellidop']) && $_REQUEST['apellidop'] != '' && isset($_REQUEST['apellidom']) && $_REQUEST['apellidom'] != '' && isset($_REQUEST['tel']) && $_REQUEST['tel'] != '' && isset($_REQUEST['id']) && $_REQUEST['id'] != '' && isset($_REQUEST['token']) && $_REQUEST['token'] != '' ) {
	require('db.php');
	require_once('email.php');
	
	$conn = mysql_connect($dbhost, $dbuser, $dbpasswd) or die(mysql_error());
	mysql_select_db($db, $conn) or die(mysql_error());
	
	//echo $_REQUEST['id'] . " " . $_REQUEST['token'];
	$cita_sql = "SELECT * FROM booking_cita WHERE status = '1' AND id = '" . $_REQUEST['id'] . "' AND cookie = '" . $_REQUEST['token'] . "';";
	//echo $cita_sql;
	$cita_result = mysql_query($cita_sql);
	$id = '';
	$cita = '';
	$token = '';
	while($cita_row = mysql_fetch_array($cita_result)) {
		$id = $cita_row['id'];
		$cita = $cita_row['fecha'];
		$token = $cita_row['cookie'];
	}
	
	if($id == '') {
		$id = -2;
	} else {
		$emailexists == 0;
		$emailexists_sql = "SELECT 1 AS emailexists FROM booking_email WHERE email = RTRIM(LTRIM(LOWER('" . $_REQUEST['email'] . "')));";
		$emailexists_result = mysql_query($emailexists_sql);
		while($emailexists_row = mysql_fetch_array($emailexists_result)) {
			$emailexists = $emailexists_row['emailexists'];
		}
		if($emailexists == 0) {
			$book_sql = "UPDATE booking_cita SET status = '2' WHERE id = '" . $id . "';";
			$register_sql = "INSERT INTO booking_registro VALUES('','" . $_REQUEST['email'] . "','" . $_REQUEST['nombre'] . "', '" . $_REQUEST['apellidop'] . "', '" . $_REQUEST['apellidom'] . "','" . $_REQUEST['tel'] . "','" . $id . "');";
			$addemail_sql = "INSERT INTO booking_email(email) VALUES(RTRIM(LTRIM(LOWER('" . $_REQUEST['email'] . "'))));";
			$folio_sql = "SELECT folio FROM booking_email WHERE email = RTRIM(LTRIM(LOWER('" . $_REQUEST['email'] . "')));";
			mysql_query($book_sql);
			mysql_query($register_sql);
			mysql_query($addemail_sql);
			$folio_result = mysql_query($folio_sql);
			$folio_row = mysql_fetch_array($folio_result);
			sendmail($_REQUEST['email'], $_REQUEST['nombre'], $cita, $folio_row['folio']);
		} else {
			$unbook_sql = "UPDATE booking_cita SET status = '0', reservado = NULL, cookie = NULL WHERE id = '" . $id . "';";
			mysql_query($unbook_sql);
			$id = -4;
		};
	}
	
	mysql_close();
		
	$returnval = array("id" => $id, "cita" => $cita, "token" => $token);
	
	echo json_encode($returnval);
} else {
	require('db.php');

	$conn = mysql_connect($dbhost, $dbuser, $dbpasswd) or die(mysql_error());
	
	if (!$conn)
	{
		die('Could not connect: ' . mysql_error());
	}	
	
	mysql_select_db($db, $conn) or die(mysql_error());
	
	//Unbook
	$unbook_sql = "UPDATE booking_cita SET status = 0, reservado = NULL, cookie = NULL WHERE status = 1 AND reservado < NOW() - INTERVAL 5 MINUTE";
	mysql_query($unbook_sql);
	
	//free
	$free_sql = "SELECT COUNT(*) AS free FROM booking_cita WHERE status = 0;";
	$free_result = mysql_query($free_sql);
	$free = 0;	
	
	while($free_row = mysql_fetch_array($free_result)) {
		$free = $free_row['free'];
	}
	
	//Pendientes
	$pending_sql = "SELECT COUNT(*) AS pending FROM booking_cita WHERE status = 1;";
	$pending_result = mysql_query($pending_sql);
	$pending = 0;	
	
	while($pending_row = mysql_fetch_array($pending_result)) {
		$pending = $pending_row['pending'];
	}
	
	$returnval = '';
	
	//If all is booked
	if( $free == 0 && $pending == 0 ) {
		$returnval = array("id" => -1, "cita" => '');
	//If all is booked or pending
	} elseif( $free == 0 && $pending > 0 ) {
		$returnval = array("id" => 0, "cita" => '');
	//If there's free space
	} else {
		$free_sql = "SELECT * FROM booking_cita WHERE status = 0 LIMIT 1;";
		$free_result = mysql_query($free_sql);
		$id = 0;
		$cita = '';
		while($free_row = mysql_fetch_array($free_result)) {
			$id = $free_row['id'];
			$cita = $free_row['fecha'];
		}
		
		$str = rand_string(26);
		$token = md5($id . $str . $cita);
		
		mysql_query("UPDATE booking_cita SET status = 1, reservado = NOW(), cookie = '" . $token . "' WHERE id = " . $id . ";");
		$returnval = array("id" => $id, "cita" => $cita, "token" => $token);
	}
	
	mysql_close($conn);
	
	//echo "free: " . $free . ". pending: " . $pending . ". returnval: " . $returnval;
	
	echo json_encode($returnval);
}
}else{
	$returnval = array("id" => -3, "cita" => $cita, "token" => $token);
	echo json_encode($returnval);
}
?>
