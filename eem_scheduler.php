<?php
require 'php/EEB_SQLITE3.php';
 session_start();
/*$_SESSION['Model'][0]="baseline";

// define the sql file path
if ($_POST['num_package'] != NULL) {
	$cur_model = $_SESSION['cur_model'] = $_POST['num_package'];
} elseif($_POST['num_package'] == NULL && $_SESSION['cur_model'] == NULL) {
	$cur_model = $_SESSION['Model'][0];
} else {
	$cur_model = $_SESSION['cur_model'];
}

$sql_file="/ENERGYPLUS/EEMs/{$cur_model}.idf/EnergyPlusPreProcess/EnergyPlus-0/eplusout.sql";

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
*/
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
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  	<link rel="stylesheet" href="/resources/demos/style.css" />

    <style>
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
	<style>
	  #EMM_1_MV_Slider .ui-slider-range { background: linear-gradient(to right, red, yellow); }
	  #EMM_1_MV_Slider .ui-slider-handle { border-color: red; }
	  #EMM_2_MV_Slider .ui-slider-range { background: linear-gradient(to right, blue, #3399ff); }
	  #EMM_2_MV_Slider .ui-slider-handle { border-color: blue; }
	  #EMM_3_MV_Slider .ui-slider-range { background: linear-gradient(to right, green, yellow); }
	  #EMM_3_MV_Slider .ui-slider-handle { border-color: green; }
  </style>
  
  <script>
	function foo(slider_id, input_name, val_1, val_2){
		$(function() {

			// initialized the object
			$(slider_id).slider({
			  range: true,
			  min: 0,
			  max: 12,
			  values: [val_1, val_2],
			  slide: function( event, ui ) {
			//	$( values_id ).val( "Month: " + ui.values[ 0 ] + " - Month:" + ui.values[ 1 ] );
				$("input[type=hidden][name='"+input_name+"']").val([ui.values[0], ui.values[1]]);
				//$(slider_id).attr("title")="Month "+ui.values[0]+" - "+ui.values[1];
			  }
			});
			
			// Reset values
			$("input[type=hidden][name='"+input_name+"']").val( [$(slider_id).slider("values", 0), $(slider_id).slider("values", 1)] );
			//$(slider_id).attr("title")="Month "+$(slider_id).slider("values", 0)+" - "+$(slider_id).slider("values", 1);
		/*	$( values_id ).val( "Month: " + $( slider_id ).slider( "values", 0 ) +
			  " - Month: " + $( slider_id ).slider( "values", 1 ) );*/
		});
	}
	  
	// EEM 1
	var eem1_bid1 = 1, eem1_bid2 = 2, eem1_install = 0, eem1_mv=4;
	//foo("#EMM_1_Bid_Slider", "#EEM_1_Bid_Value", eem1_bid1, eem1_bid2);
	//foo("#EMM_1_Install_Slider", "#EEM_1_Install_Value", eem1_bid2, eem1_install);
	foo("#EMM_1_MV_Slider", "EEM_1_MV_Value[]", eem1_install, eem1_mv);
	
	// EEM 2
	var eem2_bid1 = 5, eem2_bid2 = 6, eem2_install = 4, eem2_mv=8;
	//foo("#EMM_2_Bid_Slider", "#EEM_2_Bid_Value", eem2_bid1, eem2_bid2);
	//foo("#EMM_2_Install_Slider", "#EEM_2_Install_Value", eem2_bid2, eem2_install);
	foo("#EMM_2_MV_Slider", "EEM_2_MV_Value[]", eem2_install, eem2_mv);
	
	// EEM 3
	var eem3_bid1 = 5, eem3_bid2 = 6, eem3_install = 8, eem3_mv=12;
	//foo("#EMM_2_Bid_Slider", "#EEM_2_Bid_Value", eem2_bid1, eem2_bid2);
	//foo("#EMM_2_Install_Slider", "#EEM_2_Install_Value", eem2_bid2, eem2_install);
	foo("#EMM_3_MV_Slider", "EEM_3_MV_Value[]", eem3_install, eem3_mv);
  </script>

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
				<? echo <<<END
				<option value="$cur_model">$cur_model</option>
				<option value="{$_SESSION['Model'][0]}">Baseline</option>
				<option value="{$_SESSION['Model'][1]}">Building 101 - Model 1</option>
				<option value="{$_SESSION['Model'][2]}">Building 101 - Model 2</option>
END;
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
              <li><a href="./eem_measure.php">EEM Measure List</a></li>
			  <li class="active"><a href="eem_scheduler.php">EEM Scheduler</a></li>
              <li><a href="all-site-energy.php">EEM Model Example</a></li>
              <li><a href="./summary.php">Summary</a></li>
            </ul>
          </div>
        </div>

    <!-- Tab Content -->
    <!-- Testing Value -->
<!--	<p>
	  <label>EEM-1</label>
	  <input type="number" id="EEM_1_Bid_Value" style="border: 0; color: blue; font-weight: bold;" />
	  <input type="number" id="EEM_1_Install_Value" style="border: 0; color: blue; font-weight: bold;" />
	  <input type="number" id="EEM_1_MV_Value" style="border: 0; color: blue; font-weight: bold;" />
	  <br>
	  
	  <label>EEM-2</label>
	  <input type="number" id="EEM_2_Bid_Value" style="border: 0; color: blue; font-weight: bold;" />
	  <input type="number" id="EEM_2_Install_Value" style="border: 0; color: blue; font-weight: bold;" />
	  <input type="number" id="EEM_2_MV_Value" style="border: 0; color: blue; font-weight: bold;" />
	  <br>
	  
	  <label>EEM-3</label>
	  <input type="number" id="EEM_3_Bid_Value" style="border: 0; color: blue; font-weight: bold;" />
	  <input type="number" id="EEM_3_Install_Value" style="border: 0; color: blue; font-weight: bold;" />
	  <input type="number" id="EEM_3_MV_Value" style="border: 0; color: blue; font-weight: bold;" />
	  <br>
	</p> -->

    <!-- Electricity Consumption Table -->
		<form action="" method="get"> 
		<input type="hidden" name="EEM_1_MV_Value[]"> </input>
		<input type="hidden" name="EEM_2_MV_Value[]"> </input>
		<input type="hidden" name="EEM_3_MV_Value[]"> </input>
		
		<table class="table table-striped table-bordered" border="0" width="100%" cellspacing="0">
			<tr>
				<th>Measure Scheduling</th>
				<th>Jan</th>
				<th>Feb</th>
				<th>Mar</th>
				<th>Apr</th>
				<th>May</th>
				<th>Jun</th>
				<th>Jul</th>
				<th>Aug</th>
				<th>Sep</th>
				<th>Oct</th>
				<th>Nov</th>
				<th>Dec</th>
			</tr>
	
			<!-- ###################### EEM 1 ############################### -->
			<tr>
				<th align="left" colspan="13" style="background: linear-gradient(to right, red, yellow); color: white;"><h3>EEM 1
				<select name="EEM1_type">
					<option>Lighting</option>
					<option>Windows</option>
					<option>Economic Control Type</option>
				</select></h3>
				</th>
			</tr>
			<tr>
				<th>Bid</th>
				<td colspan="12"><div id="EMM_1_Bid_Slider"></div></td>
			</tr>
			<tr>
				<th>Install</th>
				<td colspan="12"><div id="EMM_1_Install_Slider" disabled></div></td>
			</tr>
			<tr>
				<th>M & V</th>
				<td colspan="12"><div id="EMM_1_MV_Slider"></div></td>
			</tr>
		
			<!-- ###################### EEM 2 ############################### -->

			<tr>
				<th align="left" colspan="13" style="background: linear-gradient(to right, blue, #3399ff); color: white;"><h3>EEM 2
				<select name="EEM2_type">
					<option>Windows</option>
					<option>Lighting</option>
					<option>Economic Control Type</option>
				</select></h3> 
				</th>
			</tr>
			<tr>
				<th>Bid</th>
				<td colspan="12"><div id="EMM_2_Bid_Slider"></div></td>
			</tr>
			<tr>
				<th>Install</th>
				<td colspan="12"><div id="EMM_2_Install_Slider"></div></td>
			</tr>
			<tr>
				<th>M & V</th>
				<td colspan="12"><div id="EMM_2_MV_Slider"></div></td>
			</tr>
	
			<!-- ###################### EEM 3 ############################### -->
			<tr>
				<th align="left" colspan="13" style="background: linear-gradient(to right, green, yellow); color: white;"> <h3> EEM 3 
				<select name="EEM3_type">
					<option>Economic Control Type</option>
					<option>Lighting</option>
					<option>Windows</option>
				</select></h3>
				</th>
			</tr>
			<tr>
				<th>Bid</th>
				<td colspan="12"><div id="EMM_3_Bid_Slider"></div></td>
			</tr>
			<tr>
				<th>Install</th>
				<td colspan="12"><div id="EMM_3_Install_Slider"></div></td>
			</tr>
			<tr>
				<th>M & V</th>
				<td colspan="12"><div id="EMM_3_MV_Slider" title=""></div></td>
			</tr>
		</table>

		<input class="btn btn-primary btn-large pull-right" type="submit" value="Update Schedule" style="margin-bottom: 50px;">  </input>

		</form>
    </div> <!-- /container -->

  </body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-26348074-7', 'eebhub.org');
  ga('send', 'pageview');
</script>
</html>
