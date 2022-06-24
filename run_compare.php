<?php
/*
	This file fetches members from Scipio and writes email rules to the file system to Gsuite
 */
require_once ('helper_functions.php');
require_once ('Settings.php');
require_once ('ScipioOnline.php');
require_once ('Persoon.php');
require_once ('ExtractScipioMailingList.php');
require_once ('GsuiteCompare.php');

//Arrange and setup
$settings = new Settings();
$scipioOnline = new ScipioOnline($settings->getScipioSettings());
$program = new ExtractScipioMailingList($scipioOnline, $settings);

//Act
$emailadressesScipio = $program->extract();
$gsuiteCompare = new GSuiteCompare();
$emailadressesGsuite = $gsuite->extract();
$gsuite->write_gsuite_2_text();


//Write the changes that matter!
echo "<p>Comparing</p>";
echo "<p>Scipio: " . count($emailadressesScipio) . "</p>";
echo "<p>Gsuite: " . count($emailadressesGsuite) . "</p>";
$gsuite->write_compare_emailadresses_scipio($emailadressesGsuite, $emailadressesScipio);
$gsuite->write_compare_emailadresses_gsuite($emailadressesGsuite, $emailadressesScipio);
?>