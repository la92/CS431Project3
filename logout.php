<?php

class logout{

function logout(){
	global $config, $dbh;
	if(isset($_COOKIE['session'])){
		$hashScrubbed = mysqli_real_escape_string($dbh, $_COOKIE['session']);
		mysqli_query($dbh, "DELETE FROM `Sessions` WHERE `Hash` = '$hashScrubbed'");
	}
	setcookie('session', '', 0, $config->uriRemove);
	header("Location: ".$config->htmlBase);
	die;
}

}
?>