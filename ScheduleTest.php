<?php


$tempdate = strtotime($argv[1]);

$calDate = date('Y-m-d',$tempdate);

$shiftNum = $argv[2];

$baseDate = new DateTime("2017-01-18");

$calDiff = $calDate->diff($baseDate)->format("%a");

echo "This is the date - ".$calDiff."\n";
echo "This is the shift - ".$shiftNum."\n";