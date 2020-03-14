<?php

/*
Extracts structured data from articles, writes to file
uses https://github.com/oscarotero/Embed/

 */

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');



// - - - - - - - CONFIG - - - - - - - - - -

// $data_source='../data/sources-5.json';
$output_path='../data/responses/';


// include __DIR__.'/../vendor/autoload.php';
require('functions-embed.php');
include '../vendor/Embed-master/src/autoloader.php';


$time_start = microtime(true);

// Use default config as template
$options = \Embed\Embed::$default_config;
// Do some config modifications
$options['min_image_width'] = 60;
$options['min_image_height'] = 60;
$options['html']['max_images'] = 10;
$options['html']['external_images'] = false;

//use env variables
if (is_file(__DIR__.'/../env.php')) {
    include __DIR__.'/../env.php';

    $options['google']['key'] = getenv('GOOGLE_KEY');
    $options['facebook']['key'] = getenv('FACEBOOK_KEY');
}



if (isset($_GET["url"]) && isset($_GET["action"]) && ($_GET["action"]=="write")) {
  require('functions.php');

  $url=$_GET["url"];
  echo $url;

  // check if url data is already cached
  if (file_exists($output_path.urlencode($url).'.json')) {
      $responseJSON = file_get_contents($output_path.urlencode($url).'.json');
      $responseObj=json_decode($responseJSON);

  }
  // if data is not already cached, fetch new
  else {
    try {
      $dispatcher = new Embed\Http\CurlDispatcher();
      $info = Embed\Embed::create($url, $options, $dispatcher);
      $masterObj=getEmbed($info);
      // echo 'mumu';
      // print_r($masterObj);
      $responseObj=$masterObj;

      // $responseObj=json_decode($responseJSON);
      // if ($responseJSON === false) {
      //     echo "\n\r".'xxxx ERR fetching: '.urldecode($url).'';
      // }
      // else {
      //   $responseObj=json_decode($responseJSON);
      // }

      // overwrite timestamp w date from embed info, if any
      // $responseObj->timestamp = (isset($responseObj->date) && ($responseObj->date != null )) ? date("YmdHi",strtotime($responseObj->date)) : date("YmdHi",strtotime($responseObj->timestamp));
      // $responseObj->api=$service;
      $ret = file_put_contents($output_path.urlencode($url).'.json', json_encode($responseObj), LOCK_EX);
      if ($ret === false) {
        echo 'ERR writing: ' . $output_path . urlencode($url) . '.json'; 
        die('There was an error writing this file');
      } else {
        echo '<b>'.round($ret/1000,2).' Kb</b> &rarr; <code><a href="'.$output_path.urlencode(urlencode($url)).'.json"><small>.json</small></a></code>';
      }
    } catch (Exception $exception) {
    // $output='';
    //   foreach ($dispatcher->getAllResponses() as $response) {
    //       $output.= 'getUrl'.$response->getUrl().'<br>';
    //       // printHeaders($response->getHeaders());
    //       // printArray($response->getInfo());
    //       // printText($response->getContent());
    //   }
      echo '<p><mark>'.$exception.'</mark></p>';
      throw $exception;
    }
  }
// header('Content-Type: application/json');
// echo json_encode($responseObj);
}
else {
  echo 'some err ';
  // $zz = '';
  echo (isset($_GET["url"])) ? ' urlok ' : ' *missing url* ';
  echo (isset($_GET["action"]) && ($_GET["action"] == "write")) ? ' actionok ' : ' *no action* ';

}

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
// echo '<b>Total Execution Time:</b> '.$execution_time.' s';
