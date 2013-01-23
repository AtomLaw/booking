<html
<head>
<title>Landing</title>
</head>

<body>
<h1>Land</h1>
<?php
switch($_GET['id']) {
	case 0:
		echo "Please wait a few mins and try registering again.";
		break;
	case -1:
		echo "Limit reached";
		break;
	case -2:
		echo "Timeout";
}
?>
</body>
</html>
