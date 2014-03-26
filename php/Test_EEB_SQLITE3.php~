<?php

require 'EEB_SQLITE3.php';

$eeb = new EEB_SQLITE3('modified_V8_V720.sql');

# Test Data 
echo "start\n";
$annual_site_source_values = $eeb->getValues('AnnualBuildingUtilityPerformanceSummary', 'Entire Facility', 'Site and Source Energy', '%');
//print_r($annual_site_source_values);				// need to convert GJ to kWh and MBtu
echo "\n------------------------------------------------------------------------------------------------------------\n";



$monthly_electricity_values = $eeb->getValues('BUILDING ENERGY PERFORMANCE - ELECTRICITY', 'Meter', '', '%');
//print_r($monthly_electricity_values);				// need to convert GJ to MBtu 
echo "\n------------------------------------------------------------------------------------------------------------\n";



$monthly_gas_values = $eeb->getValues('BUILDING ENERGY PERFORMANCE - ELECTRICITY', 'Meter', '', '%');
//print_r($monthly_gas_values);						// need to convert GJ to kWh
echo "\n------------------------------------------------------------------------------------------------------------\n";



$energy_cost_values = $eeb->getValues('Economics Results Summary Report', 'Entire Facility', 'Annual Cost', '%');
//print_r($energy_cost_values);    					// unit $
echo "\n------------------------------------------------------------------------------------------------------------\n";



$energy_summary_values = $eeb->getValues('Economics Results Summary Report', 'Entire Facility', 'Tariff Summary', '%');
//print_r($energy_cost_values);						// multi units
echo "\n------------------------------------------------------------------------------------------------------------\n";


$tariff_medium_electricity_values = $eeb->getValues('Tariff Report', 'VIRGINIAELEC_GS-2MEDIUMDEMAND', 'Categories', '%');
//print_r($tariff_medium_electricity_values);		// unit $
echo "\n------------------------------------------------------------------------------------------------------------\n";



$tariff_gas_values = $eeb->getValues('Tariff Report', 'VA_EIAMONTHLYRATEGAS', 'Categories', '%');
//print_r($tariff_gas_values);			// unit $
echo "\n------------------------------------------------------------------------------------------------------------\n";


$GHG_values = $eeb->getValues('EnergyMeters', 'Entire Facility', 'Annual and Peak Values - Other by Weight/Mass', '%');
$carbon_equivalent_value = $GHG_values['CarbonEquivalentEmissions:Carbon Equivalent']['Annual Value'];
echo $carbon_equivalent_value; 					// need to convert kg to million tons 
echo "\n------------------------------------------------------------------------------------------------------------\n";


$zone1_component_cooling_values = $eeb->getValues('ZoneComponentLoadSummary', 'THERMAL ZONE 1', 'Estimated Cooling Peak Load Components', '%');
print_r($zone1_component_cooling_values);			// unit W  ? need to convert to other units
echo "\n------------------------------------------------------------------------------------------------------------\n";


?>
