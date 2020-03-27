<pre><?php
// $url = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") .   "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

// echo '<meta property="og:image" content="'. $url.'app/stationery/img/covid19_xs_mit.edu-x2.png">';
if (!$_GET) {
  echo 'gimme some data';
  exit;
}

if (!isset($_GET["action"]) && ($_GET["action"] != "write"))  {
  echo 'gimme some data, no good';
  exit;
}

setlocale(LC_TIME, "ro_RO");
date_default_timezone_set('Europe/Bucharest');
require('../settings.php');
require('functions/functions-app.php');
require('functions/functions-generic.php');

$sourceJson = $GLOBALS['sourceJson'];
$targetfile= $GLOBALS['targetfile'];
$iconsDIR = $GLOBALS['iconsDIR'];
$iconsDIRrel = $GLOBALS['iconsDIRrel'];

$out = $ctgznav = '';
$header = file_get_contents('header.html');
$posts = json_decode(file_get_contents($sourceJson, true));

$i = 0;
foreach ($posts as $ctgname => $onectg) {
  $i++;
  $out .= '<div class="xctg xbox"><h2 id="ctx_' . sluggify($ctgname) . '">' . $ctgname . '</h2><ul class="list-unstyled">';
  $ctgznav .= '<li class="ctg"><small class="counter"> ' . $i . '</small> <a href="#ctx_' . sluggify($ctgname) . '"> ' . $ctgname . '</a> </li>';
  $j = 0;
  $k = 0;
  foreach ($onectg as $id => $onerow) {
    $j++;
    $tagsArr = explode(',', $onerow->tags);
    $tagz = '';
    foreach ($tagsArr as $onetag) {
      $tagz .= $onetag ? '<a xhref="#ctg_'.$onetag.'">' . $onetag .'</a>' : ''; 
    }
    
    $classes = $onerow->options;
    $opts = explode(' ', $onerow->options);
    if (in_array('newline', $opts)) {
      $out .= '<br>';
    }
    if (in_array('subtitle', $opts) && $onerow->name) {
      $k++;
      $ctgznav .= '<li class="subtitle"><small class="counter"> ' . $i . '.' . $k . '.</small> <a href="#x' . $i.$k . '"> ' . $onerow->name . '</a> </li>';
    }
 
    $bazeurl = FALSE;
    if ($onerow->name) {
      if ($onerow->url) {
        $xdomain = parse_url($onerow->url);
        if ($xdomain) {
          $bazeurl = isset($xdomain['host']) ? $xdomain['host'] : FALSE;
          $zicon = $bazeurl && file_exists($iconsDIR . $xdomain['host']. '.png') ? '<img class="favicon" src="' . $iconsDIRrel . $bazeurl . '.png">' : '';
        }
 
      }

      $rowTitle = $bazeurl ? $zicon . '<h4><a href="' . $onerow->url . '" target="_blank">'  . $onerow->name . '</a> </h4>'  : '<h4>' . $onerow->name  . '</h4>';
    }
    else {
      $rowTitle = '';
      $classes .= ' no-name ';
    }
              
    $out .= '<li class="' . $classes . '"  id="x' . $i . $j . '">' .  $rowTitle.'<span class="desc">' . $onerow->description . '</span> <span class="tagz">'. $tagz. '</span>' . '</li>';
  }
  $out .='</ul>';
  $out .= '</div>';
}


// print_r($posts);
$ctgznav =
  '<ul id="tehnav" class="list-unstyled">
  <li class="navlead"><span class="navleadwrapper">Resurse <code><mark>#COVID19RO</mark></code></span></li>
  <li class="telverde"><span class="unicon">&#128241;</span>  <a href="tel:0800800358">0800-80-03-58</a></li>
  <li class="navspacer"></li>'
  .$ctgznav
  . '<li class="navcta"><span class="cta-inner"><span class="unicon">&#9888;</span> Știi vreo resursă care merită adăugată?  <a href="https://forms.gle/zG77Qbv6h9eVwLBJ8" target="_blank">trimite-ne aici</a></span></li>
  </ul>';
$renderedHTML =  $header 
. '<div id="main-wrapper"><div class="nav-wrapper">' . $ctgznav. '</div>'
.'<div id="content-wrapper">' .$out . '</div></div>
<p id="lastupdated"> <small>ulima actualizare: '.  strftime("<b>%e %b</b> %H:%M ").'</small></p></div><img id="gphxx" src="app/stationery/img/covid19_xs_mit.edu-x2.png"/></body></html>';

 
  // write to file

  // minify html
  // $renderedHTML=sanitize_output($renderedHTML);
 
  $ret = file_put_contents($targetfile, $renderedHTML, LOCK_EX);
  if ($ret === false) {
    die('There was an error writing this file');
  } else {
    echo round($ret/1000,2)." Kb → ". $targetfile." ";
 
  }