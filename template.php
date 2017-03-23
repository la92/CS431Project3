<!DOCTYPE HTML>
<html>
<head>
	<base href="<?php echo $config->htmlBase; ?>" />
	<title><?php echo $this->pageTitle; ?></title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
	<?php echo $this->headerInfo; ?>
</head>
<body>
	<nav class="navbar navbar-default" role="navigation">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="">CS 431 Project 3</a>
		</div>
		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav">
				<?php
					if($user!== null and $user->checkAccess('student')){
						echo '
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Student <i class="fa fa-sort-asc"></i></a>
								<ul class="dropdown-menu">
									<li><a href="viewClasses/student">All Classes</a></li>
									<li><a href="viewClasses/student/myClasses">My Classes</a></li>
								</ul>
							</li>
						';
					}
				?>
				<?php
					if($user!== null and $user->checkAccess('faculty')){
						echo '
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Faculty <i class="fa fa-sort-asc"></i></a>
								<ul class="dropdown-menu">
									<li><a href="createClass">Create Class</a></li>
									<li><a href="viewClasses/faculty">View Your Classes</a></li>
								</ul>
							</li>
						';
					}
				?>
				<?php
					if($user!== null and $user->checkAccess('staff')){
						echo '
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Staff <i class="fa fa-sort-asc"></i></a>
								<ul class="dropdown-menu">
									<li><a href="createClass">Create Class</a></li>
									<li><a href="viewClasses/staff">View Classes</a></li>
									<li><a href="searchPeople">Search People</a></li>
								</ul>
							</li>
						';
					}
				?>
				<?php
					if($user!== null and $user->checkAccess('executive')){
						echo '
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Executive <i class="fa fa-sort-asc"></i></a>
								<ul class="dropdown-menu">
									<li><a href="searchPeople">Search People</a></li>
									<li><a href="statistics">University Statistics</a></li>
								</ul>
							</li>
						';
					}
				?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="info"><i class="fa fa-info"></i> Information</a></li>
				<?php
					if($user !== null){
						echo '<li><a href="logout"><i class="fa fa-unlock-o"></i> Log Out</a></li>';
					}else{
						echo '<li><a href="register"><i class="fa fa-edit"></i> Register</a></li>';
						echo '<li><a href=""><i class="fa fa-unlock-o"></i> Log in</a></li>';
					}
				?>
			</ul>
		</div>
	
	</nav>
	
	<div class="container">
		<?php echo $this->content; ?>
	</div>
	
	<hr />
	<?php //echo $this->requestNotes;?>
	<?php echo $this->footerInfo;?>
	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
</body>
</html>
