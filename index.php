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
		opacity: 0.85;
		height: 70px;
		-webkit-transition-duration: 0.3s;
       -moz-transition-duration: 0.3s;
            transition-duration: 0.3s;
	}
	.subject_table tbody tr:hover {
		opacity: 1;
		height: 70px;
	}
	.subject_table tbody tr.over {
		text-decoration: line-through;
		height: 20px;
		opacity: 0.6;
		font-size: 14px;
		-webkit-transition-duration: 0.3s;
       -moz-transition-duration: 0.3s;
            transition-duration: 0.3s;
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
	
	</style>
  </head>
  <!-- START BODY -->
  <body class="nomobile">

    <!-- START HEADER -->
    <section id="header">
		 <?php if ($empty) { ?>
        <div class='panel panel-default table-responsive'>
			<table class='table table-hover table-condensed table-bordered'  style='color: black' id='choicebox'>
				<thead>
					<tr>
						<th></th>
						<th>GP</th>
						<th>KI</th>
						<th>Math</th>
						<th>Phys</th>
						<th>Chem</th>
						<th>Bio</th>
						<th>Lit</th>
						<th>Econs</th>
						<th>Geog</th>
						<th>Hist</th>
						<th>Art</th>
						<th>Music</th>
						<th>TLL</th>
						<th>ELL</th>
						<th>CLL</th>
						<th>MLL</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>H1</td>
						<td><input type='checkbox' value='gp'></td>
						<td>-</td>
						<td><input type='checkbox' value='ma'></td>
						<td><input type='checkbox' value='ph'></td>
						<td><input type='checkbox' value='ch'></td>
						<td><input type='checkbox' value='bi'></td>
						<td><input type='checkbox' value='li'></td>
						<td><input type='checkbox' value='ec'></td>
						<td><input type='checkbox' value='ge'></td>
						<td><input type='checkbox' value='hi'></td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
						<td>-</td>
					</tr>
					<tr>
						<td>H2</td>
						<td>-</td>
						<td><input type='checkbox' value='ki'></td>
						<td><input type='checkbox' value='ma'></td>
						<td><input type='checkbox' value='ph'></td>
						<td><input type='checkbox' value='ch'></td>
						<td><input type='checkbox' value='bi'></td>
						<td><input type='checkbox' value='li'></td>
						<td><input type='checkbox' value='ec'></td>
						<td><input type='checkbox' value='ge'></td>
						<td><input type='checkbox' value='hi'></td>
						<td><input type='checkbox' value='ar'></td>
						<td><input type='checkbox' value='mu'></td>
						<td><input type='checkbox' value='tl'></td>
						<td><input type='checkbox' value='el'></td>
						<td><input type='checkbox' value='cl'></td>
						<td><input type='checkbox' value='ml'></td>
					</tr>
					<tr>
						<td>H3</td>
						<td>-</td>
						<td>-</td>
						<td><input type='checkbox' value='ma'></td>
						<td><input type='checkbox' value='ph'></td>
						<td><input type='checkbox' value='ch'></td>
						<td><input type='checkbox' value='bi'></td>
						<td>-</td>
						<td><input type='checkbox' value='ec'></td>
						<td>-</td>
						<td>-</td>
						<td><input type='checkbox' value='ar'></td>
						<td>-</td>
						<td><input type='checkbox' value='tl'></td>
						<td colspan='3'><button type='button' class='btn btn-primary btn-xs' onclick='jacq()'>update</button></td>
					</tr>
				</tbody>
			</table>
			<script type="text/javascript">
			function jacq() {
				var url = '';
				$("#choicebox tbody tr").each(function(i) {
					url += '/';
					$(this).find('input').each(function(j) {
						if (this.checked) url += this.value;
					});
				})
				window.location = url;
				return url;
			}
			</script>
		</div>
        <?php } ?>
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
								if ($s < time()) echo "<tr class='over'>";
								else {
									if ($next == null) $next = $v;
									echo "<tr>";
								}
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
							/*
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
							}*/
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
