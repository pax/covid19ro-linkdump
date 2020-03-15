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
require('functions-app.php');
require('functions-generic.php');
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
      $xdomain = parse_url($oneurl->url);
      $bazeurl = isset($xdomain['host']) ? $xdomain['host'] : false ;
      if (filter_var($bazeurl, FILTER_VALIDATE_URL) === FALSE) {
        $bazeurl = false;
      }
      if ($bazeurl && !file_exists($targetDIR . $bazeurl . '.png')) {
        $icnt++;
        $iconurl = 'https://s2.googleusercontent.com/s2/favicons?domain_url=' . $oneurl->url;
        copy($iconurl, $targetDIR . $bazeurl . '.png');
        echo ' &rang; <img src="' . $targetDIR . $bazeurl . '.png"> ';
      }
    }
  }
}

echo ':' . $icnt . ' icons ';


