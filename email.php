<?php

function sendmail($email, $name, $date, $folio) {
	$anho = substr($date, 0, 4);
	$mes = substr($date, 5, 2);
	$dia = substr($date, 8, 2);
	$horas = substr($date, 11, 2);
	$minutos = substr($date, 14, 2);
	
	if(substr($dia, 0, 1) == '0') {
		$dia = substr($date, 9, 1);
	}
	
	$dia_stren = date("D", mktime(0,0,0,$mes,$dia,$anho));
	$mes_stres = '';
	$dia_stres = '';
	switch($mes) {
		case '01':
			$mes_stres = 'enero';
			break;
		case '02':
			$mes_stres = 'febrero';
			break;
		case '03':
			$mes_stres = 'marzo';
			break;
		case '04':
			$mes_stres = 'abril';
			break;
		case '05':
			$mes_stres = 'mayo';
			break;
		case '06':
			$mes_stres = 'junio';
			break;
		case '07':
			$mes_stres = 'julio';
			break;
		case '08':
			$mes_stres = 'agosto';
			break;
		case '09':
			$mes_stres = 'septiembre';
			break;
		case '10':
			$mes_stres = 'octubre';
			break;
		case '11':
			$mes_stres = 'noviembre';
			break;
		case '12':
			$mes_stres = 'diciembre';
			break;
	}
	
	switch($dia_stren) {
		case 'Mon':
			$dia_stres = 'lunes';
			break;
		case 'Tue':
			$dia_stres = 'martes';
			break;
		case 'Wed':
			$dia_stres = 'miércoles';
			break;
		case 'Thu':
			$dia_stres = 'jueves';
			break;
		case 'Fri':
			$dia_stres = 'viernes';
			break;
		case 'Sat':
			$dia_stres = 'sábado';
			break;
		case 'Sun':
			$dia_stres = 'domingo';
			break;
	}
	
	$copy = $name . ",

¡Gracias por tu registro!

Tu cita es el día " . $dia_stres . " " . $dia . " de " . $mes_stres . " de " . $anho . " a las " . $horas . ":" . $minutos . ".

Deberás presentarte en \"Ver Bien Para Aprender Mejor\", ubicado en Insurgentes Sur 2387 5to. Piso Col. San Angel Inn.

Recuerda que una vez que se otorga la cita no puede cambiarse ni otorgarse a otra persona.


Fundación Televisa y Los Doctores cuidan tu salud



Número de folio: " . $folio;

$headers = "From: contacto@fundaciontelevisa.org\r\n";
$headers .= "BCC: thomas@clicker360.com,natalia@clicker360.com\r\n";
$headers .= "Reply-To: contacto@fundaciontelevisa.org\r\n";

mail($email, "Tu cita de lentes", $copy, $headers);
}

?>