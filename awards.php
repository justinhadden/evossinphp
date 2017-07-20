<?php

include "includes/dbconnection.php";
include "includes/functions.php";

//These functions are explained in the includes/functions.php script
$OTNeeds = getTodaysNeeds();
$OTSubmissions = getTodaysSubmissions();
$Employees = getAllEmployees();

//Match OTNeeds with Submissions
foreach($OTNeeds as $need)
{
    $eligible = [];
    $mostEligible;
    $found = false;
    
    $needShiftCode = calSchedule($need['OTDate'], $need['Shift']);

    $eligibleEmps = getEligibleEmployees($need['JobCode'], $needShiftCode);

    print_r($eligibleEmps);
    foreach($eligibleEmps as $key => $employee)
    {
        foreach($OTSubmissions as $submission)
        {
            if($submission['EmpID'] == $employee['EmpID'])
            {
                if($submission['Shift'] == $need['Shift'])
                {
                    if($submission['Awarded'] == 1)
                    {
                        unset($eligibleEmps[$key]);
                    }
                }
            }
        }
    }
    print_r($eligibleEmps);

    $EmpsSubmitted = [];

    foreach($eligibleEmps as $employee)
    {
        foreach($OTSubmissions as $submission)
        {
            if(concat($submission['SubmissionDate'],"-",$submission['Shift'],"-",$submission['JobCode']) == concat($need['OTDate'],"-",$need['Shift'],"-",$need['JobCode']))
            {
                if($employee['EmpID'] == $submission['EmpID'])
                {
                    array_push($empsSubmitted, $employee);
                    if(!$found)
                    {
                        $mostEligible = $employee;
                    }
                    $found = true;
                }
            }
        }
    }

    if($found)
    {
        /*
        //Update the overtimeNeed to show who is working it
        try
        {
            $sql = "UPDATE overtimeneed SET
                EmpID = :newempid,
                EmpComment = :newempcomment
                WHERE OTNeedID = :needid";
            $statement = $pdo->prepare($sql);
            $statement->bindvalue(":newempid", $mostEligible['EmpID']);
            $statement->bindvalue(":newempcomment", $mostEligible['EmpComment']);
            $statement->bindvalue(":needid", $need["OTNeedID"]);
            $statement->execute();
        }
        catch(PDOException $e)
        {
            $myfile = fopen("theupdatefile.txt", "w");
            fwrite($myfile, $e);
            exit();
        }

        //Calculate the emps new OTHours
        $hours = $submission["OTHoursWorked"];
        if($submission['OTBlock'] == 2)
        {
            $hours += 8;
        }
        else 
        {
            $hours += 4;    
        }

        //Update the emps OTHours
        try
        {
            $sql = "UPDATE employee SET
                OTHoursWorked = :newhours
                WHERE EmpID = :empid";
            $statement = $pdo->prepare($sql);    
            $statement->bindvalue(":newhours", $hours);
            $statement->bindvalue(":empid", $mostEligible['EmpID']);
            $statement->execute();
        }
        catch(PDOException $e)
        {
            $myfile = fopen("theEmpupdatefile.txt", "w");
            fwrite($myfile, $e);
            exit();
        }*/

        //Update the submission to indicate that it has been awarded
        try
        {
            $sql = "UPDATE submission SET
                Awarded = 1
                WHERE SubID = :subid";
            $statement = $pdo->prepare($sql);
            $statement->bindvalue(":subid", $mostEligible['SubID']);
            $statement->execute();
        }
        catch(PDOException $e)
        {
            $myfile = fopen("theSubupdatefile.txt", "w");
            fwrite($myfile, $e);
            exit();
        }

        //Unset arrays
        unset($eligible);
        unset($mostEligible);      
    }
}



