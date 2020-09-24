<?php
/**
 * Plugin Name: Old Kent Applications
 * Description: Allows prospective tenants to submit an application
 * Author: Jake Robers
 * Author URI: https://www.jakerobers.com
 */


$currentDir = dirname(__FILE__);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once($currentDir.'/PHPMailer/src/Exception.php');
require_once($currentDir.'/PHPMailer/src/PHPMailer.php');
require_once($currentDir.'/PHPMailer/src/SMTP.php');

function getErrClass($err) {
	$err_class = '';
	if ($err) {
		$err_class='is-invalid';
	}
	return $err_class;
}

function createDateInputGroup($key, $name, $value, $err) {
	if (!isset($value)) {
		$value = date('Y-m-d');
	}

	$content .= "<div class='form-group'>";

	$content .= "<label for='". $key ."'>". $name ."</label>";
	$content .= "<input class='form-control ". getErrClass($err) ."' name='". $key ."' type='date' value='". $value ."'/>";
	if ($err) {
		$content .= "<div class='invalid-feedback'>". $err ."</div>";
	}

	$content .= "</div>";
	return $content;
}

function createTextInput($key, $name, $value='', $class='') {
	return "<input class='form-control ". $class ."' name='". $key ."' type='text' placeholder='". $name ."' value='". $value ."'/>";
}

function createTextInputGroup($key, $name, $value='', $err) {
	$content .= "<div class='form-group'>";

	$content .= "<label for='". $key ."'>". $name ."</label>";
	$content .= createTextInput($key, $name, $value, getErrClass($err));
	if ($err) {
		$content .= "<div class='invalid-feedback'>". $err ."</div>";
	}

	$content .= "</div>";
	return $content;
}

function createEmailInputGroup($key, $name, $value='', $err) {
	$content .= "<div class='form-group'>";

	$content .= "<label for='". $key ."'>". $name ."</label>";
	$content .= "<input class='form-control ". getErrClass($err) ."' name='". $key ."' type='email' placeholder='". $name ."' value='". $value ."'/>";
	if ($err) {
		$content .= "<div class='invalid-feedback'>". $err ."</div>";
	}

	$content .= "</div>";
	return $content;
}

function createPhoneInputGroup($key, $name, $value='', $err) {
	$content .= "<div class='form-group'>";

	$content .= "<label for='". $key ."'>". $name ."</label>";
	$content .= "<input class='form-control ". getErrClass($err) ."' name='". $key ."' type='phone' placeholder='". $name ."' value='". $value ."'/>";
	if ($err) {
		$content .= "<div class='invalid-feedback'>". $err ."</div>";
	}

	$content .= "</div>";
	return $content;
}

function createYesNoRadiosGroup($key, $name, $value='', $err) {
	$yesChecked = '';
	$noChecked = '';
	if ($value == 'yes' || $value == '') {
		$yesChecked = 'checked';
	} else if($value == 'no') {
		$noChecked = 'checked';
	}

	$content .= "<div class='form-group mv3'>";
	$content .= "<label>". $name ."</label>";
	$content .= "<br />";
	$content .= "<label class='radio-inline'>";
	$content .= "<input type='radio' class='mr1' name='". $key ."' value='yes' ". $yesChecked .">";
	$content .= "Yes";
	$content .= "</label>";
	$content .= "<label class='radio-inline ml3'>";
	$content .= "<input type='radio' class='mr1' name='". $key ."' value='no' default='' ". $noChecked .">";
	$content .= "No";
	$content .= "</label>";

	$content .= "</div>";
	return $content;
}

function createCheckbox($key, $name, $value='off', $err, $class='') {
	$checked = '';
	if ($value == 'on') {
		$checked = 'checked';
	}

	$content .= "<div class='form-check form-check-inline ". $class ."'>";
	$content .= "<input class='form-check-input ". getErrClass($err) ."' name='". $key ."' type='checkbox' ". $checked ."/>";
	$content .= "<label class='form-check-label ml3' for='". $key ."'>". $name ."</label>";
	$content .= "</div>";
	return $content;
}

function createDropdownInputGroup($key, $name, $value, $err, $options) {
	$content .= '<div class="form-group mv4">';
	$content .= '<label for="'. $key .'">'. $name .'</label>';
	$content .= "<br />";
	$content .= '<select class="'. getErrClass($err) .'" name="'. $key .'">';
	foreach ($options as $option) {
		$selected = '';
		if ($option['key'] == $value) {
			$selected = 'selected';
		}
		$content .= '<option value="'. $option['key'] .'" '. $selected .'>'. $option['label'] .'</option>';
	}
	$content .= '</select>';
	if ($err) {
		$content .= "<div class='invalid-feedback'>". $err ."</div>";
	}

	$content .= '</div>';
	return $content;
}


function renderForm($submission, $errs) {
	$content = "
		<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
		<link rel='stylesheet' href='https://unpkg.com/tachyons@4.12.0/css/tachyons.min.css'/>

		<h1>Rental Application</h1>
		<p>
			Please read and fill out all questions completely.
			Missing answers will delay the process of evaluating this application and
			potentially allow complete applications to be evaluated and approved before you
			while missing information is being obtained. Do not leave anything blank on the
			application. If the question does not apply to you, respond \"N/A\".
		</p>
		<form class='mt4' action='/apply' method='post'>
	";

	$content .= createCheckbox('agree', 'I have read and understood the statement above', $submission['agree'], $errs['agree'], 'mb4');
	$content .= '<h2>Tenant Information</h2>';
	$content .= createTextInputGroup('firstName', 'First Name', $submission['firstName'], $errs['firstName']);
	$content .= createTextInputGroup('middleInitial', 'Middle Initial', $submission['middleInitial'], $errs['middleInitial']);
	$content .= createTextInputGroup('lastName', 'Last Name', $submission['lastName'], $errs['lastName']);
	$content .= createTextInputGroup('previousLastName', 'Previous different last name (if any)', $submission['lastName'], $errs['lastName']);
	$content .= createDateInputGroup('dob', 'Birth date', $submission['dob'], $errs['dob']);
	$content .= createTextInputGroup('licenseNumber', 'Drivers License Number', $submission['licenseNumber'], $errs['licenseNumber']);
	$content .= createEmailInputGroup('email', 'Email', $submission['email'], $errs['email']);
	$content .= createPhoneInputGroup('phone', 'Phone Number', $submission['phone'], $errs['phone']);
	$content .= createYesNoRadiosGroup('isCellphone', 'Is this a cellphone?', $submission['isCellphone'], $errs['isCellphone']);
	$content .= createYesNoRadiosGroup('canIText', 'Can I text you?', $submission['canIText'], $errs['canIText']);

	$content .= '<h2>List All Proposed Occupants</h2>';
	$content .= '<p>Please list all additional occupants that you plan to have reside in the unit. If you begin filling out an occupant, please ensure that it is entirely filled before submitting.</p>';

	$content .= '<h3>Additional Occupant #1 (if any)</h3>';
	$content .= '<div>';
	$content .= createTextInputGroup('addOcc1FirstName', 'First name', $submission['addOcc1FirstName'], $errs['addOcc1FirstName']);
	$content .= createTextInputGroup('addOcc1MiddleInitial', 'Middle initial', $submission['addOcc1MiddleInitial'], $errs['addOcc1MiddleInitial']);
	$content .= createTextInputGroup('addOcc1LastName', 'Last name', $submission['addOcc1LastName'], $errs['addOcc1LastName']);
	$content .= createTextInputGroup('addOcc1Dob', 'Birth date', $submission['addOcc1Dob'], $errs['addOcc1Dob']);
	$content .= createTextInputGroup('addOcc1Relation', 'Relation', $submission['addOcc1Relation'], $errs['addOcc1Relation']);
	$content .= '</div>';

	$content .= '<h3>Additional Occupant #2 (if any)</h3>';
	$content .= '<div>';
	$content .= createTextInputGroup('addOcc2FirstName', 'First name', $submission['addOcc2FirstName'], $errs['addOcc2FirstName']);
	$content .= createTextInputGroup('addOcc2MiddleInitial', 'Middle initial', $submission['addOcc2MiddleInitial'], $errs['addOcc2MiddleInitial']);
	$content .= createTextInputGroup('addOcc2LastName', 'Last name', $submission['addOcc2LastName'], $errs['addOcc2LastName']);
	$content .= createTextInputGroup('addOcc2Dob', 'Birth date', $submission['addOcc2Dob'], $errs['addOcc2Dob']);
	$content .= createTextInputGroup('addOcc2Relation', 'Relation', $submission['addOcc2Relation'], $errs['addOcc2Relation']);
	$content .= '</div>';


	$content .= '<h3>Additional Occupant #3 (if any)</h3>';
	$content .= '<div>';
	$content .= createTextInputGroup('addOcc3FirstName', 'First name', $submission['addOcc3FirstName'], $errs['addOcc3FirstName']);
	$content .= createTextInputGroup('addOcc3MiddleInitial', 'Middle initial', $submission['addOcc3MiddleInitial'], $errs['addOcc3MiddleInitial']);
	$content .= createTextInputGroup('addOcc3LastName', 'Last name', $submission['addOcc3LastName'], $errs['addOcc3LastName']);
	$content .= createTextInputGroup('addOcc3Dob', 'Birth date', $submission['addOcc3Dob'], $errs['addOcc3Dob']);
	$content .= createTextInputGroup('addOcc3Relation', 'Relation', $submission['addOcc3Relation'], $errs['addOcc3Relation']);
	$content .= '</div>';

	$content .= '<h3>Additional Occupant #4 (if any)</h3>';
	$content .= '<div>';
	$content .= createTextInputGroup('addOcc4FirstName', 'First name', $submission['addOcc4FirstName'], $errs['addOcc4FirstName']);
	$content .= createTextInputGroup('addOcc4MiddleInitial', 'Middle initial', $submission['addOcc4MiddleInitial'], $errs['addOcc4MiddleInitial']);
	$content .= createTextInputGroup('addOcc4LastName', 'Last name', $submission['addOcc4LastName'], $errs['addOcc4LastName']);
	$content .= createTextInputGroup('addOcc4Dob', 'Birth date', $submission['addOcc4Dob'], $errs['addOcc4Dob']);
	$content .= createTextInputGroup('addOcc4Relation', 'Relation', $submission['addOcc4Relation'], $errs['addOcc4Relation']);
	$content .= '</div>';

	$content .= '<h3>Additional Occupant #5 (if any)</h3>';
	$content .= '<div>';
	$content .= createTextInputGroup('addOcc5FirstName', 'First name', $submission['addOcc5FirstName'], $errs['addOcc5FirstName']);
	$content .= createTextInputGroup('addOcc5MiddleInitial', 'Middle initial', $submission['addOcc5MiddleInitial'], $errs['addOcc5MiddleInitial']);
	$content .= createTextInputGroup('addOcc5LastName', 'Last name', $submission['addOcc5LastName'], $errs['addOcc5LastName']);
	$content .= createTextInputGroup('addOcc5Dob', 'Birth date', $submission['addOcc5Dob'], $errs['addOcc5Dob']);
	$content .= createTextInputGroup('addOcc5Relation', 'Relation', $submission['addOcc5Relation'], $errs['addOcc5Relation']);
	$content .= '</div>';

	$content .= '<h2>Current Residence</h2>';
	$content .= createTextInputGroup('currentAddress', 'Street address', $submission['currentAddress'], $errs['currentAddress']);
	$content .= createTextInputGroup('currentCity', 'City', $submission['currentCity'], $errs['currentCity']);
	$content .= createTextInputGroup('currentState', 'State', $submission['currentState'], $errs['currentState']);
	$content .= createTextInputGroup('currentZipCode', 'Zip code', $submission['currentZipCode'], $errs['currentZipCode']);
	$content .= createTextInputGroup('currentRentAmount', 'Rent amount', $submission['currentRentAmount'], $errs['currentRentAmount']);
	$content .= createTextInputGroup('currentLandlordName', 'Landlord name (first and last)', $submission['currentLandlordName'], $errs['currentLandlordName']);
	$content .= createPhoneInputGroup('currentPhone', 'Landlord phone', $submission['currentPhone'], $errs['currentPhone']);
	$content .= createTextInputGroup('currentReasonForLeaving', 'Reason for leaving', $submission['currentReasonForLeaving'], $errs['currentReasonForLeaving']);
	$content .= createDateInputGroup('currentBeginDate', 'Date starting residency', $submission['currentBeginDate'], $errs['currentBeginDate']);
	$content .= createDateInputGroup('currentEndDate', 'Date ending residency', $submission['currentEndDate'], $errs['currentEndDate']);

	$content .= createYesNoRadiosGroup('currentGaveProperNotice', 'Did you give proper written notice?', $submission['currentGaveProperNotice'], $errs['currentGaveProperNotice']);
	$content .= createYesNoRadiosGroup('currentAskedToLeave', 'Were you asked to leave?', $submission['currentAskedToLeave'], $errs['currentAskedToLeave']);
	$content .= createYesNoRadiosGroup('currentUtilitiesInName', 'Were utilities in your name?', $submission['currentUtilitiesInName'], $errs['currentUtilitiesInName']);

	$content .= '<h2>Previous Residence</h2>';
	$content .= createTextInputGroup('previousAddress', 'Street address', $submission['previousAddress'], $errs['previousAddress']);
	$content .= createTextInputGroup('previousCity', 'City', $submission['previousCity'], $errs['previousCity']);
	$content .= createTextInputGroup('previousState', 'State', $submission['previousState'], $errs['previousState']);
	$content .= createTextInputGroup('previousZipCode', 'Zip code', $submission['previousZipCode'], $errs['previousZipCode']);
	$content .= createTextInputGroup('previousRentAmount', 'Rent amount', $submission['previousRentAmount'], $errs['previousRentAmount']);
	$content .= createTextInputGroup('previousLandlordName', 'Landlord name (first and last)', $submission['previousLandlordName'], $errs['previousLandlordName']);
	$content .= createPhoneInputGroup('previousPhone', 'Landlord phone', $submission['previousPhone'], $errs['previousPhone']);
	$content .= createTextInputGroup('previousReasonForLeaving', 'Reason for leaving', $submission['previousReasonForLeaving'], $errs['previousReasonForLeaving']);
	$content .= createDateInputGroup('previousBeginDate', 'Date starting residency', $submission['previousBeginDate'], $errs['previousBeginDate']);
	$content .= createDateInputGroup('previousEndDate', 'Date ending residency', $submission['previousEndDate'], $errs['previousEndDate']);

	$content .= createYesNoRadiosGroup('previousGaveProperNotice', 'Did you give proper written notice?', $submission['previousGaveProperNotice'], $errs['previousGaveProperNotice']);
	$content .= createYesNoRadiosGroup('previousAskedToLeave', 'Were you asked to leave?', $submission['previousAskedToLeave'], $errs['previousAskedToLeave']);
	$content .= createYesNoRadiosGroup('previousUtilitiesInName', 'Were utilities in your name?', $submission['previousUtilitiesInName'], $errs['previousUtilitiesInName']);

	$content .= '<h2>Employment Information</h2>';
	$content .= createTextInputGroup('employmentEmployerName', 'Employer name', $submission['employmentEmployerName'], $errs['employmentEmployerName']);
	$content .= createTextInputGroup('employmentEmployerAddress', 'Employer address', $submission['employmentEmployerAddress'], $errs['employmentEmployerAddress']);
	$content .= createPhoneInputGroup('employmentEmployerPhone', 'Employer phone', $submission['employmentEmployerPhone'], $errs['employmentEmployerPhone']);
	$content .= createTextInputGroup('employmentOccupation', 'Occupation', $submission['employmentOccupation'], $errs['employmentOccupation']);
	$content .= createTextInputGroup('employmentSupervisor', 'Supervisor', $submission['employmentSupervisor'], $errs['employmentSupervisor']);
	$content .= createDateInputGroup('employmentDate', 'Date of employment', $submission['employmentDate'], $errs['employmentDate']);
	$content .= createDropdownInputGroup('employmentPayFrequency', 'Pay Frequency', $submission['employmentPayFrequency'], $errs['employmentPayFrequency'], array(
		['key' => 'weekly', 'label' => 'Weekly'],
		['key' => 'biweekly', 'label' => 'Biweekly'],
		['key' => 'monthly', 'label' => 'Monthly'],
		['key' => 'na', 'label' => 'N/A']
	));

	$content .= '<h2>Miscellaneous Information</h2>';
	$content .= '<p>
		Our pet policy is as follows. Pets are allowed on a case by
		case basis at our discretion. If we allow a pet to be kept, there will be a
		rent adjustment made in the amount of an additional $25.00/month per pet. No
		more than (2) pets will be allowed. Under no circumstances will a dog
		designated as a vicious breed by the insurance industry be allowed. Our
		insurance carrier will not allow it. THIS INCLUDES ANY PITBULL MIXES.
	</p>';

	$content .= createYesNoRadiosGroup('agreesToPetPolicy', 'Do you agree to the pet policy?', $submission['agreesToPetPolicy'], $errs['agreesToPetPolicy']);
	$content .= createYesNoRadiosGroup('hasPets', 'Do you have any pets?', $submission['hasPets'], $errs['hasPets']);
	$content .= createTextInputGroup('petDescription', 'Describe your pet. Include name, age, breed, and any other pertinent information', $submission['employmentSupervisor'], $errs['employmentSupervisor']);

	$content .= createTextInputGroup('numOfVehicles', 'How many vehicles will be at the property?', $submission['numOfVehicles'], $errs['numOfVehicles']);
	$content .= createYesNoRadiosGroup('hasSmokers', 'Smoking is prohibited inside our properties. Do any proposed occupants smoke?', $submission['hasSmokers'], $errs['hasSmokers']);
	$content .= createYesNoRadiosGroup('hasBeenServedNotice', 'Have you ever been served a late rent notice?', $submission['hasBeenServedNotice'], $errs['hasBeenServedNotice']);
	$content .= createYesNoRadiosGroup('hasBeenEvicted', 'Have you ever been served an eviction notice?', $submission['hasBeenEvicted'], $errs['hasBeenEvicted']);
	$content .= createYesNoRadiosGroup('hasFelony', 'Have you ever been convicted of a felony?', $submission['hasFelony'], $errs['hasFelony']);
	$content .= createYesNoRadiosGroup('hasDeclaredBankruptcy', 'Have you ever declared bankruptcy?', $submission['hasDeclaredBankruptcy'], $errs['hasDeclaredBankruptcy']);

	$content .= createDateInputGroup('desiredMoveInDate', 'What is your desired move in date?', $submission['desiredMoveInDate'], $errs['desiredMoveInDate']);
	$content .= createTextInputGroup('plannedRentDuration', 'How long do you plan to rent from us?', $submission['plannedRentDuration'], $errs['plannedRentDuration']);

	$content .= createYesNoRadiosGroup('hasAdditionalIncome', 'Do you have an additional source of income that you would like us to consider?', $submission['hasAdditionalIncome'], $errs['hasAdditionalIncome']);
	$content .= createTextInputGroup('additionalIncomeAmount', 'Amount of additional income', $submission['additionalIncomeAmount'], $errs['additionalIncomeAmount']);
	$content .= createDropdownInputGroup('additionalIncomeFrequency', 'Additional income frequency', $submission['additionalIncomeFrequency'], $errs['additionalIncomeFrequency'], array(
		['key' => 'weekly', 'label' => 'Weekly'],
		['key' => 'biweekly', 'label' => 'Biweekly'],
		['key' => 'monthly', 'label' => 'Monthly'],
		['key' => 'na', 'label' => 'N/A']
	));
	$content .= createTextInputGroup('additionalIncomeSource', 'Description of additional income source', $submission['additionalIncomeSource'], $errs['additionalIncomeSource']);

	if (sizeof($errs) > 0) {
		$content .= '<div class="mv4 dark-red">There were some invalid fields in your submission. Please review the above fields and try again.</div>';
	}

	$content .= "
			<button type='submit' class='btn btn-primary'>Submit</button>
		</form>
	";

	return $content;
}

function renderSuccess() {
	$content .= '<div>
		<h1>Thank You</h1>
		<p>
		Your application has successfully saved and our agents have been notified.
		Thank you for your submission. Since we review applications in the order that
		they are received, please allow up to 3-5 days for us to process your
		application. If you have any questions or concerns, feel free to reach out to
		us at contact@oldkentcapital.com.
		</p>
	</div>';
	return $content;
}

function validateApplication($submission) {
	$requiredFields = array(
		'agree',
		'firstName',
		'middleInitial',
		'lastName',
		'previousLastName',
		'dob',
		'licenseNumber',
		'email',
		'phone',
		'isCellphone',
		'canIText',
		'currentAddress',
		'currentCity',
		'currentState',
		'currentZipCode',
		'currentRentAmount',
		'currentLandlordName',
		'currentPhone',
		'currentReasonForLeaving',
		'currentBeginDate',
		'currentEndDate',
		'currentGaveProperNotice',
		'currentAskedToLeave',
		'currentUtilitiesInName',
		'employmentEmployerName',
		'employmentEmployerAddress',
		'employmentEmployerPhone',
		'employmentOccupation',
		'employmentSupervisor',
		'employmentDate',
		'employmentPayFrequency',
		'agreesToPetPolicy',
		'hasPets',
		'petDescription',
		'numOfVehicles',
		'hasSmokers',
		'hasBeenServedNotice',
		'hasBeenEvicted',
		'hasFelony',
		'hasDeclaredBankruptcy',
		'desiredMoveInDate',
		'plannedRentDuration',
		'hasAdditionalIncome',
	);

	$errs = [];

	foreach ($requiredFields as $key) {
		if (!isset($submission[$key]) || $submission[$key] == "") {
			$errs[$key] = 'Field is required'; // TODO: put behind interface (getMessage())
		}
	}

	return $errs;
}

function saveSubmission($submission) {
	wp_insert_post([
		'post_type' => 'application',
		'meta_input' => $submission,
		'post_title' => $submission['email']
	]);
}

function dispatchEmail($submission) {
	$body = '';
	foreach($submission as $key => $value) {
		$body = $body . $key . ': ' . $value . "\n";
	}

	try {
		$mail = new PHPMailer();
		$mail->SMTPDebug = SMTP::DEBUG_OFF;
		$mail->isSMTP();
		$mail->Host = ''; // TODO: get from env var
		$mail->SMTPAuth = true;
		$mail->Username = ''; // TODO: get from env var
		$mail->Password   = ''; // TODO: get from env var
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port       = 587;

		//Recipients
		$mail->setFrom('', ''); // TODO get from env var
		$mail->addAddress('', ''); // TODO get from env var

		// Content
		$mail->isHTML(false);
		$mail->Subject = 'Application Submitted';
		$mail->Body = $body;
		$mail->send();
	} catch(Exception $e) {}
}

function old_kent_application_plugin($atts) {
	if ($_POST) {
		$errs = validateApplication($_POST);
		$isValidPost = sizeof($errs) == 0;
	} else {
		$errs = array();
		$isValidPost = false;
	}

	if ($isValidPost) {
		saveSubmission($_POST);
		dispatchEmail($_POST);
		$content = renderSuccess();
	} else {
		$content = renderForm($_POST, $errs);
	}

	return $content;
}

add_shortcode('oldkent-application', 'old_kent_application_plugin');
