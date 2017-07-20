<?php


$calDate = new DateTime($argv[1]);

$shiftNum = $argv[2];

$baseDate = new DateTime("2017-01-18");

$calDiff = $calDate->diff($baseDate)->format("%a");

echo "This is the date - ".$calDiff."\n";
echo "This is the shift - ".$shiftNum."\n";