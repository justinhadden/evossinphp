<?php

function calSchedule($date, $shiftNum)
{
    //Reference date for calculating ShiftCode
    $baseDate = new DateTime("2016-01-20");
    $calDate = new DateTime($date);
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
            return "A";
        }
        elseif($shiftNum == "2")
        {
            if(($daysIntoRot % 7) == 0)
            {
                return "C";
            }
            else
            {
                return "B";
            }
        }
        else
        {
            if(($daysIntoRot % 7) < 2)
            {
                return "D";
            }
            else
            {
                return "C";
            }
        }
    }
    elseif($daysIntoRot <= 13 && $daysIntoRot > 6)
    {
        if($shiftNum == "1")
        {
            return "D";
        }
        elseif($shiftNum == "2")
        {
            if(($daysIntoRot % 7) == 0)
            {
                return "B";
            }
            else
            {
                return "A";
            }
        }
        else
        {
            if(($daysIntoRot % 7) < 2)
            {
                return "C";
            }
            else
            {
                return "B";
            }
        }
    }
    elseif($daysIntoRot <= 20 && $daysIntoRot > 13)
    {
        if($shiftNum == "1")
        {
            return "C";
        }
        elseif($shiftNum == "2")
        {
            if(($daysIntoRot % 7) == 0)
            {
                return "A";
            }
            else
            {
                return "D";
            }
        }
        else
        {
            if(($daysIntoRot % 7) < 2)
            {
                return "B";
            }
            else
            {
                return "A";
            }
        }
    }
    else
    {
        if($shiftNum == "1")
        {
            return "B";
        }
        elseif($shiftNum == "2")
        {
            if(($daysIntoRot % 7) == 0)
            {
                return "D";
            }
            else
            {
                return "C";
            }
        }
        else
        {
            if(($daysIntoRot % 7) < 2)
            {
                return "A";
            }
            else
            {
                return "D";
            }
        }
    }
}
?>