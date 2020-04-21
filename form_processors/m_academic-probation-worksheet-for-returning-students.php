<?php
//*******************************************************************************************************
//	m_academic-probation-worksheet-for-returning-students.php 
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: Linda Vasavong
//*******************************************************************************************************

//===================================================================
// Initialization
//===================================================================
require_once('class_library/LoginForm.php');
require_once('class_library/Common.php');
require_once('class_library/database_drivers/MySQLDriver.php');

session_start();
session_name('academic_probation');

$loginForm = new LoginForm("Academic Probation Worksheet for Returning Students");
$common = new Common();

$loginTest = true;
$validTest = true;

$status = "";

$formData = array();
$errors = array();
$error_messages = array();
$userData = $_SESSION['UserData'];

// variables to reset the checkbox's after failed validation.
$resources = array();
$influences = array();

//===================================================================
// Request Handling
//===================================================================

if($_SESSION['Processed'] == 'Yes')
{
	//Catch to eliminate duplicate submissions
	unset($_SESSION['Processed']);
	
	// Redirect to the login page
	if($_SERVER['SERVER_NAME'] == 'secure1.wdev.rochester.edu')
		header('Location: https://secure1.wdev.rochester.edu/ccas/academic-probation-worksheet-for-returning-students.php');
	else
		header('Location: https://secure1.rochester.edu/ccas/academic-probation-worksheet-for-returning-students.php');

}
else if(isset($_POST['Login']))
{
	$loginForm->Instantiate($_POST['username'], $_POST['password']);
	$loginForm->Validate();
	
	if($loginForm->IsValid())
	{
		$_SESSION['LoggedIn'] = 'Yes';
		$userData = $_SESSION['UserData'] = $loginForm->GetInfo();
	}
	else
	{
		$loginTest = false;	
	}
}
else if(isset($_POST['Save']) && $_SESSION['LoggedIn'] == 'Yes')
{
	$formData = $_POST;
	if(!empty($userData['firstName']) && !empty($userData['lastName']) && !empty($userData['studentID']))	//checks if userdata is timed out
	{
		$errors = Validate($formData);
		
		// Write back of data for the checkboxes
		$res = $formData['resources'];
		$inf = $formData['influences'];
		
		foreach($res as $r)
		{
			$resources[$r] = 1;
		}
		
		foreach($inf as $r)
		{
			$influences[$r] = 1;
		}
		
		if(empty($errors))
		{
			if(Process($formData))
			{
				$status = "OK";
				SendEmail($formData, date('Y-m-d H:i:s', time()));
				unset($_SESSION['LoggedIn']);
				unset($_SESSION['UserData']);
				$_SESSION['Processed'] = 'Yes';
			}
			else
			{
				$status = "DB_ERR";
			}			
		}
		else
		{
			$validTest = false;
		}
	}
	else
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
	}
}
else
{
	unset($_SESSION['LoggedIn']);
	unset($_SESSION['UserData']);
}

//===================================================================
// Functions
//===================================================================

//-------------------------------------------------------------------
function Validate($data)
{
	global $errors, $error_messages;
	
	if(empty($data['studentEmail']))
	{
		$errors[] = "studentEmail";
		$error_messages[] = "You must provide an email address.";
	}
	if(empty($data['majors']) || empty($data['cluster1']))
	{
		$errors[] = "majors";
		$error_messages[] = "You must answer question #1.";
	}
	if(empty($data['problem']))
	{
		$errors[] = "problem";
		$error_messages[] = "You must answer question #2.";
	}
	if(empty($data['plan']))
	{
		$errors[] = "four";
		$error_messages[] = "You must answer question #4 by responding to how will you approach your studies differently.";
	}
	if(empty($data['studyHabits']))
	{
		$errors[] = "studyHabits";
		$error_messages[] = "You must answer question #5.";
	}
	if(empty($data['inYes']) && empty($data['inNo']))
	{
		$errors[] = "six";
		$error_messages[] = "You must answer question #6.";
	}
	else if((!empty($data['inYes']) && empty($data['inNo'])))
	{
		if(empty($data['instructorYes']) && empty($data['instructorNo']))
		{
			$errors[] = "six";
			$error_messages[] = "You must answer question #6.";
		}
		else
		{
			if(empty($data['incompletes']))
			{
				$errors[] = "six";
				$error_messages[] = "You must answer question #6.";
			}
		}
	}
	if(empty($data['schedule']))
	{
		$errors[] = "schedule";
		$error_messages[] = "You must answer question #7.";
	}
	if(empty($data['help']))
	{
		$errors[] = "help";
		$error_messages[] = "You must answer question #8.";
	}

	return $errors;	
}

//-------------------------------------------------------------------

function Process($data)
{
	global $resources;
	global $influences;
	
	$db_drvr = new MySQLDriver();

	/* Submit this record to MySQL */
	$record = array();
	
	foreach($data as $key => $value)
	{
		/* STRIP OUT ANY KEYS YOU'RE NOT SENDING TO THE MYSQL TABLE */
		if($key != 'Save' && 
		   $key != 'resources' &&
		   $key != 'influences' &&
		   $key != 'problemCount' &&
		   $key != 'resolveCount' && 
		   $key != 'planCount' && 
		   $key != 'studyHabitsCount' && 
		   $key != 'scheduleCount' && 
		   $key != 'incompletesCount' &&
		   $key != 'helpCount' &&
		   $key != 'resources_other' &&
		   $key != 'influences_other')
		{
			$record[$key] = $value;	
		}
	}

	$record['resources'] = ParseCheckboxes($data['resources'],$data['resources_other']);
	$record['influences'] = ParseCheckboxes($data['influences'],$data['influences_other']);
	$record['ipAddress'] = $_SERVER['REMOTE_ADDR'];
	$record['dateSubmitted'] = date('Y-m-d H:i:s', time());

	//echo var_dump($record);

	$id = $db_drvr->Insert('AcademicProbationReturning',$record);
	
	if($id == 0)
		return false;
	else
		return true;
}

//-------------------------------------------------------------------
function ParseCheckboxes($input, $other)
{
	$return_string = "";
	
	foreach($input as $i)
	{		
		if($i != 'Other')
		{
			if(!empty($return_string))
			{
				$return_string .= ", " . $i;
			}
			else
			{
				$return_string .= $i;
			}
		}
	}
	
	if(in_array("Other",$input))
	{
		$return_string .= ", " . $other;
	}
	
	return $return_string;
		
}

//-------------------------------------------------------------------
function SendEmail($data, $date)
{
	$to = "john.ballou@rochester.edu";
	//$to = "lvasavon@u.rochester.edu";
	
	$subject = 'Academic Probation Worksheet for Returning Students Submission';
	
	$headers = "From: " . $data['studentEmail'] . "\r\n";
	$headers .= "Bcc: " . $data['studentEmail'] . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$message = "<html><head><style>th, td{padding: 2px; text-align: left;} @media only screen and (max-width:480px){table{width:100% !important; max-width:480px !important;}</style></head><body>";
	$message .= "<table style='width:100%;'>";
	$message .= "<tr><td colspan='2' style='width: 100%; text-align: center; background-color: #021e47; color: #FFC125; border-bottom: 3px solid #FFC125; border-radius: 10px;'>Arts, Sciences and Engineering<hr/><div style='color:white; font-size: 175%; padding: 0px 0px 15px 0px;'><span style='color:#FFC125;'>U</span><span style='font-variant:small-caps;'>niversity</span> <i>of</i> <span style='color:#FFC125;'>R</span><span style='font-variant:small-caps;'>ochester</span></div></td></tr>";
	$message .= "<tr><td colspan='2'><p>A student has submitted an Academic Probation Worksheet for Returning Students</p><div align='center'><b>Student Information</b></div><hr/></td></tr>";
	$message .= "<tr><td>Student Name:</td><td>" . $data['studentFirstName'] . "  " . $data['studentLastName'] . "</td></tr>";
	$message .= "<tr><td>Student UID:</td><td>" . $data['studentID'] . "</td></tr>";
	$message .= "<tr><td>Student Email:</td><td>" . $data['studentEmail'] . "</td></tr>";
	$message .= "<tr><td>IP Address:</td><td>" . $_SERVER['REMOTE_ADDR'] . "</td></tr>";
	$message .= "<tr><td colspan='2'><div align='center'><b>Question Responses</b></div><hr/></td></tr>";
	$message .= "<tr><td colspan='2'><i>1.)What is your plan for completing Rochester Curriculum degree requirements?</i></td></tr>";
	$message .= "<tr><td colspan='2'><b>Major(s): </b>" . $data['majors'] . "</td></tr>";
	$message .= "<tr><td colspan='2'><b>Minor(s): </b>" . $data['minors'] . "</td></tr>";
	$message .= "<tr><td colspan='2'><b>Cluster 1: </b>" . $data['cluster1'] . "</td></tr>";
	$message .= "<tr><td colspan='2'><b>Cluster 2: </b>" . $data['cluster2'] . "</td></tr>";	
	$message .= "<tr><td colspan='2'><i>2.) Describe the particular difficulties you encountered when you were previously enrolled.</i></td></tr>";
	$message .= "<tr><td colspan='2'>" . $data['problem'] . "</td></tr>";
	$message .= "<tr><td colspan='2'><i>3.) Was your academic performance influenced by any of the following? Check all that apply.</i></td></tr>";
	$message .= "<tr><td colspan='2'>" . ParseCheckboxes($data['influences'],$data['influences_other']) . "</td></tr>";
	$message .= "<tr><td colspan='2'><i>If any extenuating circumstances influenced your previous academic performance, have they been resolved? </i>" . $data['resolve'] . "</td></tr>";
	$message .= "<tr><td colspan='2'><i>4.) Describe the types of academic support you sought out during your previous enrollment. Please check any and all resources you used previously.</i></td></tr>";
	$message .= "<tr><td colspan='2'>" . ParseCheckboxes($data['resources'],$data['resources_other']) . "</td></tr>";
	$message .= "<tr><td colspan='2'><i>Now that you are returning to school after time away, how will you approach your studies differently? For example, what resources do you plan to use upon your return? How do you expect to engage differently than when you were last enrolled? Have you decided to change your program of study? Are there other changes you would like to make? </i>" . $data['plan'] . "</td></tr>";
	$message .= "<tr><td colspan='2'><i>5.) Describe your study habits, including where you studied, how you organized your time, etc.</i></td></tr>";
	$message .= "<tr><td colspan='2'>" . $data['studyHabits'] . "</td></tr>";
	if(!empty($data['inYes']))
	{
		$message .= "<tr><td colspan='2'><i>6.) Do you have any I/N grades in any course(s)? </i>" . $data['inYes'] . "</td></tr>";
	}
	else if(!empty($data['inNo']))
	{
		$message .= "<tr><td colspan='2'><i>6.) Do you have any I/N grades in any course(s)? </i>" . $data['inNo'] . "</td></tr>";
	}
	if(!empty($data['instructorYes']) || !empty($data['instructorNo']))
	{
		if(!empty($data['instructorYes']))
		{
			$message .= "<tr><td colspan='2'><i>Have you been in communication with the Instructor? </i>" . $data['instructorYes'] . "</td></tr>";
		}
		else if(!empty($data['instructorNo']))
		{
			$message .= "<tr><td colspan='2'><i>Have you been in communication with the Instructor? </i>" . $data['instructorNo'] . "</td></tr>";
		}

		$message .= "<tr><td colspan='2'><i>How much work do you estimate you have left to complete in each class, and how long do you determine you will need to complete the assignments for each course?</i></td></tr>";
		$message .= "<tr><td colspan='2'>" . $data['incompletes'] . "</td></tr>";
	}
	
	$message .= "<tr><td colspan='2'><i>7.) What is your tentative course schedule for the upcoming semester?</i></td></tr>";
	$message .= "<tr><td colspan='2'>" . $data['schedule'] . "</td></tr>";
	$message .= "<tr><td colspan='2'><i>8.) How can your CCAS Advisor help you achieve your goals for the upcoming semester, and with your re-entry into the University of Rochester?</i></td></tr>";
	$message .= "<tr><td colspan='2'>" . $data['help'] . "</td></tr>";
	
	$message .= "<tr><td>Submitted on: " . $date . "</td></tr>";
	$message .= "<tr><td colspan='2' style='width: 100%; text-align: center; background-color: #021e47; color: white; border-top: 3px solid #FFC125; border-radius: 10px;'><p>Copyright &#169; 2013&#150;2015. All rights reserved.<br /><a style='color:white;' href='http://www.rochester.edu/'>University of Rochester</a> | <a style='color:white;' href='http://www.rochester.edu/college/'>AS&#38;E</a> | <a style='color:white;' href='index.html'>Registrar</a><br/><a style='color:white;' href='http://www.rochester.edu/accessibility.html'>Accessibility</a> | <a style='color:white;' href='http://text.rochester.edu/tt/referrer' title='Access a text-only version of this page.'>Text</a> | <a style='color:white;' href='http://www.rochester.edu/college/webcomm/' title='Get help with your AS&amp;E website.'>Web Communications</a></p></td></tr>";
	$message .="</table>";	
	$message .= "</body></html>";
	
	mail($to, $subject, $message, $headers);
}

?>