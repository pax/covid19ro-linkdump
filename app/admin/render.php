<?php
// require('../debug-functions.php');

$sourceJson='../data/db.json';
$targetDir='../../static/';


setlocale(LC_TIME, "ro_RO");
require('../functions.php');

include ('../render-html.php');
include('../head.php');
if (!$target) {
  echo $zehead.$body;
}
else {
  // write to file

  // minify html
  // $renderedHTML=sanitize_output($zehead.$body);
  $renderedHTML=$zehead.$body;

  $ret = file_put_contents($targetDir.$target.'.html', $renderedHTML, LOCK_EX);
  if ($ret === false) {
    die('There was an error writing this file');
  } else {
    echo round($ret/1000,2)." Kb → ".$target." ";
  }
}
?>



