<?php
class register{


private $formHead = '
	<h1 class="col-md-8 col-md-offset-2">Log In</h1>
	<hr class="col-md-8 col-md-offset-2" />
';
private $form = '
	<form class="form-horizontal" role="form" method="post" action="">
		<div class="form-group">
			<label for="inputUser1" class="col-md-2 col-md-offset-2 control-label">Username</label>
			<div class="col-md-6">
				<input type="text" class="form-control" id="inputUser1" placeholder="Username" name="username">
			</div>
		</div>
		<div class="form-group">
			<label for="inputPassword1" class="col-md-2 col-md-offset-2 control-label">Password</label>
			<div class="col-md-6">
				<input type="password" class="form-control" id="inputPassword1" placeholder="Password" name="password">
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail1" class="col-md-2 col-md-offset-2 control-label">Email</label>
			<div class="col-md-6">
				<input type="email" class="form-control" id="inputEmail1" placeholder="Email" name="email">
			</div>
		</div>
		<div class="form-group">
			<label for="inputFirstName1" class="col-md-2 col-md-offset-2 control-label">First Name</label>
			<div class="col-md-6">
				<input type="text" class="form-control" id="inputFirstName1" placeholder="First Name" name="firstName">
			</div>
		</div>
		<div class="form-group">
			<label for="inputLastName1" class="col-md-2 col-md-offset-2 control-label">Last Name</label>
			<div class="col-md-6">
				<input type="text" class="form-control" id="inputLastName1" placeholder="Last Name" name="lastName">
			</div>
		</div>
		<div class="form-group">
			<label for="inputDateOfBirth1" class="col-md-2 col-md-offset-2 control-label">Date of Birth</label>
			<div class="col-md-6">
				<input type="text" class="form-control" id="inputDateOfBirth1" placeholder="YYYY-MM-DD" name="dateOfBirth">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-4 col-md-6">
				<button type="submit" class="btn btn-default">Register</button>
			</div>
		</div>
	</form>
	<hr class="col-md-8 col-md-offset-2" />
';

private $userCheck;

function register(){
	global $user, $config, $display, $dbh;
	
	$display->pageTitle = 'Register';
	
	$userNew = new user(null, 'new');
	
	if($user !== null){
		
		header("Location: ".$config->htmlBase);
		
	}elseif(!isset($_POST['username'])){
		
		$display->content = $this->formHead.$this->form;
		
	}elseif($userNew->setUsername($_POST['username']) === false){
		
		$display->content = $this->formHead.$this->errorMessage('Username is taken').$this->form;
		
	}elseif($userNew->setPasswordHash($_POST['password']) === false){
		
		$display->content = $this->formHead.$this->errorMessage('Password must be 6 characters or longer').$this->form;
		
	}elseif($userNew->setEmail($_POST['email']) === false){
		
		$display->content = $this->formHead.$this->errorMessage('Email is invalid').$this->form;
		
	}elseif($userNew->setFirstName($_POST['firstName']) === false){
		
		$display->content = $this->formHead.$this->errorMessage('First Name must be between 3 and 20 characters').$this->form;
		
	}elseif($userNew->setLastName($_POST['lastName']) === false){
		
		$display->content = $this->formHead.$this->errorMessage('Last Name must be between 3 and 20 characters').$this->form;
		
	}elseif($userNew->setDateOfBirth($_POST['dateOfBirth']) === false){
		
		$display->content = $this->formHead.$this->errorMessage('Date of Birth must be in the format YYYY-MM-DD').$this->form;
		
	}else{
		$store = $userNew->storeUser();
		
		if($store == false){
			$display->content = $this->formHead.$this->errorMessage('Error writing to database.').$this->form;
		}else{
			
			$expiration = time()+86400;
			
			$hash = hash('sha256', $expiration.$_POST['password']);
			
			$userId = $store;
			
			mysqli_query($dbh, "DELETE FROM `Sessions` WHERE `Expiration` < ".time());
			
			$query = "INSERT INTO `Sessions` (`UserId`, `Hash`, `Expiration`) VALUES ('$userId', '$hash', '$expiration')";
			
			setcookie('session', $hash, $expiration, $config->uriRemove);
			header("Location: ". $config->htmlBase);
			die;
		}
	}
}

private function errorMessage($msg){
	return '<div class="alert alert-danger col-md-8 col-md-offset-2">'.$msg.'</div>';
}


}
?>
