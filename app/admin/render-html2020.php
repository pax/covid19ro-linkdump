<?php
// $url = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") .   "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

// echo '<meta property="og:image" content="'. $url.'app/stationery/covid19_xs_mit.edu-x2.png">';
if (!$_GET) {
  echo 'gimme some data';
  exit;
}

if (!isset($_GET["action"]) && ($_GET["action"] != "write"))  {
  echo 'gimme some data, no good';
  exit;
}

$sourceJson = '../data/google-sheets.json';
$targetfile= '../../index.html';
$iconsDIR = '../data/icons/';
$iconsDIRrel = 'app/data/icons/';
setlocale(LC_TIME, "ro_RO");
date_default_timezone_set('Europe/Bucharest');
require('../functions.php');
require('../functions-generic.php');


$out = $ctgznav = '';
$header = file_get_contents('header.html');
$posts = json_decode(file_get_contents($sourceJson, true));

foreach ($posts as $ctgname => $onectg) {
  $out .= '<div class="xctg xbox"><h2 id="ctx_' . sluggify($ctgname) . '">' . $ctgname . '</h2><ul class="list-unstyled">';
  $ctgznav .= '<li><a href="#ctx_' . sluggify($ctgname) . '">' . $ctgname . '</a> </li>';
  foreach ($onectg as $id => $oneurl) {
    
    $tagsArr = explode(',', $oneurl->tags);
    $tagz = '';
    foreach ($tagsArr as $onetag) {
      $tagz .= $onetag ?'<a xhref="#ctg_'.$onetag.'">' . $onetag .'</a>' : ''; 
    }
    $xdomain = parse_url($oneurl->URL);
    $bazeurl =  $xdomain['host'];
    $zicon = '';
    echo $iconsDIR . $bazeurl . '.png';
    if (file_exists($iconsDIR . $bazeurl . '.png')) {
      $zicon = '<img class="favicon" src="' . $iconsDIRrel . $bazeurl . '.png">';
    }
    $out .= '<li>'
      . '<h4><a href="' . $oneurl->URL . '" target="_blank">'  . $oneurl->name . '</a> '  . $zicon .'</h4><span class="desc">' . $oneurl->description . '</span> <span class="tagz">'. $tagz.'</span>
    </li>';
  }
  $out .='</ul>';
  $out .= '</div>';
}


// print_r($posts);
$ctgznav =
  '<ul id="tehnav" class="list-unstyled">
  <li class="navlead"><span class="navleadwrapper">Resurse <code><mark>#COVID19RO</mark></code> : </span></li>
  <li class="telverde"><span class="unicon">&#9888;</span> <small>TelVerde:</small> <a href="tel:0800800358">0800-80-03-58</a></li>
  <li class="navspacer"></li>'
  .$ctgznav
  . '<li class="navcta"><span class="cta-inner"><span class="unicon">&#9888;</span> Știi vreo resursă care merită adăugată?  <a href="#">trimite-ne aici</a></span></li>
  </ul>';
$renderedHTML =  $header 
. '<div class="main-wrapper"><div class="nav-wrapper">' . $ctgznav. '</div>'
.'<div class="main-content-wrapper">' .$out . '</div></div>
<p id="lastupdated"> <small>ulima actualizare: '.  strftime("<b>%e %b</b> %H:%M ").'</small></p></div><img id="gphxx" src="app/stationery/covid19_xs_mit.edu-x2.png"/></body></html>';

 
  // write to file

  // minify html
  $renderedHTML=sanitize_output($renderedHTML);
 
  $ret = file_put_contents($targetfile, $renderedHTML, LOCK_EX);
  if ($ret === false) {
    die('There was an error writing this file');
  } else {
    echo round($ret/1000,2)." Kb → ". $targetfile." ";
 
  }