<?php

class login{


private $formHead = '
	<div class="alert col-md-8 col-md-offset-2">
		<h2>Test Users</h2>
		<table class="table">
			<thead>
				<th>Username</th>
				<th>Password</th>
			</thead>
			<tbody>
				<tr>
					<td>student</td>
					<td>student</td>
				</tr>
				<tr>
					<td>faculty</td>
					<td>faculty</td>
				</tr>
				<tr>
					<td>staff</td>
					<td>staff</td>
				</tr>
				<tr>
					<td>executive</td>
					<td>executive</td>
				</tr>
				<tr>
					<td>student2</td>
					<td>student2</td>
				</tr>
				<tr>
					<td>studentTeacher</td>
					<td>studentTeacher</td>
				</tr>
			</tbody>
		</table>
	</div>
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
			<div class="col-md-offset-4 col-md-6">
				<button type="submit" class="btn btn-default">Sign in</button>
			</div>
		</div>
	</form>
	<hr class="col-md-8 col-md-offset-2" />
';

private $userCheck;

function login(){
	global $user, $config, $display, $dbh;
	
	$display->pageTitle = 'Log In';
	
	if($user !== null){
		header("Location: ".$config->htmlBase);
	}elseif(empty($_POST['username']) or empty($_POST['password'])){
		$display->content = $this->formHead.$this->form;
	}elseif($this->check($_POST['username'], $_POST['password']) !== true){
		$display->content = $this->formHead.$this->errorMessage('Invalid User Details').$this->form;
	}else{
		
		$expiration = time()+86400;
		
		$hash = hash('sha256', $expiration.$_POST['password']);
		
		$userId = $this->userCheck->getUserId();
		
		mysqli_query($dbh, "DELETE FROM `Sessions` WHERE `Expiration` < ".time());
		
		$query = "INSERT INTO `Sessions` (`UserId`, `Hash`, `Expiration`) VALUES ('$userId', '$hash', '$expiration')";
		
		if(!mysqli_query($dbh, $query)){
			$display->content = '<div class="alert alert-danger col-md-8 col-md-offset-2">Username and Password are correct, but error when creating session.</div>';
		}else{
			setcookie('session', $hash, $expiration, $config->uriRemove);
			header("Location: ". $config->htmlBase);
			die;
		}
	}
}

private function errorMessage($msg){
	return '<div class="alert alert-danger col-md-8 col-md-offset-2">'.$msg.'</div>';
}

private function check($user, $pass){
	$this->userCheck = new user($user, 'username');
	if($this->userCheck == false){
		return false;
	}
	
	return $this->userCheck->checkPassword($pass);
}

}
?>