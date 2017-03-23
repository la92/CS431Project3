<?php
class deleteClass{
####################################################################


function deleteClass(){
	global $dbh, $config, $user, $display;
	
	$display->pageTitle = 'Delete Class';
	
	
	if(!isset($_GET['class'])){
		$display->content = '<div class="alert alert-danger">No class is specified.</div>';
	}elseif($user->checkAccess('staff')){
		$classS = mysqli_real_escape_string($dbh, $_GET['class']);
		$query = mysqli_query($dbh, "DELETE FROM `Courses` WHERE `ClassId`='$classS'");
		if(!$query){
			$display->content = '<div class="alert alert-danger">Error when deleting class</div>';
		}
		
		$query = mysqli_query($dbh, "DELETE FROM `Registrations` WHERE `ClassId`='$classS'");
		if(!$query){
			$display->content = '<div class="alert alert-danger">Error when deleting class</div>';
		}else{
			$display->content = '<div class="alert alert-success">Class Deleted</div>';
		}
	}elseif($user->checkAccess('faculty')){
		$classS = mysqli_real_escape_string($dbh, $_GET['class']);
		$query = mysqli_query($dbh, "SELECT * FROM `Courses` WHERE `ClassId`='$classS'");
		if(!$query or mysqli_num_rows($query) == 0){
			$display->content = '<div class="alert alert-danger">The class doesn\'t exist.</div>';
		}else{
			$row = mysqli_fetch_assoc($query);
			if($row['FacultyId'] != $user->getUserId()){
				$display->content = '<div class="alert alert-danger">You are not a staff, you cannot delete a class that is not your own.</div>';
			}else{
				$query = mysqli_query($dbh, "DELETE FROM `Courses` WHERE `ClassId`='$classS'");
				if(!$query){
					$display->content = '<div class="alert alert-danger">Error when deleting class</div>';
				}
				
				$query = mysqli_query($dbh, "DELETE FROM `Registrations` WHERE `ClassId`='$classS'");
				if(!$query){
					$display->content = '<div class="alert alert-danger">Error when deleting class</div>';
				}else{
					$display->content = '<div class="alert alert-success">Class Deleted</div>';
				}
			}
		}	
	}else{
		$display->content = '<div class="alert alert-danger">You do not have access to delete this class.</div>';
	}
	
}



####################################################################
}

?>