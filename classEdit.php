<?php
class classEdit{
####################################################################

function classEdit(){
	global $dbh, $user, $display, $request;
	
	if(!$user->checkAccess('student')){
		$display->content = '<div class="alert alert-danger">You need to be a student to add or drop classes.</div>';
	}elseif(!isset($request[1]) or !in_array($request[1], Array('add','drop')) ){
		$display->content = '<div class="alert alert-danger">Bad page request.</div>';
	}elseif(!isset($_GET['class']) ){
		$display->content = '<div class="alert alert-danger">No course selected.</div>';
	}elseif($request[1] == 'drop'){
		$this->drop();
	}else{
		$this->add();
	}
}

private function add(){
	global $dbh, $user, $display;
	
	$display->pageTitle = 'Add Class';
	
	$classS = mysqli_real_escape_string($dbh, $_GET['class']);
	$query = mysqli_query($dbh, "INSERT INTO `Registrations` (`UserId`, `ClassId`) VALUES ('".$user->getUserId()."', '$classS')");
	if(!$query){
		$display->content = '<div class="alert alert-danger">'.mysqli_error($dbh).'Class registration faild</div>';
	}else{
		$display->content = '<div class="alert alert-success">You just registered for a class</div>';
	}
}

private function drop(){
	global $dbh, $user, $display;
	
	$display->pageTitle = 'Drop Class';
	
	$classS = mysqli_real_escape_string($dbh, $_GET['class']);
	$query = mysqli_query($dbh, "DELETE FROM `Registrations` WHERE `UserId`='".$user->getUserId()."' AND `ClassId`='$classS'");
	if(!$query){
		$display->content = '<div class="alert alert-danger">Class drop faild</div>';
	}else{
		$display->content = '<div class="alert alert-success">You just dropped a class</div>';
	}
}

####################################################################
}
?>