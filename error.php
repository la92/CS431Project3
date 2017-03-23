<?php
class error{
####################################################################

function error(){
	global $display;
	$display->pageTitle = 'Error';
	$display->content = '<div class="alert alert-danger">Page not found.</div>';
}

####################################################################
}
?>