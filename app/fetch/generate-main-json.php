<pre><?php
$rustart = getrusage();

if (!$_GET) {
  $ERR = 'ERR: feed me some data!';
}

if (!isset($_GET['action']) || ($_GET['action'] != 'write')) {
  $ERR = 'ERR: missing ?action=write';
}

$ERR =FALSE;
require('../settings.php');
$dataPath='../data/';
$urlList=$dataPath.'google-sheets.json';
$sourcesFolder=$dataPath.'responses/';
$globalJson = 'db.json';

if($ERR) {
  echo $ERR;
  exit;
}

$urlListJson=file_get_contents($urlList);
 
$jsonObj=json_decode( $urlListJson);
print_r($jsonObj);
$masterArr=[];
foreach ($jsonObj->posts as $oneUrl) {

  $opts=$oneUrl->options;
  print_r($oneUrl);
  $xopts=explode(',', $opts);
  $optsTrimmed = [];
    foreach ($xopts as $value) {
      $optsTrimmed[] = trim($value);
    }

    if (file_exists($sourcesFolder.urlencode($oneUrl->url).'.json')) {
      // echo urlencode($oneUrl->url).'.json<br>';
      $embedInfoJson=file_get_contents($sourcesFolder.urlencode($oneUrl->url).'.json');
      $tmpObj=json_decode($embedInfoJson);
      $tmpObj->url=$oneUrl->url;
      $tmpObj->url = isset($oneUrl->url) && $oneUrl->url != null ? $oneUrl->url : null;
      $tmpObj->comment = isset($oneUrl->description) && $oneUrl->description != null ? $oneUrl->description : null;
      $tmpObj->name = isset($oneUrl->name) && $oneUrl->name != null ? $oneUrl->name : null;
      $tmpObj->options = isset($oneUrl->options) && $oneUrl->options != null ? $oneUrl->options : null;
      $masterArr[]=$tmpObj;
    }

    else {
      file_put_contents('../data/_error-log.txt', date("Y-m-d H:i",time())."\t".'CANT FIND: '.urlencode($oneUrl->url).'.json'.PHP_EOL , FILE_APPEND | LOCK_EX);
    }

}
usort($masterArr, "cmpx"); // order array elements by timestamp
$masterObj=json_encode( $masterArr );
// print_r($masterObj);
// exit;


// order json by date, descending

  function cmpx($a, $b)
  {
    $adate=$a->timestamp;
    $bdate=$b->timestamp;
      if ($adate == $bdate) {
          return 0;
      }
      return ($bdate < $adate) ? -1 : 1;
  }


$ret = file_put_contents($dataPath.$globalJson, $masterObj, LOCK_EX);
if ($ret === false) {
  die('There was an error writing this file');
} else {
  echo round($ret/1000,2)." Kb → ".$dataPath.$globalJson." ";
}


// Script end
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo '['.rutime($ru, $rustart, "utime") .
    "ms computations;\n";
echo rutime($ru, $rustart, "stime") .
    "ms sys calls]\n";
?>