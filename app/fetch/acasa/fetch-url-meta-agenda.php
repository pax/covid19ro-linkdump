<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
include '../../vendor/embed/embed/src/autoloader.php';

setlocale(LC_TIME, "ro_RO");
date_default_timezone_set('Europe/Bucharest');
include '../functions/functions-embed.php';
include '../functions/functions-embed2.php';
require('../functions/functions-app.php');
require('../functions/functions-generic.php');
require('../../settings.php');


// $sourceJson = $GLOBALS['sourceJson'];
// $targetDIR = $GLOBALS['metasDIR'];
$sourceJson = '../../data/google-sheets2.json';
$targetDIR = '../../data/urlmeta/';

// Use default config as template
$fetch_meta_opts = \Embed\Embed::$default_config;
// Do some config modifications
$fetch_meta_opts['min_image_width'] = 60;
$fetch_meta_opts['min_image_height'] = 60;
$fetch_meta_opts['html']['max_images'] = 10;
$fetch_meta_opts['html']['external_images'] = false;


$providerData = [
  'title' => 'printText',
  'description' => 'printText',
  'url' => 'printUrl',
  'type' => 'printText',
  'tags' => 'printArray',
  'imagesUrls' => 'printArray',
  'code' => 'printCode',
  'feeds' => 'printArray',
  'width' => 'printText',
  'height' => 'printText',
  'authorName' => 'printText',
  'authorUrl' => 'printUrl',
  'providerIconsUrls' => 'printArray',
  'providerName' => 'printText',
  'providerUrl' => 'printUrl',
  'publishedTime' => 'printText',
  'license' => 'printUrl',
];

$adapterData = [
  'title' => 'printText',
  'description' => 'printText',
  'url' => 'printUrl',
  'type' => 'printText',
  'tags' => 'printArray',
  'image' => 'printImage',
  'imageWidth' => 'printText',
  'imageHeight' => 'printText',
  'images' => 'printArray',
  'code' => 'printCode',
  'feeds' => 'printArray',
  'width' => 'printText',
  'height' => 'printText',
  'aspectRatio' => 'printText',
  'authorName' => 'printText',
  'authorUrl' => 'printUrl',
  'providerIcon' => 'printImage',
  'providerIcons' => 'printArray',
  'providerName' => 'printText',
  'providerUrl' => 'printUrl',
  'publishedTime' => 'printText',
  'license' => 'printUrl',
];

if (!$_GET) {
  echo 'gimme some data';
  exit;
}

if (!isset($_GET["action"]) && ($_GET["action"] != "write"))  {
  echo 'gimme some data, no good';
  exit;
}
echo 'fetching mettaz!! ...';



$out = $ctgznav = '';

$posts = json_decode(file_get_contents($sourceJson, true));
$success = $errz = 0;
 
foreach ($posts as $ctgname => $onectg) {
  foreach ($onectg as $id => $oneurl) {
    if ($oneurl->url && trim($oneurl->url) != '') {
/*      
      // check if proper url - a bit of overkill
      if (filter_var($oneurl->url, FILTER_VALIDATE_URL) === FALSE) {
        echo '&#9888; improper url format: <mark>' . $bazeurl .'</mark>';
        continue;
      }  
 */   
      // check if url info already fetched OR is in error folder
      $niceurl = str_replace('https://', '', $oneurl->url);
      $niceurl = str_replace('https://', '', $niceurl);
      $niceurl = urlencode($niceurl);
      if (!file_exists($targetDIR . $niceurl . '.json') && !file_exists($targetDIR . 'errors/' . $niceurl . '.json')) {
        $success++;

        // Here we start fetching url meta
        try {
          $dispatcher = new Embed\Http\CurlDispatcher();
          $info = Embed\Embed::create($oneurl->url, $fetch_meta_opts, $dispatcher);
          $masterObj = getEmbed($info);
          writeToFile($targetDIR . $niceurl . '.json', json_encode($masterObj));
         

        } catch (Exception $exception) {
          $errz++;
          // TODO: log this / write as an error file, separately
          writeToFile($targetDIR . 'errors/' . $niceurl . '.json', json_encode(['error'=>'true']));
          /* foreach ($dispatcher->getAllResponses() as $response) {  echo '<tr>'. echo '<th>' . $response->getUrl() . '</th>'. echo '</tr><tr><td>'. printHeaders($response->getHeaders()). echo '</td><tr><td><pre>'. printArray($response->getInfo()). echo '</td><tr><td><pre>'. printText($response->getContent()). echo '</pre></td></tr>'. }           */
          echo '<hr>big <mark>error</mark>' . $oneurl->url . '<hr>';
          // throw $exception; // that would be too much
        }
 
      }
      else {
        echo '&middot;';
      }
    }
  }
}

echo '<br> &#9654; ';
echo $success ? ' <b>' . $success . '</b> infos fetched ' : ' same ole';
echo $errz ? $errz . ' known failures ' : ' ';

function writeToFile($urlpath, $zedata){
  $ret = file_put_contents($urlpath, $zedata, LOCK_EX);
  if ($ret === false) {
    die('There was an error writing this file');
  } else {
    echo '<b>' . round($ret / 1000, 2) . ' Kb</b> &rarr; <code><a href="' . $urlpath . '.json"><small>.json</small></a></code>';   
  }
}