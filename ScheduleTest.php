<?php


$tempdate = strtotime($argv[1]);

$calDate = date('Y-m-d',$tempdate);

$shiftNum = $argv[2];

$baseDate = strtotime("2017-01-18");

$datediff = $baseDate - $tempDate;

$calDiff = floor($datediff / (60 * 60 * 24));

echo "This is the date - ".$calDiff."\n";
echo "This is the shift - ".$shiftNum."\n";