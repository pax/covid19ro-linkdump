<?php

if (!$_GET) {
  echo 'gimme some data';
  exit;
}

if (!isset($_GET["action"]) && ($_GET["action"] != "write"))  {
  echo 'gimme some data, no good';
  exit;
}
echo 'fetching favicons ...';
setlocale(LC_TIME, "ro_RO");
date_default_timezone_set('Europe/Bucharest');
require('functions/functions-app.php'); 
require('functions/functions-generic.php');
require('../settings.php');

$sourceJson = $GLOBALS['sourceJson'] ;
$targetDIR = $GLOBALS['iconsDIR'] ;

$out = $ctgznav = '';
$header = file_get_contents('header.html');
$posts = json_decode(file_get_contents($sourceJson, true));
$icnt = 0;
foreach ($posts as $ctgname => $onectg) {
  foreach ($onectg as $id => $oneurl) {
    if ($oneurl->url && trim($oneurl->url) != '') {
      // echo '<br>&#11088; '; 
      $xdomain = parse_url($oneurl->url);
      $bazeurl = isset($xdomain['host']) ? $xdomain['host'] : false ;
      // echo $bazeurl . ' // ';

      // check if proper url
      if (filter_var('http://'.$bazeurl, FILTER_VALIDATE_URL) === FALSE) {
        // $bazeurl = false;
        echo '&#9888; improper url format: <mark>' . $bazeurl .'</mark>';
        continue;
      }
      
      
      if (!file_exists($targetDIR . $bazeurl . '.png')) {
        // echo ' - ' . $bazeurl;
        $icnt++;
        $iconurl = 'https://s2.googleusercontent.com/s2/favicons?domain_url=' . $oneurl->url;
        copy($iconurl, $targetDIR . $bazeurl . '.png');
        echo ' <img src="' . $targetDIR . $bazeurl . '.png">' . $bazeurl . ' ';
        // TODO: fetch embed info
      }
      else {
        echo '&middot;';
        // echo ' &#10004; <img src="' . $targetDIR . $bazeurl . '.png"> ';
      }
    }
  }
}
echo '<br> &#9654; ';
echo $icnt ? ' <b>' . $icnt . '</b> icons ' : ' no new icons';


