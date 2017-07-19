<?php

include "includes/dbconnection.php";
include "includes/getNeeds.php";

//Match OTNeeds with Submissions
foreach($OTNeeds as $need)
{
    include "includes/getSubs.php";

    $eligible = [];
    $mostEligible = [];
    $found = false;
    foreach($OTSubmissions as $submission)
    {
        if($submission['EmpSubmission'] == $need['Slot'])
        {
            array_push($eligible, $submission);
            $found = true;
        }
    }

    if($found)
    {
        foreach($eligible as $employee)
        {
            if(empty($mostEligible))
            {
                $mostEligible = $employee;
            }
            elseif($employee['OTHours'] < $mostEligible['OTHours']){
                $mostEligible = $employee;
            }
        }
    }

    if($found)
    {
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
        $hours = $submission["OTHours"];
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
                OTHours = :newhours
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
        }

        //Update the submission to indicate that it has been awarded
        try
        {
            $sql = "UPDATE submission SET
                Awarded = 1
                WHERE SubID = :subid";
            $statement = $pdo->prepare($sql);
            $statement->bindvalue(":subid", $mostEligible['SubID']);
            $statment-execute();
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



