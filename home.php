<?php
class home{
####################################################################


function home(){
	global $display, $user;
	$display->pageTitle = 'Home';
	$display->content = '
		You are logged in.<br />
		First Name: '.$user->getFirstName().'<br />
		Last Name: '.$user->getLastName().'<br />
		Email: '.$user->getEmail().'<br />
		Username: '.$user->getUsername().'<br />
		User Id: '.$user->getUserId().'<br />
	';
}

####################################################################
}
?>
