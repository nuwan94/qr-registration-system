<?php
include_once('db.php');
include_once('header.php');
?>
<style type="text/css" media="screen">
	body{
		font-size: 1.5em;
	}
	.dp{
		width: 100px;
		vertical-align: middle;
		padding: .5em;
	}
</style>
<div class="container">
	<?php
        $getLogSQL = "SELECT log.time,users.uid,team.tname,log.activity FROM log,users,team WHERE log.uid=users.uid AND users.tid=team.tid ORDER BY log.time DESC";
        $logResult = mysqli_query($conn, $getLogSQL);
		$logResultCheck = mysqli_num_rows($logResult);
		if($logResultCheck>0){
	        while($row = mysqli_fetch_array($logResult)){
	            echo('<div class="row">');
				echo('<div class="col s12">');
					echo('<img class="dp circle" src="img/users/'.$row['uid'].'.jpg" /> ');
					echo($row['activity']);
					echo(' [<b>'.$row['tname'].'</b>] - '.$row['time']);
				echo('</div></div>');
	        }
	    }
	?>
</div>