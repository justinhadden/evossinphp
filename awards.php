<?php

try
{
	
	//Connect to db
	$pdo = new PDO("mysql:host=localhost;dbname=justhrlm_EVOSS", "justhrlm_Justin", "grav1949");
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	$myfile = fopen("dbfile.txt", "w");
	fwrite($myfile, $e);
	exit();
}

try
{
    $sql = "SELECT concat(OTDate,'-',Shift,'-',JobCode) AS Slot,OTDate
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

try
{
    $sql = "SELECT concat(SubmissionDate,'-',Shift,'-',submission.JobCode) AS EmpSubmission,SubmissionDate,EmpComment,employee.EmpID,OTHours
        FROM employee,submission
        WHERE employee.EmpID=submission.EmpID
        AND SubmissionDate <= CURDATE() + 2";
    $OTSubmissions = $pdo->query($sql);    
}
catch(PDOException $e)
{
	$myfile = fopen("secondfile.txt", "w");
	fwrite($myfile, $e);
	exit();
}



foreach($OTNeeds as $need)
{
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
            //print_r($eligible);
        }
    }

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

    if($found)
    {
    	//print_r($mostEligible);
    	//print_r($need);
        try
        {
            $sql = "UPDATE overtimeneed SET
                EmpID = :newempid,
                EmpComment = :newempcomment
                WHERE :mosteligible = :need";
            $statement = $pdo->prepare($sql);
            $statement->bindvalue(":newempid", $mostEligible['EmpID']);
            $statement->bindvalue(":newempcomment", $mostEligible['EmpComment']);
            $statement->bindvalue(":mosteligible", $mostEligible['EmpSubmission']);
            $statement->bindvalue(":need", $need["Slot"]);
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



