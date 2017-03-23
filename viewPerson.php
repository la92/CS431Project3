<?php
class viewPerson{
####################################################################

function viewPerson(){
	global $user, $dbh, $display, $config;
	
	$display->pageTitle = 'View User Profile';
	
	$display->content .= '<div class="alert alert-info">
			<p>Students can only view the profile of students who are taking the same class. They cannot see private information, in this case the birth date, or system access permissions.</p>
			<p>Faculty can only view the profiles of students who are in their classes. They cannot see private information or system access permission.</p>
			<p>Staff can view the profile of anyone. They can see the private information of students. Staff can grant student and faculty access to users.</p>
			<p>Executives can view the profile of anyone. They can see the private information of everyone. Executives can grant student, faculty, staff, and executive access to users.</p>
	</div>';
	
	if(!isset($_GET['person'])){
		$display->content .= '<div class="alert alert-danger">Error! No person selected.</div>';
		return;
	}
	
	$personS = mysqli_real_escape_string($dbh, $_GET['person']);
	$pQ = mysqli_query($dbh, "SELECT `Username`,`UserId`,`FirstName`,`LastName`,`Email`,`DateOfBirth` FROM `Users` WHERE `UserId`='$personS'");
	
	if(!$pQ){
		$display->content .= '<div class="alert alert-danger">Error! Database Query Failed</div>';
		return;
	}elseif(mysqli_num_rows($pQ) == 0){
		$display->content .= '<div class="alert alert-danger">Error! Person does not exist.</div>';
		return;
	}
	
	$aQ = mysqli_query($dbh, "SELECT * FROM `Access` WHERE `UserId`='$personS'");
	$accessArr = Array();
	while($r = mysqli_fetch_assoc($aQ)){
		$accessArr[] = $r['Access'];
	}
	
	if($user->checkAccess('executive') or $user->checkAccess('staff')){
		// you have access to non student profiles
	}elseif($user->checkAccess('faculty') or $user->checkAccess('student')){
		$accessQ = mysqli_query($dbh, "
					SELECT * FROM `Registrations` WHERE
						`UserId`='$personS'
						AND
						(
							`ClassId` IN (SELECT `ClassId` FROM `Courses` WHERE `FacultyId`='".$user->getUserId()."')
							OR
							`ClassId` IN (SELECT `ClassId` FROM `Registrations` WHERE `UserId`='".$user->getUserId()."')
						)
			");
		if(!$accessQ){
			$display->content .= '<div class="alert alert-danger">Error! Database Query Failed</div>';
			return;
		}elseif(mysqli_num_rows($pQ) == 0){
			$display->content .= '<div class="alert alert-danger">You cannot only access the information of people who are in your classes.</div>';
			return;
		}
	}
	
	$personData = mysqli_fetch_assoc($pQ);
	
	if( $user->checkAccess('executive') or ($user->checkAccess('staff') and in_array('student', $accessArr))  ){
		$privateOut = '<tr><th>Date of Birth</th><td>'.$personData['DateOfBirth'].'</td></tr>';
	}else{
		$privateOut = '';
	}
	
	if($user->checkAccess('executive')){
		$accessOut = '<tr><th>Access</th><td>';
		
		foreach(Array('student', 'faculty', 'staff', 'executive') as $v){
			if(in_array($v, $accessArr)){
				$accessOut .= $v.'<br />';
			}else{
				$accessOut .= '<a href="grantAccess?person='.$_GET['person'].'&grant='.$v.'">make '.$v.'</a><br />';
			}
		}
		
		$accessOut .= '</td></tr>';
	}elseif( $user->checkAccess('staff') ){
		$accessOut = '<tr><th>Access</th><td>';
		
		foreach(Array('student', 'faculty') as $v){
			if(in_array($v, $accessArr)){
				$accessOut .= $v.'<br />';
			}else{
				$accessOut .= '<a href="grantAccess?person='.$_GET['person'].'&grant='.$v.'">make '.$v.'</a><br />';
			}
		}
		foreach(Array('staff', 'executive') as $v){
			if(in_array($v, $accessArr)){
				$accessOut .= $v.'<br />';
			}
		}
		
		$accessOut .= '</td></tr>';
	}else{
		$accessOut = '';
	}
	
	$display->content .= '
		<div class="col-sm-8 col-sm-offset-2">
		<table class="table">
			<tr>
				<th>UserId</th>
				<td>'.$personData['UserId'].'</td>
			</tr>
			<tr>
				<th>First Name</th>
				<td>'.$personData['FirstName'].'</td>
			</tr>
			<tr>
				<th>Last Name</th>
				<td>'.$personData['LastName'].'</td>
			</tr>
			<tr>
				<th>Username</th>
				<td>'.$personData['Username'].'</td>
			</tr>
			<tr>
				<th>Email</th>
				<td>'.$personData['Email'].'</td>
			</tr>
			'.$privateOut.'
			'.$accessOut.'
		</table>
		</div>
	';
	
}

####################################################################
}
?>