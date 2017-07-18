<?php

try
{
	
	//Connect to db
	$pdo = new PDO("mysql:host=localhost;dbname=evoss", "root", "");
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	exit();
}

try
{
    $sql = "SELECT concat(OTDate,'-',Shift,'-',JobCode) AS Slot,OTDate
        FROM OvertimeNeed
        WHERE OTDate >= CURDATE()+2";
    $OTNeeds = $pdo->query($sql);    
}
catch(PDOException $e)
{
	exit();
}

try
{
    $sql = "SELECT concat(SubmissionDate,'-',Shift,'-',JobCode) AS EmpSubmission,SubmissionDate,EmpComment,EmpID,OTHours
        FROM Employee,Submission
        WHERE Employee.EmpID=Submission.EmpID
        AND SubmissionDate >= CURDATE()+2";
    $OTSubmissions = $pdo->query($sql);    
}
catch(PDOException $e)
{
	exit();
}

$eligible = [];
$mostEligible = [];

foreach($OTNeeds as $need)
{
    foreach($OTSubmissions as $submission)
    {
        if($submission['EmpSubmission'] == $need['Slot'])
        {
            array_push($eligible, $submission);
        }
    }
    foreach($eligible as $employee)
    {
        if(is_null($mostEligible))
        {
            $mostEligible = $employee;
        }
        elseif($employee['OTHours'] < $mostEligible["OTHours"]){
            $mostEligible = $employee;
        }
    }

    if(!is_null($mostEligible))
    {
        try
        {
            $sql = "UPDATE OvertimeNeed SET
                EmpID = :newempid
                EmpComment = :newempcomment
                WHERE :mosteligible = :need";
            $statement = $pdo->prepare($sql);
            $statement->bindvalue(":newempid", $mostEligible['EmpID']);
            $statement->bindvalue(":newempcomment", $mostEligible['EmpComment']);
            $statement->bindvaule(":mosteligible", $mostEligible['EmpSubmission']);
            $statement->bindvalue(":need", $need["slot"]);
            $statement->execute();
        }
        catch(PDOException $e)
        {
            exit();
        }
        
    }

}



