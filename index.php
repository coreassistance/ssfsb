<?php

// Server Status for Status Board
// Version 0.1
// Core Assistance - Justin Michael
// Compatible with Linux, Mac OS X, macOS, and FreeBSD

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

// !---- Load ----

// sys_getloadavg() returns an array with the three load averages (1, 5, and 15 minutes).
$loadAverages = sys_getloadavg();

// We're only interested in the 15 minute load average.
$loadAverage15 = $loadAverages[2];

// Number of CPU cores.

// Variable to hold the number of CPU cores.
$cores = 0;

// Variable to hold the command we're going to use to determine the number of CPU cores on the system.
$coresCommand = false;

// First we need to determine the OS.  The uname command will give us the operating system name.
$operatingSystem = strtolower(trim(shell_exec('uname')));

// Select the appropriate command for the operating system we're running.
switch ($operatingSystem) {
	case 'linux':
		$coresCommand = 'cat /proc/cpuinfo | grep processor | wc -l';
		break;
	
	case 'freebsd':
	case 'darwin': // Includes Mac OS X & macOS
		$coresCommand = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
		break;
}

// If we have a command to execute, execute it to get the number of cores.
if ($coresCommand) {
	$cores = intval(trim(shell_exec($coresCommand)))
}

// To figure out the percentage of load on the server over the last 15 minutes, we divide the load average by the number of cores.
$loadPercentage = $loadAverage15 / $cores;
$loadPercentage = round($loadPercentage);

// Convert the load percentage into a string for display.
$loadPercentageString = $loadPercentage * 100 . '%';

// !---- Memory ----

// TODO: This only works for Linux at the moment, need to adapt for Mac and FreeBSD.
$memoryTotal = intval(trim(shell_exec("grep MemTotal /proc/meminfo | awk '{print $2}'")));
$memoryFree = intval(trim(shell_exec("grep MemFree /proc/meminfo | awk '{print $2}'")));
$memoryUsed = $memoryTotal - $memoryFree;

$memoryPercentage = $memoryUsed / $memoryTotal;
$memoryPercentage = round($memoryPercentage);

$memoryPercentageString = $memoryPercentage * 100 . '%';

// !---- Disk Space ----

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