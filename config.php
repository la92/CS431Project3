<?php

class config{
####################################################################

// set your database host here
public $host = 'sql2.njit.edu';

// set your database user here
public $user = 'la92';

// set your database password here
public $pass = '06CLHiUFj';

// set your databse here
public $db = 'la92';


// set these for your directory

public $htmlBase = 'https://web.njit.edu/~la92/CS431Project3/';

// this is removed form the $_SERVER['request'] variable to figure out which script should be called
// this should be set to everything after your hostname but before the directory where the application is
public $uriRemove = 'web.njit.edu/~la92/CS431Project3/';

public $fileDir = 'C:/Users/lionelalliaj/Desktop/CS431Project3';

public $pageDir;
public $objectDir;
public $libDir;

function config(){
	$this->pageDir = $this->fileDir.'pages/';
	$this->objectDir = $this->objectDir.'objects/';
	$this->libDir = $this->libDir.'lib/';
}

}

?>
