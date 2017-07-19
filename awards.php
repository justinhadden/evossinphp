<?php

include "includes/dbconnection.php";
include "includes/getNeeds.php";

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
        $hours = $submission["OTHours"];
        if($submission['OTBlock'] == 2)
        {
            $hours += 4;
        }
        else 
        {
            $hours += 8;    
        }
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
        unset($eligible);
        unset($mostEligible);
        
    }

}



