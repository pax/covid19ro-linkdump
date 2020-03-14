<pre><?php
// Script start
$rustart = getrusage();

/*
    Check we received all necessary variables:
    - destination
    - json
    - action=write
*/

$ERR =FALSE;
$dataPath='../data/';
$urlList=$dataPath.'google-sheets.json';
$sourcesFolder=$dataPath.'responses/';
$globalJson = 'db.json';

if (!$_GET)  {
  $ERR = 'ERR: feed me some data!';
}

if (!isset($_GET['action']) || ($_GET['action']!='write')) {
  $ERR = 'ERR: missing ?action=write';
}

if($ERR) {
  echo $ERR;
  exit;
}


$urlListJson=file_get_contents($urlList);
 


$jsonObj=json_decode( $urlListJson);
print_r($jsonObj);
$masterArr=[];
foreach ($jsonObj->posts as $oneUrl) {

  $opts=$oneUrl->Options;

  $xopts=explode(',', $opts);
  $optsTrimmed = [];
    foreach ($xopts as $value) {
      $optsTrimmed[] = trim($value);
    }
    // print_r($optsTrimmed);
  // if (in_array('published', $optsTrimmed)){
    if (file_exists($sourcesFolder.urlencode($oneUrl->URL).'.json')) {
      // echo urlencode($oneUrl->URL).'.json<br>';
      $embedInfoJson=file_get_contents($sourcesFolder.urlencode($oneUrl->URL).'.json');
      $tmpObj=json_decode($embedInfoJson);
      $tmpObj->url=$oneUrl->URL;
      $tmpObj->url = isset($oneUrl->URL) && $oneUrl->URL != null ? $oneUrl->URL : null;
      $tmpObj->comment = isset($oneUrl->Comentariu) && $oneUrl->Comentariu != null ? $oneUrl->Comentariu : null;
      $tmpObj->collections = isset($oneUrl->Collections) && $oneUrl->Collections != null ? $oneUrl->Collections : null;
      $tmpObj->options = isset($oneUrl->Options) && $oneUrl->Options != null ? $oneUrl->Options : null;
      // overwrite timestamp w date from embed info, if any
      $tmpObj->timestamp = date("YmdHi", strtotime($tmpObj->date));
      //   (isset($tmpObj->date) && ($tmpObj->date != null ) && (is_string($tmpObj->date)))
      //   // (isset($tmpObj->date) && ($tmpObj->date != null ))) //TODO see shy this causes problems
      //   ? date("YmdHi",strtotime($tmpObj->date))
      //   : date("YmdHi",strtotime($oneUrl->Timestamp));
      unset($tmpObj->date); // delete 'date' element
      // print_r($tmpObj);
      $masterArr[]=$tmpObj;
    }

    else {
      // echo 'cantfind:<mark>'.urlencode($oneUrl->URL).'.json</mark><br>';
      file_put_contents('../data/_error-log.txt', date("Y-m-d H:i",time())."\t".'CANT FIND: '.urlencode($oneUrl->URL).'.json'.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
  // }
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