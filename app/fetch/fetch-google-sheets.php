<?php
/*
  Fetches data from Google Sheets
*/

require('functions/functions.php');
require('../settings.php');

$zeFile = $GLOBALS['zeFile'];
$docID = $GLOBALS['docID'];
$sheets = $GLOBALS['sheets'];

date_default_timezone_set('Europe/Bucharest');
setlocale(LC_ALL, "ro_RO.UTF-8");

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