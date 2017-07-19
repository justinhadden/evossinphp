<?php

include "includes/dbconnection.php";

try
{
    $sql = "SELECT concat(SubmissionDate,'-',Shift,'-',submission.JobCode) AS EmpSubmission,
        SubmissionDate,EmpComment,employee.EmpID,OTHours,Shift,submission.JobCode
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