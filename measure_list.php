<?php 
require 'php/EEB_SQLITE3.php';

echo "<br><a href='tracking-sheet.php' > Go Back to tracking-sheet.php </a><br>";

// start a session
session_start();

$_SESSION['installed_measures'][$_SESSION['current_yr']] = $_POST['selected'];    // update the installed measure list by year
$user_dir = $_SESSION['user_dir'];                                                // the name of dir which result files is stored 

// update the eem version number
if($_SESSION['eem_cnt'] < 1) {       

    # initialize the eem version to 1
    copy('baseline.idf', "EEMs/$user_dir/temp.idf");
    echo $version = $_SESSION['eem_cnt'] = 1;   # eem version 
	
	// run autosizing to the baseline 
	//$cmd = 'ruby ./eeb_rb/autosize.rb';
	//echo $run = shell_exec($cmd);

} else {
 
    # Update the eem version number 
    $version = ++$_SESSION['eem_cnt'];
	
	echo "check pre_version: ", $pre_version = $version-1;
	# use the previous the version as temp.idf
    echo copy("EEMs/$user_dir/eem_{$pre_version}.idf", "EEMs/$user_dir/temp.idf");
}

// keep tracking the updated file names
$file_in = "EEMs/$user_dir/temp.idf";                       // the name of temporary file for modification
$file_out = "EEMs/$user_dir/improved_eem.idf";              // the name of the post-measured eem file
$file_save = "EEMs/$user_dir/eem_{$version}.idf";           // the name of the pre-measured eem file with the updated version number

/*
 *  function: reset a single measure field in idf file
 *  $file_source: input file name, type=>string
 *  $file_target: output file name, type=>string
 *  $header: field header in idf file, type=>string
 *  $replace_str: body of field in idf file, type=>string 
 */
function setMeasure($file_source, $file_target, $header, $replace_str) {

    $rh = fopen($file_source, 'rb');
    $wh = fopen($file_target, 'wb');

	// section indicators 
	$lighting_def_sch_section = false;

    if ($rh===false || $wh===false) {
		// error reading or opening file
		return true;
    }

    while (!feof($rh) && ($buffer = fgets($rh, 4096)) !== false) {

        // start of the section 
        $pattern = $header;
		if(preg_match($pattern, $buffer, $matches))
		{
			$lighting_def_sch_section = true;
		};	

		// Replace the default section
		if($lighting_def_sch_section == true&&!preg_match('/.*;.*/', $buffer, $matches)) {
			$buffer = '';
		}

		// end of the section of electric equipment  
		$pattern = '/.*;.*/';
		if($lighting_def_sch_section == true&&preg_match($pattern, $buffer, $matches))
		{

			$buffer = $replace_str;
			$lighting_def_sch_section = false;
		};

		// Write to file_target 
		if (fwrite($wh, $buffer) === FALSE) {
		   // 'Download error: Cannot write to file ('.$file_target.')';
		   return true;
		}
    }
    fclose($rh);
    fclose($wh);

    copy($file_target, $file_source);

    // No error
    return false;
}

/*
 *  function: add a new measure field at the end of idf file 
 *  $file_source: input file name, type=>string
 *  $file_target: output file name, type=>string
 *  $added_str: body of field in idf file, type=>string 
 */
function addMeasure($file_source, $file_target, $added_str) {

    $rh = fopen($file_source, 'rb');
    $wh = fopen($file_target, 'wb');

	// section indicators 
	$lighting_def_sch_section = false;


    if ($rh===false || $wh===false) {
		// error reading or opening file
		return true;
    }

    while (!feof($rh) && ($buffer = fgets($rh, 4096)) !== false) {


        // start of the section of lighting default schedule
        $pattern = $header;
		if(preg_match($pattern, $buffer, $matches))
		{
			$lighting_def_sch_section = true;
		};	


		// Replace the default lighting schedule
		if($lighting_def_sch_section == true&&!preg_match('/.*;.*/', $buffer, $matches)) {
			$buffer = '';
		}

		// end of the section of electric equipment  
		$pattern = '/.*;.*/';
		if($lighting_def_sch_section == true&&preg_match($pattern, $buffer, $matches))
		{

			$buffer = $replace_str;
			$lighting_def_sch_section = false;
		};

		// Write to file_target 
		if (fwrite($wh, $buffer) === FALSE) {
		   // 'Download error: Cannot write to file ('.$file_target.')';
		   return true;
		}
    }
    fclose($rh);
    fclose($wh);

    copy($file_target, $file_source);

    // No error
    return false;
}


// Tracking-Sheet Cost Variables
$installationCost = 0;

$cur_model = $_SESSION['cur_model']="eem_{$version}"; //assign the current model
$selectedValues = $_POST['selected'];                 // The measure(s) that is/are ready to be installed 

// iterate the selected measure list in order to installation
for( $cnt = 0; $cnt < count($selectedValues); $cnt++)  {

    $measureCost = 0;

    switch ($selectedValues[$cnt]) {
        case 'oblsChecked':
####=======================================================================================================================###############
# OCCUPANCY SENSING
setMeasure($file_in, $file_out, '/.*Medium Office_Bldg_Light Default Schedule,.*!- Name.*/', "    Medium Office_Bldg_Light Default Schedule,  !- Name
    Fraction,                !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    07:00,                   !- Time 1
    0.05,                    !- Value Until Time 1
    08:00,                   !- Time 2
    0.2,                     !- Value Until Time 2
    09:00,                   !- Time 3
    0.33,                     !- Value Until Time 3
    10:00,                   !- Time 4
    0.39,                     !- Value Until Time 4
    12:00,                   !- Time 5
    0.42,                     !- Value Until Time 5
    13:00,                   !- Time 6
    0.4,                     !- Value Until Time 6
    15:00,                   !- Time 7
    0.42,                     !- Value Until Time 7
    16:00,                   !- Time 8
    0.4,                     !- Value Until Time 8
    17:00,                   !- Time 9
    0.38,                    !- Value Until Time 9
    18:00,                   !- Time 10
    0.32,                    !- Value Until Time 10
    19:00,                   !- Time 11
    0.23,                    !- Value Until Time 11
    20:00,                   !- Time 12
    0.18,                    !- Value Until Time 12
    22:00,                   !- Time 13
    0.16,                    !- Value Until Time 13
    24:00,                   !- Time 14
    0.05;                    !- Value Until Time 14\n");

# LIGHTING Rule Day 1 SCHEDULE
setMeasure($file_in, $file_out, '/.*Medium Office_Bldg_Light Rule 1 Day Schedule,.*!- Name.*/', "    Medium Office_Bldg_Light Rule 1 Day Schedule,  !- Name
    Fraction,                !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    24:00,                   !- Time 1
    0.05;                    !- Value Until Time 1\n");


# LIGHTING Rule Day 2 SCHEDULE
setMeasure($file_in, $file_out, '/.*Medium Office_Bldg_Light Rule 2 Day Schedule,.*!- Name.*/', "    Medium Office_Bldg_Light Rule 2 Day Schedule,  !- Name
    Fraction,                !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    24:00,                   !- Time 1
    0.05;                    !- Value Until Time 1\n");

                $_SESSION['measureFinished']['oblsChecked'] = 'finished';
                echo "<br>!!!!!!!!!!!!!!!!! oblsChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                $measureCost = 50000;
            break;

          
 case 'emergencyLightingChecked':

####=======================================================================================================================###############
# EMERGENCY LIGHTING SCHEDULE
setMeasure($file_in, $file_out, '/.*Medium Office_Bldg_Light Default Schedule,.*!- Name.*/', "    Medium Office_Bldg_Light Default Schedule,  !- Name
    Fraction,                !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    07:00,                   !- Time 1
    0.10,                    !- Value Until Time 1
    08:00,                   !- Time 2
    0.17,                     !- Value Until Time 2
    09:00,                   !- Time 3
    0.30,                     !- Value Until Time 3
    10:00,                   !- Time 4
    0.36,                     !- Value Until Time 4
    12:00,                   !- Time 5
    0.39,                     !- Value Until Time 5
    13:00,                   !- Time 6
    0.37,                     !- Value Until Time 6
    15:00,                   !- Time 7
    0.39,                     !- Value Until Time 7
    16:00,                   !- Time 8
    0.37,                     !- Value Until Time 8
    17:00,                   !- Time 9
    0.35,                    !- Value Until Time 9
    18:00,                   !- Time 10
    0.29,                    !- Value Until Time 10
    19:00,                   !- Time 11
    0.20,                    !- Value Until Time 11
    20:00,                   !- Time 12
    0.15,                    !- Value Until Time 12
    22:00,                   !- Time 13
    0.13,                    !- Value Until Time 13
    24:00,                   !- Time 14
    0.10;                    !- Value Until Time 14\n");

# LIGHTING Rule Day 1 SCHEDULE
setMeasure($file_in, $file_out, '/.*Medium Office_Bldg_Light Rule 1 Day Schedule,.*!- Name.*/', "    Medium Office_Bldg_Light Rule 1 Day Schedule,  !- Name
    Fraction,                !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    24:00,                   !- Time 1
    0.09;                    !- Value Until Time 1\n");


# LIGHTING Rule Day 2 SCHEDULE
setMeasure($file_in, $file_out, '/.*Medium Office_Bldg_Light Rule 2 Day Schedule,.*!- Name.*/', "    Medium Office_Bldg_Light Rule 2 Day Schedule,  !- Name
    Fraction,                !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    24:00,                   !- Time 1
    0.09;                    !- Value Until Time 1\n");

                $_SESSION['measureFinished']['emergencyLightingChecked'] = 'finished';
                echo "<br>!!!!!!!!!!!!!!!!! emergencyLightingChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                $measureCost = 40000;
            break;

             case 'plugLoadChecked':

####=======================================================================================================================###############
# Plug Load Control
setMeasure($file_in, $file_out, '/.*Medium Office_Bldg_Equip Default Schedule,.*!- Name.*/', "    Medium Office_Bldg_Equip Default Schedule,  !- Name
    Fraction,                !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    07:00,                   !- Time 1
    0.25,                     !- Value Until Time 1
    8:00,                   !- Time 2
    0.57,                     !- Value Until Time 2
    9:00,                   !- Time 3
    0.65,                     !- Value Until Time 3
    10:00,                   !- Time 4
    0.78,                     !- Value Until Time 4
    12:00,                   !- Time 5
    0.82,                     !- Value Until Time 5
    13:00,                   !- Time 6
    0.8,                     !- Value Until Time 6
    16:00,                   !- Time 7
    0.82,                     !- Value Until Time 7
    17:00,                   !- Time 8
    0.79,                     !- Value Until Time 8
    18:00,                   !- Time 9
    0.66,                     !- Value Until Time 9
    19:00,                   !- Time 10
    0.6,                     !- Value Until Time 10
    20:00,                   !- Time 11
    0.52,                     !- Value Until Time 11
    24:00,                   !- Time 12
    0.25;                     !- Value Until Time 12\n");


# EQUIP Rule Day 1 SCHEDULE
setMeasure($file_in, $file_out, '/.*Medium Office_Bldg_Equip Rule 1 Day Schedule,.*!- Name.*/', "    Medium Office_Bldg_Equip Rule 1 Day Schedule,  !- Name
    Fraction,                !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    24:00,                   !- Time 1
    0.25;                    !- Value Until Time 1\n");

# EQUIP Rule Day 2 SCHEDULE
setMeasure($file_in, $file_out, '/.*Medium Office_Bldg_Equip Rule 2 Day Schedule,.*!- Name.*/', "    Medium Office_Bldg_Equip Rule 2 Day Schedule,  !- Name
    Fraction,                !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    24:00,                   !- Time 1
    0.25;                    !- Value Until Time 1\n");

                  echo "<br>!!!!!!!!!!!!!!!!! plugLoadChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                  $_SESSION['measureFinished']['plugLoadChecked'] = 'finished';
                  $measureCost = 60000;

              break;

              case 'OfficefixturedChecked':
####=======================================================================================================================###############
# LIGHTING POWER DENSITY - OFFICE FIXTURE
setMeasure($file_in, $file_out, '/.*Lights,.*/', "  Lights,
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding Lights,  !- Name
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding,  !- Zone or ZoneList Name
    Medium Office_Bldg_Light,!- Schedule Name
    Watts/Area,              !- Design Level Calculation Method
    ,                        !- Lighting Level {W}
    17.06279,                !- Watts per Zone Floor Area {W/m2}
    ,                        !- Watts per Person {W/person}
    ,                        !- Return Air Fraction
    0.42,                    !- Fraction Radiant
    0.18,                    !- Fraction Visible
    1;                       !- Fraction Replaceable\n");

                    echo "<br>!!!!!!!!!!!!!!!!! OfficeFixturedChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                    $_SESSION['measureFinished']['OfficefixturedChecked'] = 'finished';
                    $measureCost = 200000;
                break;

              case 'bathroomFixturedChecked':
####=======================================================================================================================###############
# LIGHTING POWER DENSITY - BATHROOM
setMeasure($file_in, $file_out, '/.*Lights,.*/', "  Lights,
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding Lights,  !- Name
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding,  !- Zone or ZoneList Name
    Medium Office_Bldg_Light,!- Schedule Name
    Watts/Area,              !- Design Level Calculation Method
    ,                        !- Lighting Level {W}
    21.34795,                !- Watts per Zone Floor Area {W/m2}
    ,                        !- Watts per Person {W/person}
    ,                        !- Return Air Fraction
    0.42,                    !- Fraction Radiant
    0.18,                    !- Fraction Visible
    1;                       !- Fraction Replaceable\n");

                    echo "<br>!!!!!!!!!!!!!!!!! bathroomFixturedChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                    $_SESSION['measureFinished']['bathroomFixturedChecked'] = 'finished';
                    $measureCost = 8000;
                break;

                 case 'energyStarEquipmentChecked':
####=======================================================================================================================###############
# EnergyStar Equipment
setMeasure($file_in, $file_out, '/.*ElectricEquipment,.*/', " ElectricEquipment,
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding Electric Equipment,  !- Name
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding,  !- Zone or ZoneList Name
    Medium Office_Bldg_Equip,!- Schedule Name
    Watts/Area,              !- Design Level Calculation Method
    ,                        !- Design Level {W}
    9.149315,                !- Watts per Zone Floor Area {W/m2}
    ,                        !- Watts per Person {W/person}
    ,                        !- Fraction Latent
    0.2,                        !- Fraction Radiant
    ;                        !- Fraction Lost\n");

             echo "<br>!!!!!!!!!!!!!!!!! energyStarEquipmentChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
             $_SESSION['measureFinished']['energyStarEquipmentChecked'] = 'finished';
             $measureCost = 15000;          
 
                    break;
					
                     

                    case 'bmsSBChecked':
####=======================================================================================================================###############
# HEATING SETPOINT SCHEDULE
setMeasure($file_in, $file_out, '/.*Heating Sch Default,.*!- Name.*/', "    Heating Sch Default,     !- Name
    Temperature 2,           !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    7:00,                   !- Time 1
    13,                      !- Value Until Time 1
    19:00,                   !- Time 2
    21,                      !- Value Until Time 2
    24:00,                   !- Time 3
    13;                      !- Value Until Time 3\n");



####=======================================================================================================================###############
# COOLING SETPOINT SCHEDULE
setMeasure($file_in, $file_out, '/.*Cooling Sch Default,.*!- Name.*/', "    Cooling Sch Default,     !- Name
    Temperature 3,           !- Schedule Type Limits Name
    No,                      !- Interpolate to Timestep
    7:00,                    !- Time 1
    33,                      !- Value Until Time 1
    19:00,                   !- Time 2
    24,                      !- Value Until Time 2
    24:00,                   !- Time 3
    33;                      !- Value Until Time 3\n");

####=======================================================================================================================###############
# Ventilation Setback

setMeasure($file_in, $file_out, '/.*Fan Variable Volume 1,.*!- Name.*/', "  Fan Variable Volume 1,   !- Name
    VentilationSetback,      !- Availability Schedule Name
    0.5,                     !- Fan Efficiency
    1200,                    !- Pressure Rise {Pa}
    24.58337,                !- Maximum Flow Rate {m3/s}
    Fraction,                !- Fan Power Minimum Flow Rate Input Method
    0,                       !- Fan Power Minimum Flow Fraction
    0,                       !- Fan Power Minimum Air Flow Rate {m3/s}
    0.93,                    !- Motor Efficiency
    1,                       !- Motor In Airstream Fraction
    0.040759894,             !- Fan Power Coefficient 1
    0.08804497,              !- Fan Power Coefficient 2
    -0.07292612,             !- Fan Power Coefficient 3
    0.943739823,             !- Fan Power Coefficient 4
    0,                       !- Fan Power Coefficient 5
    Node 40,                 !- Air Inlet Node Name
    Node 32;                 !- Air Outlet Node Name\n");


            $_SESSION['measureFinished']['bmsSBChecked'] = 'finished';
            echo "<br>!!!!!!!!!!!!!!!!! bmsSBChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> "; 
                        $measureCost = 50000;
                        break;

                        case 'enclosureRecommisChecked':

####=======================================================================================================================###############
# INFILTRATION:  ENCLOSURE RECOMMISSIONING MEASURE
setMeasure($file_in, $file_out, '/.*ZoneInfiltration:DesignFlowRate,.*/', "ZoneInfiltration:DesignFlowRate,
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding Infiltration,  !- Name
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding,  !- Zone or ZoneList Name
    Medium Office_Infil_Quarter_On,  !- Schedule Name
    Flow/ExteriorArea,       !- Design Flow Rate Calculation Method
    ,                        !- Design Flow Rate {m3/s}
    ,                        !- Flow per Zone Floor Area {m3/s-m2}
    ,                        !- Flow per Exterior Surface Area {m3/s-m2}
    0.135,                    !- Air Changes per Hour {1/hr}
    ,                        !- Constant Term Coefficient
    ,                        !- Temperature Term Coefficient
    ,                        !- Velocity Term Coefficient
    ;                        !- Velocity Squared Term Coefficient\n");


                             echo "<br>!!!!!!!!!!!!!!!!! enclosureRecommisChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                             $_SESSION['measureFinished']['enclosureRecommisChecked'] = 'finished';
                             $measureCost = 25000;
                        break;



                     case 'doorWeatherizationChecked':

####=======================================================================================================================###############
# INFILTRATION - WEATHERIZATION
setMeasure($file_in, $file_out, '/.*ZoneInfiltration:DesignFlowRate,.*/', "ZoneInfiltration:DesignFlowRate,
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding Infiltration,  !- Name
    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding,  !- Zone or ZoneList Name
    Medium Office_Infil_Quarter_On,  !- Schedule Name
    Flow/ExteriorArea,       !- Design Flow Rate Calculation Method
    ,                        !- Design Flow Rate {m3/s}
    ,                        !- Flow per Zone Floor Area {m3/s-m2}
    ,                        !- Flow per Exterior Surface Area {m3/s-m2}
    0.14,                    !- Air Changes per Hour {1/hr}
    ,                        !- Constant Term Coefficient
    ,                        !- Temperature Term Coefficient
    ,                        !- Velocity Term Coefficient
    ;                        !- Velocity Squared Term Coefficient\n");


             				echo "<br>!!!!!!!!!!!!!!!!! doorWeatherizationChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
							$_SESSION['measureFinished']['doorWeatherizationChecked'] = 'finished';
							$measureCost = 5000;
                        break;



####=======================================================================================================================###############
# WALL CONSTRUCTION:
                        case 'wallInsulation1Checked':

# THE R-10 MEASURE
setMeasure($file_in, $file_out, '/.*Wall Insulation \[39\],.*!- Name.*/', "    Wall Insulation [39],    !- Name
    MediumRough,             !- Roughness
    0.07925,                 !- Thickness {m}
    0.045,                   !- Conductivity {W/m-K}
    265,                     !- Density {kg/m3}
    836.8,                   !- Specific Heat {J/kg-K}
    0.9,                     !- Thermal Absorptance
    0.7,                     !- Solar Absorptance
    0.7;                     !- Visible Absorptance\n");

setMeasure($file_in, $file_out, '/.*ASHRAE_189.1-2009_ExtWall_SteelFrame_ClimateZone 4-8,.*!- Name.*/', "    ASHRAE_189.1-2009_ExtWall_SteelFrame_ClimateZone 4-8,  !- Name
    Wall Insulation [39],   !- Outside Layer
    Bldg101BrickExtWall;    !- Layer 1\n");




                            echo "<br>!!!!!!!!!!!!!!!!! wallInsulation1Checked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                            $_SESSION['measureFinished']['wallInsulation1Checked'] = 'finished';
                            $measureCost = 100000;

                        break;


                        case 'wallInsulation2Checked':
setMeasure($file_in, $file_out, '/.*ASHRAE_189.1-2009_ExtWall_SteelFrame_ClimateZone 4-8,.*!- Name.*/', "    ASHRAE_189.1-2009_ExtWall_SteelFrame_ClimateZone 4-8,  !- Name
    Wall Insulation [39],   !- Outside Layer
    Bldg101BrickExtWall;    !- Layer 1\n");

# R-20 MEASURE
setMeasure($file_in, $file_out, '/.*Wall Insulation \[39\],.*!- Name.*/', "    Wall Insulation [39],    !- Name
    MediumRough,             !- Roughness
    0.1585,                  !- Thickness {m}
    0.045,                   !- Conductivity {W/m-K}
    265,                     !- Density {kg/m3}
    836.8,                   !- Specific Heat {J/kg-K}
    0.9,                     !- Thermal Absorptance
    0.7,                     !- Solar Absorptance
    0.7;                     !- Visible Absorptances\n");

                              echo "<br>!!!!!!!!!!!!!!!!! wallInsulation2Checked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                              $_SESSION['measureFinished']['wallInsulation2Checked'] = 'finished';
                              $measureCost = 130000;
                        break;




                        case 'roofInsulationChecked':
####=======================================================================================================================###############
#setMeasure($file_in, $file_out, '/.*ZoneInfiltration:DesignFlowRate,.*/', "ZoneInfiltration:DesignFlowRate,
#    ASHRAE_90.1-2004 ClimateZone 1-8 MediumOffice WholeBuilding Infiltration,  !- Name


# ROOF CONSTRUCTION

setMeasure($file_in, $file_out, '/.*Glass fibre\/wool - wool at 10C degrees_0.192303400000769,.*!- Material name.*/', "Glass fibre/wool - wool at 10C degrees_0.192303400000769,  !- Material name
   Rough,                                         !- Roughness
   0.3226,                                        !- Thickness {m}
   0.037,                                         !- Conductivity {w/m-K}
   16,                                            !- Density {kg/m3}
   840,                                           !- Specific Heat {J/kg-K}
   0.9,                                           !- Thermal Emittance
   0.6,                                           !- Solar Absorptance
   0.6;                                           !- Visible Absorptance\n");

                            echo "<br>!!!!!!!!!!!!!!!!! roofInsulationChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                            $_SESSION['measureFinished']['roofInsulationChecked'] = 'finished';
                            $measureCost = 50000;

                        break;

                        case 'windowsUpgradelChecked':
####=======================================================================================================================###############
# WINDOW CONSTRUCTION: WINDOW REPLACEMENT
setMeasure($file_in, $file_out, '/.*WindowMaterial:SimpleGlazingSystem,.*/', "WindowMaterial:SimpleGlazingSystem,
    Low-E Glass,             !- Name
    1.34,                   !- U-Factor {W/m2-K}
    0.35;                    !- Solar Heat Gain Coefficient\n");


                             echo "<br>!!!!!!!!!!!!!!!!! windowsUpgradelChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
                             $_SESSION['measureFinished']['windowsUpgradelChecked'] = 'finished';
                             $measureCost = 100000;
                        break;

       case 'windowsFilmChecked':
####=======================================================================================================================###############
# WINDOW CONSTRUCTION: WINDOW FILM
setMeasure($file_in, $file_out, '/.*WindowMaterial:SimpleGlazingSystem,.*/', "WindowMaterial:SimpleGlazingSystem,
    Low-E Glass,             !- Name
    2.7,                   !- U-Factor {W/m2-K}
    0.35;                    !- Solar Heat Gain Coefficient\n");


							echo "<br>!!!!!!!!!!!!!!!!! windowsFilmChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
							$_SESSION['measureFinished']['windowsFilmChecked'] = 'finished';
							$measureCost = 25000;
                        break;


             

                        case 'sysEffChecked':
####=======================================================================================================================###############
# Condensing Unit Replacement

// get condensing unit sizing
echo $sql_file1="./ENERGYPLUS/EEMs/{$user_dir}/eem_{$pre_version}.idf/EnergyPlusPreProcess/EnergyPlus-0/eplusout.sql";
$eeb0 = new EEB_SQLITE3("$sql_file1");
$cc = $eeb0->getValues('EquipmentSummary', 'Entire Facility', 'Cooling Coils','%');
print_r($cc);
// convert btu per hour to watt
echo "check", $unit_cap = $cc['COIL COOLING DX TWO SPEED 1']['Nominal Total Capacity']*0.29307107;


setMeasure($file_in, $file_out, '/.*Coil Cooling DX Two Speed 1,.*!- Name.*/', "     Coil Cooling DX Two Speed 1,  !- Name
    Always On Discrete,      !- Availability Schedule Name
    $unit_cap,                !- Rated High Speed Total Cooling Capacity {W}
    0.67608,                 !- Rated High Speed Sensible Heat Ratio
    4.37,                    !- Rated High Speed COP {W/W}
    6.66747,                 !- Rated High Speed Air Flow Rate {m3/s}
    ,                        !- Unit Internal Static Air Pressure {Pa}
    Node 38,                 !- Air Inlet Node Name
    Node 39,                 !- Air Outlet Node Name
    Curve Biquadratic 1,     !- Total Cooling Capacity Function of Temperature Curve Name
    Curve Quadratic 1,       !- Total Cooling Capacity Function of Flow Fraction Curve Name
    Curve Biquadratic 2,     !- Energy Input Ratio Function of Temperature Curve Name
    Curve Quadratic 2,       !- Energy Input Ratio Function of Flow Fraction Curve Name
    Curve Quadratic 3,       !- Part Load Fraction Correlation Curve Name
    55184.15938,             !- Rated Low Speed Total Cooling Capacity {W}
    0.69,                    !- Rated Low Speed Sensible Heat Ratio
    5.37,                    !- Rated Low Speed COP {W/W}
    2.22227,                 !- Rated Low Speed Air Flow Rate {m3/s}
    Curve Biquadratic 3,     !- Low Speed Total Cooling Capacity Function of Temperature Curve Name
    Curve Biquadratic 4,     !- Low Speed Energy Input Ratio Function of Temperature Curve Name
    ,                        !- Condenser Air Inlet Node Name
    AirCooled,               !- Condenser Type
    0,                       !- High Speed Evaporative Condenser Effectiveness {dimensionless}
    ,                        !- High Speed Evaporative Condenser Air Flow Rate {m3/s}
    ,                        !- High Speed Evaporative Condenser Pump Rated Power Consumption {W}
    0,                       !- Low Speed Evaporative Condenser Effectiveness {dimensionless}
    ,                        !- Low Speed Evaporative Condenser Air Flow Rate {m3/s}
    ,                        !- Low Speed Evaporative Condenser Pump Rated Power Consumption {W}
    ,                        !- Supply Water Storage Tank Name
    ,                        !- Condensate Collection Water Storage Tank Name
    10,                      !- Basin Heater Capacity {W/K}
    2;                       !- Basin Heater Setpoint Temperature {C};");

             				echo "<br>!!!!!!!!!!!!!!!!! sysEffChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
			   $_SESSION['measureFinished']['sysEffChecked'] = 'finished';
		           $addCondensingUnitCost = true;
                        break;


						case 'condensingBoilerChecked':
####=======================================================================================================================###############
# BOILER CAPACITY AND EFFICIENCY

// get boiler sizing
echo $sql_file1="./ENERGYPLUS/EEMs/{$user_dir}/eem_{$pre_version}.idf/EnergyPlusPreProcess/EnergyPlus-0/eplusout.sql";
$eeb0 = new EEB_SQLITE3("$sql_file1");
$cp = $eeb0->getValues('EquipmentSummary', 'Entire Facility', 'Central Plant','%');

// convert btu per hour to watt
$boiler_cap = $cp['BOILER HOT WATER 1']['Nominal Capacity']*0.29307107;

setMeasure($file_in, $file_out, '/.*Boiler Hot Water 1,.*!- Name.*/', "    Boiler Hot Water 1,      !- Name
    NaturalGas,              !- Fuel Type
    $boiler_cap,               !- Nominal Capacity {W}
    0.93,                    !- Nominal Thermal Efficiency
    LeavingBoiler,           !- Efficiency Curve Temperature Evaluation Variable
    CondensingBoilerEff,       !- Normalized Boiler Efficiency Curve Name
    60,                      !- Design Water Outlet Temperature {C}
    6.16016E-003,            !- Design Water Flow Rate {m3/s}
    0,                       !- Minimum Part Load Ratio
    1,                       !- Maximum Part Load Ratio
    1,                       !- Optimum Part Load Ratio
    Node 43,                 !- Boiler Water Inlet Node Name
    Node 48,                 !- Boiler Water Outlet Node Name
    99,                      !- Water Outlet Upper Temperature Limit {C}
    ConstantFlow,            !- Boiler Flow Mode
    0,                       !- Parasitic Electric Load {W}
    1;                       !- Sizing Factor\n");

setMeasure($file_in, $file_out, '/.*Curve:Cubic,.*/', "    Curve:Biquadratic,
	CondensingBoilerEff, !- Name
	1.124970374, !- Coefficient1 Constant
	0.014963852, !- Coefficient2 x
	-0.02599835, !- Coefficient3 x**2
	0.0, !- Coefficient4 y
	-1.40464E-6, !- Coefficient5 y**2
	-0.00153624, !- Coefficient6 x*y
	0.1, !- Minimum Value of x
	1.0, !- Maximum Value of x
	30.0, !- Minimum Value of y
	85.0; !- Maximum Value of y\n");

		         echo "<br>!!!!!!!!!!!!!!!!! condensingBoilerChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
		         $_SESSION['measureFinished']['condensingBoilerChecked'] = 'finished';
		         $addCondensingBoilerCost = true;

             break;

case 'airEconChecked':
####=======================================================================================================================###############
# OUTDOOR AIR ECNOMIZER
setMeasure($file_in, $file_out, '/.*Controller Outdoor Air 1,.*!- Name,$/', "  Controller Outdoor Air 1,               !- Name
  Node 37,                                !- Relief Air Outlet Node Name
  Node 31,                                !- Return Air Node Name
  Node 38,                                !- Mixed Air Node Name
  Node 36,                                !- Actuator Node Name
  Autosize,                               !- Minimum Outdoor Air Flow Rate {m3/s}
  Autosize,                               !- Maximum Outdoor Air Flow Rate {m3/s}
  FixedDryBulb,                           !- Economizer Control Type
  ModulateFlow,                           !- Economizer Control Action Type
  28,                                     !- Economizer Maximum Limit Dry-Bulb Temperature {C}
  64000,                                  !- Economizer Maximum Limit Enthalpy {J/kg}
  ,                                       !- Economizer Maximum Limit Dewpoint Temperature {C}
  ,                                       !- Electronic Enthalpy Limit Curve Name
  -100,                                   !- Economizer Minimum Limit Dry-Bulb Temperature {C}
  NoLockout,                              !- Lockout Type
  FixedMinimum,                           !- Minimum Limit Type
  ,                                       !- Minimum Outdoor Air Schedule Name
  ,                                       !- Minimum Fraction of Outdoor Air Schedule Name
  ,                                       !- Maximum Fraction of Outdoor Air Schedule Name
  Controller Mechanical Ventilation 1,    !- Mechanical Ventilation Controller Name
  ,                                       !- Time of Day Economizer Control Schedule Name
  No,                                     !- High Humidity Control
  ,                                       !- Humidistat Control Zone Name
  ,                                       !- High Humidity Outdoor Air Flow Ratio
  Yes,                                    !- Control High Indoor Humidity Based on Outdoor Humidity Ratio
  BypassWhenOAFlowGreaterThanMinimum;     !- Heat Recovery Bypass Control Type\n");
             
				echo "<br>!!!!!!!!!!!!!!!!! airEconChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
           		$_SESSION['measureFinished']['airEconChecked'] = 'finished';
                      
                        $addAirEconCost = true;        
            


           	break;

case 'daylightDimmingChecked':
$dayLightDiming = "! EEM 
! DAYLIGHT DIMMING       FOR Proposed
!
!

!(NOTE!!! THE X,Y,Z coordinates should be at the work plane (1 meter) at the center of each zone)
  Daylighting:Controls, 
  Thermal Zone 19,                            !- Zone Name
  1,                                          !- Total Daylighting Reference Points
  45,                                        !- X-coordinate of first reference point {m}
  -2,                                       !- Y-coordinate of first reference point {m}
  10.0,                                       !- Z-coordinate of first reference point {m}
  ,                                           !- X-coordinate of second reference point {m}
  ,                                           !- Y-coordinate of second reference point {m}
  ,                                           !- Z-coordinate of second reference point {m}
  0.66,                                       !- Fraction of zone controlled by first reference point
  0,                                          !- Fraction of zone controlled by 2nd reference point
  500,                                        !- Illuminance setpoint at first reference point {lux}
  ,                                           !- Illuminance setpoint at second reference point {lux}
  1,                                          !- Lighting control type
  0,                                          !- view azimuth angle
  22,                                         !- Maximum allowable discomfort glare index
  0.1,                                        !- Minimum input power fraction for continuous dimming control
  0.1,                                        !- Minimum light output fraction for continuous dimming control
  0,                                          !- Number of steps (excluding off) for stepped control
  1;                                          !- Probability lighting will be reset when needed in manual stepped control


!(NOTE!!! THE X,Y,Z coordinates should be at the work plane (1 meter) at the center of each zone)
  Daylighting:Controls, 
  Thermal Zone 23,                            !- Zone Name
  1,                                          !- Total Daylighting Reference Points
  45,                                        !- X-coordinate of first reference point {m}
  -2,                                       !- Y-coordinate of first reference point {m}
  6.0,                                        !- Z-coordinate of first reference point {m}
  ,                                           !- X-coordinate of second reference point {m}
  ,                                           !- Y-coordinate of second reference point {m}
  ,                                           !- Z-coordinate of second reference point {m}
  0.66,                                       !- Fraction of zone controlled by first reference point
  0,                                          !- Fraction of zone controlled by 2nd reference point
  500,                                        !- Illuminance setpoint at first reference point {lux}
  ,                                           !- Illuminance setpoint at second reference point {lux}
  1,                                          !- Lighting control type
  0,                                          !- view azimuth angle
  22,                                         !- Maximum allowable discomfort glare index
  0.1,                                        !- Minimum input power fraction for continuous dimming control
  0.1,                                        !- Minimum light output fraction for continuous dimming control
  0,                                          !- Number of steps (excluding off) for stepped control
  1;                                          !- Probability lighting will be reset when needed in manual stepped control


!(NOTE!!! THE X,Y,Z coordinates should be at the work plane (1 meter) at the center of each zone)
  Daylighting:Controls, 
  Thermal Zone 12,                            !- Zone Name
  1,                                          !- Total Daylighting Reference Points
  45.0,                                       !- X-coordinate of first reference point {m}
  -2.0,                                       !- Y-coordinate of first reference point {m}
  1.5,                                        !- Z-coordinate of first reference point {m}
  ,                                           !- X-coordinate of second reference point {m}
  ,                                           !- Y-coordinate of second reference point {m}
  ,                                           !- Z-coordinate of second reference point {m}
  0.66,                                       !- Fraction of zone controlled by first reference point
  0,                                          !- Fraction of zone controlled by 2nd reference point
  500,                                        !- Illuminance setpoint at first reference point {lux}
  ,                                           !- Illuminance setpoint at second reference point {lux}
  1,                                          !- Lighting control type
  0,                                          !- view azimuth angle
  22,                                         !- Maximum allowable discomfort glare index
  0.1,                                        !- Minimum input power fraction for continuous dimming control
  0.1,                                        !- Minimum light output fraction for continuous dimming control
  0,                                          !- Number of steps (excluding off) for stepped control
  1;                                          !- Probability lighting will be reset when needed in manual stepped control";

			// add the strings to the end of file
			$fh = fopen($file_in, 'a') or die("can't open file");
			fwrite($fh, $dayLightDiming);
			fclose($fh);
			$_SESSION['measureFinished']['daylightDimmingChecked'] = 'finished';
             echo "<br>!!!!!!!!!!!!!!!!! daylightDimmingChecked !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br> ";
             $measureCost = 75000;
 
			break;

        default:
            # code...
            break;
    }  // end of switch statement

    $installationCost = $installationCost + $measureCost; 

}  // end of for-loop



copy($file_in, $file_save);                       # save a pre-measure eem version 
//echo "!!!!!!File Name: eem_{$version}";
//echo "!!!! Measure Done: ";
//print_r($_SESSION['measureFinished']);
//echo "!!!!";

if(!chdir('./eeb_rb')) { echo "Can't find this dirctory"; return 1; }                                  # go to eeb_rb folder
	
$cmd = "xvfb-run -a ruby run_single_simulation.rb eem_{$version}.idf $user_dir";
echo $run = shell_exec($cmd);   # run the simulation
//echo `php child_run.php eem_{$version}.idf $user_dir`;  // run simulation

# add the new version EEM model to the list
$_SESSION['Model'][$version] = "eem_{$version}";

// udpate the latest version
$cur_model = "eem_{$version}"; //assign the current model

// baseline energy cost
$baselineCost = 132968;

// the current year from 1 to 10 
$current_yr = ++$_SESSION['current_yr'];

// add a new energy simulation cost
$sql_file="../ENERGYPLUS/EEMs/{$user_dir}/{$cur_model}.idf/EnergyPlusPreProcess/EnergyPlus-0/eplusout.sql";
$eeb = new EEB_SQLITE3("$sql_file");
$electric_tariff = $eeb->getValues('Tariff Report', 'BLDG101_ELECTRIC_RATE', 'Categories', '%');
$gas_tariff = $eeb->getValues('Tariff Report', 'BLDG101_GAS_RATE',  'Categories', '%');
$newCost = $_SESSION['newCost'][$current_yr] = round($electric_tariff['Total (~~$~~)'][Sum] + $gas_tariff['Total (~~$~~)'][Sum],2);

$_SESSION['cur_model']="eem_{$version}";

// add a new installation cost
if($addAirEconCost) {

    // get the air economizer data from Fans report table
    $fans = $eeb->getValues('EquipmentSummary', 'Entire Facility', 'Fans','%');

    $airEconUnit = 0.5;                                   // air economizer unit ($/CFM)
    $measureCost = $fans['FAN VARIABLE VOLUME 1']['Max Air Flow Rate'] * $airEconUnit; 
    $installationCost += $measureCost;
} 

if($addCondensingBoilerCost) {

    // get the water boiler data from Central Plant report table
    $centralPlant = $eeb->getValues('EquipmentSummary', 'Entire Facility', 'Central Plant','%');

    $boilerUnit = 25;                    // condensing boilder unit ($/MBH)
    $measureCost = $centralPlant['BOILER HOT WATER 1']['Nominal Capacity'] * $boilerUnit / 1000;
    $installationCost += $measureCost;
}

if($addCondensingUnitCost) {

    // get the data from Cooling Coils report table 
    $coolingCoils = $eeb->getValues('EquipmentSummary', 'Entire Facility', 'DX Cooling Coils', '%');

    $coilsUnit = 1250;          // condensing cooling coils unit ($/ton)
    $measureCost = $coolingCoils['COIL COOLING DX TWO SPEED 1']['Standard Rated Net Cooling Capacity'] * $coilsUnit / 1000;
    $installationCost += $measureCost;
}

$_SESSION['installationCost'][$current_yr] = round($installationCost,2);               

// the current available cap
$availableCap = $_SESSION['availableCap'][$current_yr];

// add a new remaining cap
$remainingCap = $_SESSION['remainingCap'][$current_yr] = round($availableCap - $installationCost,2); 

// add a new cumulated saving
$cumulatedSaving = $_SESSION['cumulatedSaving'][$current_yr] = round($baselineCost - $newCost + $_SESSION['cumulatedSaving'][$current_yr-1],2);

// add a new (remaining cap + cumulated saving)
$_SESSION['remainingCapPlusCumulatedSaving'][$current_yr] = round($remainingCap + $cumulatedSaving,2);

// add a new 3% interest rate comparision
$_SESSION['3PercentInterestRate'][$current_yr] =  round($_SESSION['remainingCapPlusCumulatedSaving'][$current_yr-1] *1.03 + 50000,2);

// add a new percentage saving
$_SESSION['percentageSaving'][$current_yr] = round((1 - $newCost / $baselineCost)*100, 1);

// add a new available cap
$_SESSION['availableCap'][$current_yr+1] = round($_SESSION['remainingCap'][$current_yr] + 50000,2); 


// redirect to index.php page
header("location: http://rmt.eebhub.org/game/tracking-sheet.php");


?>
