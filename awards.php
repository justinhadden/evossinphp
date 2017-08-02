<?php

include "includes/dbconnection.php";
include "includes/functions.php";

//These functions are explained in the includes/functions.php script
$OTNeeds = getTodaysNeeds();

$chargedEmployees = [];

//Match OTNeeds with Submissions
foreach($OTNeeds as $need)
{
	print_r($need);
    $shiftCode = calSchedule($need['OTDate'], $need['Shift']);
    $eligibleEmps = getEligibleEmployees($need['JobCode'], $shiftCode);
    
    $applicableSubmissions = getApplicableSubmissions($need['OTDate'],$need['Shift'],$need['JobCode']);
	if(empty($applicableSubmissions))
	{
		break;
	}
    $mostEligibleEmployee;
    $mostEligibleSubmission;
	if(!empty($applicableSubmissions))
	{
		foreach($applicableSubmissions as $submission)
		{	
			$applicable = true;
			$employee = getEmployee($submission['EmpID']);
			foreach($OTNeeds as $thisNeed)
			{
				$getSubmission = getSubmission($thisNeed['SubmissionID']);
				if(empty($getSubmission))
				{
					break;
				}
				if($employee['ID'] == $getSubmission['EmpID'])
				{
					$key = array_search($submission['ID'], $applicableSubmissions);
					unset($applicableSubmissions[$key]);
					$applicable = false;
					break;
				}
			}
			$needShift = $need['Shift'];
			$needDate = new DateTime($need['OTDate']);
			if($needShift == 1)
			{
				$needShift = 4;
				$needDate = strtotime('-1 days',$needDate = time());
			}
			
			if($applicable)
			{
				$calShiftCode = calSchedule($needDate,$needShift);
				if($calShiftCode = $employee['ShiftCode'])
				{
					$key = array_search($submission['ID'], $applicableSubmissions);
					unset($applicableSubmissions[$key]);
					$applicable = false;
				}
			}
		}
	}
	$awardedEmployee;
	$awarding;
	foreach($applicableSubmissions as $submission)
	{
		$awarding = $submission;
		updateNeed($awarding['ID'], $need['ID']);
		$awardedEmployee = $awarding['EmpID'];
		break;
	}
	
	foreach($eligibleEmps as $employee)
	{
		if($employee['ID'] != $awardedEmployee)
		{
			$tempArray = [];
			array_push($tempArray,$employee['ID']);
			array_push($tempArray,$awarding['OTBlock']);
			array_push($chargedEmployees, $tempArray);
		}
	}
	
	foreach($chargedEmployees as $employee)
	{
		updateEmployee($employee[0],$employee[1]);
	}
}

if(!empty($chargedEmployees))
{
	foreach($chargedEmployees as $employee)
	{
		updateEmployee($employee[0],$employee[1]);
	}
}



