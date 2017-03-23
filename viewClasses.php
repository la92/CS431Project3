<?php
class viewClasses{
####################################################################

private $facultyList = Array();
private $queryBegin = "
SELECT
	`Courses`.`ClassId`,
	`Courses`.`Course`,
	`Courses`.`CourseTitle`,
	`Courses`.`FacultyId`,
	COUNT(`Registrations`.`UserId`) AS `NumberStudents`
FROM `Courses` LEFT JOIN `Registrations`
ON `Courses`.`ClassId`=`Registrations`.`ClassId`

";

private $queryEnd = "

GROUP BY `Courses`.`ClassId`
";

function viewClasses(){
	global $dbh, $config, $user, $display, $request;
	
	$display->pageTitle = 'View Classes';
	
	$query = mysqli_query($dbh, "SELECT `Users`.`UserId`, `FirstName`,`LastName` FROM `Users`,`Access` WHERE `Users`.`UserId`=`Access`.`UserId` AND `Access`.`Access`='faculty'");
	if(!$query){
		echo mysqli_error($dbh);
	}else{
		while($row = mysqli_fetch_assoc($query)){
			$this->facultyList[$row['UserId']] = $row['FirstName'] . " " . $row['LastName'];
		}
	}
	
	if(!isset($request[1])){
		$this->general();
	}elseif($request[1] == 'faculty'){
		$this->faculty();
	}elseif($request[1] == 'staff'){
		$this->staff();
	}elseif($request[1] == 'student' and isset($request[2]) and $request[2] == 'myClasses'){
		$this->studentClasses();
	}elseif($request[1] == 'student'){
		$this->studentAddDrop();
	}else{
		$this->general();
	}
	
}

private function faculty(){
	global $dbh, $config, $user, $display;
	
	
	if(!$user->checkAccess('faculty')){
		$display->content = '<div class="alert alert-danger">You do not have access to view faculty classes.</div>';
	}else{
		$display->content .= '<h1>Classes I\'m Teaching</h1>
		
		<table class="table">
		<thead>
			<tr>
				<th>Class Code</th>
				<th>Class Name</th>
				<th>Instructor</th>
				<th>Number of Students</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$q = $this->queryBegin." WHERE `FacultyId`='".$user->getUserId()."'".$this->queryEnd;
		$query = mysqli_query($dbh, $q);
		while($row = mysqli_fetch_assoc($query)){
			$display->content .= '<tr>
					<td>'.$row['Course'].'</td>
					<td>'.$row['CourseTitle'].'</td>
					<td>'.$this->facultyList[$row['FacultyId']].'</td>
					<td><a href="viewClassParticipants?class='.$row['ClassId'].'">'.$row['NumberStudents'].' Students</a></td>
					<td><a href="deleteClass?class='.$row['ClassId'].'"><i class="fa fa-ban"></i></a></td>
				</tr>';
		}
		
		$display->content .= '</tbody></table>';
	}
}

private function staff(){
	global $dbh, $config, $user, $display;
	
	
	if(!$user->checkAccess('staff')){
		$display->content = '<div class="alert alert-danger">You do not have access to view classes as staff.</div>';
	}else{
		$display->content .= '<h1>Classes</h1>
		
		<table class="table">
		<thead>
			<tr>
				<th>Class Code</th>
				<th>Class Name</th>
				<th>Instructor</th>
				<th>Number of Students</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$q = $this->queryBegin.$this->queryEnd;
		$query = mysqli_query($dbh, $q);
		while($row = mysqli_fetch_assoc($query)){
			$display->content .= '<tr>
					<td>'.$row['Course'].'</td>
					<td>'.$row['CourseTitle'].'</td>
					<td>'.$this->facultyList[$row['FacultyId']].'</td>
					<td><a href="viewClassParticipants?class='.$row['ClassId'].'">'.$row['NumberStudents'].' Students</a></td>
					<td><a href="deleteClass?class='.$row['ClassId'].'"><i class="fa fa-ban"></i></a></td>
				</tr>';
		}
		
		$display->content .= '</tbody></table>';
	}
}

private function studentAddDrop(){
	global $dbh, $config, $user, $display;
	
	
	if(!$user->checkAccess('student')){
		$display->content = '<div class="alert alert-danger">You are not a student.</div>';
	}else{
		$query = mysqli_query($dbh, "SELECT `ClassId` FROM `Registrations` WHERE `UserId`='".$user->getUserId()."'");
		$classArr = Array();
		while($row = mysqli_fetch_assoc($query)){
			$classArr[] = $row['ClassId'];
		}
		
		$display->content .= '<h1>Register or Drop Classes</h1>
		
		<table class="table">
		<thead>
			<tr>
				<th>Class Code</th>
				<th>Class Name</th>
				<th>Instructor</th>
				<th>Number of Students</th>
				<th>Registered</th>
				<th>Register/Drop</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$q = $this->queryBegin.$this->queryEnd;
		$query = mysqli_query($dbh, $q);
		while($row = mysqli_fetch_assoc($query)){
			$display->content .= '<tr>
						<td>'.$row['Course'].'</td>
						<td>'.$row['CourseTitle'].'</td>
						<td>'.$this->facultyList[$row['FacultyId']].'</td>
						<td>'.( in_array($row['ClassId'], $classArr)? '<a href="viewClassParticipants?class='.$row['ClassId'].'">'.$row['NumberStudents'].' Students</a>': $row['NumberStudents']).'</td>
						<td>'.( in_array($row['ClassId'], $classArr)? '<span style="font-weight:bold;">Registered</span>': '').'</td>
						<td>' . ( in_array($row['ClassId'], $classArr)? '<a href="classEdit/drop?class='.$row['ClassId'].'"><i class="fa fa-ban"></i></a>' : '<a href="classEdit/add?class='.$row['ClassId'].'"><i class="fa fa-edit"></i></a>' ) . '</td>
					</tr>';
		}
		
		$display->content .= '</tbody></table>';
	}
}

private function studentClasses(){
	global $dbh, $config, $user, $display;
	
	
	if(!$user->checkAccess('student')){
		$display->content = '<div class="alert alert-danger">You are not a student.</div>';
	}else{
		$query = mysqli_query($dbh, "SELECT `ClassId` FROM `Registrations` WHERE `UserId`='".$user->getUserId()."'");
		$classArr = Array();
		while($row = mysqli_fetch_assoc($query)){
			$classArr[] = $row['ClassId'];
		}
		
		$display->content .= '<h1>Register or Drop Classes</h1>
		
		<table class="table">
		<thead>
			<tr>
				<th>Class Code</th>
				<th>Class Name</th>
				<th>Instructor</th>
				<th>Students</th>
				<th>Drop</th>
			</tr>
		</thead>
		<tbody>
		';
		
		$q = $this->queryBegin.$this->queryEnd;
		$query = mysqli_query($dbh, $q);
		while($row = mysqli_fetch_assoc($query)){
			if( !in_array($row['ClassId'], $classArr) ){
				continue;
			}
			$display->content .= '<tr>
						<td>'.$row['Course'].'</td>
						<td>'.$row['CourseTitle'].'</td>
						<td>'.$this->facultyList[$row['FacultyId']].'</td>
						<td>'.( in_array($row['ClassId'], $classArr)? '<a href="viewClassParticipants?class='.$row['ClassId'].'">'.$row['NumberStudents'].' Students</a>': $row['NumberStudents']).'</td>
						<td><a href="classEdit/drop?class='.$row['ClassId'].'"><i class="fa fa-ban"></i></a></td>
					</tr>';
		}
		
		$display->content .= '</tbody></table>';
	}
}

private function general(){
	global $dbh, $config, $user, $display;
	
	
	$display->content .= '<h1>View All Classes</h1>
	
	<table class="table">
	<thead>
		<tr>
			<th>Class Code</th>
			<th>Class Name</th>
			<th>Instructor</th>
			<th>Number of Students</th>
		</tr>
	</thead>
	<tbody>
	';
	
	$q = $this->queryBegin.$this->queryEnd;
	$query = mysqli_query($dbh, $q);
	while($row = mysqli_fetch_assoc($query)){
		$display->content .= '<tr>
					<td>'.$row['Course'].'</td>
					<td>'.$row['CourseTitle'].'</td>
					<td>'.$this->facultyList[$row['FacultyId']].'</td>
					<td>'.$row['NumberStudents'].'</td>
				</tr>';
	}
	
	$display->content .= '</tbody></table>';
}


####################################################################
}

?>