<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Building Energy Retrofit Game: Tutorial</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
        <!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <style>
          body {

		font-family: Verdana,Arial,Helvetica,sans-serif;
		font-size: 0.9em;
		font-size: 13px;
      }
#content{
	    padding-top: 30px;
        padding-bottom: 40px;
        padding-left: 20px;
        padding-right: 20px;
        width: 850px;
        margin:0 auto;
}
      .divFooter{
        width: 120px;
        height: 41px;
        margin: 0 auto;
      }
p{text-align: justify;}

    h2.title-label {
text-transform: uppercase;
font-size: 20px;
font-weight: bold;
color: white;
text-shadow: 2px 2px 8px #058e9b;
background-image: url('img/green-head.jpg');
background-position: 0 0;
background-repeat: no-repeat;
line-height: 37px;
text-indent: 18px;
height: 38px;
margin: 0;
}
ul
{
    list-style-type: none;
}

      .table{
        text-align: center;
      box-shadow: 0px 3px 5px #999;
      }

      .table-striped th {
         color: #333;
         font-size: 11px;
         text-align: center;
      }
    .table-striped td {
     text-align: center;
     font-size: 11px;
      }

      td[data-title]:hover{
        background-color: yellow;
        position: relative;
      }

      td[data-title]:hover:after {
  content: attr(data-title);
  padding: 4px 8px;
  color: #333;
  position: absolute;
  left: 0;
  top: 100%;
  white-space: nowrap;
  z-index: 20px;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  -moz-box-shadow: 0px 0px 4px #222;
  -webkit-box-shadow: 0px 0px 4px #222;
  box-shadow: 0px 0px 4px #222;
  background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);
  background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, #eeeeee),color-stop(1, #cccccc));
  background-image: -webkit-linear-gradient(top, #eeeeee, #cccccc);
  background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);
  background-image: -ms-linear-gradient(top, #eeeeee, #cccccc);
  background-image: -o-linear-gradient(top, #eeeeee, #cccccc);
}

    </style>
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="http://code.jquery.com/jquery-1.7.js"></script>
  <script src="js/bootstrap.min.js"></script>


</head>
<body>
	<div id="content">
<h2 class="title-label">Energy Retrofit Game: Tutorial</h2>
<br>
<p style="font-size: 15px;font-family:arial;">You have an annual budget of $50,000 to install retrofit measures, plus any capital you choose to invest from the energy cost savings from prior years.  You can install up to five measures at a time.  Once you’ve selected your measures, you simulate the building to calculate the energy and cost savings from the newly installed measures.  Once you’ve simulated the building, you can compare your results against your % energy savings and investment return goals.</p>
<h4 style="background-color:#00cc00;width: 129px;">How to play:</h4>
<p>Access the game at <a href="http://tools.eebhub.org/game">tools.eebhub.org/game</a></p>
<p>The current version of the game is pre-loaded with data from Building 101 at the Philadelphia Navy Yard, located on the Building Facts page.  This data includes architectural, mechanical, and other characteristics of Building 101.  This information may give you clues on which strategy to pursue.</p>
<p>
<dl>
  <dt style="color:#339933;font-size:14px">1. The game screen is a spreadsheet with the following columns:</dt>
  <dd>
    <br>
  	<table border="1" class="table table-borderd table-striped" id="table-column">
  		<tr><td>1</td><td>2</td><td>3~7</td><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td><td>13</td><td>14</td><td>15</td></tr>
  		<tr><td data-title="Column 1: The year when you will install the retrofit measure(s)">Year</td>
        <td data-title="Column 2: Capital available to purchase retrofit measure(s)">$ Available</td>
        <td data-title="Columns 3-7: A list of available retrofit measures. You can install one or more at any year.">Installed Measure 1~5</td>
        <td data-title="Column 8: The total cost of the chosen measure(s) to be installed">Installation cost</td>
        <td data-title="Column 9: Simulation button to determine the new annual energy use of the building after installing measure(s)">Simulation</td>
        <td data-title="Column 10: Remaining capital after installing the measure(s)">$ Remaining</td>
        <td data-title="Column 11: New annual energy cost after installing all measures from the beginning of the game">New cost (from simulation)</td>
        <td data-title="Column 12: Cumulative energy cost savings for simulation years">Cumulative Savings</td>
        <td data-title="Column 13: The remaining capital plus the cumulative energy cost savings">$ Remaining Capital + Cumulative Savings</td>
        <td data-title="Column 14: Interest if invested at 3% real return">3% real interest rate comparison</td>
        <td data-title="Column 15: % energy cost savings">% Savings</td></tr>	
  	</table>

    <div class="accordion" id="accordion1">
                <!-- Element Group Heading -->
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse1"> 
                    see definition of each column
                    </a>
                </div>
                <!-- Elements -->
                <div id="collapse1" class="accordion-body collapse">
                    <ul>
      <li>Column 1: The year when you will install the retrofit measure(s)</li>
    <li>Column 2: Capital available to purchase retrofit measure(s)</li>
    <li>Columns 3-7: A list of available retrofit measures. You can install one or more at any year.</li>
    <li>Column 8: The total cost of the chosen measure(s) to be installed</li>
    <li>Column 9: Simulation button to determine the new annual energy use of the building after installing measure(s)</li>
    <li>Column 10: Remaining capital after installing the measure(s)</li>
    <li>Column 11: New annual energy cost after installing all measures from the beginning of the game</li>
    <li>Column 12: Cumulative energy cost savings from all measures from the beginning of the game</li>
    <li>Column 13: The remaining capital plus the cumulative energy cost savings</li> 
    <li>Column 14: The amount of capital with interest if invested at 3% real return instead of investing in measures</li>
    <li>Column 15: % energy savings after installing all measures from the beginning of the game</li>
    </ul>
                </div>
                

            </div>

  </dd>
  <dt style="color:#339933;font-size:14px">2. Here are the steps to perform one demo year:</dt>
  <dd><ul><li>a. Set your cost-% savings target (say you want to accomplish 20% energy savings over the whole period of the game which is 10 years)</li>

<li>b.	In the Row of 2015, you have starting capital for investment of $50,000</li>

<li>c.	Within your capital budget, choose the retrofit measure(s) that you would like to install from retrofit-list in columns 3-7 (say Bathroom Lighting Upgrade)</li> 

<li>d.	There are two measures – the metered interval data and the energy audit – which are not installed in the building.   Instead, these provide you with more information to make strategic investment decisions.</li>  

<li>e.	After choosing the retrofit measure(s) , Column 8 “Installation Cost” will automatically change to the total cost of installation</li>

<li>f.	Click the “1st year” button to run the simulation (Note the pink tacking line at the top of the screen indicating whether the simulation is complete or not)</li>

<li>g.	When simulation is complete, columns 10-15 are automatically populated with simulation cost results</li>

<li>h.	Now you can compare the “remaining capital and cumulative savings” (Column 13) if it is higher than Column 14 and the “% savings” (Column 15) if it meets your target savings percentage that you set at the beginning</li></ul>
</dd>
<dt style="color:#339933;font-size:14px">3. Play the game for 10 years. </dt>
	<dd>To win this game, you need to have the value of Column 13 of the 10th year to be larger than the value in Column 14, and have the value in Column 15 to be the same or higher than your target percent savings.  You can also go for a higher savings target and see how the game would change if you track energy savings out to 15 years instead.  Enjoy!</dd>
</dl>
</p>
<br>
<div style="float:left;width:50%;">
  <div>
  <a href="index.php" class="btn btn-warning"> Back</a>
  </div>
</div>
<div style="float:left;width:50%;">
<div class="pull-right"><a href="building-fact.php" class="btn btn-success"><i class="icon-white icon-play"></i> Continue</a></div> 
</div>
</div>
<br><br><br><hr>
</body>
<footer>
  <div class="divFooter"><a href="http://tools.eebhub.org"><img src="http://www.eebhub.org/images/assets/eebhub-logo.png"/></a></div>
  <br><br>
</footer>
</html>
