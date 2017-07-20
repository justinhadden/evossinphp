<?php


$calDate = new DateTime($argv[1]);

$shiftNum = $argv[2];

$baseDate = new DateTime("2016-01-20");

$calDiff = $calDate->diff($baseDate)->format("%a");

echo "Number of days since 2016-01-20: ".$calDiff."\n";
echo "Shift to evaluate: ".$shiftNum."\n";

$daysIntoRot = ($calDiff % 28);

$shiftCode = $daysIntoRot / 7;

if(floor($shiftCode) == 0)
{
    if($shiftNum == "1")
    {
        echo "This is A Shift\n";
    }
    elseif($shiftNum == "2")
    {
        if($daysIntoRot == 0)
        {
            echo "This is C Shift\n";
        }
        else
        {
            echo "This is B Shift\n";
        }
    }
    else
    {
        if($daysIntoRot < 2)
        {
            echo "This is D Shift\n";
        }
        else
        {
            echo "This is C Shift\n";
        }
    }
}
elseif(floor($shiftCode) == 1)
{
     if($shiftNum == "1")
    {
        echo "This is D Shift\n";
    }
    elseif($shiftNum == "2")
    {
        if($daysIntoRot == 0)
        {
            echo "This is B Shift\n";
        }
        else
        {
            echo "This is A Shift\n";
        }
    }
    else
    {
        if($daysIntoRot < 2)
        {
            echo "This is C Shift\n";
        }
        else
        {
            echo "This is B Shift\n";
        }
    }
}
elseif(floor($shiftCode) == 2)
{
     if($shiftNum == "1")
    {
        echo "This is C Shift\n";
    }
    elseif($shiftNum == "2")
    {
        if($daysIntoRot == 0)
        {
            echo "This is A Shift\n";
        }
        else
        {
            echo "This is D Shift\n";
        }
    }
    else
    {
        if($daysIntoRot < 2)
        {
            echo "This is B Shift\n";
        }
        else
        {
            echo "This is A Shift\n";
        }
    }
}
else
{
    if($shiftNum == "1")
    {
        echo "This is B Shift\n";
    }
    elseif($shiftNum == "2")
    {
        if($daysIntoRot == 0)
        {
            echo "This is D Shift\n";
        }
        else
        {
            echo "This is C Shift\n";
        }
    }
    else
    {
        if($daysIntoRot < 2)
        {
            echo "This is A Shift\n";
        }
        else
        {
            echo "This is D Shift\n";
        }
    }
}

?>