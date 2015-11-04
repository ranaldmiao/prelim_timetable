<?php
mysql_connect('localhost','timetable','uranus') or die(mysql_error());
mysql_select_db('zadmin_prelim') or die(mysql_error());
$exams = array();
$h1 = strtoupper(trim(rtrim($_GET['h1'])));
$h2 = strtoupper(trim(rtrim($_GET['h2'])));
$h3 = strtoupper(trim(rtrim($_GET['h3'])));
$empty = ($h1 == "" && $h2 == "" && $h3 == "");
if ($empty) {
	$q = mysql_query("SELECT * FROM exams ORDER BY start ASC");
	while ($r = mysql_fetch_assoc($q)) {
		$exams[] = $r;
	}
}
else {
	$chain = array();
	for ($i = 0, $l = strlen($h1); $i < $l; $i = $i + 2) {
		$chain[] = "( level = 1 AND subject LIKE '".$h1[$i].$h1[$i+1]."')";
	}
	for ($i = 0, $l = strlen($h2); $i < $l; $i = $i + 2) {
		$chain[] = "( level = 2 AND subject LIKE '".$h2[$i].$h2[$i+1]."')";
	}
	for ($i = 0, $l = strlen($h3); $i< $l; $i = $i + 2) {
		$chain[] = "( level = 3 AND subject LIKE '".$h3[$i].$h3[$i+1]."')";
	}
	
	$q = mysql_query( "SELECT * FROM exams WHERE ".implode(" OR ", $chain)." ORDER BY start ASC");
	while ($r = mysql_fetch_assoc($q)) {
		$exams[] = $r;
	}
}
$completed = 0;
$total = count($exams);
foreach ($exams as $k => $v) {
	$s = strtotime($v['start']);
	if ($s < time()) ++$completed;
}
$percent = floatval(100*$completed/$total);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>RI Prelims 2014 Timetable</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/soon.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
      <script src="assets/js/respond.min.js"></script>
    <![endif]-->
    
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
	<style type='text/css'>
	.subject_table {
		width: 100%;
	}
	.subject_table tbody td {
		vertical-align: middle !important;
	}
	.subject_table tbody tr {
		height: 70px;
	}
	.subject_table tbody tr.over {
		text-decoration: line-through;
		height: 40px;
		opacity: 0.7;
	}
	.subject_table tbody tr.over:hover {
		opacity: 0.9;
	}
	.subject_table thead tr {
		height: 50px;
	}
	.subject_table th {
		text-align: center;
	}
	#progress-bar {
		background-color: rgba(255, 255, 255, .7);
		border-radius: 3px;
		width: 0px;
		height: 10px;
		-webkit-animation-duration: 2s;
		   -moz-animation-duration: 2s;
			 -o-animation-duration: 2s;
				animation-duration: 2s;
		-webkit-animation-fill-mode: both;
		   -moz-animation-fill-mode: both;
			 -o-animation-fill-mode: both;
				animation-fill-mode: both;
	}
	
	</style>
  </head>
  <!-- START BODY -->
  <body class="nomobile">

    <!-- START HEADER -->
    <section id="header">
        <div class="container">
            <header>
                <!-- HEADLINE -->
                <h4 data-animated="GoIn">YOUR NEXT EXAM IS...</h4>
            </header>
            <!-- START TIMER -->
            <div id="timer" data-animated="FadeIn">
                <h1 id="message"></h1>
                <div id="days" class="timer_box"></div>
                <div id="hours" class="timer_box"></div>
                <div id="minutes" class="timer_box"></div>
                <div id="seconds" class="timer_box"></div>
            </div>
            <!-- END TIMER -->
             <?php if (!$empty) { ?>
             <div class="col-lg-8 col-lg-offset-2 mt centered">
				 <h4>YOUR PROGRESS</h4><br><br>
				<div class="progress-bar" id='progress-bar' role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100" >
				</div>
				<br>
				
			</div>
			<?php } ?>
            <div class="col-lg-8 col-lg-offset-2 mt centered">
            	<h4>YOUR SUBJECTS</h4>
				<table class="table subject_table">
					<thead>
						<tr>
							<th>Subject</th>
							<th>Time</th>
							<th>Duration</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$next = null;
							foreach ($exams as $k => $v) {
								$s = strtotime($v['start']);
								$e = strtotime($v['end']);
								if ($s < time()) continue;//echo "<tr class='over'>";
								if ($next == null) $next = $v;
								echo "<tr>";
								echo "<td>$v[content]</td>";
								echo "<td>".date("l j M Y, Hi", $s)."-".date("Hi", $e)."</td>";
								$d = ($e - $s)/60;
								$h = intval($d / 60);
								$m = $d % 60;
								echo "<td>";
								if ($h > 0) echo $h."h ";
								if ($m > 0) echo $m."m";
								echo "</td>";
								echo "</tr>";
							}
							foreach ($exams as $k => $v) {
								$s = strtotime($v['start']);
								$e = strtotime($v['end']);
								if ($s >= time()) continue;
								echo "<tr class='over'>";
								echo "<td>$v[content]</td>";
								echo "<td>".date("l j M Y, Hi", $s)."-".date("Hi", $e)."</td>";
								$d = ($e - $s)/60;
								$h = intval($d / 60);
								$m = $d % 60;
								echo "<td>";
								if ($h > 0) echo $h."h ";
								if ($m > 0) echo $m."m";
								echo "</td>";
								echo "</tr>";
							}
							if ($next == null) $next = $exams[count($exams)-1];
						?>
						
					</tbody>
				</table>           
			</div>
            
        </div>
        
        
        
    </section>
    <!-- END HEADER -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/modernizr.custom.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/soon/plugins.js"></script>
    <script src="/assets/js/soon/jquery.themepunch.revolution.min.js"></script>
    <script src="/assets/js/soon/custom.js"></script>
    <script type='text/javascript'>
		/******************************************************************************************************************************
		COMMING SOON PAGE
		*******************************************************************************************************************************/
		(function($) {
			setTimeout(move_bar, 1000);
			function move_bar() {
				$('#progress-bar').css('width', '<?php echo $percent; ?>%');
				
			}
			/**
			* Set your date here  (YEAR, MONTH (0 for January/11 for December), DAY, HOUR, MINUTE, SECOND)
			* according to the GMT+8 Timezone
			**/
			var launch = new Date(<?php echo (strtotime($next['start']))*1000; ?>);
			/**
			* The script
			**/
			var message = $('#message');
			var days = $('#days');
			var hours = $('#hours');
			var minutes = $('#minutes');
			var seconds = $('#seconds');
			
			setDate();
			function setDate(){
				var now = new Date();
				if( launch < now ){
					days.html('<h1>0</H1><p>Day</p>');
					hours.html('<h1>0</h1><p>Hour</p>');
					minutes.html('<h1>0</h1><p>Minute</p>');
					seconds.html('<h1>0</h1><p>Second</p>');
					//message.html('NO MORE');
				}
				else{
					var s = -8*60*60 -now.getTimezoneOffset()*60 + (launch.getTime() - now.getTime())/1000;
					var d = Math.floor(s/86400);
					days.html('<h1>'+d+'</h1><p>Day'+(d>1?'s':''),'</p>');
					s -= d*86400;

					var h = Math.floor(s/3600);
					hours.html('<h1>'+h+'</h1><p>Hour'+(h>1?'s':''),'</p>');
					s -= h*3600;

					var m = Math.floor(s/60);
					minutes.html('<h1>'+m+'</h1><p>Minute'+(m>1?'s':''),'</p>');

					s = Math.floor(s-m*60);
					seconds.html('<h1>'+s+'</h1><p>Second'+(s>1?'s':''),'</p>');
					setTimeout(setDate, 1000);

					message.html('<?php echo $next['content']; ?>');
				}
			}
		})(jQuery);
    </script>
  </body>
  <!-- END BODY -->
</html>
