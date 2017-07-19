<?php

include "includes/dbconnection.php";
include "includes/getNeeds.php";
include "includes/getSubs.php";

foreach($OTNeeds as $need)
{

    $eligible = [];
    $mostEligible = [];
    $found = false;
    foreach($OTSubmissions as $submission)
    {
    	print_r("-|-".$need['Slot']."---".$submission['EmpSubmission']);
        if($submission['EmpSubmission'] == $need['Slot'])
        {
            array_push($eligible, $submission);
            $found = true;
            print_r("Found");
        }
    }
    //print_r($eligible);
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
    	//print_r($mostEligible);
    	//print_r($need);
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
        unset($eligible);
        unset($mostEligible);
        
    }

}



