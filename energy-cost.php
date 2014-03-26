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

$electric_tariff = $eeb->getValues('Tariff Report', 'BLDG101_ELECTRIC_RATE', 'Categories', '%');
$gas_tariff = $eeb->getValues('Tariff Report', 'BLDG101_GAS_RATE',  'Categories', '%');

#print_r($electric_tariff);

function printRow($Row) {
	foreach ($Row as $r) {
		if($r > 0){
			echo "<td>".number_format($r)."</td>";
		} else {
			echo "<td> 0.0 </td>";
		}
	}
}

function getRowData($Row) {
	echo '[';
	foreach ($Row as $r) {
		if($r < $Row['Sum']){
				echo "$r,";
		}
	}
	echo ']';
}

//printRow($electric_tariff['EnergyCharges (~~$~~)']);
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
  		
		<form action="energy-cost.php" method="post">
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
              <li class="active"><a href="./energy-cost.php">Energy Cost</a></li>
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
          <div id="electricity_monthly_cost_chart" style="min-width: 400px; height: 400px; margin: 40px auto"></div>
           <!-- Electricity Consumption Table -->
          <table class="table table-striped table-bordered" style="margin: 40px auto; width: 100%">
            <caption style="background: linear-gradient(to right, yellow, purple, #3399ff); color: #fff;"> <h3>Electricity Monthly Cost<h3> </caption>
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
				<th> Max
              </th>
            </tr>
            <tr class="table-row-even">
              <th> Energy Charges ($)
              </th>
             <?php printRow($electric_tariff['EnergyCharges (~~$~~)']); ?>
            </tr>
            <tr class="table-row-odd">
              <th> Demand Charges ($)
              </th>
              <?php printRow($electric_tariff['DemandCharges (~~$~~)']); ?>
            </tr>
            <tr class="table-row-even">
              <th> Service Charge ($)
              </th>
              <?php printRow($electric_tariff['ServiceCharges (~~$~~)']); ?>
            </tr>
            <tr class="table-row-odd">
              <th> Adjustment ($)
              </th>
              <?php printRow($electric_tariff['Adjustment (~~$~~)']); ?>
            </tr>
            <tr class="table-row-odd">
              <th> Taxes ($)
              </th>
              <?php printRow($electric_tariff['Taxes (~~$~~)']); ?>
            </tr>
              <tr class="table-row-odd">
              <th> Total ($)
              </th>
              <?php printRow($electric_tariff['Total (~~$~~)']); ?>
            </tr>
           
          </table>

          <div id="natural_gas_monthly_cost_chart" style="min-width: 400px; height: 400px; margin: 40px auto"></div>
          <table class="table table-striped table-bordered" style="margin: 40px auto; width: 100%">
            <caption style="background: linear-gradient(to right, red, #3399ff, yellow); color: #fff;"> <h3>Natural Gas Monthly Cost<h3> </caption>
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
				<th> Max
              </th>
            </tr>
            <tr class="table-row-even">
              <th> Energy Charges ($)
              </th>
             <?php printRow($gas_tariff['EnergyCharges (~~$~~)']); ?>
            </tr>
            <tr class="table-row-odd">
              <th> Demand Charges ($)
              </th>
              <?php printRow($gas_tariff['DemandCharges (~~$~~)']); ?>
            </tr>
            <tr class="table-row-even">
              <th> Service Charge ($)
              </th>
              <?php printRow($gas_tariff['ServiceCharges (~~$~~)']); ?>
            </tr>
            <tr class="table-row-odd">
              <th> Adjustment ($)
              </th>
              <?php printRow($gas_tariff['Adjustment (~~$~~)']); ?>
            </tr>
            <tr class="table-row-odd">
              <th> Taxes ($)
              </th>
              <?php printRow($gas_tariff['Taxes (~~$~~)']); ?>
            </tr>
              <tr class="table-row-odd">
              <th> Total ($)
              </th>
              <?php printRow($gas_tariff['Total (~~$~~)']); ?>
            </tr>
           
            </table>
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
              $('#electricity_monthly_cost_chart').highcharts({
                  chart: {
                      zoomType: 'xy'
                  },
                  title: {
                      text: 'Electricity Monthly Cost ($)'
                  },
                  xAxis: {
                      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Max']
                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: 'dollars ($) '
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
                      name: 'Energy Charges ($)',
                      type: 'column',
                      data: <?php echo getRowData($electric_tariff['EnergyCharges (~~$~~)']);?>
                  }, {
                      name: 'Demand Charges ($)',
                      type: 'column',
                      data: <?php echo getRowData($electric_tariff['DemandCharges (~~$~~)']);?>
                  }, {
                      name: 'Service Changes ($)',
                      type: 'column',
                      data: <?php echo getRowData($electric_tariff['ServiceCharges (~~$~~)']);?>
                  },{
                      name: 'Adjustment ($)',
                      type: 'column',
                      data: <?php echo getRowData($electric_tariff['Adjustment (~~$~~)']);?>
                  }, {
                      name: 'Taxes',
                      type: 'column',
                      data: <?php echo getRowData($electric_tariff['Taxes (~~$~~)']);?>
                  },{
                      name: 'Bill ($)',
                      type: 'spline',
                      marker: {
                          symbol: 'square'
                      },
                      data: <?php echo getRowData($electric_tariff['Total (~~$~~)']);?>
          
                  }]
              });
      });
    


      // monthly natural gas cost chart -->
      $(function () {
              $('#natural_gas_monthly_cost_chart').highcharts({
                  chart: {
                      zoomType: 'xy'
                  },
                  title: {
                      text: 'Natural Gas Monthly Cost ($)'
                  },
                  xAxis: {
                      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Max']
                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: 'dollars ($) '
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
                      name: 'Energy Charges ($)',
                      type: 'column',
                      data: <?php echo getRowData($gas_tariff['EnergyCharges (~~$~~)']);?>
                  }, {
                      name: 'Demand Charges ($)',
                      type: 'column',
                      data: <?php echo getRowData($gas_tariff['DemandCharges (~~$~~)']);?>
                  }, {
                      name: 'Service Changes ($)',
                      type: 'column',
                      data: <?php echo getRowData($gas_tariff['ServiceCharges (~~$~~)']);?>
                  },{
                      name: 'Adjustment ($)',
                      type: 'column',
                      data: <?php echo getRowData($gas_tariff['Adjustment (~~$~~)']);?>
                  }, {
                      name: 'Taxes',
                      type: 'column',
                      data: <?php echo getRowData($gas_tariff['Taxes (~~$~~)']);?>
                  },{
                      name: 'Bill ($)',
                      type: 'spline',
                      marker: {
                          symbol: 'square'
                      },
                      data: <?php echo getRowData($gas_tariff['Total (~~$~~)']);?>
          
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
