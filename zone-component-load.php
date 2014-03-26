<?php
session_start();

require 'php/EEB_SQLITE3.php';

$_SESSION['Model'][0]="baseline";



// define the sql file path
if ($_POST['num_package'] != NULL) {
	$cur_model = $_SESSION['cur_model'] = $_POST['num_package'];
} elseif($_POST['num_package'] == NULL && $_SESSION['cur_model'] == NULL) {
	$cur_model = $_SESSION['Model'][0];
} else {
	$cur_model = $_SESSION['cur_model'];
}

if($cur_model != 'baseline'){
     $user_dir = "$_SESSION[user_dir]/";
}

$sql_file="/ENERGYPLUS/EEMs/{$user_dir}{$cur_model}.idf/EnergyPlusPreProcess/EnergyPlus-0/eplusout.sql";

#$eeb = new EEB_SQLITE3('php/modified_V8_V720.sql');
$eeb = new EEB_SQLITE3("./$sql_file");

if( $_GET['for'] == '' && $_GET['table'] == ''){
	$_SESSION['for'] = '1ST FLOOR-MIDDLE';
	$_SESSION['table'] = 'Estimated Cooling Peak Load Components';
}
else {
	if($_GET['for'] == '') {} 
	else { $_SESSION['for'] = $_GET['for'];}

	if($_GET['table'] == '') {} 
	else {$_SESSION['table'] = $_GET['table'];}
}

//echo $_SESSION['for'];
//echo $_SESSION['table'];


$load_vals = $eeb->getValuesByColumn('ZoneComponentLoadSummary', $_SESSION['for'], $_SESSION['table'], '%');
$zone = $eeb->getReportForStrings('ZoneComponentLoadSummary');
//print_r($load_vals['Total']);

function printZone($zone){
	foreach($zone as $z) {
		if($z == $_SESSION['for']) {
			echo "<li class='selected'> <a href='?for=$z'> $z </a></li>";
		}
		else {
			echo "<li> <a href='?for=$z'> $z </a></li>";
		}
	}
}

// Net_Sensible = Total_Sensible - Latent_Sensible
function getNetSensibleLoad($total, $latent) {
	$total_load = NULL;	
	$net_load = NULL;
	$i = 0;

	foreach($total as $t) {
		$total[] = $t;
	}

	foreach($latent as $l) {
		$net_load[] = $total[$i++] - $l;
	}

	return $net_load;
}

//print_r(getNetSensibleLoad($load_vals['Total'], $load_vals['Latent']);

function printLoadData($load_vals) {
	echo '[';
	foreach($load_vals as $c) {
		echo "$c, ";
	}
	echo ']';
}

//printLoadData($load_vals['Total'])

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>EEB Hub Simulation Tools: Comprehensive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">

    <style>
	 .selected {
		background: yellow;
		}
      body {
        background: linear-gradient(to bottom, #999, #fff);
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }

      .container{
        width: 90%;
      }

      .table-striped{
        background: #eee;
      }

      .table{
        text-align: center;
      }

    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

  </head>
  <body>

    <!-- Navbar
    ================================================== -->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container" >
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand pull-left" href="./">EEB Hub Simulation Platform</a>
          <div class="nav-collapse collapse">s
            <ul class="nav">
              <li class="">
                <a href="http://tools.eebhub.org/">Home</a>
              </li>
              <li class="">
                <a href="http://tools.eebhub.org/">Lite</a>
              </li>
              <li class="">
                <a href="http://tools.eebhub.org/">Partial</a>
              </li>
              <li class="">
                <a href="http://tools.eebhub.org/substantial">Substantial</a>
              </li>
              <li class="active">
                <a href="http://tools.eebhub.org/comprehensive">Comprehensive</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>


    <!-- Container -->
    <div class="container"  >
  
		<!-- Switch Pacakge -->
		<form action="" method="post">
			<select id="num_package" name="num_package" onchange="this.form.submit();">
  <?php
          echo "<option value=\"$cur_model\">$cur_model</option>";
          foreach( $_SESSION['Model'] as $eem_model ) {
            echo "<option value=\"$eem_model\">$eem_model</option>";
          }       
        ?>
			</select>
		</form>


      <!-- Sub-Nav-bar -->
        <div class="navbar" >
          <div class="navbar-inner">
            <a class="brand" href="#">Open Studio SDK And Energy Plus</a>
            <ul class="nav">
              <li><a href="./energy-use.php">Energy Use</a></li>
              <li class="active"><a href="./zone-component-load.php">Zone Component Load</a></li>
              <li><a href="./energy-intensity.php">Energy Intensity</a></li>
              <li><a href="./energy-cost.php">Energy Cost</a></li>
              <li><a href="#">GHG Emissions</a></li>
			  <li><a href="tracking-sheet.php">Tracking Sheet</a></li>
              <li><a href="eem_measure.php">EEM Measure List</a></li>
			        <li><a href="eem_scheduler.php">EEM Scheduler</a></li>
              <li><a href="all-site-energy.php">EEM Model Example</a></li>
              <li><a href="./summary.php">Summary</a></li>
            </ul>
          </div>
        </div>
 		
        <!-- Zone Components Load Summary-->
            <div class="container-fluid" style="background: #fff">
                <div class="row-fluid">
                
                    <!--Sidebar content-->
                    <div class="span3" style="background: #fff; border-right: thin solid #eee; padding-left: 20px;">
                    	<legend>Building Name </legend>
                      	<div style="height: 580px; overflow: scroll; overflow-x: hidden; padding: 2px; scroller"> 
		                  <!-- Link or button to toggle dropdown -->
		                  <ul class="unstyled" >
		                    <?php printZone($zone); ?>
		                  </ul>
						</div>
                   	</div>

                    <!--Body content-->
                    <div class="span9">
                    
                    <!-- Cooling or Heating -->
                    <ul class="nav nav-tabs">
                        <li >
                        <a href="#">Annual</a>
                        </li>
						<?php
							echo '<li';
							if($_SESSION['table'] == 'Estimated Heating Peak Load Components'){ echo ' class="active"';}
							echo '><a href="?table=Estimated Heating Peak Load Components">Heating Design Day</a></li>';

							echo '<li';
		                    if($_SESSION['table'] == 'Estimated Cooling Peak Load Components'){ echo ' class="active"';}
							echo '><a href="?table=Estimated Cooling Peak Load Components">Cooling Design Day</a></li>';
						?>
                    </ul>
                        
                    <div id="zone_cooling_chart" style="min-width: 310px; width: 100%; min-height: 600px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>

        
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
   

    <!-- load highchart libs -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="js/Highcharts-3.0.4/js/highcharts.js"></script>
    <script src="js/Highcharts-3.0.4/js/modules/exporting.js"></script>
    
     <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>

    <!-- Charts' Defination -->
    <script>
     $(function () {
        $('#zone_cooling_chart').highcharts({
            chart: {
                type: 'bar',
                margin: [ 50, 50, 50, 200]
            },
            title: {
                text: 'Zone Component Load Summary Without Latent (Btu/h) '
            },
            xAxis: {
                categories: [
                    'People:',
                    'Lights:',
                    'Equipment:',
                    'Refrigeration:',
                    'Water Use Equipment:',
                    'HVAC Equipment Losses:',
                    'Power Generation Equipment:',
                    'Infiltration:',
                    'Zone Ventilation',
                    'Interzone Mixing:',
                    'Roof:',
					'Interzone Ceiling',
                    'Other Roof:',
                    'Exterior Wall:',
                    'Interzone Wall:',
					'Ground Contact Wall:',
                    'Other Wall:',
                    'Exterior Floor:',
                    'Interzone Floor:',
                    'Ground Contact Floor:',
                    'Other Floor:',
                    'Fenestration COnduction:',
					'Fenestration Solar:',
					'Opaque Door:',
					'Net Sensible Load:'
                ],
                labels: {
                    rotation: 0,
                    align: 'right',
                    style: {
						width: '200px',
                        fontSize: '12px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: <?php echo min($load_vals['Total']);?>,
                title: {
                    text: ''
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Load / hour Summary: <b>{point.y:.1f} Btu/h</b>',
            },
            series: [{
                name: 'Energy Per Hour',
                data: <?php printLoadData(getNetSensibleLoad($load_vals['Total'], $load_vals['Latent']));?>,
                dataLabels: {
                    enabled: true,
                    rotation: 0,
                    color: 'red',
                    align: 'left',
                    x: 10,
                    y: 8,
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 3px #eee'
                    }
                }
            }]
        });
    });
    
    </script>

  </body>
</html>
