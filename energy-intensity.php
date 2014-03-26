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

if($cur_model != 'baseline'){
     $user_dir = "$_SESSION[user_dir]/";
}

$sql_file="/ENERGYPLUS/EEMs/{$user_dir}{$cur_model}.idf/EnergyPlusPreProcess/EnergyPlus-0/eplusout.sql";

#$eeb = new EEB_SQLITE3('php/modified_V8_V720.sql');
$eeb = new EEB_SQLITE3("./$sql_file");

$total_vals = $eeb->getValues('AnnualBuildingUtilityPerformanceSummary', 'Entire Facility', 'Site and Source Energy', '%');
$site_vals = $eeb->getValues('AnnualBuildingUtilityPerformanceSummary', 'Entire Facility',  'End Uses', 'kBtu');
$source_vals = $eeb->getValues('SourceEnergyEndUseComponentsSummary', 'Entire Facility',  'Source Energy End Use Components Summary', 'kBtu');
$area_vals = $eeb->getValues('AnnualBuildingUtilityPerformanceSummary', 'Entire Facility', 'Building Area', '%');
//print_r($area_vals);
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
              <li class="active"><a href="./energy-intensity.php">Energy Intensity</a></li>
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
 		
        <!-- Energy Intensity div -->
        <div>
          <div id="annula_energy_chart" style="min-width: 400px; height: 400px; margin: 40px auto;"></div>

<table class="table table-striped table-bordered " style="margin: 40px auto; ">
            <caption style="background: linear-gradient(to left, yellow, #3399ff, purple ); color: #fff; "> <h3>Annual Energy Consumption And Intensity<h3> </caption>
     <?php
echo "
       <tr id='table-row-head'>
              <th> -
              </th>
              <th> Total Electricity [kBtu]
              </th>
              <th> Total Natural Gas [kBtu]
              </th>
              <th> Total Energy [kBtu]
              </th>
              <th> Area [ft^2]
              </th>
              <th> Energy Per Total Building Area [kBtu/ft^2]
              </th>
              <th> Energy Per Conditioned Building Area [kBtu/ft^2]
              </th>
            </tr>
            <tr class='table-row-even'>
              <th> Total Site Energy 
              </th>
              <td>".number_format($site_vals['Total End Uses']['Electricity'])."
              </td>
              <td>".number_format($site_vals['Total End Uses']['Natural Gas'])."
              </td>
              <td>".number_format($total_vals['Total Site Energy']['Total Energy'])."
              </td>
              <td>".number_format($area_vals['Total Building Area']['Area'])."
              </td>
              <td style='color:red'>".$total_vals['Total Site Energy']['Energy Per Total Building Area']."
              </td>
              <td>".$total_vals['Total Site Energy']['Energy Per Conditioned Building Area']."
              </td>
         
            </tr>
            <tr class='table-row-even'>
              <th> Total Source Energy 
              </th>
                <td>".number_format($source_vals['Total Source Energy End Use Components']['Source Electricity'])."
              </td>
              <td>".number_format($source_vals['Total Source Energy End Use Components']['Source Natural Gas'])."
              </td>
              <td>".number_format($total_vals['Total Source Energy']['Total Energy'])."
              </td>
              <td>".number_format($area_vals['Total Building Area']['Area'])."
              </td>
              <td>".$total_vals['Total Source Energy']['Energy Per Total Building Area']."
              </td>
              <td>".$total_vals['Total Source Energy']['Energy Per Conditioned Building Area']."
              </td>
         
            </tr>";
?>
            </table>

          <div id="energy_per_total_building_area_chart" style="min-width: 400px; width: 50%; height: 500px; float: left;"></div>
          <div id="energy_per_conditioned_building_area_chart" style="min-width: 400px; width: 50%; height: 500px; float: left; margin-bottom: 50px;"></div>
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
     // Annual Total Site and Source Energy
     $(function () {
        $('#annula_energy_chart').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Annual Site and Source Energy Consumption (kBtu)'
            },
            xAxis: {
                categories: ['Total Site Energy', 'Total Source Energy' ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Energy Consumption (kBtu)'
                }
            },
            legend: {
                backgroundColor: '#FFFFFF',
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
<?php

echo <<<END
                series: [{
                name: 'Pumps',
				color: "orange",
                data: [{$site_vals['Pumps']['Electricity']}, {$source_vals['Pumps']['Source Electricity']}]
            }, {
                name: 'Fans',
				color: 'black',
                data: [{$site_vals['Fans']['Electricity']}, {$source_vals['Fans']['Source Electricity']}]
            }, {
                name: 'Cooling',
				color: 'green',
				data: [{$site_vals['Cooling']['Electricity']}, {$source_vals['Cooling']['Source Electricity']}]
            }, {
                name: 'Heating-Natural-Gas',
				color: 'red',
                data: [{$site_vals['Heating']['Natural Gas']}, {$source_vals['Heating']['Source Natural Gas']}]
            }, {
                name: 'Iterior Lighting',
				color: '#3388ff',
				data: [{$site_vals['Interior Lighting']['Electricity']}, {$source_vals['Interior Lighting']['Source Electricity']}]
            }, {
                name: 'Interior Equipment',
				color: 'purple',
                data: [{$site_vals['Interior Equipment']['Electricity']}, {$source_vals['Interior Equipment']['Source Electricity']}]
            }, {
                name: 'Exterior Lighting',
				visible: false,
                data: [{$site_vals['Exterior Lighting']['Electricity']}, {$source_vals['Exterior Lighting']['Source Electricity']}]
            }, {
                name: 'Heating-Electricity',
				 visible: false,
                data: [{$site_vals['Heating']['Electricity']}, {$source_vals['Heating']['Source Electricity']}]
            }, {
                name: 'Exterior Equipment',
 				visible: false,
                data: [{$site_vals['Exterior Equipment']['Electricity']}, {$source_vals['Exterior Equipment']['Source Electricity']}]
            }, {
                name: 'Heating Rejecation',
 				visible: false,
                data: [{$site_vals['Heating Rejecation']['Electricity']}, {$source_vals['Heating Rejecation']['Source Electricity']}]
            }, {
                name: 'Humidification',
				visible: false,
                data: [{$site_vals['Humidification']['Electricity']}, {$source_vals['Humidification']['Source Electricity']}]
            }, {
                name: 'Heat Recovery',
 				visible: false,
                data: [{$site_vals['Heat Recovery']['Electricity']}, {$source_vals['Heat Recovery']['Source Electricity']}]
            }, {
                name: 'Water Systems',
 				visible: false,
                data: [{$site_vals['Water Systems']['Electricity']}, {$source_vals['Water Systems']['Source Electricity']}]
            }, {
                name: 'Refrigeration',
 				visible: false,
                data: [{$site_vals['Refrigeration']['Electricity']}, {$source_vals['Refrigeration']['Source Electricity']}]
            }, {
                name: 'Generations',
 				visible: false,
                data: [{$site_vals['Generations']['Electricity']}, {$source_vals['Generations']['Source Electricity']}]
            }]
END;
?>
        });
    });

$(function () {
        $('#energy_per_total_building_area_chart').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Energy Per Total Building Area (kBtu/ft2)'
            },
            xAxis: {
                categories: ['Total Site Energy', 'Total Source Energy' ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Energy Consumption (kBtu/ft2)'
                }
            },
            legend: {
                backgroundColor: '#FFFFFF',
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
<?php
$site_ele_intensity =  $site_vals['Total End Uses']['Electricity'] /  $area_vals['Total Building Area']['Area'];
$site_gas_intensity =  $site_vals['Total End Uses']['Natural Gas'] / $area_vals['Total Building Area']['Area'];
$source_ele_intensity = $source_vals['Total Source Energy End Use Components']['Source Electricity'] /  $area_vals['Total Building Area']['Area'];
$source_gas_intensity = $source_vals['Total Source Energy End Use Components']['Source Natural Gas'] /  $area_vals['Total Building Area']['Area'];

echo <<<END
                series: [{
                name: 'Electricity',
                data: [$site_ele_intensity, $source_ele_intensity]
            }, {
                name: 'Natural Gas',
				color: 'red',
                data: [$site_gas_intensity, $source_gas_intensity]
            }]
END;
?>
        });
    });
    
    $(function () {
        $('#energy_per_conditioned_building_area_chart').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Energy Per Conditioned Building Area (kBtu/ft2s)'
            },
            xAxis: {
                categories: ['Total Site Energy', 'Total Source Energy' ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Energy Consumption (kBtu/ft2)'
                }
            },
            legend: {
                backgroundColor: '#FFFFFF',
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
<?php
$site_ele_intensity =  $site_vals['Total End Uses']['Electricity'] /  $area_vals['Net Conditioned Building Area']['Area'];
$site_gas_intensity =  $site_vals['Total End Uses']['Natural Gas'] / $area_vals['Net Conditioned Building Area']['Area'];
$source_ele_intensity = $source_vals['Total Source Energy End Use Components']['Source Electricity'] /  $area_vals['Net Conditioned Building Area']['Area'];
$source_gas_intensity = $source_vals['Total Source Energy End Use Components']['Source Natural Gas'] /  $area_vals['Net Conditioned Building Area']['Area'];
echo <<<END
                series: [{
                name: 'Electricity',
                data: [$site_ele_intensity, $source_ele_intensity]
            }, {
                name: 'Natural Gas',
				color: 'red',
                data: [$site_gas_intensity, $source_gas_intensity]
            }]
END;
?>
        });
    });
  
    

    
    </script>

  </body>
</html>
