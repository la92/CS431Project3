<?php

class user{


function user($id, $type){
	global $dbh;
	$idScrubbed = mysqli_real_escape_string($dbh, $id);
	if($type == 'new'){
		return true;
	}elseif($type == 'username'){
		$query = mysqli_query($dbh, "SELECT * FROM `Users` WHERE `username`='$idScrubbed'");
		if(!$query or mysqli_num_rows($query) == 0){
			return false;
		}
		$data = mysqli_fetch_assoc($query);
	}elseif($type == 'cookie'){
		mysqli_query($dbh, "DELETE FROM `Sessions` WHERE `Expiration` < ".time());
		$query = mysqli_query($dbh, "SELECT * FROM `Sessions` WHERE `hash`='$idScrubbed'");
		if(!$query or mysqli_num_rows($query) == 0){
			return false;
		}
		$data = mysqli_fetch_assoc($query);
		$query = mysqli_query($dbh, "SELECT * FROM `Users` WHERE `UserId`='$data[UserId]'");
		if(!$query or mysqli_num_rows($query) == 0){
			return false;
		}
		$data = mysqli_fetch_assoc($query);
	}else{
		return false;
	}
	$this->userId = $data['UserId'];
	$this->username = $data['Username'];
	$this->email = $data['Email'];
	$this->firstName = $data['FirstName'];
	$this->lastName = $data['LastName'];
	$this->passwordHash = $data['PasswordHash'];
	$this->dateOfBirth = $data['DateOfBirth'];
	
	$query = mysqli_query($dbh, "SELECT * FROM `Access` WHERE `UserId`='".$this->userId."'");
	if(!$query or mysqli_num_rows($query)==0){
		
	}else{
		while($row = mysqli_fetch_assoc($query)){
			$this->access[] = $row['Access'];
		}
	}
}

private $userId;
private $username;
private $email;
private $firstName;
private $lastName;
private $passwordHash;
private $dateOfBirth;
private $access = Array();

public function getUserId(){
	return $this->userId;
}

public function getUsername(){
	return $this->username;
}
public function setusername($username){
	if(strlen($username) > 20){
		return false;
	}
	
	global $dbh;
	
	$userS = mysqli_real_escape_string($dbh, $username);
	$q = mysqli_query($dbh, "SELECT * FROM `Users` WHERE `Username`='$userS'");
	if(!$q){
		return false;
	}
	if(mysqli_num_rows($q) > 0){
		return false;
	}
	$this->username = $username;
	return true;
}
public function getEmail(){
	return $this->email;
}
public function setEmail($email){
	if(strlen($email) < 3){
		return false;
	}elseif(strlen($email) > 254){
		return false;
	}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		return false;
	}
	$this->email = $email;
	return true;
}

public function getFirstName(){
	return $this->firstName;
}
public function setFirstName($firstName){
	if(strlen($firstName) > 20){
		return false;
	}elseif(strlen($firstName) < 3){
		return false;
	}
	$this->firstName = $firstName;
	return true;
}

public function getLastName(){
	return $this->lastName;
}
public function setLastName($lastName){
	if(strlen($lastName) > 20){
		return false;
	}elseif(strlen($lastName) < 3){
		return false;
	}
	$this->lastName = $lastName;
	return true;
}


public function getPasswordHash(){
	return $this->passwordHash;
}
public function setPasswordHash($password){
	global $config;
	if(strlen($password) < 6){
		return false;
	}
	require($config->libDir.'PasswordHash.php');
	$PasswordHash = new PasswordHash(7, false);
	$this->passwordHash = $PasswordHash->HashPassword($password);
	return true;
}
public function checkPassword($password){
	global $config;
	require($config->libDir.'PasswordHash.php');
	$PasswordHash = new PasswordHash(7, false);
	
	return $PasswordHash->CheckPassword($password, $this->passwordHash);
}

public function getDateOfBirth(){
	return $this->dateOfBirth;
}
public function setDateOfBirth($dateOfBirth){
	$test = explode("-", $dateOfBirth);
	if(count($test) !== 3){
		return false;
	}elseif(!checkdate($test[1], $test[2], $test[0])){
		return false;
	}
	$this->dateOfBirth = $dateOfBirth;
	return true;
}

public function checkAccess($req){
	return in_array($req, $this->access);
}

public function storeUser($dbh){
	global $dbh;
	$userS = mysqli_real_escape_string($dbh, $this->username);
	$passwordHashS = mysqli_real_escape_string($dbh, $this->passwordHash);
	$emailS = mysqli_real_escape_string($dbh, $this->email);
	$firstNameS = mysqli_real_escape_string($dbh, $this->firstName);
	$lastNameS = mysqli_real_escape_string($dbh, $this->lastName);
	$dateOfBirthS = mysqli_real_escape_string($dbh, $this->dateOfBirth);
	
	$q = mysqli_query($dbh, "INSERT INTO `Users` (`Username`, `PasswordHash`, `Email`, `FirstName`, `LastName`, `DateOfBirth`) VALUES ('$userS', '$passwordHashS', '$emailS', '$firstNameS', '$lastNameS', '$dateOfBirthS')");
	
	if(!$q){
		return false;
	}else{
		$id = mysqli_insert_id($dbh);
		if(is_numeric($id)){
			return $id;
		}else{
			return false;
		}
	}
}

}

?>
