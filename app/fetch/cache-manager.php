<?php

$cache_folder='../data/responses/';
$out='';
/*
  Read all files from folder
*/


if(isset($_GET['action']) && isset($_GET['file']) && ($_GET['action']=='delete')){
  $file=$_GET['file'];

  if (!file_exists($file)) {
    echo '<p>couldn\'t find <b>'.$file.'</b></p>';
    // exit;
  }
  else {
    $zz = unlink($file);
    if ($zz) echo '<p>deleted &rarr; <small>'.urldecode($file).'</small></p>';
  }
}
else if (isset($_GET['action']) && ($_GET['action']=='delete-all'))
{
  // delete all files in folder
  $files = glob($cache_folder."*.json"); // get all file names
  $ii=0;
  foreach($files as $file){ // iterate files
    if(is_file($file))
      unlink($file); // delete file
    $ii++;
  }
  $out.= '<p>deleted '.$ii.' files</p>';
}




$files = array();
  $ii=0;
foreach (glob($cache_folder."*.json") as $file) {
  $files[] = $file;
  $ii++;
}
$out.= '<p><b>'.$ii.'</b> cached resources</p>';

foreach ($files as $filename) {
  $out.='<a href="?action=delete&file='.urlencode($filename).'">delete</a> <small><s>refetch</s></small> &nbsp; <small>'.urldecode($filename).'</small><br> ';
}

?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>&middot;/&middot; cache manager</title>
<style>
  body {font-family: sans-serif; padding: 4em; 10vw;  background-color: WhiteSmoke;}
  #wrapper {padding: 3em; background-color: White; display: inline-block;}
  nav {
     background-color: Whit; display: inline-block; padding: .5ex 1.1ex;
    position: fixed; z-index: 4; right: 1em; top: 1em; box-shadow:1px 1px 0 #EEE; 
  }
</style>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


</head>
<body>
<nav>&larr; <a href="../admin">admin</a> | <a href="../../">homepage</a> &rarr;</nav>
<div id="wrapper">
  <p>&rarr; <a href="?action=delete-all">delete all</a> <small>– deletes all files in folder</small></p>
  <?=$out;?>
</div>

 </body>
</html>
