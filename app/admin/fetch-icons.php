<pre><?php


if (!$_GET) {
  echo 'gimme some data';
  exit;
}

if (!isset($_GET["action"]) && ($_GET["action"] != "write"))  {
  echo 'gimme some data, no good';
  exit;
}

$sourceJson = '../data/google-sheets.json';
$targetDIR = '../data/icons/';
setlocale(LC_TIME, "ro_RO");
date_default_timezone_set('Europe/Bucharest');
require('../functions.php');
require('../functions-generic.php');


$out = $ctgznav = '';
$header = file_get_contents('header.html');
$posts = json_decode(file_get_contents($sourceJson, true));

foreach ($posts as $ctgname => $onectg) {

  foreach ($onectg as $id => $oneurl) {
    $xdomain = parse_url($oneurl->URL);
    $bazeurl =  $xdomain['host'];  
    if (file_exists($targetDIR . $bazeurl . '.png')) {
      echo ' <img src="' . $targetDIR . $bazeurl . '.png"> '; 
    }
    else {
      $iconurl = 'https://s2.googleusercontent.com/s2/favicons?domain_url='. $oneurl->URL;
      
      copy($iconurl, $targetDIR. $bazeurl.'.png');
      echo 'new: '. $bazeurl.' / ';
    }
}
}


