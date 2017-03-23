<?php
class info{

function info(){
global $display, $dbh, $config;




$display->pageTitle = 'CS431 Project 3';

$display->content = '
<div class="row">
	<div class="col-sm-4">
		<h3>Authors</h3>
		<div class="well">
			Geoffrey Ching<br />
			Edmund Odai<br />
			Alexander Pinho<br />
		</div>
	</div>
	
</div>

<h1>User Access</h1>
<div class="row">
	<div class="col-md-6">
		<h3>Students</h3>
		<ul>
			<li>Can Add/Drop classes</li>
			<li>Can view members in their classes</li>
		</ul>
	</div>
	<div class="col-md-6">
		<h3>Faculty</h3>
		<ul>
			<li>Can View the Students in their Class</li>
			<li>Can Add/Remove Classes they will be teaching</li>
			<li>Do not have access to classes taught by other faculty</li>
		</ul>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<h3>Staff</h3>
		<ul>
			<li>Can Add/Remove Classes for any Faculty</li>
			<li>Can Grant Student and Faculty Access to Users of the University Database</li>
			<li>Can access Students Private Information</li>
			<li>Can search for Students or anyone in the University Database System by Email, Username, User ID, First name and Last name</li>
		</ul>
	</div>
	<div class="col-md-6">
		<h3>Executives</h3>
		<ul>
			<li>Can Grant Student, Faculty, Staff, and Executive Access to Users of the University Database</li>
			<li>Can access Students, Faculty and Staff private information</li>
			<li>Can access the Statistic data page for the University Database System</li>
		</ul>
	</div>
</div>


;

$tables = Array('proj2users', 'proj2sessions', 'proj2access', 'proj2classes', 'proj2classregistrations');

foreach($tables as $table){
	$q = mysqli_query($dbh, "SHOW COLUMNS FROM `".$table."`");

	$fieldArr = Array();
	$fieldRow = '<tr>';
	$extraRow = '<tr>';
	while($r = mysqli_fetch_assoc($q)){
		$fieldArr[] = $r['Field'];
		$fieldRow .= '<th style="vertical-align:top;">'.$r['Field'].'</th>';
		$extraRow .= '<td style="vertical-align:top;">'.$r['Type'].'<br />'.$r['Null'].'<br />'.$r['Key'].'<br />'.$r['Default'].'<br />'.$r['Extra'].'</td>';
	}
	$fieldRow .= '</tr>';
	$extraRow .= '</tr>';
	
	$q = mysqli_query($dbh, "SELECT * FROM `".$table."`");
	$rows = '';
	while($r = mysqli_fetch_assoc($q)){
		$rows .= '<tr>';
		foreach($fieldArr as $col){
			$rows .= '<td>'.$r[$col].'</td>';
		}
		$rows .= '</tr>';
	}


	$display->content .= '
	<h3 style="margin-top:100px;">'.$table.'</h3>
	<table class="table">
		<thead>
			'.$fieldRow.'
		</thead>
		<tbody>
			'.$extraRow.'
			'.$rows.'
		</tbody>
	</table>
	';
}



}

}
?>