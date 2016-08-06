<?php

// Server Status for Status Board
// Version 0.1
// Core Assistance - Justin Michael

// !---- Configuration ----

$serverName = 'Concordia';
	
// !---- Reference Material ----

// Panic's DIY Status Board Panel Documentation: https://library.panic.com/statusboard/diy-panels/

// Tiles are 64 x 64, so a 4 x 4 panel (like this one) is 256 x 256.

// Use font-family: "StatusBoardFontLight" for labels and headers.
// Use font-family: "StatusBoardFont" for content.

// Green: rgb(0,186,0)
// Yellow: rgb(255,198,0)
// Red: rgb(255,48,0)

?><!DOCTYPE html>
<html>
	<head>
		<title>Server Status for Status Board</title>
		<meta data-refresh-every-n-seconds="300" 
			application-name="Server Status: <?php echo $serverName; ?>"
			data-allows-resizing="NO"
			data-default-size="4,4"
			data-min-size="4,4"
			data-max-size="4,4"
			data-allows-scrolling="NO">
	</head>
	<body>
		
	</body>
</html>