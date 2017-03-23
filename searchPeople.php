<?php
class searchPeople{

private $form = <<<EOF
	<h1 class="col-md-8 col-md-offset-2">Search for People</h1>
	<hr class="col-md-8 col-md-offset-2" />

	<form class="form-horizontal" role="form" method="get" action="">
		<div class="form-group">
			<label for="inputSearch1" class="col-md-2 col-md-offset-2 control-label">Search</label>
			<div class="col-md-6">
				<input type="text" class="form-control" id="inputSearch1" placeholder="User Id, Email, Name, Username" name="search">
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-4 col-md-6">
				<button type="submit" class="btn btn-default">Search</button>
			</div>
		</div>
	</form>
	<hr class="col-md-8 col-md-offset-2" />
EOF;

private $headerInfo = <<<EOF
<style type="text/css">
table td a{
width:100%;
height:100%;
display:block;
}
</style>
EOF;

function searchPeople(){
	global $user, $dbh, $display, $config;
	
	$display->pageTitle = 'People Search';
	
	if(!$user->checkAccess('staff') and !$user->checkAccess('executive')){
		$display->content = '<div class="alert alert-danger">You are not a staff or executive, you cannot search for people.</div>';
		return;
	}
	
	if(empty($_GET['search'])){
		$display->content = $this->form;
	}else{
		$results = $this->searchDB($_GET['search']);
		if($results == false){
			$display->content = '<div class="alert alert-danger">Error when querying from database.'.mysqli_error($dbh).'</div>';
			return;
		}
		$display->content = $this->form.'<br /><br /><h1>Search: '.$_GET['search'].'</h1>';
		$display->content .= $this->parseResults($results);
		$display->headerInfo = $this->headerInfo;
	}
	
}



private function searchDB($string){
	global $dbh;
	
	$stringS = mysqli_real_escape_string($dbh, $string);
	
	if(is_numeric($string)){
		$q = "SELECT * FROM `Users` WHERE `UserId` LIKE '$stringS'";
	}elseif(strpos($string, '@') !== false){
		$q = "SELECT * FROM `Users` WHERE `Email` LIKE '%$stringS%'";
	}else{
		$q = "SELECT * FROM `Users` WHERE `FirstName` LIKE '%$stringS%' OR `LastName` LIKE '%$stringS%' Or `Username` LIKE '%$stringS%' Or `Email` LIKE '%$stringS%'";
	}
	
	$r = mysqli_query($dbh, $q);
	
	if(!$r){
		return false;
	}else{
		return $r;
	}
}


private function parseResults($results){
	global $dbh;
	$return = '
		<table class="table table-hover">
			<thead>
				<tr>
					<th>First Name</th>
					<th>Last Name</th>
					<th>User Id</th>
					<th>Username</th>
					<th>Email</th>
				</tr>
			</thead>
			<tbody>
	';
	while($row = mysqli_fetch_assoc($results)){
		$return .= '<tr>
						<td><a href="viewPerson?person='.$row['UserId'].'">'.$row['FirstName'].'</a></td>
						<td><a href="viewPerson?person='.$row['UserId'].'">'.$row['LastName'].'</a></td>
						<td><a href="viewPerson?person='.$row['UserId'].'">'.$row['UserId'].'</a></td>
						<td><a href="viewPerson?person='.$row['UserId'].'">'.$row['Username'].'</a></td>
						<td><a href="viewPerson?person='.$row['UserId'].'">'.$row['Email'].'</a></td>
					</tr>
		';
	}
	$return .= '
		</tbody>
		</table>
	';
	return $return;
}


}
?>