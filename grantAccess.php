<?php
class grantAccess{


private $permissionsArr = Array(
	'student' => 'student',
	'faculty' => 'faculty',
	'staff' => 'staff',
	'executive' => 'executive',
);

function grantAccess(){
	global $dbh, $display, $user, $config;
	
	$display->pageTitle = 'Grant User Access';
	
	if(empty($_GET['grant']) or empty($_GET['person'])){
		$display->content = '<div class="alert alert-danger">No permission specified.</div>';
		return;
	}elseif( !in_array($_GET['grant'], $this->permissionsArr) ){
		$display->content = '<div class="alert alert-danger">Error, this level of access doesn\'t exist.</div>';
		return;
	}elseif( !in_array($_GET['grant'], $this->checkGrantingPowers($user)) ){
		$display->content = '<div class="alert alert-danger">You don\'t have permission to grant this access.</div>';
		return;
	}elseif( $this->checkUserExists($_GET['person']) == false){
		$display->content = '<div class="alert alert-danger">This person doesn\'t exist.</div>';
		return;
	}elseif( $this->checkUserAccess($_GET['person'], $_GET['grant']) == false){
		$display->content = '<div class="alert alert-danger">This person already has '.$_GET['grant'].' access.</div>';
		return;
	}
	
	$personS = mysqli_real_escape_string($dbh, $_GET['person']);
	$grantS = mysqli_real_escape_string($dbh, $_GET['grant']);
	
	$q = mysqli_query($dbh, "INSERT INTO `Access` (`UserId`, `Access`) VALUES ('$personS', '$grantS')");
	if(!$q){
		$display->content = '<div class="alert alert-danger">Error updating database.</div>';
		return;
	}else{
		header("Location: ".$config->htmlBase.'viewPerson?person='.$personS);
		die;
	}
}


private function checkGrantingPowers($user){
	if($user->checkAccess('executive')){
		return Array('executive', 'staff', 'student', 'faculty');
	}elseif($user->checkAccess('staff')){
		return Array('faculty', 'student');
	}else{
		return Array();
	}
}

private function checkUserExists($person){
	global $dbh;
	
	$personS = mysqli_real_escape_string($dbh, $person);
	
	$q = mysqli_query($dbh, "SELECT * FROM `Users` WHERE `UserId`='$personS'");
	if(!$q or mysqli_num_rows($q) == 0){
		return false;
	}
	return true;
}

private function checkUserAccess($person, $grant){
	global $dbh;
	
	$personS = mysqli_real_escape_string($dbh, $person);
	$grantS = mysqli_real_escape_string($dbh, $grant);
	
	$q = mysqli_query($dbh, "SELECT * FROM `Access` WHERE `UserId`='$personS' AND `Access`='$grantS'");
	if(!$q or mysqli_num_rows($q) > 0){
		return false;
	}
	return true;
}




}
?>