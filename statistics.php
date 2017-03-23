<?php
class statistics{

function statistics(){
	global $display, $user, $dbh;
	
	$display->pageTitle = 'Statistics';
	
	if(!$user->checkAccess('executive')){
		$display->content = '<div class="alert alert-danger">Only executives can view the statistics report.</div>';
		return;
	}
	
	$q1 = mysqli_query($dbh, "SELECT COUNT(*) FROM `Users`");
	if(!$q1){
		$numbUsers = 'N/A';
	}else{
		$r = mysqli_fetch_assoc($q1);
		$numbUsers = $r['COUNT(*)'];
	}
	
	
	$q2 = mysqli_query($dbh, "
		SELECT COUNT(`Access`) AS `Count`,`Access`
			FROM `Access`
			GROUP BY `Access`
	");
	$labelArr = Array();
	$valueArr = Array();
	if(!$q2){
	}else{
		while( $r = mysqli_fetch_assoc($q2) ){
			$labelArr[] = $r['Access'];
			$valueArr[] = $r['Count'];
		}
	}
	
	$q3 = mysqli_query($dbh, "SELECT COUNT(*) FROM `Courses`");
	if(!$q3){
		$numbClasses = 'N/A';
	}else{
		$r = mysqli_fetch_assoc($q3);
		$numbClasses = $r['COUNT(*)'];
	}
	
	
	$q4 = mysqli_query($dbh, "SELECT COUNT(*) FROM `Registrations`");
	if(!$q4){
		$numbRegistrations = 'N/A';
	}else{
		$r = mysqli_fetch_assoc($q4);
		$numbRegistrations = $r['COUNT(*)'];
	}
	
	
	$q5 = mysqli_query($dbh, "
		SELECT
			DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(DateOfBirth, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(DateOfBirth, '00-%m-%d')) AS `Age`,
			COUNT(*) AS `Count`
		FROM `Users`
		WHERE `UserId` IN (SELECT `UserId` FROM `Access` WHERE `Access`='Student')
		GROUP BY `Age`
	");
	$stAgeArr = Array();
	$stCntArr = Array();
	if(!$q5){
		echo mysqli_error($dbh);
	}else{
		while( $r = mysqli_fetch_assoc($q5) ){
			$stAgeArr[$r['Age']] = $r['Age'];
			$stCntArr[$r['Age']] = $r['Count'];
		}
	}
	$min = min($stAgeArr);
	$max = max($stAgeArr);
	$stAgeOut = Array();
	$stCntOut = Array();
	for($i = $min; $i<=$max; $i++){
		$stAgeOut[$i] = $i;
		if(isset($stCntArr[$i])){
			$stCntOut[$i] = $stCntArr[$i];
		}else{
			$stCntOut[$i] = 0;
		}
	}
	
	$q6 = mysqli_query($dbh, "
		SELECT
			DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(DateOfBirth, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(DateOfBirth, '00-%m-%d')) AS `Age`,
			COUNT(*) AS `Count`
		FROM `Users`
		WHERE `UserId` IN (SELECT `UserId` FROM `Access` WHERE `Access`='Faculty')
		GROUP BY `Age`
	");
	$faAgeArr = Array();
	$faCntArr = Array();
	if(!$q6){
		echo mysqli_error($dbh);
	}else{
		while( $r = mysqli_fetch_assoc($q6) ){
			$faAgeArr[$r['Age']] = $r['Age'];
			$faCntArr[$r['Age']] = $r['Count'];
		}
	}
	$min = min($faAgeArr);
	$max = max($faAgeArr);
	$faAgeOut = Array();
	$faCntOut = Array();
	for($i = $min; $i<=$max; $i++){
		$faAgeOut[$i] = $i;
		if(isset($faCntArr[$i])){
			$faCntOut[$i] = $faCntArr[$i];
		}else{
			$faCntOut[$i] = 0;
		}
	}
	
	
	$display->content .= '
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-primary">
					<div class="panel-heading">Number of Users</div>
					<div class="panel-body" style="text-align:center;">
						<span style="font-size:100px;">'.$numbUsers.'</span>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-primary">
					<div class="panel-heading">Number of Users with Access Types</div>
					<div class="panel-body">
						<canvas id="userAccessChart" width="400px" height="300px"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-primary">
					<div class="panel-heading">Number of Classes</div>
					<div class="panel-body" style="text-align:center;">
						<span style="font-size:100px;">'.$numbClasses.'</span>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-primary">
					<div class="panel-heading">Number of Students per Class</div>
					<div class="panel-body" style="text-align:center;">
						<span style="font-size:100px;">'.round($numbRegistrations/$numbClasses, 1).'</span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-primary">
					<div class="panel-heading">Student Age</div>
					<div class="panel-body" style="text-align:center;">
						<canvas id="studentAgeChart" width="400px" height="300px"></canvas>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-primary">
					<div class="panel-heading">Faculty Age</div>
					<div class="panel-body">
						<canvas id="facultyAgeChart" width="400px" height="300px"></canvas>
					</div>
				</div>
			</div>
		</div>
	';
	
	$display->footerInfo .= '<script type="text/javascript" src="assets/js/Chart.min.js"></script>';
	$display->footerInfo .= '<script type="text/javascript">
	var data = {
		labels : ["'.implode('","', $labelArr).'"],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,1)",
				data : ['.implode(",", $valueArr).']
			}
		]
	}
	var options = {
		scaleOverride : true,
		scaleSteps : '.(max($valueArr)+1).',
		scaleStepWidth : 1,
		scaleStartValue : 0,
	}
	var ctx = document.getElementById("userAccessChart").getContext("2d");
	var myNewChart = new Chart(ctx).Bar(data, options);
	
	
	var data2 = {
		labels : ["'.implode('","', $stAgeOut).'"],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,1)",
				data : ['.implode(",", $stCntOut).']
			}
		]
	}
	var options2 = {
		scaleOverride : true,
		scaleSteps : '.(max($stCntOut)+1).',
		scaleStepWidth : 1,
		scaleStartValue : 0,
	}
	var ctx2 = document.getElementById("studentAgeChart").getContext("2d");
	var myNewChart2 = new Chart(ctx2).Bar(data2, options2);
	
	
	var data3 = {
		labels : ["'.implode('","', $faAgeOut).'"],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,1)",
				data : ['.implode(",", $faCntOut).']
			}
		]
	}
	var options3 = {
		scaleOverride : true,
		scaleSteps : '.(max($faCntOut)+1).',
		scaleStepWidth : 1,
		scaleStartValue : 0,
	}
	var ctx3 = document.getElementById("facultyAgeChart").getContext("2d");
	var myNewChart3 = new Chart(ctx3).Bar(data3, options3);
	
	
	</script>';
}


}
?>