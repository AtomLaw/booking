<?php

session_start();

//If the id is not set
if( !isset($_SESSION['id']) || $_SESSION['id'] == '' ){
	header('Location: landing.php');
}
?>

<html>
<head>
<title>Form</title>
</head>

<body>
<h1>Form</h1>

Registro #<?php echo $_SESSION['id']; ?> - Reservacion id <?php echo $_SESSION['token']; ?>

<form method="POST" action="reserva.php">
Email: <input type="text" name="email" /><br />
Nombre: <input type="text" name="nombre" /><br />
Tel: <input type="text" name="tel" /><br />
<input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>" />
<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
<button>Send</button>
</form>

</body>
</html>
