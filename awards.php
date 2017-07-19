<?php

include "includes/dbconnection.php";

try
{
    $sql = "SELECT concat(OTDate,'-',Shift,'-',JobCode) AS Slot,OTDate,Shift,JobCode,OTNeedID
        FROM overtimeneed
        WHERE OTDate <= CURDATE() + 2";
    $OTNeeds = $pdo->query($sql);    
}
catch(PDOException $e)
{
	$myfile = fopen("firstfile.txt", "w");
	fwrite($myfile, $e);
	exit();
}

foreach($OTNeeds as $need)
{
    include "includes/getSubs.php";
    if($need['Slot'] == "2017-07-19-1-FPO")
    {
        print_r($need['Slot']);

        foreach($OTSubmissions as $submission)
        {
            print_r($submission['EmpSubmission']);
        }
    }
    $eligible = [];
    $mostEligible = [];
    $found = false;
    foreach($OTSubmissions as $submission)
    {
    	//print_r($submission);
    	//print_r($need);
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



