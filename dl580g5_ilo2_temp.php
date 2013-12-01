#!/usr/bin/php
<?php

// HP iLO Management Temperature Monitoring Script for Munin (DL580G5)
//
// hpasmcli reference -- http://h50146.www5.hp.com/products/software/oe/linux/mainstream/support/doc/general/mgmt/ima/v790/hpasmcli.txt
// 
// v.1.0 aleksandar.todorovic

	if ((count($argv) > 1) && ($argv[1] == 'config')) {
		print("graph_title iLO2 - System Temperature(s)
	graph_category          iLO2
	graph_vlabel            Temperature (C)
	cpu1zone.label          CPU Zone (1)
	cpu2zone.label          CPU Zone (2)
	cpu3zone.label          CPU Zone (3)
	cpu4zone.label          CPU Zone (4)
	systemzone.label        System Zone
	ambientzone.label       Ambient
	memoryzone.label        Memory Board
	IO1zone.label           I/O Board zone (1)
	IO2zone.label           I/O Board Zone (2)\n");

		exit();
	}
 
	// grab our system values
 
	$temperature = shell_exec("hpasmcli -s 'SHOW TEMP;' | head -n 12 | tail -n 9 | awk '{ if (i<=8) { print substr($3,0,2); i+=1 } }'");

    $temperature_arr = array('cpu1zone', 
                             'cpu2zone', 
                             'cpu3zone', 
                             'cpu4zone', 
                             'systemzone', 
                             'ambientzone', 
                             'memoryzone', 
                             'IO1zone', 
                             'IO2zone');

	$temps = array_map('intval', explode("\n", $temperature));

	// remove our last element from hpasmcli output
	unset($temps[9]);

	// combine the two arrays
	$result = array_combine ($temperature_arr, $temps);

	// Output our data
	foreach ($result as $x=>$x_value) {
		echo $x .".value ".  $x_value ."\n";
	}

?>
