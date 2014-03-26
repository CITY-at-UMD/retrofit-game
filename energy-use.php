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

$e_vals = $eeb->getValuesByMonthly('END USE ENERGY CONSUMPTION ELECTRICITY MONTHLY', 'Meter', '', '%');
$ng_vals = $eeb->getValuesByMonthly('END USE ENERGY CONSUMPTION NATURAL GAS MONTHLY', 'Meter', '', '%');
//print_r($ng_vals);

function printRow($row){
	foreach($row as $v) {
		if($v >=0) {
			echo "<td>".number_format($v)."</td>";
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
	    min-width: 1024px;
        background: linear-gradient(to bottom, #888, #fff);
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
              <li class="active"><a href="./energy-use.php">Energy Use</a></li>
              <li><a href="./zone-component-load.php">Zone Component Load</a></li>
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
 

      <!-- Tab Content -->
      
        <!-- Electricity Consumption Bar Chart -->
        <div id="Electricity-Consumption-Bar-Chart" style="width: 50%; min-width: 400px; height: 500px; float:left;"></div>
         
        <!-- Natural Gas Consumption Bar Chart -->
        <div id="Natural-Gas-Consumption-Bar-Chart" style="width: 50%; min-width: 400px; height: 500px; float: left; margin-bottom: 25px;"></div>

        <!-- Natural Gas Consumption Table -->
 <!--       <table class="table table-striped table-bordered" style="margin: 30px auto; max-width: 800px;">
          <caption style="background: #F5001D; color: #fff;"> <h3>Natural Gas Energy Consumption (MBtu)<h3> </caption>
          <tr id="table-row-head">
            <th> -
            </th>
            <th> Jan
            </th>
            <th> Feb
            </th>
            <th> Mar
            </th>
            <th> Apr
            </th>
            <th> May
            </th>
            <th> Jun
            </th>
            <th> Jul
            </th>
            <th> Aug
            </th>
            <th> Sep
            </th>
            <th> Oct
            </th>
            <th> Nov
            </th>
            <th> Dec
            </th>
            <th> Annual
            </th>
          </tr>
          <tr class="table-row-even">
            <th> Heating
            </th>
            <td> 1
            </td>
            <td> 2
            </td>
            <td> 3
            </td>
            <td> 4
            </td>
            <td> 5
            </td>
            <td> 6
            </td>
            <td> 7
            </td>
            <td> 8
            </td>
            <td> 9
            </td>
            <td> 10
            </td>
            <td> 11
            </td>
            <td> 12
            </td>
            <td> -
            </td>
          </tr>
        </table>-->

        <!-- Electricity Consumption Table -->
        <table class="table table-striped table-bordered" style="margin: 40px auto; width: 100%">
          <caption style="background: linear-gradient(to left, yellow, purple, red); color: #fff;"> <h3>Electricity Energy Consumption (kWh)<h3> </caption>
          <tr id="table-row-head">
            <th> -
            </th>
            <th> Jan
            </th>
            <th> Feb
            </th>
            <th> Mar
            </th>
            <th> Apr
            </th>
            <th> May
            </th>
            <th> Jun
            </th>
            <th> Jul
            </th>
            <th> Aug
            </th>
            <th> Sep
            </th>
            <th> Oct
            </th>
            <th> Nov
            </th>
            <th> Dec
            </th>
          </tr>
          <tr class="table-row-even">
            <th> Heating
            </th>
           	<?php printRow($e_vals['HEATING:ELECTRICITY']); ?>
          </tr>
          <tr class="table-row-odd">
            <th> Cooling
            </th>
            <?php printRow($e_vals['COOLING:ELECTRICITY']); ?>
          </tr>
          <tr class="table-row-even">
            <th> Interior Lighting
            </th>
           	<?php printRow($e_vals['INTERIORLIGHTS:ELECTRICITY']); ?>
          </tr>
          <tr class="table-row-odd">
            <th> Interior Equipment
            </th>
            <?php printRow($e_vals['INTERIOREQUIPMENT:ELECTRICITY']); ?>
          </tr>
          <tr class="table-row-even">
            <th> Fans
            </th>
             <?php printRow($e_vals['FANS:ELECTRICITY']); ?>
          </tr>
          <tr class="table-row-odd">
            <th> Pumps
            </th>
            <?php printRow($e_vals['PUMPS:ELECTRICITY']); ?>
          </tr>
          <tr class="table-row-even">
            <th> Heat Rejection
            </th>
            <?php printRow($e_vals['HEATREJECTION:ELECTRICITY']); ?>
          </tr>
          <tr class="table-row-odd">
            <th> Humidification
            </th>
            <?php printRow($e_vals['HUMIDIFIER:ELECTRICITY']); ?>
          </tr>
          <tr class="table-row-even">
            <th> Heat Recovery
            </th>
             <?php printRow($e_vals['HEATRECOVERY:ELECTRICITY']); ?>
          </tr>
			<tr class="table-row-odd">
		    <th> Water Systems
		    </th>
		    <?php printRow($e_vals['HUMIDIFIER:ELECTRICITY']); ?>

		  </tr>
		  <tr class="table-row-even">
		    <th> Regrigeration
		    </th>
		     <?php printRow($e_vals['HEATRECOVERY:ELECTRICITY']); ?>
		  </tr>
 		  <tr class="table-row-even">
		    <th> Generation
		    </th>
		     <?php printRow($e_vals['HEATRECOVERY:ELECTRICITY']); ?>
		  </tr>

          </table>

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
      // Eletricity Consumption Bar Chart Defination-->
      $(function () {
              $('#Electricity-Consumption-Bar-Chart').highcharts({
                  chart: {
                      type: 'column'
                  },
                  title: {
                      text: 'Electricity Consumption (kWh)'
                  },
                  xAxis: {
                      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: '(x0000) '
                      },
                      stackLabels: {
                          enabled: true,
						  rotation: -45,
						  y: -20,
                          style: {
                              fontWeight: 'bold',
                              color: (Highcharts.theme && Highcharts.theme.textColor) || 'grey'
                          }
                      }
                  },
                  legend: {
                      align: 'center',
                      x: 0,
                      verticalAlign: 'bottom',
                      y: 0,
                      floating: false,
                      backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                      borderColor: '#CCC',
                      borderWidth: 1,
                      shadow: true
                  },
                  tooltip: {
                      formatter: function() {
                          return '<b>'+ this.x +'</b><br/>'+
                              this.series.name +': '+ this.y +'<br/>'+
                              'Total: '+ this.point.stackTotal;
                      }
                  },
                  plotOptions: {
                      column: {
                          stacking: 'normal',
                          dataLabels: {
                              enabled: false,
                              color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                          }
                      }
                  },
                  series: [{
                      name: 'Pumps',
                
                      data: <?php printMonthlyData($e_vals['PUMPS:ELECTRICITY']);?>
                  },{
                      name: 'Fans',
                      data: <?php printMonthlyData($e_vals['FANS:ELECTRICITY']);?>
                  }, {
                      name: 'Cooling',
                      data: <?php printMonthlyData($e_vals['COOLING:ELECTRICITY']);?>
                  }, {
                      name: 'Interior Lighting',
                      data: <?php printMonthlyData($e_vals['INTERIORLIGHTS:ELECTRICITY']);?>
                  }, {
                      name: 'Interior Equipment',
                      data: <?php printMonthlyData($e_vals['INTERIOREQUIPMENT:ELECTRICITY']);?>
                  }, {
                      name: 'Heating',
                      color: 'red',
                       visible: false,
                      data: <?php printMonthlyData($e_vals['HEATING:ELECTRICITY']);?>
                  }, {
                      name: 'Exterior Lighting',
                 	  visible: false,
                      data: <?php printMonthlyData($e_vals['EXTERIORLIGHTS:ELECTRICITY']);?>
                  }, {
                      name: 'Heat Rejection',
                      color: 'orange',
	 				  visible: false,
                      data: <?php printMonthlyData($e_vals['HEATREJECTION:ELECTRICITY']);?>
                  }, {
                      name: 'Humidification',
                   visible: false,
                      data: <?php printMonthlyData($e_vals['HUMIDIFICATION:ELECTRICITY']);?>
                  }, {
                      name: 'Heating Recovery',
                      visible: false,
                      data: <?php printMonthlyData($e_vals['HEATINGRECOVERY:ELECTRICITY']);?>
                  },{
                      name: 'Water Systems',
                       visible: false,
                      data: <?php printMonthlyData($e_vals['WATERSYSTEMS:ELECTRICITY']);?>
                  }, {
                      name: 'Refrigeration',
                      visible: false,
                      data: <?php printMonthlyData($e_vals['REGRIGERATION:ELECTRICITY']);?>
                  }, {
                      name: 'Generation',
                      visible: false,
                      data: <?php printMonthlyData($e_vals['GENERATION:ELECTRICITY']);?>
                  }]
              });
      });

      // Natural Gas Consumption Bar Chart Defination-->
      $(function () {
              $('#Natural-Gas-Consumption-Bar-Chart').highcharts({
                  chart: {
                      type: 'column'
                  },
                  title: {
                      text: 'Natural Gas Consumption (MBtu)'
                  },
                  xAxis: {
                      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: '(x000,000)'
                      },
                      stackLabels: {
                          enabled: true,
                          style: {
                              fontWeight: 'bold',
                              color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                          }
                      }
                  },
                  legend: {
                      align: 'center',
                      x: 0,
                      verticalAlign: 'bottom',
                      y: 0,
                      floating: false,
                      backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                      borderColor: '#CCC',
                      borderWidth: 1,
                      shadow: true
                  },
                  tooltip: {
                      formatter: function() {
                          return '<b>'+ this.x +'</b><br/>'+
                              this.series.name +': '+ this.y +'<br/>'+
                              'Total: '+ this.point.stackTotal;
                      }
                  },
                  plotOptions: {
                      column: {
                          stacking: 'normal',
                          dataLabels: {
                              enabled: false,
                              color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                          }
                      }
                  },
                  series: [{
                      name: 'Heating',
                      color: 'red',
                      data: <?php printMonthlyData($ng_vals['HEATING:GAS']);?>
                  }]

              });
      });
    </script>
    <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        
          ga('create', 'UA-26348074-7', 'eebhub.org');
          ga('send', 'pageview');
        
        </script>

  </body>
</html>
