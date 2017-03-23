<?php


require('config.php');
$config = new config();

require('display.php');
$display = new display();

require($config->'user.php');


$dbh = mysqli_connect($config->host, $config->user, $config->pass);
if(!$dbh){
	$display->content = '<div class="alert alert-danger">Connection to database failed.</div>';
	$display->output();
	die;
}
mysqli_select_db($dbh, $config->db);


$request = str_replace($config->uriRemove, '', $_SERVER['REQUEST_URI']);
$qPos = strpos($request, '?');
if($qPos !== false){
	$request = substr($request, 0, $qPos);
}
$pPos = strpos($request, '.');
if($pPos !== false){
	$request = substr($request, 0, $pPos);
}
$request = explode("/", $request);



$display->requestNotes .= print_r($request, true);


if(!isset($_COOKIE['session'])){
	//login cookie is not set
	if($request[0] !== 'login' and $request[0] !== 'register' and $request[0] !== 'info'){
		//redirect to login if not already on login or register page
		header("Location: ".$config->htmlBase."login");
		die;
	}
	$user = null;
}else{
	//user cookie is set
	//try to load the user
	$user = new user($_COOKIE['session'], 'cookie');
	
	if($user === false){
		//user is not valid
		if($request[0] !== 'login' and $request[0] !== 'register' and $request[0] !== 'info'){
			//if not on the login or register page, redirect to login page
			header("Location: ".$config->htmlBase."login");
			die;
		}
		$user = null;
	}
}


if($request[0] == ''){
	// if request is the homepage (blank) visit home page
	require($config->pageDir.'home.php');
	new home();
	$display->requestNotes .= 'request home<br />';
}elseif($request[0] == 'home' or $request[0] == 'index'){
	// if request is for home or index, redirect to blank, which is the home page
	header("Location: ".$config->htmlBase);
	die;
}elseif(file_exists($config->pageDir.$request[0].'.php') == true){
	// if page exists in page dir, request page
	require($config->pageDir.$request[0].'.php');
	new $request[0];
	$display->requestNotes .= 'page request '.$request[0].'<br />';
}else{
	// otherwise, redirect to error
	header("Location: ".$config->htmlBase."error");
	die;
}


$display->requestNotes .= $_SERVER['HTTP_HOST'].'<br />';
$display->requestNotes .= $_SERVER['QUERY_STRING']. '<br />';
$display->requestNotes .= $_SERVER['REQUEST_URI']. '<br />';
$display->requestNotes .= '<pre>'.print_r($_REQUEST, true).'</pre>';

$display->output();

?>
