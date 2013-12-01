#!/usr/bin/php
<?php

// HP iLO Management Fan Speed Monitoring Script for Munin (DL-580G5)
//
// hpasmcli reference -- http://h50146.www5.hp.com/products/software/oe/linux/mainstream/support/doc/general/mgmt/ima/v790/hpasmcli.txt
// 
// v.1.0 aleksandar.todorovic

	if ((count($argv) > 1) && ($argv[1] == 'config')) {
		print("graph_title iLO2 - Fan Speed(s)
				graph_category          iLO2
				graph_vlabel            Speed (%)
				systemfan1.label        System Zone - Fan (1)
				systemfan2.label        System Zone - Fan (2)
				cpufan1.label           CPU Zone - Fan (1)
				cpufan2.label           CPU Zone - Fan (2)
				cpufan3.label           CPU Zone - Fan (3)
				cpufan4.label           CPU Zone - Fan (4)\n");

                exit();
	}
 
    // grab our system values
 
    $fanspeed = shell_exec("hpasmcli -s 'SHOW FAN;' | head -n 9 | tail -n6 | awk '{ if (i<=5) { print $5; i+=1 } }'");
	
    $fans_arr = array('systemfan1', 
                      'systemfan2', 
                      'cpufan1', 
                      'cpufan2', 
                      'cpufan3', 
                      'cpufan4'); 

    $fan_speeds = array_map('intval', explode("\n", $fanspeed));

    // remove our last element from hpasmcli output
    unset($fan_speeds[6]);

    // combine the two arrays
    $result = array_combine ($fans_arr, $fan_speeds);


    // Output our data
    foreach ($result as $x=>$x_value) {
		echo $x .".value ". $x_value ."\n";
	}
?>
