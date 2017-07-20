<?php


//Get Args from script parameters
$calDate = new DateTime($argv[1]);
$shiftNum = $argv[2];

//Reference date for calculating ShiftCode
$baseDate = new DateTime("2016-01-20");

//Get difference between argument date and reference date
$calDiff = $calDate->diff($baseDate)->format("%a");

//Get day into shift schedule rotation
$daysIntoRot = ($calDiff % 28);

//Determine who is working first shift
if($daysIntoRot <= 6)
{
    //From first shift determine second and third
    if($shiftNum == "1")
    {
        echo "A\n";
    }
    elseif($shiftNum == "2")
    {
        if(($daysIntoRot % 7) == 0)
        {
            echo "C\n";
        }
        else
        {
            echo "B\n";
        }
    }
    else
    {
        if(($daysIntoRot % 7) < 2)
        {
            echo "D\n";
        }
        else
        {
            echo "C\n";
        }
    }
}
elseif($daysIntoRot <= 13 && $daysIntoRot > 6)
{
     if($shiftNum == "1")
    {
        echo "D\n";
    }
    elseif($shiftNum == "2")
    {
        if(($daysIntoRot % 7) == 0)
        {
            echo "B\n";
        }
        else
        {
            echo "A\n";
        }
    }
    else
    {
        if(($daysIntoRot % 7) < 2)
        {
            echo "C\n";
        }
        else
        {
            echo "B\n";
        }
    }
}
elseif($daysIntoRot <= 20 && $daysIntoRot > 13)
{
     if($shiftNum == "1")
    {
        echo "C\n";
    }
    elseif($shiftNum == "2")
    {
        if(($daysIntoRot % 7) == 0)
        {
            echo "A";
        }
        else
        {
            echo "D\n";
        }
    }
    else
    {
        if(($daysIntoRot % 7) < 2)
        {
            echo "B\n";
        }
        else
        {
            echo "A\n";
        }
    }
}
else
{
    if($shiftNum == "1")
    {
        echo "B\n";
    }
    elseif($shiftNum == "2")
    {
        if(($daysIntoRot % 7) == 0)
        {
            echo "D\n";
        }
        else
        {
            echo "C\n";
        }
    }
    else
    {
        if(($daysIntoRot % 7) < 2)
        {
            echo "A\n";
        }
        else
        {
            echo "D\n";
        }
    }
}

?>