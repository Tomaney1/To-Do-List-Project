<?php
include 'includes/classes/Calendar.php';
$currentDate = date('Y-m-d');
$calendar = new Calendar($currentDate);
$calendar->add_event('Birthday', '2024-03-03', 1, 'green');
$calendar->add_event('Software Engineering Class', '2024-03-05', 1, 'red');
$calendar->add_event('Spring Break', '2024-03-11', 5);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Event Calendar</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link href="calendar.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	    <nav class="navtop">
	    	<div>
	    		<h1>Event Calendar</h1>
	    	</div>
	    </nav>
		<div class="content home">
			<?=$calendar?>
		</div>
	</body>
</html>
