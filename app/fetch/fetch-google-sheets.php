<?php
/*
  Fetches data from Google Sheets
*/

require('functions.php');

$zeFile='../data/google-sheets.json';
// $docID = '2PACX-1vSjwrfiPQ6ckjttGUw-_NbxVF9TsvTMx8VLXPWSV22jChYEs6itSAQLQs8E-XB22RRbYvNGS9VyCD2L';
$docID = '2PACX-1vTERxGzP9c65waSCL3Wskg2JDFi4GkIfC62uPIKo9Drxy5L46K1JvPFudehEEFd_gzIuIam74PDbwAs';
$sheets=array(
  'România' => 0,
  'Ghiduri – internațional' => 1584676943,
  'Monitorizare (Global)' => 333501499,
  'Site-uri dedicate' => 519156796,
  'Articole' => 147533401,
  'Quality time, casnice' => 1148678370
);

date_default_timezone_set('Europe/Bucharest');
setlocale(LC_ALL, "ro_RO.UTF-8");

// require_once('../init.php');

$spreadsheet_data=$out=null;
$msg='';

if (!$_GET) {
  echo 'gimme some data';
  exit;
}
if(isset($_GET["output"])) {
  $output=$_GET["output"];
}
if (isset($_GET["action"]) && ($_GET["action"]=="write")) {

// GET DATA
  foreach ($sheets as $sheet_name => $sheet_id) {
    $spreadsheet_url='https://docs.google.com/spreadsheets/d/e/'.$docID.'/pub?gid='.$sheets[$sheet_name].'&single=true&output=csv';
    $spreadsheet_data[$sheet_name]=fetchGoogleSheets($spreadsheet_url);
  }
// echo '<pre>'; print_r($spreadsheet_data['posts']);
  $zeData = json_encode($spreadsheet_data);
  $ret = file_put_contents($zeFile, $zeData, LOCK_EX);
  if ($ret === false) {
    die('There was an error writing this file');
  } else {
    $msg.= ''.round($ret/1000,2)." Kb &rarr; ".$zeFile.'';
  }
}
else {
  $msg.= '<p>Nothing to do ?action=write, maybe?</p>';
}

echo $msg;