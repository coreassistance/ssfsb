<?php

// Server Status for Status Board
// Brought to you by Justin Michael at [Core Assistance](http://coreassistance.com/).
	
// !---- Reference Material ----

// Panic's DIY Status Board Panel Documentation: https://library.panic.com/statusboard/diy-panels/

// Tiles are 64 x 64, so a 4 x 4 panel (like this one) is 256 x 256.

// Use font-family: "StatusBoardFontLight" for labels and headers.
// Use font-family: "StatusBoardFont" for content.

// Green: rgb(0,186,0)
// Yellow: rgb(255,198,0)
// Red: rgb(255,48,0)

// !---- Functions ----

function trimmedResultOfCommand($command) {
	return trim(shell_exec($command));
}

// !---- Setup ----

$version = '1.1 Beta 1';

// The length of time, in seconds, before this panel is reloaded from the server.
$updateInterval = 60;

// Server Name

$serverName = '';

$serverNameFile = 'ssfsb.name';

// If there's a file with a server name inside, use it.
if (file_exists($serverNameFile)) {
	$serverName = file_get_contents($serverNameFile);
}

// Populate the server name using the hostname command if we don't have one yet.
if (empty($serverName)) {
	$serverName = trimmedResultOfCommand('hostname');
}

// Determine what OS we're on.
$operatingSystem = strtolower(trimmedResultOfCommand('uname'));

// !---- Load ----

// sys_getloadavg() returns an array with the three load averages (1, 5, and 15 minutes).
$loadAverages = sys_getloadavg();

// We're only interested in the 15 minute load average.
$loadAverage15 = $loadAverages[2];

// Variable to hold the number of CPU cores.  Assume one core going in.
$cores = 1;

// Variable to hold the command we're going to use to determine the number of CPU cores on the system.
$coresCommand = false;

// Select the appropriate command for the operating system we're running.
switch ($operatingSystem) {
	case 'linux':
		$coresCommand = 'cat /proc/cpuinfo | grep processor | wc -l';
		break;
	
	case 'darwin': // Mac OS X & macOS
		$coresCommand = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
		break;
}

// If we have a command to execute, execute it to get the number of cores.
if ($coresCommand) {
	$cores = intval(trimmedResultOfCommand($coresCommand));
}

// To figure out the percentage of load on the server over the last 15 minutes, we divide the load average by the number of cores.
$loadPercentage = ($loadAverage15 / $cores) * 100;
// Round the value, as this is a *simple* status panel.
$loadPercentage = round($loadPercentage);

// Override for screenshots.
//$loadPercentage = 42;

// Convert the load percentage into a string for display.
$loadPercentageString = $loadPercentage . '%';

// Determine if the load is good, something that should be warned about, or bad.
$loadStatus = 'good';

if ($loadPercentage >= 70) {
	$loadStatus = 'warn';
}

if ($loadPercentage >= 90) {
	$loadStatus = 'bad';
}

// !---- Memory ----

$memoryPercentage = 0;

// For Linux we can extract memory information from /proc/meminfo easily.
// Note that the MemAvailable part of meminfo is only present in Linux kernel 3.14 or higher.
if ($operatingSystem == 'linux') {
	$memoryTotal = intval(trimmedResultOfCommand("grep MemTotal /proc/meminfo | awk '{print $2}'"));
	$memoryFree = intval(trimmedResultOfCommand("grep MemAvailable /proc/meminfo | awk '{print $2}'"));
	$memoryUsed = $memoryTotal - $memoryFree;
	
	$memoryPercentage = ($memoryUsed / $memoryTotal) * 100;
	$memoryPercentage = round($memoryPercentage);
}

// On the Mac we can use the memory_pressure command, which displays the "System-wide memory free percentage"
if ($operatingSystem == 'darwin') {
	// The free memory percentage is the only percentage memory_pressure spits out.  This regular expression looks for one to three numbers followed by a percent sign, and encloses the numbers only in a capture group.  The matches are stored in $matches, with the entire match at $matches[0] and the capture group at $matches[1].  So, if the percentage from the memory_pressure command was 42%, $matches[0] would be '42%' and $matches[1] would be '42'.
	preg_match('/([0-9]{1,3})%/', shell_exec('memory_pressure'), $matches);
	
	// Convert the percentage numbers to an integer.
	$memoryFreePercentage = intval($matches[1]);
	
	// And calculate how much memory is in use.
	$memoryPercentage = 100 - $memoryFreePercentage;
}

// Override for screenshots.
//$memoryPercentage = 92;

$memoryPercentageString = $memoryPercentage . '%';

$memoryStatus = 'good';

if ($memoryPercentage >= 70) {
	$memoryStatus = 'warn';
}

if ($memoryPercentage >= 90) {
	$memoryStatus = 'bad';
}

// !---- Disk Space ----

// Yay for easy, built in PHP functions!
$diskTotal = disk_total_space('/');
$diskFree = disk_free_space('/');
$diskUsed = $diskTotal - $diskFree;

$diskPercentage = ($diskUsed / $diskTotal) * 100;
$diskPercentage = round($diskPercentage);

// Override for screenshots.
//$diskPercentage = 71;

$diskPercentageString = $diskPercentage . '%';

$diskStatus = 'good';

if ($diskPercentage >= 70) {
	$diskStatus = 'warn';
}

if ($diskPercentage >= 90) {
	$diskStatus = 'bad';
}

?><!DOCTYPE html>
<html>
	<head>
		<title>Server Status for Status Board</title>
		<meta data-refresh-every-n-seconds="<?php echo $updateInterval + 5; // Trust our own update code first, use Status Board's refresh functionallity as a fallback. ?>" 
			application-name="<?php echo ucfirst($serverName); ?> Status"
			data-allows-resizing="YES"
			data-default-size="4,4"
			data-min-size="4,4"
			data-max-size="16,4"
			data-allows-scrolling="NO">
		<style type="text/css">
			
			html,
			body,
			h1,
			h2,
			p {
				margin: 0;
				padding: 0;
			}
			
			html,
			body {
				color: white;
				font-family: "StatusBoardFont";
				font-size: 22px;
			}
			
			body {
				padding: 0.5rem;
			}
			
			h1,
			h2 {
				font-family: "StatusBoardFontLight";				
				text-transform: uppercase;
			}
			
			h1 {
				text-align: center;
				font-size: 0.85rem;
				margin: 0.25rem 0;
			}
			
			h2 {
				color: #7e7e7e;
				font-size: 0.75rem;
				margin: .5rem 0 0;
			}
			
			p {
				height: 1rem;
				line-height: 1rem;
				font-weight: bold;
				padding: .3rem .25rem 0;
			}
			
			#age {
				color: #7e7e7e;
				font-size: .6rem;
				text-align: center;
				margin-top: .5rem;
			}
			
			.bar {
				border-radius: .25rem;
			}
						
			.bar.good {
				background: rgb(0,186,0);
			}
			
			.bar.warn {
				background: rgb(255,198,0);
			}
			
			.bar.bad {
				background: rgb(255,48,0);
			}
			
		</style>
	</head>
	<body onload="update();">
		<h1><?php echo $serverName; ?></h1>

		<div id="load">
			<h2>System Load</h2>
			<p class="bar <?php echo $loadStatus; ?>" style="width: <?php echo $loadPercentageString; ?>;"><?php echo $loadPercentageString; ?></p>
		</div>
		
		<div id="memory">
			<h2>Memory Use</h2>
			<p class="bar <?php echo $memoryStatus; ?>" style="width: <?php echo $memoryPercentageString; ?>;"><?php echo $memoryPercentageString; ?></p>
		</div>
		
		<div id="disk">
			<h2>Disk Use</h2>
			<p class="bar <?php echo $diskStatus; ?>" style="width: <?php echo $diskPercentageString; ?>;"><?php echo $diskPercentageString; ?></p>
		</div>
		
		<div id="age"></div>
		
		<script>
			
			function updateAgeElement(age) {
				if (age < 10) {
					age = '0' + age;
				}
				var ageElement = document.getElementById('age');
				ageElement.innerHTML = 'Data is ' + age + ' seconds old. (SSfSB v<?php echo $version; ?>)';
			}
			
			// This is called by the onload attribute of the body element.
			function update() {
				// Keep track of how hold the information displayed is.
				var age = 0;
				
				// Update the age immediately upon load.
				updateAgeElement(age);
				
				// Every second, increment the age count, update the age element, and when we hit the update interval, reload.
				setInterval(function () {
					age++;
					
					updateAgeElement(age);
					
					if (age >= <?php echo $updateInterval; ?>) {
						document.location.reload(true);
					}
				}, 1000);
			}
			
		</script>
	</body>
</html>