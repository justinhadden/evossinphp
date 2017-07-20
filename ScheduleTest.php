<?php


$calDate = new DateTime($argv[1]);

$shiftNum = $argv[2];

$baseDate = new DateTime("2016-01-20");

$calDiff = $calDate->diff($baseDate)->format("%a");

echo "Number of days since 2017-01-18: ".$calDiff."\n";
echo "Shift to evaluate: ".$shiftNum."\n";

$daysIntoRot = ($calDiff % 28) / 7;

if(floor($daysIntoRot) == 0)
{
    echo "This is A Shift\n";
}
elseif(floor($daysIntoRot) == 1)
{
    echo "This is D shift\n";
}
elseif(floor($daysIntoRot) == 2)
{
    echo "This is C shift\n";
}
else {
    echo "This is B Shift\n";
}