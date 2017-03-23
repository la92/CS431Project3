<?php
class display{



public $headerInfo = '';

public $content = '';

// html head->title 
public $pageTitle = '';


public $footerInfo = '';

// notes about the page request for after the page
public $requestNotes = '';

function output(){
	global $config, $user;
	require('template.php');
}

}
?>
