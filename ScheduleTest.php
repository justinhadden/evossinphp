<?php


$tempdate = strtotime($argv[1]);

$calDate = date('Y-m-d',$tempdate);

$shiftNum = $argv[2];


echo "This is the date - ".$calDate."\n";
echo "This is the shift - ".$shiftNum."\n";