<?php

// Server Status for Status Board
// Core Assistance - Justin Michael
// Requires PHP 5.1.3
// Compatible with Linux (kernel 3.14+) and Mac OS X/macOS 10.9+.

// !---- Configuration ----

// If you want to customize the name of this server just populate this variable.
$serverName = '';
	
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

$version = '0.1';

$updateInterval = 60;

// Server Name

$serverName = '';

$serverNameFile = 'ssfsb.name';

if (file_exists($serverNameFile)) {
	$serverName = file_get_contents($serverNameFile);
}

// Populate the server name if not otherwise supplied.
if (empty($serverName)) {
	$serverName = trimmedResultOfCommand('hostname');
}

// Determine what OS we're running on.
$operatingSystem = strtolower(trimmedResultOfCommand('uname'));

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
$loadPercentage = round($loadPercentage);

// Convert the load percentage into a string for display.
$loadPercentageString = $loadPercentage . '%';

$loadStatus = 'good';

if ($loadPercentage >= 70) {
	$loadStatus = 'warn';
}

if ($loadPercentage >= 90) {
	$loadStatus = 'bad';
}

// !---- Memory ----

$memoryPercentage = 0;

if ($operatingSystem == 'linux') {
	$memoryTotal = intval(trimmedResultOfCommand("grep MemTotal /proc/meminfo | awk '{print $2}'"));
	$memoryFree = intval(trimmedResultOfCommand("grep MemAvailable /proc/meminfo | awk '{print $2}'"));
	$memoryUsed = $memoryTotal - $memoryFree;
	
	$memoryPercentage = ($memoryUsed / $memoryTotal) * 100;
	$memoryPercentage = round($memoryPercentage);
}

if ($operatingSystem == 'darwin') {
	$matches = [];
	preg_match('/([0-9]{1,3})%/', shell_exec('memory_pressure'), $matches);
	$memoryFreePercentage = intval($matches[1]);
	$memoryPercentage = 100 - $memoryFreePercentage;
}

$memoryPercentageString = $memoryPercentage . '%';

$memoryStatus = 'good';

if ($memoryUsedPercentage >= 70) {
	$memoryStatus = 'warn';
}

if ($memoryPercentage >= 90) {
	$memoryStatus = 'bad';
}

// !---- Disk Space ----

$diskTotal = disk_total_space('/');
$diskFree = disk_free_space('/');
$diskUsed = $diskTotal - $diskFree;

$diskPercentage = ($diskUsed / $diskTotal) * 100;
$diskPercentage = round($diskPercentage);

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
		<meta data-refresh-every-n-seconds="<?php echo $updateInterval; ?>" 
			application-name="<?php echo ucfirst($serverName); ?> Status"
			data-allows-resizing="NO"
			data-default-size="4,4"
			data-min-size="4,4"
			data-max-size="4,4"
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
			<h2>Memory In Use</h2>
			<p class="bar <?php echo $memoryStatus; ?>" style="width: <?php echo $memoryPercentageString; ?>;"><?php echo $memoryPercentageString; ?></p>
		</div>
		
		<div id="disk">
			<h2>Disk In Use</h2>
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
			
			function update() {
				var age = 0;
				
				updateAgeElement(age);
				
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