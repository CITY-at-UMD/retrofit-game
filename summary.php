<?php
require 'php/EEB_SQLITE3.php';
 session_start();
$_SESSION['Model'][0]="baseline";



// define the sql file path
if ($_POST['num_package'] != NULL) {
	$cur_model = $_SESSION['cur_model'] = $_POST['num_package'];
} elseif($_POST['num_package'] == NULL && $_SESSION['cur_model'] == NULL) {
	$cur_model = $_SESSION['Model'][0];
} else {
	$cur_model = $_SESSION['cur_model'];
}

$sql_file="./ENERGYPLUS/EEMs/baseline.idf/EnergyPlusPreProcess/EnergyPlus-0/eplusout.sql";

if($cur_model != 'baseline'){
     $user_dir = "$_SESSION[user_dir]/";
}
$sql_file2="./ENERGYPLUS/EEMs/{$user_dir}{$cur_model}.idf/EnergyPlusPreProcess/EnergyPlus-0/eplusout.sql";



#$eeb = new EEB_SQLITE3('php/modified_V8_V720.sql');
$eeb = new EEB_SQLITE3("./$sql_file");

$e_vals = $eeb->getValuesByMonthly('END USE ENERGY CONSUMPTION ELECTRICITY MONTHLY', 'Meter', '', '%');
$ng_vals = $eeb->getValuesByMonthly('END USE ENERGY CONSUMPTION NATURAL GAS MONTHLY', 'Meter', '', '%');
//print_r($ng_vals);

function printRow($row){
	foreach($row as $v) {
		if($v >=0) {
			echo "<td> $v </td>";
		} else {
			echo "<td> 0.0 </td>";
		}
	}
}

function printMonthlyData($row){
	echo '[';
	foreach($row as $v) {
		if($v > 0)
			echo "$v, ";
		else
			echo "0.0, ";
	}
	echo ']';
}

//printMonthlyData($e_vals['INTERIOREQUIPMENT:ELECTRICITY']);
//printRow($e_vals['INTERIOREQUIPMENT:ELECTRICITY']);

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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">

    <style>
      body {
        background: linear-gradient(to left, #888, #fff);
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
          <div class="nav-collapse collapse">
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
    <div class="container">

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
        <div class="navbar">
          <div class="navbar-inner">
            <a class="brand" href="#">Open Studio SDK And Energy Plus</a>
            <ul class="nav">
              <li><a href="./energy-use.php">Energy Use</a></li>
              <li><a href="./zone-component-load.php">Zone Component Load</a></li>
              <li><a href="./energy-intensity.php">Energy Intensity</a></li>
              <li><a href="./energy-cost.php">Energy Cost</a></li>
              <li><a href="#">GHG Emissions</a></li>
			  <li><a href="tracking-sheet.php">Tracking Sheet</a></li>
              <li><a href="eem_measure.php">EEM Measure List</a></li>
			        <li><a href="eem_scheduler.php">EEM Scheduler</a></li>
              <li><a href="all-site-energy.php">EEM Model Example</a></li>
              <li class="active"><a href="./summary.php">Summary</a></li>

            </ul>
          </div>
        </div>
 

      <!-- Tab Content -->
     <?php  echo shell_exec("ruby ./eeb_rb/getSQL.rb $sql_file $sql_file2");  ?>
        <!-- Summary Table -->
    <!--    <table class="table table-striped table-bordered" style="margin: 40px auto; max-width: 800px;">
          <caption style="background: green; color: #fff;"> <h3>Summary<h3> </caption>
          <tr id="table-row-head">
            <th> Total Site Energy Difference (-)
            </th>
            <th> -
            </th>
          </tr>
          <tr class="table-row-even">
            <th> Electricity Cost Difference ($)
            </th>
            <th> -
            </th>
          </tr>
          <tr class="table-row-odd">
             <th> Gas Cost (W)
            </th>
            <th> -
            </th>
          </tr>
          <tr class="table-row-even">
            <th> Nominal Boiler Capacity (W)
            </th>
            <th> -
            </th>
          </tr>
          <tr class="table-row-odd">
            <th> DX Coil Capacity (W)
            </th>
            <th> -
            </th>
          </tr>
          <tr class="table-row-even">
            <th> Max Fan Flow Rate (CFM)
            </th>
            <th> -
            </th>
          </tr>
          </table>-->

          

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



  </body>
</html>
