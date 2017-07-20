<?php


$calDate = new DateTime($argv[1]);

$shiftNum = $argv[2];

$baseDate = new DateTime("2017-01-18");

$calDiff = $calDate->diff($baseDate)->format("%a");

echo "Number of days since 2017-01-18: ".$calDiff."\n";
echo "Shift to evaluate: ".$shiftNum."\n";

$daysIntoRot = $calDiff % 28 + 1;

if(floor($daysIntoRot) == 0)
{
    echo "This is A Shift";
}
elseif(floor($daysIntoRot) == 1)
{
    echo "This is D shift";
}
elseif(floor($daysIntoRot) == 2)
{
    echo "This is C shift";
}
else {
    echo "This is B Shift";
}