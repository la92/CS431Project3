<?php
class createClass{
####################################################################

private $formHead = '
	<h1 class="col-md-8 col-md-offset-2">Create Class</h1>
	<hr class="col-md-8 col-md-offset-2" />';

private $form = '
	<form class="form-horizontal" role="form" method="post" action="">
		<div class="form-group">
			<label for="inputClassCode" class="col-md-2 col-md-offset-2 control-label">Class Code</label>
			<div class="col-md-6">
				<input type="text" class="form-control" id="inputClassCode" placeholder="Class Code" name="ClassCode">
			</div>
		</div>
		<div class="form-group">
			<label for="inputClassName" class="col-md-2 col-md-offset-2 control-label">Class Name</label>
			<div class="col-md-6">
				<input type="text" class="form-control" id="inputClassName" placeholder="Class Name" name="ClassName">
			</div>
		</div>
		<!--^faculty^-->
		<div class="form-group">
			<div class="col-md-offset-4 col-md-6">
				<button type="submit" class="btn btn-default">Create Class</button>
			</div>
		</div>
	</form>
	<hr class="col-md-8 col-md-offset-2" />
';

function createClass(){
	global $dbh, $config, $user, $display;
	
	$display->pageTitle = 'Create Classes';
	
	/*
	$display->content .= '<div class="alert alert-info">
			Staff can create classes taught by any professor. Faculty can create classes that they are planning to teach.
	</div>';
	*/
	
	if($user->checkAccess('staff')){
		$faculty = '<div class="form-group">
		<label for="inputFacultyId" class="col-md-2 col-md-offset-2 control-label">Faculty</label>
		<div class="col-md-6">
		<select name="FacultyId" class="form-control" id="inputFacultyId"><option></option>';
		$query = mysqli_query($dbh, "SELECT * FROM `Access`,`Users` WHERE `Access`.`UserId`=`Users`.`UserId` AND`access`='faculty'");
		while($row = mysqli_fetch_assoc($query)){
			$faculty .= '<option value="'.$row['UserId'].'">'.$row['FirstName']." ".$row['LastName'].'</option>';
		}
		$faculty .= '</select></div></div>';
	}else{
		$faculty = '<input type="hidden" value="'.$user->getUserId().'" name="FacultyId" />';
	}
	$form = str_replace('<!--^faculty^-->', $faculty, $this->form);
	
	
	if(!$user->checkAccess('faculty') and !$user->checkAccess('staff')){
		$display->content .= '<div class="alert alert-danger">You do not have access to create a class.</div>';
	}elseif(empty($_POST['Course']) or empty($_POST['CourseTitle']) or empty($_POST['FacultyId'])){
		$display->content .= $this->formHead.$form;
	}elseif(strlen($_POST['Course']) > 10){
		$display->content .= $this->formHead.$this->errorMessage('Class Code must be under 10 characters').$form;
	}elseif(strlen($_POST['CourseTitle']) > 50){
		$display->content .= $this->formHead.$this->errorMessage('Class Name must be under 50 characters').$form;
	}else{
		$ClassCodeS = mysqli_real_escape_string($dbh, $_POST['Course']);
		$ClassNameS = mysqli_real_escape_string($dbh, $_POST['CourseTitle']);
		$FacultyIdS = mysqli_real_escape_string($dbh, $_POST['FacultyId']);
		
		$query = mysqli_query($dbh, "INSERT INTO `Courses` (`Course`, `CoureTitle`, `FacultyId`) VALUES ('$CourseS', '$CourseTitleS', '$FacultyIdS')");
		if(!$query){
			$display->content .= $this->formHead.$this->errorMessage('Error inserting into db').$form;
		}else{
			$display->content .= '<div class="alert alert-success">Class Added</div>';
		}
	}
}


private function errorMessage($msg){
	return '<div class="alert alert-danger col-md-8 col-md-offset-2">'.$msg.'</div>';
}

####################################################################
}

?>