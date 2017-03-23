<?php
class viewClassParticipants{
####################################################################

function viewClassParticipants(){
	global $user, $dbh, $display, $config;
	
	$display->pageTitle = 'Class Participants';
	
	if(empty($_GET['class'])){
		$display->content = '<div class="alert alert-danger">Error! No class selected.</div>';
		return;
	}
	
	$classS = mysqli_real_escape_string($dbh, $_GET['class']);
	$q = mysqli_query($dbh, "SELECT * FROM `Courses` WHERE `ClassId`='$classS'");
	if(!$q){
		$display->content = '<div class="alert alert-danger">Error when selecting from database.</div>';
		return;
	}elseif(mysqli_num_rows($q) == 0){
		$display->content = '<div class="alert alert-danger">Error! The class you selected doesn\'t exist.</div>';
		return;
	}
	$r = mysqli_fetch_assoc($q);
	
	
	$qS = "
		SELECT `Users`.`UserId`,`FirstName`,`LastName`,`Email` FROM `Registrations`,`Users`
		WHERE		`Registrations`.`UserId` = `Users`.`UserId`
			AND		`ClassId` = '$classS'
	";
	$st = mysqli_query($dbh, $qS);
	if(!$st){
		$display->content = '<div class="alert alert-danger">Error when selecting from database.</div>';
		return;
	}
	$students = Array();
	while($row = mysqli_fetch_assoc($st)){
		$students[$row['UserId']] = $row;
	}
	
	if($user->checkAccess('executive')){
	}elseif($user->checkAccess('staff')){
	}elseif($r['FacultyId'] != $user->getUserId() and !isset($students[$user->getUserId()])  ){
		$display->content = '<div class="alert alert-danger">You can only view the students in your own classes.</div>';
		return;
	}
	
	$display->content = '
		<h1>'.$r['Course'].'</h1>
		<table class="table">
			<thead>
				<tr>
					<th>Name</th><th>Email</th>
				</tr>
			</thead>
			<tbody>
	';
	foreach($students as $k => $v){
		$display->content .= '
			<tr>
				<td><a href="viewPerson?person='.$k.'">'.$v['FirstName'].' '.$v['LastName'].'</a></td>
				<td>'.$v['Email'].'</td>
			</tr>
		';
	}
	$display->content .= '</tbody></table>';
}




####################################################################
}
?>